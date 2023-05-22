<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_year',
        'stop_year',
        'school_id',
    ];

    public function name()
    {
        return "$this->start_year - $this->stop_year";
    }

    public function getNameAttribute()
    {
        return "$this->start_year - $this->stop_year";
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }

    /**
     * Get all of the exams for the AcademicYear.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function exams(): HasManyThrough
    {
        return $this->hasManyThrough(Exam::class, Semester::class, 'academic_year_id', 'semester_id', 'id', 'id');
    }

    /**
     * The studentRecords that belong to the AcademicYear.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function studentRecords(): BelongsToMany
    {
        return $this->belongsToMany(StudentRecord::class)->as('studentAcademicYearBasedRecords')->using(AcademicYearStudentRecord::class)->withPivot('my_class_id', 'section_id');
    }
}
