<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'vacation_plan_id'
    ];

    public function vacation_plan()
    {
        return $this->belongsTo(VacationPlan::class, 'vacation_plan_id', 'id');
    }
}
