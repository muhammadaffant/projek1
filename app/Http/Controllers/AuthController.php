<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;


class AuthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function register()
{
    $validator = Validator::make(request()->all(),[
        'name' => 'required|min:3|max:25',
        'email' => 'required|email|unique:users',
        // 'email' => 'required|email:dns|unique:users',
        'password' => 'required|min:4'
    ]);

    if($validator->fails()){
        return response()->json($validator->messages());
    }

    $email = request('email');
    if (strpos($email, '@gmail.com') === false) {
        return response()->json(['message' => 'Hanya alamat email Gmail yang diizinkan untuk registrasi.']);
    }

    $user = User::create([
        'name' => request('name'),
        'email' => $email,
        'password' => Hash::make(request('password')),
    ]);

    if($user){
        return response()->json(['message' => 'Register Berhasil']);
    } else {
        return response()->json(['message' => 'Register Gagal']);
    }
}


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
{
    $credentials = request(['email', 'password']);

    $user = User::where('email', $credentials['email'])->first();

    if (!$user) {
        return response()->json(['error' => 'Email not found'], 401);
    }

    if (!Hash::check($credentials['password'], $user->password)) {
        return response()->json(['error' => 'Password salah'], 401);
    }

    if (!$token = auth()->attempt($credentials)) {
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
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
