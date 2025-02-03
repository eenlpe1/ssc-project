<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Check if user has access
        if (auth()->user()->role === 'user') {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
        }

        try {
            $query = User::query();

            // Filter by role if provided
            if ($request->has('role')) {
                $query->where('role', $request->role);
            }

            $users = $query->orderBy('name')->get();

            return view('users.index', [
                'users' => $users,
                'currentRole' => $request->role ?? 'all'
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading users: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // Check if user has access
        if (!auth()->user()->canCreateUsers()) {
            return redirect()->route('dashboard')->with('error', 'Only administrators can create new users.');
        }

        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|string|in:admin,adviser,user',
            ]);

            $validated['password'] = Hash::make($validated['password']);

            User::create($validated);

            DB::commit();
            return redirect()->route('users.index')
                ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating user: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, User $user)
    {
        // Check if user has access
        if (auth()->user()->role === 'user') {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to perform this action.');
        }

        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'role' => 'required|string|in:admin,adviser,user',
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            if (isset($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            DB::commit();
            return redirect()->route('users.index')
                ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating user: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(User $user)
    {
        // Check if user has access
        if (!auth()->user()->canDeleteUsers()) {
            return response()->json([
                'success' => false,
                'error' => 'Only administrators can delete users.'
            ], 403);
        }

        try {
            DB::beginTransaction();
            
            if ($user->id === auth()->id()) {
                throw new \Exception('Cannot delete your own account.');
            }
            
            $user->delete();
            
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Error deleting user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(User $user)
    {
        // Check if user has access
        if (auth()->user()->role === 'user') {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this information.');
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'created_at' => $user->created_at->format('M d, Y'),
        ]);
    }
} 