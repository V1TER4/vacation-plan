<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use \App\Models\VacationPlan;
use PDF;

class PdfController extends Controller
{
    protected $rules = [
        'date' => 'Required|ValidDate',
    ];

    public function export(Request $request){
        $response = parent::findErrors($request);
        if ($response) {
            return $response;
        }
        try {
            $vacationPlans = VacationPlan::with('participant')->where('date', $request->date)->get();
            if ($vacationPlans->count() == 0) return response()->json(['msg'=> 'Vacation Plan not found!', 'data'=> null, 'statusCode' => 401],401);
    
            $pdf = PDF::loadView('PDF.pdf', compact('vacationPlans'));
            
            $filePath = 'public/vacation_plans/vacation_plans_'.date('Ymd_His').'.pdf';
            $test = Storage::put($filePath, $pdf->output());
        } catch (\Exception $th) {
            return response()->json(['msg'=> null, 'data'=> $e->getMessage(), 'statusCode' => 400],400);
        }

        return response()->json(['msg'=> 'Export success!', 'data'=> null, 'statusCode' => 200],200);
    }
}
