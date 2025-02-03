@extends('layouts.app')

@section('content')
<div class="p-6 mt-20">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
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

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">USERS</h2>
        @if(Auth::user()->canCreateUsers())
            <button id="addUserBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add User
            </button>
        @endif
    </div>

    <!-- User Filters -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="flex border-b">
            <a href="{{ route('users.index') }}" 
               class="px-6 py-3 {{ $currentRole === 'all' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-blue-600 hover:border-b-2 hover:border-blue-600' }}">
                All
            </a>
            <a href="{{ route('users.index', ['role' => 'admin']) }}" 
               class="px-6 py-3 {{ $currentRole === 'admin' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-blue-600 hover:border-b-2 hover:border-blue-600' }}">
                Admin
            </a>
            <a href="{{ route('users.index', ['role' => 'adviser']) }}" 
               class="px-6 py-3 {{ $currentRole === 'adviser' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-blue-600 hover:border-b-2 hover:border-blue-600' }}">
                Adviser
            </a>
            <a href="{{ route('users.index', ['role' => 'user']) }}" 
               class="px-6 py-3 {{ $currentRole === 'user' ? 'text-blue-600 border-b-2 border-blue-600 font-medium' : 'text-gray-500 hover:text-blue-600 hover:border-b-2 hover:border-blue-600' }}">
                User
            </a>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full whitespace-nowrap">
            <thead>
                <tr class="bg-[#1e3a8a] text-white">
                    <th class="px-6 py-4 text-center">#</th>
                    <th class="px-6 py-4 text-left">Name</th>
                    <th class="px-6 py-4 text-left">Email</th>
                    <th class="px-6 py-4 text-center">Role</th>
                    <th class="px-6 py-4 text-center">Stars</th>
                    <th class="px-6 py-4 text-center">Tasks</th>
                    <th class="px-6 py-4 text-center">Projects</th>
                    @if(Auth::user()->canDeleteUsers())
                    <th class="px-6 py-4 text-center">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors duration-200 cursor-pointer" onclick="showUserDetails({{ $user->id }})">
                        <td class="px-6 py-4 text-center text-blue-600 font-medium">{{ $user->id }}</td>
                        <td class="px-6 py-4 text-blue-600 font-medium">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($user->role === 'admin') bg-red-100 text-red-800
                                @elseif($user->role === 'adviser') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800
                                @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-yellow-500">★</span> {{ $user->stars }}
                        </td>
                        <td class="px-6 py-4 text-center">{{ $user->task_count }}</td>
                        <td class="px-6 py-4 text-center">{{ $user->project_count }}</td>
                        @if(Auth::user()->canDeleteUsers())
                        <td class="px-6 py-4 text-center">
                            <button onclick="event.stopPropagation(); deleteUser({{ $user->id }})" 
                                    class="text-red-600 hover:text-red-800 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No users found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add User Modal -->
<div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Add New User</h3>
            <button class="text-gray-500 hover:text-gray-700" onclick="closeModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Select Role</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="adviser" {{ old('role') === 'adviser' ? 'selected' : '' }}>Adviser</option>
                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50" onclick="closeModal()">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create User</button>
            </div>
        </form>
    </div>
</div>

<!-- User Details Modal -->
<div id="userDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-4/5 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center">
            <h3 class="text-2xl font-bold">User Details</h3>
            <button onclick="closeUserDetails()" class="text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="mt-4">
            <div class="space-y-4">
                <div>
                    <h4 class="text-lg font-semibold">Name</h4>
                    <p id="userName" class="text-gray-600"></p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold">Email</h4>
                    <p id="userEmail" class="text-gray-600"></p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold">Role</h4>
                    <p id="userRole" class="text-gray-600"></p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold">Member Since</h4>
                    <p id="userCreatedAt" class="text-gray-600"></p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('addUserModal');
    const addUserBtn = document.getElementById('addUserBtn');

    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    addUserBtn.addEventListener('click', openModal);

    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Close modal when pressing escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    // Show modal if there are validation errors
    @if($errors->any())
        openModal();
    @endif

    function showUserDetails(userId) {
        fetch(`/users/${userId}`)
            .then(response => response.json())
            .then(user => {
                document.getElementById('userName').textContent = user.name;
                document.getElementById('userEmail').textContent = user.email;
                document.getElementById('userRole').textContent = user.role.toUpperCase();
                document.getElementById('userCreatedAt').textContent = user.created_at;
                document.getElementById('userDetailsModal').classList.remove('hidden');
            });
    }

    function closeUserDetails() {
        document.getElementById('userDetailsModal').classList.add('hidden');
    }

    function deleteUser(userId) {
        if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            return;
        }

        fetch(`/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.error || 'Error deleting user');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting user');
        });
    }
</script>
@endpush
@endsection 