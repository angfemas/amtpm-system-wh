<x-industrial-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="bi bi-person mr-3 text-red-600"></i>
                    {{ trans('messages.edit_profile') }}
                </h1>
                <p class="mt-1 text-sm text-gray-500">Kelola informasi akun dan pengaturan profil Anda</p>
            </div>
        </div>
    </x-slot>

    <!-- Status Messages -->
    @if (session('status'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 mb-4 flex items-center">
            <i class="bi bi-check-circle-fill text-green-600 mr-3 text-xl"></i>
            <div>
                <p class="font-medium">{{ session('status') == 'profile-updated' ? trans('messages.profile_updated') : session('status') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Profile Picture Section -->
                    <div class="lg:col-span-1">
                        <div class="bg-gray-50 rounded-xl p-6 text-center">
                            <div class="mb-4">
                                <img src="{{ $user->profile_photo_url }}" 
                                     alt="{{ $user->name }}" 
                                     class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-white shadow-lg">
                            </div>
                            <div class="space-y-2">
                                <label for="profile_photo" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 cursor-pointer transition">
                                    <i class="bi bi-upload mr-2"></i>
                                    {{ trans('messages.upload_photo') }}
                                </label>
                                <input type="file" 
                                       id="profile_photo" 
                                       name="profile_photo" 
                                       class="hidden"
                                       accept="image/*"
                                       onchange="previewProfilePhoto(this)">
                                
                                @if ($user->profile_photo_path || $user->google_avatar)
                                    <form method="POST" action="{{ route('profile.remove-photo') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-50 border border-red-200 rounded-lg text-sm font-medium text-red-700 hover:bg-red-100 transition">
                                            <i class="bi bi-trash mr-2"></i>
                                            {{ trans('messages.remove_photo') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mt-2">{{ trans('messages.profile_picture') }}</p>
                        </div>
                    </div>

                    <!-- Account Information Section -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Account Info Cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">{{ trans('messages.account_type') }}</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $user->account_type == 'admin' ? 'bg-red-100 text-red-800' : ($user->account_type == 'leader' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ trans('messages.' . $user->account_type) }}
                                </span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">{{ trans('messages.status') }}</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $user->status == 'active' ? 'bg-green-100 text-green-800' : ($user->status == 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                    {{ trans('messages.' . $user->status) }}
                                </span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">{{ trans('messages.member_since') }}</p>
                                <p class="text-sm font-medium text-gray-900">{{ $user->member_since }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">{{ trans('messages.last_login') }}</p>
                                <p class="text-sm font-medium text-gray-900">{{ $user->last_login }}</p>
                            </div>
                        </div>

                        <!-- Section Selection -->
                        <div>
                            <label for="section" class="block text-sm font-medium text-gray-700 mb-2">{{ trans('messages.section') }}</label>
                            <select id="section" name="section" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition" autocomplete="off">
                                <option value="">{{ trans('messages.select_section') }}</option>
                                @foreach($sections as $key => $value)
                                    <option value="{{ $key }}" {{ $user->section == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Quick Actions -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-3">{{ trans('messages.quick_actions') }}</h3>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#changePasswordModal" 
                                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                    <i class="bi bi-key mr-2"></i>
                                    {{ trans('messages.change_password') }}
                                </button>
                                
                                <form method="POST" action="{{ route('profile.toggle-notifications') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition
                                        {{ $user->notification_enabled ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                        <i class="bi bi-{{ $user->notification_enabled ? 'bell' : 'bell-slash' }} mr-2"></i>
                                        {{ $user->notification_enabled ? trans('messages.disable_notifications') : trans('messages.enable_notifications') }}
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="pt-4 border-t border-gray-200">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition shadow-sm">
                                <i class="bi bi-save mr-2"></i>
                                {{ trans('messages.save') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('messages.change_password') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewProfilePhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('img[alt="{{ $user->name }}"]').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-industrial-layout>
