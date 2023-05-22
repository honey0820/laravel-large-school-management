<?php

namespace App\Http\Livewire;

use App\Services\MyClass\MyClassService;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class CreateStudentForm extends Component
{
    public $myClasses;
    public $myClass;
    public $sections;
    public $section;
    protected $myClassService;
    public $school = null;
    public bool $includeFormTag = true;

    protected $rules = [
        'myClass' => 'string',
        'section' => 'string',
    ];

    public function mount(MyClassService $myClassService)
    {
        $this->myClasses = $myClassService->getAllClasses($this->school);

        if ($this->myClasses->isNotEmpty()) {
            $this->sections = collect(App::make(MyClassService::class)->getClassById($this->myClasses[0]['id'])->sections);
        }
    }

    public function updatedMyClass()
    {
        $this->reset('section');
        $this->sections = collect(App::make(MyClassService::class)->getClassById($this->myClass)->sections);
    }

    public function render()
    {
        return view('livewire.create-student-form');
    }
}
