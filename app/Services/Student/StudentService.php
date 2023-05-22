<?php

namespace App\Services\Student;

use App\Models\Promotion;
use App\Models\User;
use App\Services\MyClass\MyClassService;
use App\Services\Print\PrintService;
use App\Services\Section\SectionService;
use App\Services\User\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentService
{
    /**
     *Instance of class service.
     *
     * @var MyClassService
     */
    public $myClassService;
    /**
     * Instance of user service.
     *
     * @var UserService
     */
    public $userService;
    public $section;

    public function __construct(MyClassService $myClass, UserService $userService, SectionService $section)
    {
        $this->myClass = $myClass;
        $this->section = $section;
        $this->userService = $userService;
    }

    /**
     * Get all students in school.
     *
     * @return lluminate\Database\Eloquent\Collection
     */
    public function getAllStudents()
    {
        return $this->userService->getUsersByRole('student')->load('studentRecord');
    }

    /**
     * Get all active students in school.
     *
     * @return lluminate\Database\Eloquent\Collection
     */
    public function getAllActiveStudents()
    {
        return $this->userService->getUsersByRole('student')->load('studentRecord')->filter(function ($student) {
            if ($student->studentRecord) {
                return $student->studentRecord->is_graduated == false;
            }
        });
    }

    /**
     * Get all graduated students in school.
     *
     * @return lluminate\Database\Eloquent\Collection
     */
    public function getAllGraduatedStudents()
    {
        return $this->userService->getUsersByRole('student')->load('studentRecord')->filter(function ($student) {
            return $student->studentRecord()->withoutGlobalScopes()->first()->is_graduated == true;
        });
    }

    /**
     * Get a student by id.
     *
     * @param array|int $id student id
     *
     * @return \App\Models\User
     */
    public function getStudentById($id)
    {
        return $this->userService->getUserById($id)->load('studentRecord');
    }

    /**
     * Create student.
     *
     * @param array $record Array of student record
     *
     * @return void
     */
    public function createStudent($record)
    {
        DB::transaction(function () use ($record) {
            $student = $this->userService->createUser($record);
            $student->assignRole('student');

            $this->createStudentRecord($student, $record);
        });
        session()->flash('success', 'Student Created Successfully');
    }

    /**
     * Create record for student.
     *
     * @param [type] $record
     *
     * @return void
     */
    public function createStudentRecord($student, $record)
    {
        $record['admission_number'] || $record['admission_number'] = $this->generateAdmissionNumber();
        $section = $this->section->getSectionById($record['section_id']);
        if (!$this->myClass->getClassById($record['my_class_id'])->sections->contains($section)) {
            session()->flash('danger', 'Section is not in class');

            return;
        }

        $student->studentRecord()->firstOrCreate([
            'user_id' => $student->id
        ],[
            'my_class_id'      => $record['my_class_id'],
            'section_id'       => $record['section_id'],
            'admission_number' => $record['admission_number'],
            'admission_date'   => $record['admission_date'],
        ]);

        //create record history
        $currentAcademicYear = $student->school->academicYear;
        $student->studentRecord->load('academicYears')->academicYears()->sync([$currentAcademicYear->id => [
            'my_class_id'      => $record['my_class_id'],
            'section_id'       => $record['section_id'],
        ]]);
    }

    /**
     * Update student.
     *
     * @param User $student
     * @param $records
     *
     * @return void
     */
    public function updateStudent(User $student, $records)
    {
        $student = $this->userService->updateUser($student, $records);
        session()->flash('success', 'Student Updated Successfully');
    }

    /**
     * Delete student.
     *
     * @param User $student
     *
     * @return void
     */
    public function deleteStudent(User $student)
    {
        $student->delete();
        session()->flash('success', 'Student Deleted Successfully');
    }

    /**
     * Generate admission number.
     *
     * @return string
     */
    public function generateAdmissionNumber()
    {
        return Str::random(10);
    }

    public function printProfile(string $name, string $view, array $data)
    {
        return PrintService::createPdfFromView($name, $view, $data)->download();
    }

    //promote student method
    public function promoteStudents($records)
    {
        $oldClass = $this->myClass->getClassById($records['old_class_id']);
        $newClass = $this->myClass->getClassById($records['new_class_id']);
        $records['academic_year_id'] = auth()->user()->school->academic_year_id;

        if (!$oldClass->sections()->where('id', $records['old_section_id'])->exists()) {
            return session()->flash('danger', 'Old section is not in old class');
        }

        if (!$newClass->sections()->where('id', $records['new_section_id'])->exists()) {
            return session()->flash('danger', 'New section is not in new class');
        }

        //make sure academic year is present
        if ($records['academic_year_id'] == null) {
            return session()->flash('danger', 'Academic year is not set');
        }

        //get all students for promotion
        $students = $this->getAllActiveStudents()->whereIn('id', $records['student_id']);

        // make sure there are students to promote
        if (!$students->count()) {
            return session()->flash('danger', 'No students to promote');
        }

        $currentAcademicYear = auth()->user()->school->academicYear;
        // update each student's class
        foreach ($students as $student) {
            if (in_array($student->id, $records['student_id'])) {
                $student->studentRecord()->update([
                    'my_class_id' => $records['new_class_id'],
                    'section_id'  => $records['new_section_id'],
                ]);
                $student->studentRecord->load('academicYears')->academicYears()->syncWithoutDetaching([$currentAcademicYear->id => [
                    'my_class_id'      => $records['new_class_id'],
                    'section_id'       => $records['new_section_id'],
                ]]);
            }
        }

        // create promotion record
        Promotion::create([
            'old_class_id'     => $records['old_class_id'],
            'new_class_id'     => $records['new_class_id'],
            'old_section_id'   => $records['old_section_id'],
            'new_section_id'   => $records['new_section_id'],
            'students'         => $students->pluck('id'),
            'academic_year_id' => $records['academic_year_id'],
            'school_id'        => auth()->user()->school_id,
        ]);

        return session()->flash('success', 'Students Promoted Successfully');
    }

    //get all promotion record

    public function getAllPromotions()
    {
        return Promotion::where('school_id', auth()->user()->school_id)->get();
    }

    public function getPromotionsByAcademicYearId($academicYearId)
    {
        return Promotion::where('school_id', auth()->user()->school_id)->where('academic_year_id', $academicYearId)->get();
    }

    // reset promotion method

    public function resetPromotion($promotion)
    {
        $students = $this->getStudentById($promotion->students);
        $currentAcademicYear = auth()->user()->school->academicYear;

        foreach ($students as $student) {
            $student->studentRecord->load('academicYears')->academicYears()->syncWithoutDetaching([$currentAcademicYear->id => [
                'my_class_id' => $promotion->old_class_id,
                'section_id'  => $promotion->old_section_id,
            ]]);
            $student->studentRecord()->update([
                'my_class_id' => $promotion->old_class_id,
                'section_id'  => $promotion->old_section_id,
            ]);
        }

        $promotion->delete();

        return session()->flash('success', 'Promotion Reset Successfully');
    }

    //graduate student method
    public function graduateStudents($records)
    {
        //get all students for graduation
        $students = $this->getAllActiveStudents()->whereIn('id', $records['student_id']);

        // make sure there are students to graduate
        if (!$students->count()) {
            return session()->flash('danger', 'No students to graduate');
        }

        // update each student's graduation status
        foreach ($students as $student) {
            if (in_array($student->id, $records['student_id'])) {
                $student->studentRecord()->update([
                    'is_graduated' => true,
                ]);
            }
        }

        return session()->flash('success', 'Students graduated Successfully');
    }

    //reset graduation method

    public function resetGraduation(User $student)
    {
        $student->studentRecord()->update([
            'is_graduated' => false,
        ]);

        return session()->flash('success', 'Graduation Reset Successfully');
    }
}
