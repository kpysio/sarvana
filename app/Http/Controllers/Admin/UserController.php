<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        // Filter by user type
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('membership_status', 'active');
            } elseif ($request->status === 'inactive') {
                $query->where('membership_status', '!=', 'active');
            }
        }
        
        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $userTypes = User::select('user_type')->distinct()->pluck('user_type');
        $statuses = ['active', 'inactive'];
        
        return view('admin.users.index', compact('users', 'userTypes', 'statuses'));
    }

    public function show(User $user)
    {
        $user->load(['customerOrders', 'providerOrders', 'foodItems', 'reviews']);
        
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'user_type' => 'required|in:customer,provider,admin',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'bio' => 'nullable|string',
            'membership_status' => 'nullable|in:active,expired,pending',
            'is_verified' => 'boolean',
        ]);

        $user->update($request->all());

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->user_type === 'admin') {
            return back()->with('error', 'Cannot delete admin users.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function approveProvider(User $user)
    {
        if ($user->user_type !== 'provider') {
            return back()->with('error', 'User is not a provider.');
        }

        $user->update([
            'membership_status' => 'active',
            'membership_expires_at' => now()->addYear(),
            'is_verified' => true,
        ]);

        return back()->with('success', 'Provider approved successfully.');
    }

    public function rejectProvider(User $user)
    {
        if ($user->user_type !== 'provider') {
            return back()->with('error', 'User is not a provider.');
        }

        $user->update([
            'membership_status' => 'expired',
            'is_verified' => false,
        ]);

        return back()->with('success', 'Provider rejected successfully.');
    }

    public function resetPassword(User $user)
    {
        $newPassword = Str::random(8);
        
        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        // Send email with new password
        Mail::send('emails.password-reset', [
            'user' => $user,
            'password' => $newPassword
        ], function($message) use ($user) {
            $message->to($user->email)
                    ->subject('Password Reset - Sarvana');
        });

        return back()->with('success', 'Password reset successfully. New password sent to user email.');
    }

    public function deactivateAccount(User $user)
    {
        if ($user->user_type === 'admin') {
            return back()->with('error', 'Cannot deactivate admin accounts.');
        }

        $user->update([
            'membership_status' => 'expired',
            'membership_expires_at' => now(),
        ]);

        return back()->with('success', 'Account deactivated successfully.');
    }

    public function reactivateAccount(User $user)
    {
        $user->update([
            'membership_status' => 'active',
            'membership_expires_at' => now()->addYear(),
        ]);

        return back()->with('success', 'Account reactivated successfully.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,deactivate,reactivate,delete',
            'users' => 'required|array',
            'users.*' => 'exists:users,id'
        ]);

        $users = User::whereIn('id', $request->users);
        
        switch ($request->action) {
            case 'approve':
                $users->where('user_type', 'provider')->update([
                    'membership_status' => 'active',
                    'membership_expires_at' => now()->addYear(),
                    'is_verified' => true,
                ]);
                $message = 'Providers approved successfully.';
                break;
                
            case 'reject':
                $users->where('user_type', 'provider')->update([
                    'membership_status' => 'expired',
                    'is_verified' => false,
                ]);
                $message = 'Providers rejected successfully.';
                break;
                
            case 'deactivate':
                $users->where('user_type', '!=', 'admin')->update([
                    'membership_status' => 'expired',
                    'membership_expires_at' => now(),
                ]);
                $message = 'Accounts deactivated successfully.';
                break;
                
            case 'reactivate':
                $users->update([
                    'membership_status' => 'active',
                    'membership_expires_at' => now()->addYear(),
                ]);
                $message = 'Accounts reactivated successfully.';
                break;
                
            case 'delete':
                $users->where('user_type', '!=', 'admin')->delete();
                $message = 'Users deleted successfully.';
                break;
        }

        return back()->with('success', $message);
    }
} 