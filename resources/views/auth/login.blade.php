<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SSC Project Management Tool</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#1e3a8a] min-h-screen flex items-center justify-center">
    <div class="w-full max-w-4xl p-4 sm:p-6">
        <div class="bg-[#1e3a8a] rounded-lg shadow-lg flex flex-col md:flex-row">
            <!-- Left side - Login Form -->
            <div class="w-full md:w-1/2 p-4 sm:p-6 md:p-8">
                <div class="mb-6 md:mb-8">
                    <div class="flex justify-center mb-4">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-6">
                    @csrf
                    <div>
                        <input type="email" name="email" id="email" required
                            class="w-full px-4 py-2 rounded-md bg-white border-0 focus:ring-2 focus:ring-blue-500"
                            placeholder="admin@admin.com"
                            value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="relative">
                            <input type="password" name="password" id="password" required
                                class="w-full px-4 py-2 rounded-md bg-white border-0 focus:ring-2 focus:ring-blue-500"
                                placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('password.forgot') }}" class="text-sm text-gray-300 hover:text-white transition-colors duration-200">
                            Forgot Password?
                        </a>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full bg-[#60a5fa] text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            LOG IN
                        </button>
                    </div>
                </form>
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