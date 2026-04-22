<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['ອີເມວ ຫຼື ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ'],
            ]);
        }

        $user = Auth::user();

        if (! $user->is_active) {
            Auth::logout();
            return response()->json(['message' => 'ບັນຊີຂອງທ່ານຖືກລ໊ອກ'], 403);
        }

        $user->update(['last_login' => now()]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'          => $user->id,
                'email'       => $user->email,
                'username'    => $user->username,
                'full_name'   => $user->full_name_lo ?: $user->full_name_en,
                'role'        => $user->role,
                'avatar_url'  => $user->avatar_url,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'ອອກຈາກລະບົບສຳເລັດ']);
    }

    public function user(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id'         => $user->id,
            'email'      => $user->email,
            'username'   => $user->username,
            'full_name'  => $user->full_name_lo ?: $user->full_name_en,
            'role'       => $user->role,
            'avatar_url' => $user->avatar_url,
        ]);
    }
}
