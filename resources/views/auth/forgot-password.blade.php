<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - SSC Project Management Tool</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#1e3a8a] min-h-screen flex items-center justify-center">
    <div class="w-full max-w-4xl p-4 sm:p-6">
        <div class="bg-[#1e3a8a] rounded-lg shadow-lg flex flex-col md:flex-row">
            <!-- Left side - Message -->
            <div class="w-full md:w-1/2 p-4 sm:p-6 md:p-8">
                <div class="mb-6 md:mb-8">
                    <div class="flex justify-center mb-4">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <h2 class="text-xl sm:text-2xl font-bold text-white mb-4">Forgot Your Password?</h2>
                    
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="bg-white/10 rounded-lg p-4 mb-6">
                        <p class="text-white text-sm mb-4">Are you an administrator? Reset your password below:</p>
                        
                        <form method="POST" action="{{ route('password.admin.request') }}" class="space-y-4">
                            @csrf
                            <div>
                                <input type="email" name="email" id="email" required
                                    class="w-full px-4 py-2 rounded-md bg-white border-0 focus:ring-2 focus:ring-blue-500"
                                    placeholder="Enter your admin email"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="w-full bg-[#60a5fa] text-white py-2 px-4 rounded-md hover:bg-blue-600 transition-colors duration-300">
                                Send Reset Code
                            </button>
                        </form>
                        
                        @if(session('verification_sent'))
                        <div class="mt-4">
                            <form method="POST" action="{{ route('password.admin.verify') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="email" value="{{ session('admin_email') }}">
                                <div>
                                    <input type="text" name="verification_code" id="verification_code" required
                                        class="w-full px-4 py-2 rounded-md bg-white border-0 focus:ring-2 focus:ring-blue-500"
                                        placeholder="Enter verification code">
                                    @error('verification_code')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <input type="password" name="password" id="password" required
                                        class="w-full px-4 py-2 rounded-md bg-white border-0 focus:ring-2 focus:ring-blue-500"
                                        placeholder="New password">
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <input type="password" name="password_confirmation" id="password_confirmation" required
                                        class="w-full px-4 py-2 rounded-md bg-white border-0 focus:ring-2 focus:ring-blue-500"
                                        placeholder="Confirm new password">
                                </div>
                                <button type="submit" class="w-full bg-[#60a5fa] text-white py-2 px-4 rounded-md hover:bg-blue-600 transition-colors duration-300">
                                    Reset Password
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                    
                    <p class="text-gray-300 mb-6">For non-admin users, please contact the administrator for assistance in resetting your password.</p>
                    <div class="bg-white/10 rounded-lg p-4 mb-6">
                        <p class="text-white text-sm">Administrator Contact:</p>
                        <p class="text-white font-medium">{{ env('ADMIN_EMAIL', 'admin@admin.com') }}</p>
                    </div>
                    <a href="{{ route('login') }}" class="inline-block bg-[#60a5fa] text-white py-2 px-4 rounded-md hover:bg-blue-600 transition-colors duration-300">
                        Back to Login
                    </a>
                </div>
            </div>

            <!-- Right side - Logo -->
            <div class="w-full md:w-1/2 p-4 sm:p-6 md:p-8 flex flex-col items-center justify-center">
                <img src="{{ asset('images/ssc-logo.png') }}" alt="SSC Logo" class="w-full max-w-[200px] md:max-w-xs mb-4">
                <h1 class="text-xl sm:text-2xl font-bold text-white text-center">Project Management Tool</h1>
            </div>
        </div>
    </div>
</body>
</html> 