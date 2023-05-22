<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\App;
use App\Services\MyClass\MyClassService;
use App\Services\Timetable\TimetableService;

class ListTimetablesTable extends Component
{
    public $class;
    public $timetables;
    public $classes;
    public function mount(TimetableService $timetableService, MyClassService $myClassService)
    {
        //get current semester
        $semester = auth()->user()->school->semester_id;
        //check if user is a student
        if (auth()->user()->hasRole('student')) {
            // get student class and set class name
            $this->class = auth()->user()->studentRecord->myClass->name;
            $class = auth()->user()->studentRecord->my_class_id;
            //get timetables in semester and class
            $this->timetables = $timetableService->getAllTimetablesInSemesterAndClass($semester,$class);
        }
        //user isn't a student
        else {
            //get all classes
            $this->classes = $myClassService->getAllClasses();
            //set intial record
            $this->timetables = $timetableService->getAllTimetablesInSemesterAndClass($semester,$this->classes[0]['id']);
        }

        if($this->timetables->isEmpty()) {
            $this->timetables = null;
        }
    }

    public function updatedClass()
    {
        //get current semester
        $semester = auth()->user()->school->semester_id;
        //get timetables in semester and class
        $this->timetables = collect(App::make(TimetableService::class)->getAllTimetablesInSemesterAndClass($semester,$this->class));

        if($this->timetables->isEmpty()) {
            $this->timetables = null;
        }
    }

    public function render()
    {
        return view('livewire.list-timetables-table');
    }
}
