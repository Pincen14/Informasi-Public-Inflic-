<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'    => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value, '@student.com') && !str_ends_with($value, '@admin.com')) {
                        $fail('masukkan email yang sesuai');
                    }
                }
            ],
            'phone'    => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $role = str_ends_with($validated['email'], '@admin.com') ? 'admin' : 'user';

        $user = User::create([
            'name'     => $validated['name'],  
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'],
            'role'     => $role,
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);



        /**
         * Redirect ke dashboard,
         * dashboard akan handle redirect berdasarkan role
         */
        return redirect()->route('dashboard');
    }
}
