<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\VacationPlan;
use \App\Models\Participant;
use \App\Validators\VacationPlanValidator;
use DB;

class VacationPlanController extends Controller
{
    protected $rules = [
        'title' => 'Required|Max:255',
        'description' => 'Required|Max:255',
        'date' => 'Required|ValidDate',
        'location' => 'Required|Max:255'
    ];
    
    public function list(){
        $vacations = VacationPlan::with('participant')->get();

        return response()->json(['msg'=> 'success', 'data'=> $vacations, 'statusCode' => 200],200);
    }

    public function show($id){
        $vacations = VacationPlan::where('id',$id)->with('participant')->first();
        if(!$vacations) return response()->json(['msg'=> 'Vacation Plan not found!', 'data'=> null, 'statusCode' => 401],401);

        return response()->json(['msg'=> 'success', 'data'=> $vacations, 'statusCode' => 200],200);
    }

    public function create(Request $request){
        $response = parent::findErrors($request);
        if ($response) {
            return $response;
        }
        
        $valid = VacationPlanValidator::valid($request);
        if($valid) return $valid ;
        
        DB::beginTransaction();
        try {
            $vacation = VacationPlan::create($request->all());
            if ($request->has('participants')) {
                foreach($request->input('participants') as $index => $participant){
                    $participant['vacation_plan_id'] = $vacation->id;
                    $participants = Participant::create($participant);
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['data'=> $e->getMessage(), 'statusCode' => 200, 'msg' => 'Vacation Plan created'],200);
        }
        DB::commit();

        return response()->json(['data'=> null, 'statusCode' => 200, 'msg' => 'Vacation Plan created'],200);
    }

    public function delete(Request $request){
        $vacations = VacationPlan::where('id',$id)->with('participant')->first();
        if(!$vacations) return response()->json(['msg'=> 'Vacation Plan not found!', 'data'=> null, 'statusCode' => 401],401);
        
        $valid = VacationPlanValidator::valid($request);
        if($valid) return $valid ;
    }
}
