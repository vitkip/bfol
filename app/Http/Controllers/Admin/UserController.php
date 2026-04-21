<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('full_name_lo', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhere('username', 'like', "%$s%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        $user = new User();
        return view('admin.user.form', compact('user'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username'     => ['required', 'string', 'max:60', 'unique:users,username', 'alpha_dash'],
            'email'        => ['required', 'email', 'max:120', 'unique:users,email'],
            'full_name_lo' => ['required', 'string', 'max:120'],
            'full_name_en' => ['nullable', 'string', 'max:120'],
            'full_name_zh' => ['nullable', 'string', 'max:120'],
            'role'         => ['required', Rule::in(['superadmin', 'admin', 'editor', 'viewer'])],
            'password'     => ['required', 'confirmed', Password::min(8)],
            'is_active'    => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'ສ້າງຜູ້ໃຊ້ສໍາເລັດແລ້ວ');
    }

    public function show(User $user)
    {
        return redirect()->route('admin.users.edit', $user);
    }

    public function edit(User $user)
    {
        return view('admin.user.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'username'     => ['required', 'string', 'max:60', Rule::unique('users')->ignore($user->id), 'alpha_dash'],
            'email'        => ['required', 'email', 'max:120', Rule::unique('users')->ignore($user->id)],
            'full_name_lo' => ['required', 'string', 'max:120'],
            'full_name_en' => ['nullable', 'string', 'max:120'],
            'full_name_zh' => ['nullable', 'string', 'max:120'],
            'role'         => ['required', Rule::in(['superadmin', 'admin', 'editor', 'viewer'])],
            'password'     => ['nullable', 'confirmed', Password::min(8)],
            'is_active'    => ['nullable', 'boolean'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $data['is_active'] = $request->boolean('is_active');

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'ອັບເດດຜູ້ໃຊ້ສໍາເລັດແລ້ວ');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'ບໍ່ສາມາດລົບບັນຊີຕົນເອງໄດ້');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'ລົບຜູ້ໃຊ້ສໍາເລັດແລ້ວ');
    }
}
