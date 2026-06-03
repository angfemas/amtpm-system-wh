<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nrp' => ['required', 'string', 'max:50'],
        ]);

        $user = User::query()->where('nrp', $request->string('nrp')->trim())->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'nrp' => __('NRP tidak ditemukan.'),
            ]);
        }

        $status = Password::sendResetLink([
            'email' => $user->email,
        ]);

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('nrp'))
                        ->withErrors(['nrp' => __($status)]);
    }
}
