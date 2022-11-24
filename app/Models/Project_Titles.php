<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project_Titles extends Model
{
    //
    use SoftDeletes;
    protected $dates = ['deleted_at']; 
    protected $table = 'project_titles';
    protected $fillable = [
        'employee_id',
        'department_id',
        'campus',
        'project_title',
        'project_code',
        'project_year',
        'fund_source',
        'project_type',
        'project_category',
        'allocated_budget',
        'immediate_supervisor',
        'status',
        'remark',
        'year_created'
    ];
}
