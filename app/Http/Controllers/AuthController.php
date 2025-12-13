<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['usuario', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            "user" => auth()->user()
        ]);
    }

    public function authCheck()
    {

        try {
            $payload = auth()->payload();
            $exp = $payload->get('exp');
            $secondsLeft = $exp - time();
            $valid = auth()->check();
            $code = 200;
            if (!$valid) {
                throw new Exception("No logeado", 401);
            }
            // Log::debug($exp);
            // Log::debug($secondsLeft);
            $refresh = false;
            $token = "";
            if ($secondsLeft < 300) {
                $token = auth()->refresh();
                // Log::debug("TOKEN");
                // Log::debug($token);
                $refresh = true;
            }

            // Log::debug("code: " . $code);
            // Log::debug("VALID: " . $valid);

            return response()->JSON([
                "valid" => $valid,
                'exp' => $exp,
                'secondsLeft' => $secondsLeft,
                "refresh" => $refresh,
                "token" => $token
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Token inválido o expirado'
            ], 401);
        } catch (JWTException $e) {
            Log::debug("Erro JWTException: " . $e->getMessage());
            return response()->json([
                'error' => 'Token inválido o expirado'
            ], 401);
        }
    }
}
