<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoodItem;
use App\Models\Tag;
use App\Models\User;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = FoodItem::with(['provider', 'tags'])
            ->where('status', 'active')
            ->where('available_date', '>=', now()->format('Y-m-d'));

        // Search by keyword
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('description', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Filter by postcode
        if ($request->filled('postcode')) {
            $query->whereHas('provider', function ($q) use ($request) {
                $q->where('postcode', $request->postcode);
            });
        }

        // Filter by tags
        if ($request->filled('tags')) {
            $tagIds = is_array($request->tags) ? $request->tags : explode(',', $request->tags);
            $query->whereHas('tags', function ($q) use ($tagIds) {
                $q->whereIn('tags.id', $tagIds);
            });
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by rating
        if ($request->filled('min_rating')) {
            $query->whereHas('provider', function ($q) use ($request) {
                $q->where('rating', '>=', $request->min_rating);
            });
        }

        // Sorting
        $sort = $request->get('sort', 'created_at');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->join('users', 'food_items.provider_id', '=', 'users.id')
                      ->orderBy('users.rating', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $foodItems = $query->paginate(12);
        
        // Get all tags for filters
        $tags = Tag::where('is_active', true)->get()->groupBy('category');
        
        // Get unique postcodes for location filter
        $postcodes = User::where('user_type', 'provider')
            ->distinct()
            ->pluck('postcode')
            ->filter()
            ->sort();

        // Precompute food items for modal JS (array, not Collection)
        $foodItemsJson = json_encode($foodItems->map(function($item) {
            return array(
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'price' => $item->price,
                'available_date' => $item->available_date,
                'available_quantity' => $item->available_quantity,
                'status' => $item->status,
                'photos' => $item->photos,
                'tags' => $item->tags->map(function($tag) {
                    return array(
                        'id' => $tag->id,
                        'name' => $tag->name,
                        'icon' => $tag->icon,
                        'color' => $tag->color,
                    );
                })->all(),
                'provider' => array(
                    'id' => $item->provider->id,
                    'name' => $item->provider->name,
                    'rating' => $item->provider->rating,
                ),
            );
        })->values()->all());

        return view('search.index', compact('foodItems', 'tags', 'postcodes', 'foodItemsJson'));
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

        return view('search.show', compact('foodItem', 'relatedItems'));
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

        return view('search.providers', compact('providers'));
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

        return view('search.provider', compact('provider', 'foodItems', 'reviews'));
    }
}
