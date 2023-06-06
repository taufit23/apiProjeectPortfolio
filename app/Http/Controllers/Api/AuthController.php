<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function message()
    {
        return [
            'name.required' => 'Nama harus diisi',
            'name.string' => 'Nama harus berupa huruf',
            'name.max' => 'Nama tidak boleh melebihi 200 karakter',
            'email.required' => 'Alamat emalil harus diisi',
            'email.unique' => 'Alamat email telah digunakan',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Password konfirmasi tidak sama',
            'password.regex' => 'Password harus memiliki karakter spesial'
        ];
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'email' => 'required|unique:users',
            'password' => 'required|min:8|confirmed|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
        ], $this->message());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'code' => 401]);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer']);
    }
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unautorized'], 401);
        }
        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Hi ' .
                $user->name .
                ' Selamat Datang di home',
            'access_token' => $token
        ]);
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'Logout berhasil, semua token anda sudah di hapus'
        ];
    }
}
