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

    public static function update($request){
        if (!isset($request->title) || !isset($request->date) || !isset($request->description) || !isset($request->location)) {
            return response()->json(['data'=> null, 'statusCode' => 401, 'msg' => 'At least one field must be submitted.'],200);
        }
        
        if (VacationPlan::where('date', $request->date)->exists()) 
            return response()->json(['data'=> null, 'statusCode' => 401, 'msg' => 'Already has a VacationPlanValidator to this date!'],200);

        if (VacationPlan::where('title', $request->title)->exists()) 
            return response()->json(['data'=> null, 'statusCode' => 401, 'msg' => 'Already has a VacationPlanValidator with this title!'],200);
        
        return false ;
    }
}