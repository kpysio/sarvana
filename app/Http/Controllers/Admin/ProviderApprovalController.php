<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\ProviderStatusNotification;

class ProviderApprovalController extends Controller
{
    public function index()
    {
        $pendingProviders = User::where('user_type', 'provider')->where('membership_status', 'pending')->get();
        return view('admin.providers.pending', compact('pendingProviders'));
    }

    public function approve($id)
    {
        $provider = User::where('user_type', 'provider')->where('id', $id)->firstOrFail();
        $provider->membership_status = 'active';
        $provider->save();
        $provider->notify(new ProviderStatusNotification('approved'));
        return back()->with('status', 'Provider approved successfully.');
    }

    public function reject($id)
    {
        $provider = User::where('user_type', 'provider')->where('id', $id)->firstOrFail();
        $provider->membership_status = 'rejected';
        $provider->save();
        $provider->notify(new ProviderStatusNotification('rejected'));
        return back()->with('status', 'Provider rejected.');
    }
} 