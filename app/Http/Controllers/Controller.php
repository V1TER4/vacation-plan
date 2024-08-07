<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private function Required($field, $name){
        if (!isset($field) || empty($field)) {
            return 'Campo '.$name.' é obrigatório';
        }
        return false;
    }
    protected function ValidDate($field, $name){
        if (!empty($field)) {
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$field)) {
                return 'Campo '.$name.' é Inválido';
            }
            if (strtotime($field) <= strtotime(date('Y-m-d'))) {
                return 'Campo '.$name.' é Inválido';
            }
        }
        return false;
    }

    private function Max($field, $name,$length){
        if (isset($field)) {
            if (strlen($field) > $length) {
                return 'O Tamanho Máximo para o campo '.$name.' é de '.$length.' caracteres';
            }
            return false;
        }
    }

    protected function findErrors($request, $id = false){
        try {
            if (!empty($this->rules)) {
                $msg = array();
                $field_name_error = array();
                $count = 0;
                foreach ($this->rules as $field_name => $validations) {
                    $count++;
                    $validate = explode('|', $validations);
                    foreach ($validate as $index => $rule) {
                        $count++;
                        $a = explode(':', $rule);
                        $method = $a[0]; 
                        if ($id) {
                            if (count($a) == 1 && $this->$method($request->input($field_name), $field_name, $id)) {
                                $msg[$count] = $this->$method($request->input($field_name), $field_name, $id);
                                $field_name_error[$count] = $field_name;
                            }                            
                        } else {
                            if (count($a) == 1 && $this->$method($request->input($field_name), $field_name)) {
                                $msg[$count] = $this->$method($request->input($field_name), $field_name);
                                $field_name_error[$count] = $field_name;
                            }
                        }
                        if (count($a) == 2 && $this->$method($request->input($field_name), $field_name ,$a[1])) {
                            $msg[$count] = $this->$method($request->input($field_name), $field_name ,$a[1]);
                                $field_name_error[$count] = $field_name;
                        }
                    }   
                }
                if (!empty($msg)) {
                    sort($msg);
                    sort($field_name_error);
                    return response()->json(['data'=> null, 'msg' => $msg, 'statusCode' => 401, 'field_name' => $field_name_error],401);
                }
            }
        } catch (\Exception $e) {
            if (!env('APP_DEBUG')) {
                    return response()->json(['data'=> null, 'msg' => 'Houve um erro interno', 'statusCode' => 500],500);
                }
            return response()->json(['data'=> null, 'msg' => $e->getMessage(), 'statusCode' => 401],401);
        }


        try {
            if (!empty($this->validators)) {
                $msg = array();
                $count = 0;
                foreach ($this->validators as $method => $fields) {
                    $back = $this->$method($fields, $request);
                    if ($back) {
                        $msg = $back;
                    }
                    if (!empty($msg)) {
                        break;
                    }
                    $count++;
                }
                if (!empty($msg)) {
                    if (!is_array($msg)) {
                        $a = $msg;
                        $msg = array();
                        $msg[] = $a;
                    }

                    sort($msg);
                    return response()->json(['data'=> null, 'msg' => $msg, 'statusCode' => 401],401);
                }
            }
        } catch (\Exception $e) {
            if (!env('APP_DEBUG')) {
                    return response()->json(['data'=> null, 'msg' => 'Houve um erro interno', 'statusCode' => 500],500);
                }
            return response()->json(['data'=> null, 'msg' => $e->getMessage(), 'statusCode' => 401],401);
        }

        return false;
    }
}
