<?php

namespace App\Services\MyClass;

use App\Exceptions\ResourceNotEmptyException;
use App\Models\ClassGroup;
use App\Models\MyClass;
use App\Services\School\SchoolService;

class MyClassService
{
    /**
     * School service variable.
     *
     * @var SchoolService
     */
    public SchoolService $schoolService;

    //construct method
    public function __construct(SchoolService $schoolService)
    {
        $this->schoolService = $schoolService;
    }

    /**
     * Get all classes in school.
     *
     * @return Illuminate\Support\Collection
     */
    public function getAllClasses()
    {
        return $this->schoolService->getSchoolById(auth()->user()->school_id)->myClasses->load('classGroup', 'sections');
    }

    /**
     * Get all ClassGroups in school.
     *
     * @return Illuminate\Eloquent\Collection
     */
    public function getAllClassGroups()
    {
        return ClassGroup::where('school_id', auth()->user()->school_id)->get();
    }

    /**
     * Get all classes in school.
     *
     * @param int $id
     *
     * @return App\Models\MyClass
     */
    public function getClassById(int $id)
    {
        return MyClass::find($id);
    }

    /**
     * Get class by id or else return 404.
     *
     * @param int $id
     *
     * @return void
     */
    public function getClassByIdOrFail(int $id)
    {
        return $this->schoolService->getSchoolById(auth()->user()->school_id)->myClasses()->findOrFail($id);
    }

    /**
     * Get class group by id.
     *
     * @param int $id
     *
     * @return void
     */
    public function getClassGroupById(int $id)
    {
        return ClassGroup::where('school_id', auth()->user()->school_id)->find($id);
    }

    /**
     * Create new class.
     *
     * @param array|object $record
     *
     * @return App\Models\MyClass
     */
    public function createClass($record)
    {
        $myClass = MyClass::create($record);

        return $myClass;
    }

    /**
     * Create new class group.
     *
     * @param array|object $record
     *
     * @return App\Models\ClassGroup
     */
    public function createClassGroup($record)
    {
        $classGroup = ClassGroup::create($record);

        return $classGroup;
    }

    /**
     * Update class.
     *
     * @param App\Models\MyClass $class
     * @param array|object       $records
     *
     * @return App\Models\MyClass
     */
    public function updateClass($class, $records)
    {
        $class->update([
            'name'           => $records['name'],
            'class_group_id' => $records['class_group_id'],
        ]);

        return $class;
    }

    /**
     * Update class group.
     *
     * @param App\Models\ClassGroup $classGroup
     * @param array|object          $records
     *
     * @return App\Models\ClassGroup
     */
    public function updateClassGroup(ClassGroup $classGroup, $records)
    {
        $classGroup->update(
            [
                'name' => $records['name'],
            ]
        );

        return $classGroup;
    }

    /**
     * Delete class group.
     *
     * @param App\Models\ClassGroup $classGroup
     *
     * @throws ResourceNotEmptyException
     *
     * @return void
     */
    public function deleteClassGroup(ClassGroup $classGroup)
    {
        if ($classGroup->classes->count()) {
            throw new ResourceNotEmptyException('Class Group contains classes');
        }
        $classGroup->delete();
    }

    /**
     * Delete class.
     *
     * @param App\Models\MyClass $class
     *
     * @throws ResourceNotEmptyException
     *
     * @return void
     */
    public function deleteClass(MyClass $class)
    {
        if ($class->studentRecords->count()) {
            throw new ResourceNotEmptyException('Class contains students');
        }
        $class->delete();
    }
}
