<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Models\User;
use Illuminate\Http\Request;

class BrowseController extends Controller
{
    /**
     * Display a listing of available food items.
     */
    public function index(Request $request)
    {
        $query = FoodItem::active()->with('provider');

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by date
        if ($request->has('date')) {
            $query->where('available_date', $request->date);
        }

        // Search by title or description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $foodItems = $query->latest()->paginate(12);
        $categories = FoodItem::distinct()->pluck('category');

        return view('browse.index', compact('foodItems', 'categories'));
    }

    /**
     * Display the specified food item.
     */
    public function show(FoodItem $foodItem)
    {
        $foodItem->load('provider', 'reviews.reviewer');
        $relatedItems = FoodItem::active()
            ->where('category', $foodItem->category)
            ->where('id', '!=', $foodItem->id)
            ->take(4)
            ->get();

        return view('browse.show', compact('foodItem', 'relatedItems'));
    }

    /**
     * Display providers listing.
     */
    public function providers(Request $request)
    {
        $query = User::where('user_type', 'provider')
                    ->where('is_verified', true)
                    ->withCount('foodItems');

        // Filter by rating
        if ($request->has('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $providers = $query->latest()->paginate(12);

        return view('browse.providers', compact('providers'));
    }

    /**
     * Display a specific provider's food items.
     */
    public function provider(User $provider)
    {
        if (!$provider->isProvider()) {
            abort(404);
        }

        $foodItems = $provider->foodItems()->active()->latest()->paginate(12);
        $reviews = $provider->providerReviews()->with('reviewer')->latest()->take(5)->get();

        return view('browse.provider', compact('provider', 'foodItems', 'reviews'));
    }
}
