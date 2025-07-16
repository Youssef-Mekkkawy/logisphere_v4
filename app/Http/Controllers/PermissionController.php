<?php

// File: app/Http/Controllers/PermissionController.php
namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:roles.manage-permissions');
    }

    /**
     * Display a listing of permissions.
     */
    public function index(Request $request)
    {
        $query = Permission::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('group', 'like', "%{$search}%");
            });
        }

        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $permissions = $query->orderBy('group')->orderBy('name')->paginate(20);
        $groups = Permission::getGroups();

        return view('permissions.index', compact('permissions', 'groups'));
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create()
    {
        $groups = Permission::getGroups();
        return view('permissions.create', compact('groups'));
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'group' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['group'] . '.' . $validated['name']);

        // Check if slug already exists
        if (Permission::where('slug', $validated['slug'])->exists()) {
            return back()->withInput()->with('error', 'A permission with this name and group already exists.');
        }

        Permission::create($validated);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission created successfully!');
    }

    /**
     * Display the specified permission.
     */
    public function show(Permission $permission)
    {
        $permission->load(['roles' => function ($query) {
            $query->orderBy('name');
        }]);

        return view('permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission)
    {
        $groups = Permission::getGroups();
        return view('permissions.edit', compact('permission', 'groups'));
    }

    /**
     * Update the specified permission.
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'group' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['group'] . '.' . $validated['name']);

        // Check if slug already exists (except for current permission)
        if (Permission::where('slug', $validated['slug'])->where('id', '!=', $permission->id)->exists()) {
            return back()->withInput()->with('error', 'A permission with this name and group already exists.');
        }

        $permission->update($validated);

        return redirect()->route('permissions.show', $permission)
            ->with('success', 'Permission updated successfully!');
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(Permission $permission)
    {
        // Check if permission is used by any roles
        if ($permission->roles()->count() > 0) {
            return redirect()->route('permissions.index')
                ->with('error', 'Cannot delete permission that is assigned to roles.');
        }

        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission deleted successfully!');
    }

    /**
     * Toggle permission status
     */
    public function toggleStatus(Permission $permission)
    {
        $permission->update(['is_active' => !$permission->is_active]);

        $status = $permission->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Permission {$status} successfully!");
    }
}
