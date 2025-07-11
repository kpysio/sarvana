@extends('layouts.admin')

@section('title', 'Edit User - ' . $user->name)

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit User</h2>
            <p class="text-gray-600">{{ $user->name }} ({{ $user->email }})</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.users.show', $user) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                Back to User
            </a>
        </div>
    </div>
</div>

<div class="bg-white p-6 rounded-lg shadow">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PATCH')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h3>
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    @error('phone')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea id="address" name="address" rows="3" 
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">{{ old('address', $user->address) }}</textarea>
                    @error('address')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                    <textarea id="bio" name="bio" rows="3" 
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Account Settings -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Account Settings</h3>
                
                <div>
                    <label for="user_type" class="block text-sm font-medium text-gray-700 mb-2">User Type</label>
                    <select id="user_type" name="user_type" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="customer" {{ old('user_type', $user->user_type) === 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="provider" {{ old('user_type', $user->user_type) === 'provider' ? 'selected' : '' }}>Provider</option>
                        <option value="admin" {{ old('user_type', $user->user_type) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('user_type')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="membership_status" class="block text-sm font-medium text-gray-700 mb-2">Membership Status</label>
                    <select id="membership_status" name="membership_status" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="active" {{ old('membership_status', $user->membership_status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ old('membership_status', $user->membership_status) === 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="pending" {{ old('membership_status', $user->membership_status) === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                    @error('membership_status')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_verified" value="1" 
                               {{ old('is_verified', $user->is_verified) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                        <span class="ml-2 text-sm text-gray-700">Verified Account</span>
                    </label>
                    @error('is_verified')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('admin.users.show', $user) }}" 
               class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                Update User
            </button>
        </div>
    </form>
</div>
@endsection 