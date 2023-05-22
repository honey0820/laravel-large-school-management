<?php

namespace App\Services\Teacher;

use App\Models\User;
use App\Services\Print\PrintService;
use App\Services\User\UserService;

class TeacherService
{
    /**
     * User service variable.
     *
     * @var UserService
     */
    public userService $user;

    public function __construct(UserService $user)
    {
        $this->user = $user;
    }

    /**
     * Get all teachers in school.
     *
     * @return void
     */
    public function getAllTeachers()
    {
        return $this->user->getUsersByRole('teacher')->load('teacherRecord');
    }

    /**
     * Create a new teacher.
     *
     * @param collection $record
     *
     * @return void
     */
    public function createTeacher($record)
    {
        $teacher = $this->user->createUser($record);
        $teacher->assignRole('teacher');
    }

    /**
     * Update a teacher.
     *
     * @param User                    $teacher
     * @param array|object|collection $records
     *
     * @return void
     */
    public function updateTeacher(User $teacher, $records)
    {
        $this->user->updateUser($teacher, $records, 'teacher');
    }

    /**
     * Delete teacher.
     *
     * @param User $teacher
     *
     * @return void
     */
    public function deleteTeacher(User $teacher)
    {
        $this->user->deleteUser($teacher);
    }

    /**
     * Print a uset profiel.
     *
     * @param string $name
     * @param string $view
     * @param array  $data
     *
     * @return mixed
     */
    public function printProfile(string $name, string $view, array $data)
    {
        return PrintService::createPdfFromView($name, $view, $data);
    }
}
