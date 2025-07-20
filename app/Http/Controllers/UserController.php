<?php

// File: app/Http/Controllers/UserController.php (Fixed Index Method)
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:users.view')->only(['index', 'show']);
        // $this->middleware('permission:users.create')->only(['create', 'store']);
        // $this->middleware('permission:users.edit')->only(['edit', 'update']);
        // $this->middleware('permission:users.delete')->only(['destroy']);
        // $this->middleware('permission:permissions.view')->only(['index', 'show']);
        // $this->middleware('permission:permissions.create')->only(['create', 'store']);
        // $this->middleware('permission:permissions.edit')->only(['edit', 'update']);
        // $this->middleware('permission:permissions.delete')->only(['destroy']);
        // $this->middleware('permission:roles.view')->only(['index', 'show']);
        // $this->middleware('permission:roles.create')->only(['create', 'store']);
        // $this->middleware('permission:roles.edit')->only(['edit', 'update']);
        // $this->middleware('permission:roles.delete')->only(['destroy']);
    }

    /**
     * Display the integrated user management interface.
     */
    public function index(Request $request)
    {
        try {
            // 🔥 DEBUG: Check current user permissions
            $currentUser = auth()->user();

            // Get users with filters
            $query = User::with('roles');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($request->filled('role')) {
                $query->whereHas('roles', function ($q) use ($request) {
                    $q->where('slug', $request->role);
                });
            }

            $users = $query->orderBy('name')->paginate(20);

            // Get all roles with their permissions and users count
            $roles = Role::with(['permissions', 'users'])->get();

            // Get all permissions grouped by group
            $permissions = Permission::with('roles')->orderBy('group')->orderBy('name')->get();

            // User statistics
            $userStats = [
                'total' => User::count(),
                'active' => User::whereNotNull('last_login')
                    ->where('last_login', '>=', now()->subDays(30))
                    ->count(),
                'admins' => User::whereHas('roles', function ($q) {
                    $q->where('slug', 'admin');
                })->count(),
                'online_today' => User::whereNotNull('last_login')
                    ->whereDate('last_login', today())
                    ->count(),
            ];

            // 🔥 DEBUG INFO - Remove after fixing
            if ($request->has('debug')) {
                dd([
                    'current_user' => $currentUser->name,
                    'is_admin' => $currentUser->isAdmin(),
                    'roles' => $currentUser->getRoleSlugs(),
                    'permissions' => $currentUser->getAllPermissions()->pluck('slug')->toArray(),
                    'has_users_view' => $currentUser->hasPermission('users.view')
                ]);
            }

            return view('users.index', compact('users', 'roles', 'permissions', 'userStats'));
        } catch (\Exception $e) {
            // Show the actual error for debugging
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }


    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);

        try {
            $validated['password'] = Hash::make($validated['password']);
            $roles = $validated['roles'] ?? [];
            unset($validated['roles']);

            $user = User::create($validated);

            // Assign roles if provided
            if (!empty($roles)) {
                $user->roles()->sync($roles);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User created successfully!',
                    'user' => $user->load('roles')
                ]);
            }

            return redirect()->route('users.index')
                ->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create user: ' . $e->getMessage()
                ], 400);
            }

            return back()->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created role.
     */
    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        try {
            $validated['slug'] = Str::slug($validated['name']);
            $validated['is_active'] = true;

            // Check if slug already exists
            if (Role::where('slug', $validated['slug'])->exists()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'A role with this name already exists.'
                    ], 400);
                }
                return back()->withInput()->with('error', 'A role with this name already exists.');
            }

            $permissions = $validated['permissions'] ?? [];
            unset($validated['permissions']);

            $role = Role::create($validated);

            // Sync permissions
            if (!empty($permissions)) {
                $role->permissions()->sync($permissions);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Role created successfully!',
                    'role' => $role->load('permissions')
                ]);
            }

            return redirect()->route('users.index')
                ->with('success', 'Role created successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create role: ' . $e->getMessage()
                ], 400);
            }

            return back()->withInput()
                ->with('error', 'Failed to create role: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created permission.
     */
    public function storePermission(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'group' => 'required|string|max:100',
            'new_group' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000'
        ]);

        try {
            // Use new_group if provided and group is "new"
            if ($validated['group'] === 'new' && !empty($validated['new_group'])) {
                $validated['group'] = $validated['new_group'];
            }

            $validated['slug'] = Str::slug($validated['group'] . '.' . $validated['name']);
            $validated['is_active'] = true;

            // Remove new_group from validated data
            unset($validated['new_group']);

            // Check if slug already exists
            if (Permission::where('slug', $validated['slug'])->exists()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'A permission with this name and group already exists.'
                    ], 400);
                }
                return back()->withInput()->with('error', 'A permission with this name and group already exists.');
            }

            $permission = Permission::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Permission created successfully!',
                    'permission' => $permission
                ]);
            }

            return redirect()->route('users.index')
                ->with('success', 'Permission created successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create permission: ' . $e->getMessage()
                ], 400);
            }

            return back()->withInput()
                ->with('error', 'Failed to create permission: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['roles.permissions' => function ($query) {
            $query->orderBy('group')->orderBy('name');
        }]);

        $groupedPermissions = $user->getAllPermissions()->groupBy('group');

        return view('users.show', compact('user', 'groupedPermissions'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::active()->get();
        $userRoles = $user->roles->pluck('id')->toArray();

        return view('users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $user->id . '|max:50',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);

        try {
            $roles = $validated['roles'] ?? [];
            unset($validated['roles']);

            // Only update password if provided
            if (empty($validated['password'])) {
                unset($validated['password']);
            } else {
                $validated['password'] = Hash::make($validated['password']);
            }

            $user->update($validated);

            // Sync roles
            $user->roles()->sync($roles);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User updated successfully!',
                    'user' => $user->load('roles')
                ]);
            }

            return redirect()->route('users.show', $user)
                ->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update user: ' . $e->getMessage()
                ], 400);
            }

            return back()->withInput()
                ->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account!'
                ], 400);
            }
            return back()->with('error', 'You cannot delete your own account!');
        }

        try {
            $user->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User deleted successfully!'
                ]);
            }

            return redirect()->route('users.index')
                ->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete user: ' . $e->getMessage()
                ], 400);
            }

            return redirect()->route('users.index')
                ->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Delete a role.
     */
    public function destroyRole(Role $role)
    {
        // Prevent deletion of system roles
        if ($role->is_system) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'System roles cannot be deleted.'
                ], 400);
            }
            return redirect()->route('users.index')
                ->with('error', 'System roles cannot be deleted.');
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete role that has users assigned to it.'
                ], 400);
            }
            return redirect()->route('users.index')
                ->with('error', 'Cannot delete role that has users assigned to it.');
        }

        try {
            $role->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Role deleted successfully!'
                ]);
            }

            return redirect()->route('users.index')
                ->with('success', 'Role deleted successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete role: ' . $e->getMessage()
                ], 400);
            }

            return redirect()->route('users.index')
                ->with('error', 'Failed to delete role: ' . $e->getMessage());
        }
    }

    /**
     * Delete a permission.
     */
    public function destroyPermission(Permission $permission)
    {
        // Check if permission is used by any roles
        if ($permission->roles()->count() > 0) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete permission that is assigned to roles.'
                ], 400);
            }
            return redirect()->route('users.index')
                ->with('error', 'Cannot delete permission that is assigned to roles.');
        }

        try {
            $permission->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Permission deleted successfully!'
                ]);
            }

            return redirect()->route('users.index')
                ->with('success', 'Permission deleted successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete permission: ' . $e->getMessage()
                ], 400);
            }

            return redirect()->route('users.index')
                ->with('error', 'Failed to delete permission: ' . $e->getMessage());
        }
    }

    /**
     * Get role details for editing (AJAX)
     */
    public function getRole(Role $role)
    {
        $role->load('permissions');
        return response()->json([
            'success' => true,
            'role' => $role
        ]);
    }

    /**
     * Get permission details for editing (AJAX)
     */
    public function getPermission(Permission $permission)
    {
        return response()->json([
            'success' => true,
            'permission' => $permission
        ]);
    }

    /**
     * Get user details for editing (AJAX)
     */
    public function getUser(User $user)
    {
        $user->load('roles');
        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
}
