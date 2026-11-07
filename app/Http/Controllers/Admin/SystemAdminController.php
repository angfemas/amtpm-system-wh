<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppLogo;
use App\Models\User;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SystemAdminController extends Controller
{
    protected ImageCompressionService $imageCompression;

    public function __construct(ImageCompressionService $imageCompression)
    {
        $this->imageCompression = $imageCompression;
    }

    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'total_roles' => Role::count(),
            'total_permissions' => Permission::count(),
            'recent_users' => User::latest()->take(5)->get(),
        ];

        return view('admin.system.dashboard', compact('stats'));
    }

    // ==================== USERS MANAGEMENT ====================

    public function usersIndex()
    {
        $users = User::with('roles')->latest()->paginate(10);
        $roles = Role::all();
        $sections = config('sections.sections');

        return view('admin.system.users.index', compact('users', 'roles', 'sections'));
    }

    public function usersCreate()
    {
        $roles = Role::all();
        $sections = config('sections.sections');

        return view('admin.system.users.create', compact('roles', 'sections'));
    }

    public function usersStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'nrp' => 'required|string|unique:users,nrp',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'section' => 'nullable|in:' . implode(',', array_keys(config('sections.sections'))),
            'status' => 'required|in:active,inactive,suspended',
            'notification_enabled' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nrp' => $request->nrp,
            'password' => Hash::make($request->password),
            'section' => $request->section,
            'status' => $request->status,
            'notification_enabled' => $request->boolean('notification_enabled', true),
        ]);

        $user->assignRole($request->role);

        Log::info('User created by admin', ['user_id' => $user->id, 'created_by' => auth()->id()]);

        return redirect()->route('admin.system.users.index')
            ->with('success', 'User created successfully.');
    }

    public function usersEdit(User $user)
    {
        $roles = Role::all();
        $sections = config('sections.sections');
        $user->load('roles');

        return view('admin.system.users.edit', compact('user', 'roles', 'sections'));
    }

    public function usersUpdate(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'nrp' => 'required|string|unique:users,nrp,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'section' => 'nullable|in:' . implode(',', array_keys(config('sections.sections'))),
            'status' => 'required|in:active,inactive,suspended',
            'notification_enabled' => 'boolean',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'nrp' => $request->nrp,
            'section' => $request->section,
            'status' => $request->status,
            'notification_enabled' => $request->boolean('notification_enabled', true),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Sync role
        $user->syncRoles([$request->role]);

        Log::info('User updated by admin', ['user_id' => $user->id, 'updated_by' => auth()->id()]);

        return redirect()->route('admin.system.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function usersDestroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.system.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        Log::info('User deleted by admin', ['user_id' => $user->id, 'deleted_by' => auth()->id()]);

        return redirect()->route('admin.system.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function usersToggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active'
        ]);

        return redirect()->route('admin.system.users.index')
            ->with('success', 'User status updated successfully.');
    }

    // ==================== ROLES MANAGEMENT ====================

    public function rolesIndex()
    {
        $roles = Role::with('permissions')->latest()->paginate(10);
        $permissions = Permission::all()->groupBy(function ($item) {
            return explode('.', $item->name)[0];
        });

        return view('admin.system.roles.index', compact('roles', 'permissions'));
    }

    public function rolesCreate()
    {
        $permissions = Permission::all()->groupBy(function ($item) {
            return explode('.', $item->name)[0];
        });

        return view('admin.system.roles.create', compact('permissions'));
    }

    public function rolesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        Log::info('Role created by admin', ['role_id' => $role->id, 'created_by' => auth()->id()]);

        return redirect()->route('admin.system.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function rolesEdit(Role $role)
    {
        $role->load('permissions');
        $permissions = Permission::all()->groupBy(function ($item) {
            return explode('.', $item->name)[0];
        });

        return view('admin.system.roles.edit', compact('role', 'permissions'));
    }

    public function rolesUpdate(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]);
        }

        Log::info('Role updated by admin', ['role_id' => $role->id, 'updated_by' => auth()->id()]);

        return redirect()->route('admin.system.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function rolesDestroy(Role $role)
    {
        $role->delete();

        Log::info('Role deleted by admin', ['role_id' => $role->id, 'deleted_by' => auth()->id()]);

        return redirect()->route('admin.system.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    // ==================== PERMISSIONS MANAGEMENT ====================

    public function permissionsIndex()
    {
        $permissions = Permission::latest()->paginate(20);
        $groupedPermissions = Permission::all()->groupBy(function ($item) {
            return explode('.', $item->name)[0];
        });

        return view('admin.system.permissions.index', compact('permissions', 'groupedPermissions'));
    }

    public function permissionsCreate()
    {
        return view('admin.system.permissions.create');
    }

    public function permissionsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);

        Log::info('Permission created by admin', ['permission_name' => $request->name, 'created_by' => auth()->id()]);

        return redirect()->route('admin.system.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    public function permissionsEdit(Permission $permission)
    {
        return view('admin.system.permissions.edit', compact('permission'));
    }

    public function permissionsUpdate(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update(['name' => $request->name]);

        Log::info('Permission updated by admin', ['permission_id' => $permission->id, 'updated_by' => auth()->id()]);

        return redirect()->route('admin.system.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    public function permissionsDestroy(Permission $permission)
    {
        $permission->delete();

        Log::info('Permission deleted by admin', ['permission_id' => $permission->id, 'deleted_by' => auth()->id()]);

        return redirect()->route('admin.system.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }

    // ==================== SECTIONS MANAGEMENT ====================

    public function sectionsIndex()
    {
        $sections = config('sections.sections');
        $usersBySection = [];

        foreach ($sections as $key => $name) {
            $usersBySection[$key] = User::where('section', $key)->count();
        }

        return view('admin.system.sections.index', compact('sections', 'usersBySection'));
    }

    public function sectionsCreate()
    {
        return view('admin.system.sections.create');
    }

    public function sectionsStore(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:sections,key',
            'name' => 'required|string',
        ]);

        // For now, we'll just show a message that sections need to be added to config
        // In production, you might want to make sections database-driven

        return redirect()->route('admin.system.sections.index')
            ->with('info', 'To add sections, please edit config/sections.php file manually for now.');
    }

    // ==================== ACTIVITY LOG ====================

    public function activityLog()
    {
        // Using Laravel's built-in logging or you can use spatie/laravel-activitylog package
        // For now, we'll display a message to install the package if needed
        return view('admin.system.activity-log');
    }

    // ==================== SETTINGS MANAGEMENT ====================

    public function settingsIndex()
    {
        return view('admin.system.settings.index');
    }

    public function settingsUpdate(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'default_role' => 'required|in:admin,leader,operator',
            'default_section' => 'nullable|in:' . implode(',', array_keys(config('sections.sections'))),
            'max_width' => 'nullable|integer|min:800|max:4096',
            'max_height' => 'nullable|integer|min:600|max:4096',
            'quality' => 'nullable|integer|min:10|max:100',
            'convert_to_webp' => 'nullable|boolean',
            'progressive_jpeg' => 'nullable|boolean',
            'optimize_png' => 'nullable|boolean',
        ]);

        // Update app settings in .env or database
        // For now, we'll use AppSetting model
        \App\Models\AppSetting::setValue('site_name', $request->site_name);
        \App\Models\AppSetting::setValue('site_description', $request->site_description);
        \App\Models\AppSetting::setValue('default_role', $request->default_role);
        \App\Models\AppSetting::setValue('default_section', $request->default_section);

        // Update image compression settings in .env
        if ($request->has('max_width')) {
            $this->updateEnvSetting('IMAGE_MAX_WIDTH', $request->max_width);
        }
        if ($request->has('max_height')) {
            $this->updateEnvSetting('IMAGE_MAX_HEIGHT', $request->max_height);
        }
        if ($request->has('quality')) {
            $this->updateEnvSetting('IMAGE_QUALITY', $request->quality);
        }
        if ($request->has('convert_to_webp')) {
            $this->updateEnvSetting('IMAGE_CONVERT_TO_WEBP', $request->convert_to_webp ? 'true' : 'false');
        }
        if ($request->has('progressive_jpeg')) {
            $this->updateEnvSetting('IMAGE_PROGRESSIVE_JPEG', $request->progressive_jpeg ? 'true' : 'false');
        }
        if ($request->has('optimize_png')) {
            $this->updateEnvSetting('IMAGE_OPTIMIZE_PNG', $request->optimize_png ? 'true' : 'false');
        }

        // Clear config cache
        Artisan::call('config:clear');

        Log::info('Settings updated by admin', ['updated_by' => auth()->id()]);

        return redirect()->route('admin.system.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    protected function updateEnvSetting($key, $value)
    {
        $envContent = file_get_contents(base_path('.env'));
        $pattern = "/^{$key}=.*/m";

        if (preg_match($pattern, $envContent)) {
            $envContent = preg_replace($pattern, "{$key}={$value}", $envContent);
        } else {
            $envContent .= "\n{$key}={$value}";
        }

        file_put_contents(base_path('.env'), $envContent);
    }

    // ==================== LOGO MANAGEMENT ====================

    public function logoIndex()
    {
        $logos = AppLogo::all();
        return view('admin.system.logo.index', compact('logos'));
    }

    public function logoCreate()
    {
        return view('admin.system.logo.create');
    }

    public function logoStore(Request $request)
    {
        $validated = $request->validate([
            'logo_type' => 'required|string|max:50',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
            'alt_text' => 'nullable|string|max:255',
            'width' => 'nullable|integer|min:10|max:2000',
            'height' => 'nullable|integer|min:10|max:2000',
            'is_active' => 'boolean',
        ]);

        $data = [
            'logo_type' => $validated['logo_type'],
            'alt_text' => $validated['alt_text'] ?? null,
            'width' => $validated['width'] ?? null,
            'height' => $validated['height'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('image_path')) {
            $data['image_path'] = $this->storeLogoImage($request->file('image_path'));
        }

        if ($data['is_active']) {
            AppLogo::byType($data['logo_type'])->active()->update(['is_active' => false]);
        }

        AppLogo::create($data);

        Log::info('Logo created by admin', ['logo_type' => $data['logo_type'], 'created_by' => auth()->id()]);

        return redirect()->route('admin.system.logo.index')
            ->with('success', 'Logo created successfully.');
    }

    public function logoEdit(AppLogo $logo)
    {
        return view('admin.system.logo.edit', compact('logo'));
    }

    public function logoUpdate(Request $request, AppLogo $logo)
    {
        $validated = $request->validate([
            'logo_type' => 'required|string|max:50',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
            'alt_text' => 'nullable|string|max:255',
            'width' => 'nullable|integer|min:10|max:2000',
            'height' => 'nullable|integer|min:10|max:2000',
            'is_active' => 'boolean',
        ]);

        $data = [
            'logo_type' => $validated['logo_type'],
            'alt_text' => $validated['alt_text'] ?? $logo->alt_text,
            'width' => $validated['width'] ?? $logo->width,
            'height' => $validated['height'] ?? $logo->height,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('image_path')) {
            if ($logo->image_path) {
                Storage::disk('public')->delete($logo->image_path);
            }

            $data['image_path'] = $this->storeLogoImage($request->file('image_path'));
        }

        if ($data['is_active']) {
            AppLogo::byType($data['logo_type'])->active()->where('id', '!=', $logo->id)->update(['is_active' => false]);
        }

        $logo->update($data);

        Log::info('Logo updated by admin', ['logo_id' => $logo->id, 'updated_by' => auth()->id()]);

        return redirect()->route('admin.system.logo.index')
            ->with('success', 'Logo updated successfully.');
    }

    public function logoDestroy(AppLogo $logo)
    {
        if ($logo->image_path) {
            Storage::disk('public')->delete($logo->image_path);
        }

        $logo->delete();

        Log::info('Logo deleted by admin', ['logo_id' => $logo->id, 'deleted_by' => auth()->id()]);

        return redirect()->route('admin.system.logo.index')
            ->with('success', 'Logo deleted successfully.');
    }

    /**
     * Store logo image, falling back to the original file when compression fails
     * (e.g. SVG or other formats the image driver cannot process).
     */
    private function storeLogoImage(UploadedFile $file): string
    {
        try {
            return $this->imageCompression->compressAndStore($file, 'logos', 'public');
        } catch (\Throwable $e) {
            Log::warning('Logo compression failed, storing original file', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
            ]);

            return $file->store('logos', 'public');
        }
    }
}
