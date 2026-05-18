<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\MongoLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request, MongoLogService $logger)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $logger->record('activity_logs', ['action' => 'login', 'user_id' => Auth::id(), 'ip' => $request->ip()]);

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register', [
            'roles' => Role::whereIn('slug', ['advocate', 'client'])->get(),
        ]);
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);
        $status = Password::sendResetLink($request->only('email'));

        return back()->with('status', __($status));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
            'role_id' => ['required', 'exists:roles,id'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $role = Role::findOrFail($data['role_id']);
        abort_unless(in_array($role->slug, ['advocate', 'client'], true), 422);

        $user = User::create($data);
        Auth::login($user);

        return redirect()->route('dashboard')->with('status', 'Registration completed.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
