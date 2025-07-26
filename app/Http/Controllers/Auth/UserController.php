<?php

// File: app/Http/Controllers/UserController.php (COMPLETE RBAC VERSION)
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // 🔥 TEMPORARILY DISABLED FOR TESTING - Re-enable after fixing
        $this->middleware('permission:users.view')->only(['index', 'show']);
        $this->middleware('permission:users.create')->only(['create', 'store']);
        $this->middleware('permission:users.edit')->only(['edit', 'update']);
        $this->middleware('permission:users.delete')->only(['destroy']);
    }

    /**
     * Display the integrated user management interface.
     */
    public function index(Request $request)
    {
        try {
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

            return view('auth.users.index', compact('users', 'roles', 'permissions', 'userStats'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading user management: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::active()->get();
        return view('auth.users.create', compact('roles'));
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

            return redirect()->route('auth.users.index')
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
     * Display the specified user.
     */
    public function show(User $user)
    {
        try {
            // Load roles with their permissions
            $user->load(['roles' => function ($query) {
                $query->with('permissions')->orderBy('name');
            }]);

            // Get grouped permissions safely
            $groupedPermissions = $user->getAllPermissions()->groupBy('group');

            return view('auth.users.show', compact('user', 'groupedPermissions'));
        } catch (\Exception $e) {
            return redirect()->route('auth.users.index')
                ->with('error', 'Error loading user details: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        try {
            // Load user roles
            $user->load('roles');

            // Get all active roles
            $roles = Role::where('is_active', true)->get();

            // Get user's role IDs
            $userRoles = $user->roles->pluck('id')->toArray();

            return view('auth.users.edit', compact('user', 'roles', 'userRoles'));
        } catch (\Exception $e) {
            return redirect()->route('auth.users.index')
                ->with('error', 'Error loading user edit form: ' . $e->getMessage());
        }
    }


    // Replace the update method in your UserController.php with this version that includes debugging

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        // 🔥 DEBUG: Log the request details
        Log::info('UserController@update called', [
            'user_id' => $user->id,
            'request_method' => $request->method(),
            'request_url' => $request->url(),
            'form_data' => $request->except(['password', 'password_confirmation']),
            'has_password' => $request->filled('password')
        ]);

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

            // 🔥 DEBUG: Log what we're updating
            Log::info('Updating user with data', [
                'user_id' => $user->id,
                'update_data' => array_keys($validated),
                'roles_count' => count($roles)
            ]);

            $user->update($validated);

            // Sync roles
            $user->roles()->sync($roles);

            // 🔥 DEBUG: Log successful update
            Log::info('User updated successfully', [
                'user_id' => $user->id,
                'user_name' => $user->name
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User updated successfully!',
                    'user' => $user->load('roles')
                ]);
            }

            return redirect()->route('auth.users.show', $user)
                ->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            // 🔥 DEBUG: Log any errors
            Log::error('User update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

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

            return redirect()->route('auth.users.index')
                ->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete user: ' . $e->getMessage()
                ], 400);
            }

            return redirect()->route('auth.users.index')
                ->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    // ===== 🔥 ROLE MANAGEMENT METHODS =====

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
                return response()->json([
                    'success' => false,
                    'message' => 'A role with this name already exists.'
                ], 400);
            }

            $permissions = $validated['permissions'] ?? [];
            unset($validated['permissions']);

            $role = Role::create($validated);

            // Sync permissions
            if (!empty($permissions)) {
                $role->permissions()->sync($permissions);
            }

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully!',
                'role' => $role->load('permissions')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create role: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Delete a role.
     */
    public function destroyRole(Role $role)
    {
        // Prevent deletion of system roles
        if ($role->is_system) {
            return response()->json([
                'success' => false,
                'message' => 'System roles cannot be deleted.'
            ], 400);
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete role that has users assigned to it.'
            ], 400);
        }

        try {
            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete role: ' . $e->getMessage()
            ], 400);
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

    // ===== 🔥 PERMISSION MANAGEMENT METHODS =====

    /**
     * Store a newly created permission.
     */


    // Verify this method exists in your UserController.php
    // If it doesn't, add this method:

    /**
     * Store a newly created permission.
     */
    public function storePermission(Request $request)
    {
        // Add debug logging
        Log::info('storePermission called', [
            'request_data' => $request->all(),
            'user' => auth()->user()->name
        ]);

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
                Log::warning('Permission slug already exists', ['slug' => $validated['slug']]);

                return response()->json([
                    'success' => false,
                    'message' => 'A permission with this name and group already exists.'
                ], 400);
            }

            $permission = Permission::create($validated);

            Log::info('Permission created successfully', [
                'permission_id' => $permission->id,
                'slug' => $permission->slug
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permission created successfully!',
                'permission' => $permission
            ]);
        } catch (\Exception $e) {
            Log::error('Permission creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create permission: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Delete a permission.
     */
    public function destroyPermission(Permission $permission)
    {
        // Check if permission is used by any roles
        if ($permission->roles()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete permission that is assigned to roles.'
            ], 400);
        }

        try {
            $permission->delete();

            return response()->json([
                'success' => true,
                'message' => 'Permission deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete permission: ' . $e->getMessage()
            ], 400);
        }
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
