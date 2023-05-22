<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\Student\StudentService;
use App\Services\AcademicYear\AcademicYearService;

class ListPromotionsTable extends Component
{
    public $academicYear;
    public $promotions;

    public function mount(StudentService $studentService,AcademicYearService $academicYearService)
    {
        if(!$this->academicYear) {
            $this->academicYear = auth()->user()->school->load('academicYear')->academicYear->first();
        }else{
            $this->academicYear = $academicYearService->getAcademicYearById($this->academicYear);
        }
        $this->promotions = $studentService->getPromotionsByAcademicYearId($this->academicYear->id);
    }

    public function render()
    {
        return view('livewire.list-promotions-table');
    }
}
