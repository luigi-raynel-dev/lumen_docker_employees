<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Occupation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id', 
        'name',
        'department_id'
    ];

    /**
     * The validations for each attribute.
     *
     * @var string[]
     */
    public static $rules = [
        "name" => "required|unique:occupations,name|max:50",
        "department_id" => "required|numeric|exists:departments,id"
    ];

    /**
     * This entity belongs to departments
     * 
     * @return Department
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
