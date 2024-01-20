<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'validateToken']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): Object
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'errors' => [
                    'message' => 'Email atau kata sandi tidak valid'
                ]
            ], 401);
        }
        $user = auth()->user();
        return $this->respondWithToken($token, $user);
    }

    /**
     * Register a new user
     * Untuk daftar user baru
     */
    public function register(Request $request): Object
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return $this->respondWithToken(auth()->login($user), $user);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(): Object
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): Object
    {
        auth()->logout();
        return response()->json(['message' => 'Berhasil Keluar']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): Object
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Validate the user token and return the user object if authenticated.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateToken(): Object
    {
        if (auth()->check()) {
            $user = auth()->user();

            return response()->json([
                'valid' => auth()->check(),
                'userData' => $user,
            ]);
        } else {
            return response()->json([
                'valid' => auth()->check(),
            ]);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user = null): Object
    {
        if ($user) {
            return response()->json([
                'accessToken' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'userData' => $user
            ]);
        }
        return response()->json([
            'accessToken' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
