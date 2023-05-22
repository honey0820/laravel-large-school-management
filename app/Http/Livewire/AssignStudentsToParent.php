<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Services\MyClass\MyClassService;
use App\Services\Section\SectionService;

class AssignStudentsToParent extends Component
{
    public User $parent;
    public $classes, $class, $sections, $section, $students, $student, $children;
  
    public function mount(SectionService $sectionService, MyClassService $myClassService)
    {
        $this->classes = $myClassService->getAllClasses();
        if ($this->classes->isEmpty()) {
            return;
        }
        $this->class = $this->classes[0]->id;
        $this->updatedClass();

        $this->children = $this->parent->parentRecord->students;
    }
    
    public function updatedClass()
    {
        //get instance of class
        $class = app("App\Services\MyClass\MyClassService")->getClassById($this->class);

        //get sections in class
        $this->sections = $class->sections;

        //set section if the fetched records aren't empty
        if ($this->sections->isEmpty()) {
            $this->students = null;

            return;
        }
        $this->section = $this->sections[0]->id;

        $this->updatedSection();
    }

    public function updatedSection()
    {
        //get instance of section
        $section = app("App\Services\Section\SectionService")->getSectionById($this->section);

        //get students in section
        $this->students = $section->studentRecords->map(function ($studentRecord) {
            return $studentRecord->user;
        });

        //set student if the fetched records aren't empty
        $this->students->count() ? $this->student = $this->students[0]->id : $this->student = null;
    }  
    
    public function render()
    {
        return view('livewire.assign-students-to-parent');
    }
}
