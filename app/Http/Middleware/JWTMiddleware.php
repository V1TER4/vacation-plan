<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (env('CHECK_TOKEN')) {
            $token = $request->bearerToken();
            if(!$token) {
                return response()->json([
                    'data' => null,
                    'statusCode' => 402,
                    'msg' => 'Token não encontrado.'
                ], 402);
            }
            try {
                $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
            } catch(ExpiredException $e) {
                return response()->json([
                    'data' => null,
                    'statusCode' => 402,
                    'msg' => 'Token expirado.'
                ], 402);
            } catch(Exception $e) {
                return response()->json([
                    'data' => null,
                    'statusCode' => 402,
                    'msg' => 'Token é inválido.'
                ], 402);
            }
        } else {
            try {
                $token = $request->bearerToken();
                if(!$token) return response()->json(['data' => null,'statusCode' => 402,'msg' => 'Token é inválido.'], 402); 
                $credentials = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            } catch(\ExpiredException $e) {
                return response()->json([
                    'data' => null,
                    'statusCode' => 402,
                    'msg' => 'Token expirado.'
                ], 402);
            } catch(\Exception $e) {
                return response()->json([
                    'data' => null,
                    'statusCode' => 402,
                    'msg' => 'Token é inválido.'
                ], 402);
            }
        }

        return $next($request);
    }
}
