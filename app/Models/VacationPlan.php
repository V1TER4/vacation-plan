<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacationPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'date', 'location'
    ];

    public function participant(){
        return $this->hasMany(Participant::class, 'vacation_plan_id', 'id');
    }
}
