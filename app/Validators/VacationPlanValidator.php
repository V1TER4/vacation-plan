<?php

namespace App\Validators;

use \App\Models\VacationPlan;

class VacationPlanValidator {
    public static function valid($request){
        if (VacationPlan::where('date', $request->date)->exists()) 
            return response()->json(['data'=> null, 'statusCode' => 401, 'msg' => 'Already has a VacationPlanValidator to this date!'],200);

        if (VacationPlan::where('title', $request->title)->exists()) 
            return response()->json(['data'=> null, 'statusCode' => 401, 'msg' => 'Already has a VacationPlanValidator with this title!'],200);

        return false ;
    }
    
    public static function update($request, $vacation){
        if (VacationPlan::where('date', $request->date)->where('id', '<>', $vacation->id)->exists()) 
            return response()->json(['data'=> null, 'statusCode' => 401, 'msg' => 'Already has a VacationPlanValidator to this date!'],200);

        if (VacationPlan::where('title', $request->title)->where('id', '<>', $vacation->id)->exists()) 
            return response()->json(['data'=> null, 'statusCode' => 401, 'msg' => 'Already has a VacationPlanValidator with this title!'],200);

        return false ;
    }
}