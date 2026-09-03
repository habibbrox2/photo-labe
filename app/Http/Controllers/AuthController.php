<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class AuthController extends Controller
{
    // ─── Login ────────────────────────────────────────────
    public function showLogin()
    {
        return view('auth.login', ['socialProviders' => $this->availableSocialProviders()]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        // Redirect based on role
        return $this->redirectBasedOnRole();
    }

    // ─── Register ─────────────────────────────────────────
    public function showRegister()
    {
        return view('auth.register', ['socialProviders' => $this->availableSocialProviders()]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        auth()->login($user);

        // Send verification email
        if (config('mail.mailers')) {
            $user->sendEmailVerificationNotification();
        }

        return redirect()->intended(route('home'))->with('success', 'Account created successfully! Please verify your email.');
    }

    // ─── Logout ───────────────────────────────────────────
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    // ─── Forgot Password ──────────────────────────────────
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    // ─── Reset Password ──────────────────────────────────
    public function showResetPassword(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->route('token'),
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    // ─── Email Verification ──────────────────────────────
    public function showVerifyEmail()
    {
        return view('auth.verify-email');
    }

    public function sendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('home'));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent!');
    }

    public function verifyEmail(Request $request)
    {
        if (! hash_equals((string) $request->route('id'), (string) $request->user()->getKey())) {
            abort(403);
        }

        if (! hash_equals((string) $request->route('hash'), sha1($request->user()->getEmailForVerification()))) {
            abort(403);
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('home'));
        }

        $request->user()->markEmailAsVerified();

        return redirect()->intended(route('home'))->with('success', 'Email verified successfully!');
    }

    // ─── Profile ──────────────────────────────────────────
    public function showProfile()
    {
        return view('auth.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    // ─── Change Password ─────────────────────────────────
    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (!Hash::check($request->current_password, $request->user()->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

    // ─── Helper ──────────────────────────────────────────
    protected function redirectBasedOnRole()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('home'));
    }

    // ─── Social Login ─────────────────────────────────────
    /** Providers supported via Laravel Socialite. */
    protected array $socialProviders = ['google', 'facebook'];

    /** Providers that have OAuth credentials configured in .env. */
    protected function availableSocialProviders(): array
    {
        return array_values(array_filter(
            $this->socialProviders,
            fn (string $provider) => !empty(config("services.{$provider}.client_id"))
        ));
    }

    /** Send the visitor to the OAuth provider's consent screen. */
    public function redirectToProvider(string $provider)
    {
        if (!in_array($provider, $this->availableSocialProviders())) {
            return redirect()->route('login')
                ->with('error', ucfirst($provider) . ' login is not configured yet. Please sign in with your email.');
        }

        $driver = Socialite::driver($provider);

        // Fall back to the callback route when no explicit redirect URI is set.
        if (empty(config("services.{$provider}.redirect"))) {
            $driver->redirectUrl(route('auth.social.callback', $provider));
        }

        return $driver->redirect();
    }

    /** Handle the OAuth provider's callback. */
    public function handleProviderCallback(string $provider)
    {
        if (!in_array($provider, $this->availableSocialProviders())) {
            return redirect()->route('login')
                ->with('error', ucfirst($provider) . ' login is not configured yet. Please sign in with your email.');
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (Throwable) {
            return redirect()->route('login')
                ->withErrors(['email' => 'We could not sign you in with ' . ucfirst($provider) . '. Please try again or use your email.']);
        }

        if (empty($socialUser->getEmail())) {
            return redirect()->route('login')
                ->withErrors(['email' => ucfirst($provider) . ' did not return an email address for your account. Please sign up with your email instead.']);
        }

        // Match an existing social account first, then a local account by email.
        $user = User::where('provider', $provider)
            ->where('provider_id', (string) $socialUser->getId())
            ->first()
            ?? User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $socialUser->getName()
                    ?: $socialUser->getNickname()
                    ?: explode('@', $socialUser->getEmail())[0],
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(32)),
                'role' => 'customer',
                'email_verified_at' => now(), // verified by the OAuth provider
                'provider' => $provider,
                'provider_id' => (string) $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
            ]);
        } else {
            // First social sign-in for an existing account: link it and mark verified.
            if (empty($user->provider_id)) {
                $user->forceFill([
                    'provider' => $provider,
                    'provider_id' => (string) $socialUser->getId(),
                    'email_verified_at' => $user->email_verified_at ?? now(),
                    'avatar' => $user->avatar ?? $socialUser->getAvatar(),
                ])->save();
            }
        }

        auth()->login($user, true);
        request()->session()->regenerate();

        return $this->redirectBasedOnRole();
    }
}
