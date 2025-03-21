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
                    <p class="text-gray-300 mb-6">Please contact the administrator for assistance in resetting your password.</p>
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