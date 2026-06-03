<x-industrial-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
                <p class="mt-1 text-sm text-gray-500">Manage your account information and preferences</p>
            </div>
            <div class="flex items-center space-x-3">
                <x-industrial-button variant="secondary" icon="arrow-left">
                    Back to Dashboard
                </x-industrial-button>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column - Profile Info -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Profile Picture -->
            <x-industrial-card title="Profile Picture">
                <div class="text-center">
                    <div class="relative inline-block">
                        <div class="w-32 h-32 bg-gray-200 rounded-full mx-auto flex items-center justify-center">
                            <i class="bi bi-person-fill text-gray-400 text-5xl"></i>
                        </div>
                        <button class="absolute bottom-0 right-0 w-10 h-10 bg-honda-red text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-700 transition-colors">
                            <i class="bi bi-camera text-sm"></i>
                        </button>
                    </div>
                    
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ Auth::user()->name }}</h3>
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                        <p class="text-xs text-gray-400 mt-1">NRP: {{ Auth::user()->nrp ?? 'N/A' }}</p>
                    </div>
                    
                    <div class="mt-4 space-y-2">
                        <button class="btn-secondary w-full text-sm">
                            <i class="bi bi-upload mr-2"></i> Upload Photo
                        </button>
                        <button class="text-sm text-gray-500 hover:text-gray-700">
                            Remove Photo
                        </button>
                    </div>
                </div>
            </x-industrial-card>

            <!-- Account Status -->
            <x-industrial-card title="Account Status">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Account Type</span>
                        <span class="text-sm font-medium text-gray-900">Administrator</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Status</span>
                        <x-status-badge status="completed" text="Active" />
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Member Since</span>
                        <span class="text-sm font-medium text-gray-900">Jan 15, 2023</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Last Login</span>
                        <span class="text-sm font-medium text-gray-900">2 hours ago</span>
                    </div>
                </div>
            </x-industrial-card>

            <!-- Quick Actions -->
            <x-industrial-card title="Quick Actions">
                <div class="space-y-3">
                    <button class="w-full text-left p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="bi bi-key text-gray-600"></i>
                            <span class="text-sm font-medium text-gray-900">Change Password</span>
                        </div>
                        <i class="bi bi-chevron-right text-gray-400"></i>
                    </button>
                    
                    <button class="w-full text-left p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="bi bi-shield-check text-gray-600"></i>
                            <span class="text-sm font-medium text-gray-900">Two-Factor Auth</span>
                        </div>
                        <i class="bi bi-chevron-right text-gray-400"></i>
                    </button>
                    
                    <button class="w-full text-left p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="bi bi-bell text-gray-600"></i>
                            <span class="text-sm font-medium text-gray-900">Notifications</span>
                        </div>
                        <i class="bi bi-chevron-right text-gray-400"></i>
                    </button>
                </div>
            </x-industrial-card>
        </div>

        <!-- Right Column - Forms -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Personal Information -->
            <x-industrial-card title="Personal Information">
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <input type="text" 
                                   value="{{ Auth::user()->name ?? '' }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                            <input type="text" 
                                   placeholder="Enter last name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" 
                               value="{{ Auth::user()->email ?? '' }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" 
                               placeholder="+62 812-3456-7890"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent">
                            <option>Maintenance</option>
                            <option>Operations</option>
                            <option>Warehouse</option>
                            <option>Management</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                        <textarea rows="4" 
                                  placeholder="Tell us about yourself..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent"></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" class="btn-secondary">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary">
                            Save Changes
                        </button>
                    </div>
                </form>
            </x-industrial-card>

            <!-- Password Change -->
            <x-industrial-card title="Change Password">
                <form class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                        <input type="password" 
                               placeholder="Enter current password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" 
                               placeholder="Enter new password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                        <input type="password" 
                               placeholder="Confirm new password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent">
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" class="btn-secondary">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary">
                            Update Password
                        </button>
                    </div>
                </form>
            </x-industrial-card>

            <!-- Danger Zone -->
            <x-industrial-card title="Danger Zone" class="border-red-200">
                <div class="space-y-4">
                    <div class="p-4 bg-red-50 rounded-lg">
                        <h4 class="text-sm font-medium text-red-900 mb-2">Delete Account</h4>
                        <p class="text-sm text-red-700 mb-4">
                            Once you delete your account, there is no going back. Please be certain.
                        </p>
                        <button class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm">
                            <i class="bi bi-trash mr-2"></i> Delete Account
                        </button>
                    </div>
                </div>
            </x-industrial-card>
        </div>
    </div>
</x-industrial-layout>
