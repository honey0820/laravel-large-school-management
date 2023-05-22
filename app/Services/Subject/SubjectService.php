<?php

namespace App\Services\Subject;

use App\Models\Subject;
use App\Models\User;
use App\Services\User\UserService;

class SubjectService
{
    /**
     * Instance of user class.
     *
     * @var UserService
     */
    public UserService $user;

    public function __construct(UserService $user)
    {
        $this->user = $user;
    }

    /**
     * Get all subjects.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllSubjects()
    {
        return Subject::where(['school_id' => auth()->user()->school_id])->get();
    }

    /**
     * Get a subject by Id.
     *
     * @param int $id
     *
     * @return App\Models\Subject
     */
    public function getSubjectById(int $id)
    {
        return Subject::find($id);
    }

    /**
     * Create subject.
     *
     * @param mixed $data
     *
     * @return void
     */
    public function createSubject($data)
    {
        $subject = Subject::firstOrCreate([
            'name'        => $data['name'],
            'short_name'  => $data['short_name'],
            'school_id'   => auth()->user()->school_id,
            'my_class_id' => $data['my_class_id'],
        ]);

        if (!$subject->wasRecentlyCreated) {
            return session()->flash('danger', 'Subject already exists or something went wrong');
        }

        if (isset($data['teachers'])) {
            $teachers = [];
            foreach ($data['teachers'] as $teacher) {
                if ($this->user->verifyRole($teacher, 'teacher')) {
                    $teachers[] = $teacher;
                }
            }

            $subject->teachers()->sync($teachers);
        }
    }

    /**
     * Update subject.
     *
     * @param Subject $subject
     * @param mixed   $data
     *
     * @return void
     */
    public function updateSubject(Subject $subject, $data)
    {
        $subject->name = $data['name'];
        $subject->short_name = $data['short_name'];

        $subject->save();

        if (isset($data['teachers'])) {
            $teachers = [];
            foreach ($data['teachers'] as $teacher) {
                if ($this->user->verifyRole($teacher, 'teacher')) {
                    $teachers[] = $teacher;
                }
            }
            $subject->teachers()->sync($teachers);
        } else {
            $subject->teachers()->sync([]);
        }
    }

    /**
     * Delete subject.
     *
     * @param Subject $subject
     *
     * @return void
     */
    public function deleteSubject(Subject $subject)
    {
        $subject->delete();
    }

    /**
     * Assign a teacher to a list of subjects.
     *
     * @param User $teacher
     * @param array!mixed $records Array or collection of ids
     *
     * @return void
     */
    public function assignTeacherToSubjects(User $teacher, $records)
    {
        $teacher->subjects()->sync(array_filter(array_values($records['subjects'])));
    }
}
