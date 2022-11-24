<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ppmp extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at']; 
    protected $table = 'ppmps';
    protected $fillable = [
        'employee_id',
        'department_id',
        'project_code',
        'campus',
        'item_name',
        'app_type',
        'estimated_price',
        'item_description',
        'quantity',
        'unit_of_measurement',
        'mode_of_procurement',
        'expected_month',
        'status'
    ];
}
