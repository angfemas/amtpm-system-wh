@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Permissions Management</h2>
                        <p class="text-gray-600 text-sm mt-1">Manage system permissions</p>
                    </div>
                    <a href="{{ route('admin.system.permissions.create') }}"
                       class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add New Permission
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Grouped Permissions -->
                <div class="space-y-6">
                    @foreach($groupedPermissions as $group => $groupPermissions)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">{{ ucfirst($group) }}</h3>
                                <p class="text-sm text-gray-500">{{ $groupPermissions->count() }} permissions</p>
                            </div>
                            <div class="divide-y divide-gray-200">
                                @foreach($groupPermissions as $permission)
                                    <div class="px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $permission->name }}</div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                Used by {{ $permission->roles->count() }} role(s)
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.system.permissions.edit', $permission) }}"
                                               class="text-blue-600 hover:text-blue-900 text-sm">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.system.permissions.destroy', $permission) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this permission?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginated List -->
                @if($permissions->hasPages())
                    <div class="mt-6">
                        {{ $permissions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
