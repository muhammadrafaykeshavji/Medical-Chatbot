<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialLoginController extends Controller
{
    /**
     * Redirect to the OAuth provider
     */
    public function redirectToProvider($provider)
    {
        $allowedProviders = ['google', 'github'];
        
        if (!in_array($provider, $allowedProviders)) {
            return redirect()->route('login')->with('error', 'Invalid social login provider.');
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the OAuth callback
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            // Check if user already exists with this email
            $existingUser = User::where('email', $socialUser->getEmail())->first();
            
            if ($existingUser) {
                // Log in the existing user
                Auth::login($existingUser);
                return redirect()->intended('/dashboard');
            }
            
            // Create new user
            $user = User::create([
                'name' => $socialUser->getName() ?: $socialUser->getNickname() ?: 'User',
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(24)), // Random password since they'll use OAuth
                'email_verified_at' => now(), // Consider OAuth emails as verified
            ]);
            
            Auth::login($user);
            
            return redirect()->intended('/dashboard')->with('success', 'Welcome! Your account has been created successfully.');
            
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Unable to login with ' . ucfirst($provider) . '. Please try again.');
        }
    }
}
