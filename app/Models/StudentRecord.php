<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\MyClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentRecord extends Model
{
    use HasFactory;

    protected $fillable = ['admission_number', 'admission_date', 'my_class_id', 'section_id'];

     /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'admission_date' => 'datetime:Y-m-d'
    ];

    //accessor for admission_date

    public function getAdmissionDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y/m/d');
    }

    /**
     * Get the MyClass that owns the Section
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function myClass()
    {
        return $this->belongsTo(MyClass::class);
    }

    /**
     * Get the section that owns the StudentRecord
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the user that owns the StudentRecord
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
