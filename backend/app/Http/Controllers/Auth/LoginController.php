<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {

            $request->validate([
                'username' => 'required',
                'password' => 'required'
            ]);

            $user = User::where('username', $request->username)
                ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'username' => ['akun tidak ditemukan']
                ]);
            }

            return response()->json([
                'user' => $user,
                'token' => $user->createToken('WEB')->plainTextToken
            ]);
        } catch (\Throwable $th) {

            return response()->json($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout()
    {
        try {
            $user = auth()->user();


            $user->currentAccessToken()->delete();

            return response()->json('', Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $user = auth()->user();

            $user->update([
                'password' => bcrypt($request->password)
            ]);

            return response()->json('OK', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
