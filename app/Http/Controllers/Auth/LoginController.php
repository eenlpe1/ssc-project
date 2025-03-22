<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check if user exists
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'The provided email does not exist.',
            ])->onlyInput('email');
        }

        // Attempt to log in
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        // If we reach here, the password was incorrect
        return back()->withErrors([
            'password' => 'Incorrect password.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }
    
    public function adminPasswordResetRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user || !$user->isAdmin()) {
            return back()->with('error', 'No administrator account found with this email.');
        }
        
        // Generate a random 6-digit verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store the verification code in cache for 15 minutes
        $cacheKey = 'admin_reset_' . $user->email;
        Cache::put($cacheKey, $verificationCode, now()->addMinutes(15));
        
        // Send the verification code via Node.js email service
        try {
            $emailServerPort = env('EMAIL_SERVER_PORT', 3333);
            $emailServerUrl = "http://localhost:{$emailServerPort}/send-verification";
            
            $response = Http::post($emailServerUrl, [
                'email' => $user->email,
                'code' => $verificationCode
            ]);
            
            if (!$response->successful()) {
                throw new \Exception('Email service responded with an error: ' . $response->body());
            }
            
            return back()->with([
                'verification_sent' => true,
                'admin_email' => $user->email,
                'success' => 'A verification code has been sent to your email.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email: ' . $e->getMessage());
            return back()->with('error', 'Could not send verification email. Please try again later.');
        }
    }
    
    public function verifyAdminReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'verification_code' => 'required|size:6',
            'password' => 'required|min:8|confirmed',
        ]);
        
        $user = User::where('email', $request->email)->first();
        
        if (!$user || !$user->isAdmin()) {
            return back()->with('error', 'Invalid user.');
        }
        
        $cacheKey = 'admin_reset_' . $user->email;
        $storedCode = Cache::get($cacheKey);
        
        if (!$storedCode || $storedCode !== $request->verification_code) {
            return back()->with([
                'verification_sent' => true,
                'admin_email' => $user->email,
                'error' => 'Invalid or expired verification code.'
            ]);
        }
        
        // Reset the password
        $user->password = Hash::make($request->password);
        $user->save();
        
        // Remove the verification code from cache
        Cache::forget($cacheKey);
        
        return redirect()->route('login')->with('success', 'Your password has been reset successfully. You can now log in with your new password.');
    }
} 