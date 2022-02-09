<?php

namespace App\Models;

use App\Models\MyClass;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'my_class_id'];

    /**
     * Get the MyClass that owns the Section
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function MyClass()
    {
        return $this->belongsTo(MyClass::class);
    }
}
