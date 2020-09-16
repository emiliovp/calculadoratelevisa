<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comparelaboraconcilia extends Model
{
    protected $table = 'compare_labora_concilia';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'employee_number',
        'name',
        'lastname1',
        'lastname2',
        'created',
        'origen_id',
        'consecutive',
        'operation'
    ];
    public function employeeByNumber($number)
    {
        return Comparelaboraconcilia::where("employee_number", "like",$number. '%')
        ->where("origen_id", "<>", 999)
        ->limit(5)
        ->get()
        ->toArray();
    }
}
