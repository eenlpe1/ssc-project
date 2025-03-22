@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h2 class="text-2xl font-bold mb-6">PROFILE</h2>

    <!-- Profile Card -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <div class="flex items-center gap-8">
            <!-- Profile Section -->
            <div class="flex flex-col items-center">
                <div class="w-32 h-32 bg-gray-100 rounded-lg mb-3 overflow-hidden">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                <h4 class="font-semibold text-lg mb-2">{{ $user->name }}</h4>
                <div class="bg-{{ $user->role === 'admin' ? 'red' : ($user->role === 'adviser' ? 'yellow' : 'green') }}-100 rounded-full px-3 py-1">
                    <span class="text-sm font-medium text-{{ $user->role === 'admin' ? 'red' : ($user->role === 'adviser' ? 'yellow' : 'green') }}-800">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="flex-1 grid grid-cols-3 gap-4">
                <!-- Stars -->
                <div class="flex flex-col items-center justify-center bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-4xl font-bold">{{ $user->total_stars }}</span>
                        <span class="text-3xl text-yellow-400">★</span>
                    </div>
                    <span class="text-gray-600">Stars</span>
                </div>

                <!-- Completed Tasks -->
                <div class="flex flex-col items-center justify-center bg-gray-50 rounded-lg p-4">
                    <div class="text-4xl font-bold text-blue-600 mb-2">
                        {{ $completedTasks }}
                    </div>
                    <span class="text-gray-600">Completed Tasks</span>
                </div>

                <!-- Completed Projects -->
                <div class="flex flex-col items-center justify-center bg-gray-50 rounded-lg p-4">
                    <div class="text-4xl font-bold text-blue-600 mb-2">
                        {{ $completedProjects }}
                    </div>
                    <span class="text-gray-600">Completed Projects</span>
                </div>
            </div>
        </div>
    </div>

    <!-- About Me Form -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h3 class="text-xl font-bold text-gray-800 mb-6">About Me</h3>
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-8" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6 flex flex-col">
                    <div>
                        <label class="block text-lg font-semibold text-gray-700 mb-2">Profile Picture</label>
                        <input type="file" name="profile_picture" accept="image/*"
                               class="w-full rounded-lg border-gray-300 bg-gray-50 p-3 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-lg file:border-0
                                      file:text-sm file:font-medium
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100">
                    </div>
                    <div>
                        <label class="block text-lg font-semibold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                               class="w-full rounded-lg border-gray-300 bg-gray-50 p-3 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200"
                               required>
                    </div>
                    <div>
                        <label class="block text-lg font-semibold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="w-full rounded-lg border-gray-300 bg-gray-50 p-3 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200"
                               required>
                    </div>

                    <div class="flex-grow mt-auto pt-6">
                        <button type="button" 
                                onclick="showResetPasswordModal()"
                                class="border border-yellow-300 bg-yellow-50 hover:bg-yellow-100 px-4 py-2 rounded-lg text-yellow-600 hover:text-yellow-700 font-medium flex items-center transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            Change Password
                        </button>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-lg font-semibold text-gray-700 mb-2">Nickname</label>
                        <input type="text" name="nickname" value="{{ old('nickname', $user->nickname) }}"
                               class="w-full rounded-lg border-gray-300 bg-gray-50 p-3 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200"
                               placeholder="Enter your preferred nickname">
                    </div>
                    <div>
                        <label class="block text-lg font-semibold text-gray-700 mb-2">Department</label>
                        <input type="text" name="department" value="{{ old('department', $user->department) }}"
                               class="w-full rounded-lg border-gray-300 bg-gray-50 p-3 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200"
                               placeholder="Enter your department">
                    </div>
                    <div>
                        <label class="block text-lg font-semibold text-gray-700 mb-2">Position</label>
                        <input type="text" name="position" value="{{ old('position', $user->position) }}"
                               class="w-full rounded-lg border-gray-300 bg-gray-50 p-3 focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200"
                               placeholder="Enter your position">
                    </div>
                    <div>
                        <label class="block text-lg font-semibold text-gray-700 mb-2">Role</label>
                        <div class="w-full rounded-lg bg-gray-50 p-3 text-gray-600">
                            {{ ucfirst($user->role) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-8 pt-6 border-t border-gray-200">
                <button type="submit" 
                        class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors duration-300 flex items-center gap-2 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reset Password Modal -->
<div id="resetPasswordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Change Your Password</h3>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeResetPasswordModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="resetPasswordForm" action="{{ route('profile.reset-password') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Current Password</label>
                    <input type="password" name="current_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">New Password</label>
                    <input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50" onclick="closeResetPasswordModal()">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">Reset Password</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function showResetPasswordModal() {
        document.getElementById('resetPasswordModal').classList.remove('hidden');
        document.getElementById('resetPasswordModal').classList.add('flex');
    }

    function closeResetPasswordModal() {
        document.getElementById('resetPasswordModal').classList.add('hidden');
        document.getElementById('resetPasswordModal').classList.remove('flex');
    }

    // Close modal when clicking outside
    document.getElementById('resetPasswordModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('resetPasswordModal')) {
            closeResetPasswordModal();
        }
    });

    // Close modal when pressing escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeResetPasswordModal();
        }
    });
</script>
@endpush
@endsection 