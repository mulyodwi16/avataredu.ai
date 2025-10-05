<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    /**
     * Display user management page
     */
    public function index(Request $request): View
    {
        $query = User::with(['enrolledCourses', 'createdCourses'])->latest();

        // Apply filters
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('verified')) {
            if ($request->verified == '1') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->paginate(15);

        // User statistics
        $stats = [
            'total_users' => User::count(),
            'super_admin_users' => User::where('role', 'super_admin')->count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'regular_users' => User::where('role', 'user')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Display specific user
     */
    public function show(User $user): View
    {
        if ($user->role === 'admin') {
            // For admin users, load created courses
            $userCourses = $user->createdCourses()->with('category')->get();
        } else {
            // For regular users, load enrolled courses
            $userCourses = $user->enrolledCourses()
                ->withPivot(['progress_percentage', 'completed_at'])
                ->with('category')
                ->get();
        }

        return view('admin.users.show', compact('user', 'userCourses'));
    }

    /**
     * Update user (role changes, etc.)
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // Only super admin can change roles
        if (!auth()->user()->isSuperAdmin()) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Only Super Admin can change user roles.');
        }

        // Super admin cannot be demoted by anyone
        if ($user->isSuperAdmin() && $request->role !== 'super_admin') {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Super Admin role cannot be changed.');
        }

        $validated = $request->validate([
            'role' => 'required|in:user,admin,super_admin',
            'is_active' => 'boolean',
        ]);

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }
}