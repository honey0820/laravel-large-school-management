<?php

namespace App\Http\Livewire;

use App\Services\MyClass\MyClassService;
use Livewire\Component;

class ListSubjectsTable extends Component
{
    public function mount(MyClassService $myClassService)
    {
        $this->classes = $myClassService->getAllClasses()->load('subjects', 'subjects.teachers');
    }

    public function render()
    {
        return view('livewire.list-subjects-table');
    }
}
