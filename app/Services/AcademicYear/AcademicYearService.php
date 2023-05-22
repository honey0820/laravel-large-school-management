<?php

namespace App\Services\AcademicYear;

use App\Models\AcademicYear;
use App\Services\School\SchoolService;

class AcademicYearService
{
    /**
     * @var SchoolService
     */
    public $school;

    public function __construct(SchoolService $school)
    {
        $this->school = $school;
    }

    /**
     * Get all academic years.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAllAcademicYears()
    {
        return AcademicYear::where('school_id', auth()->user()->school_id)->get();
    }

    /**
     * Get academic year by Id.
     * 
     *@param int $id
     *
     * @return App\Models\AcademicYear
     */
    public function getAcademicYearById($id)
    {
        return AcademicYear::where('id', $id)->first();
    }

    /**
     * Create academic year
     * 
     * @param array|Collection $records
     * 
     * @return void
     */
    public function createAcademicYear($records)
    {
        $records['school_id'] = auth()->user()->school_id;
        AcademicYear::create($records);
        session()->flash('success', 'Academic year created successfully');

        return;
    }

    /**
     * Set academic year as current.one in school.
     *
     * @param int $academicYearId
     * @param int $schoolId
     * @return void
     */
    public function setAcademicYear($academicYearId, $schoolId = null)
    {
        if (!isset($schoolId)) {
            $schoolId = auth()->user()->school_id;
        }
        $school = $this->school->getSchoolById($schoolId);
        $school->academic_year_id = $academicYearId;
        //set semester id to null
        $school->semester_id = null;
        $school->save();
        session()->flash('success', "Academic year set for {$school->name} successfully");

        return;
    }

    /**
     * Update Academic Year
     *
     * @param AcademicYear $academicYear
     * @param array|Collection $records
     * @return void
     */
    public function updateAcademicYear(AcademicYear $academicYear, $records)
    {
        $academicYear->start_year = $records['start_year'];
        $academicYear->stop_year = $records['stop_year'];
        $academicYear->save();
        session()->flash('success', 'Academic year updated successfully');

        return;
    }

    /**
     * Delete an academic year
     *
     * @param AcademicYear $academicYear
     * @return void
     */
    public function deleteAcademicYear(AcademicYear $academicYear)
    {
        $academicYear->delete();
        session()->flash('success', 'Academic year deleted successfully');
        
        return;
    }
}
