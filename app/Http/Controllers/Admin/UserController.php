<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtering
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $users = $query->latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }
 

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
       $roles = User::getAllRoles();


        $donorTypes = [
            'individual',
            'organization',
            'anonymous',
        ];

        return view('admin.users.create', compact('roles', 'donorTypes'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'nullable|string|max:20',
            'password'    => 'required|string|min:6|confirmed',
            'role'        => 'required|in:admin,super_admin,secretary,assistant_secretary,treasurer,member,donor',
            'donor_type'  => 'nullable|in:individual,organization,anonymous',
            'is_active'   => 'nullable|boolean',
        ]);

        User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'password'     => Hash::make($request->password),
            'role'         => $request->role,
            'donor_type'   => $request->donor_type,
            'is_anonymous' => $request->donor_type === 'anonymous' ? 1 : 0,
            'is_active'    => $request->is_active ?? 1,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = [
            'admin',
            'super_admin',
            'secretary',
            'assistant_secretary',
            'treasurer',
            'member',
            'donor',
        ];

        $donorTypes = [
            'individual',
            'organization',
            'anonymous',
        ];

        return view('admin.users.edit', compact('user', 'roles', 'donorTypes'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'password'    => 'nullable|string|min:6|confirmed',
            'role'        => 'required|in:admin,super_admin,secretary,assistant_secretary,treasurer,member,donor',
            'donor_type'  => 'nullable|in:individual,organization,anonymous',
            'is_active'   => 'nullable|boolean',
        ]);

        $user->update([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'password'     => $request->filled('password') ? Hash::make($request->password) : $user->password,
            'role'         => $request->role,
            'donor_type'   => $request->donor_type,
            'is_anonymous' => $request->donor_type === 'anonymous' ? 1 : 0,
            'is_active'    => $request->is_active ?? 1,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->back()->with('success', 'User status updated successfully.');
    }
}
