<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project_Timeline extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at']; 
    protected $table = 'project_timeline';
    protected $fillable = [
        'employee_id',
        'department_id',
        'role',
        'campus',
        'project_id',
        'status',
    ];
}
