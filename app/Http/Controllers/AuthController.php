<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use App\Models\User;
use App\Constants\Statuses;
use Hash;

class AuthController extends Controller
{
    public function auth(Request $request){
        $user = User::where('email', $request->get('email'))->first();
        if($user->status_id != Statuses::ATIVO) return response()->json(['data' => null, 'msg' => ['Usuário não está ativo'], 'statusCode' => 401], 401);

        if(!$user){
            return response()->json(['data' => null, 'msg' => ['Usuário/Senha inválidos'], 'statusCode' => 401], 401);
        }
        
        $password = Hash::check($request->get('password'), $user->password);
        if(!$password){
            return response()->json(['data' => null, 'msg' => ['Usuário/Senha inválidos'], 'statusCode' => 401], 401);
        }
        
        $token = self::token_generate($user);

        $user->token = $token;
        $user->save();
        
        return $user;
    }

    public function token_generate($user){
        $time = 60*60;
        if(env('JWT_EXPIRED_TIME')){
            $time = (int) env('JWT_EXPIRED_TIME');
        }
        $payload = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + $time // Expiration time
        ];
        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }
}
