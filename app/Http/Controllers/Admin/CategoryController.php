<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoodItem;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = FoodItem::query();
        
        // Get unique categories with counts
        $categories = $query->select('category', DB::raw('count(*) as item_count'))
            ->groupBy('category')
            ->orderBy('category')
            ->get();
        
        // Search by category name
        if ($request->filled('search')) {
            $categories = $categories->filter(function($category) use ($request) {
                return str_contains(strtolower($category->category), strtolower($request->search));
            });
        }
        
        return view('admin.categories.index', compact('categories'));
    }

    public function show($category)
    {
        $foodItems = FoodItem::where('category', $category)
            ->with(['provider', 'tags'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.categories.show', compact('category', 'foodItems'));
    }

    public function update(Request $request, $oldCategory)
    {
        $request->validate([
            'new_category' => 'required|string|max:255',
        ]);

        $newCategory = $request->new_category;
        
        // Update all food items with the old category
        $updated = FoodItem::where('category', $oldCategory)
            ->update(['category' => $newCategory]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Category '{$oldCategory}' renamed to '{$newCategory}'. {$updated} items updated.");
    }

    public function destroy($category)
    {
        // Check if category has items
        $itemCount = FoodItem::where('category', $category)->count();
        
        if ($itemCount > 0) {
            return back()->with('error', "Cannot delete category '{$category}' as it contains {$itemCount} food items.");
        }

        return redirect()->route('admin.categories.index')
            ->with('success', "Category '{$category}' deleted successfully.");
    }

    public function merge(Request $request, $sourceCategory)
    {
        $request->validate([
            'target_category' => 'required|string|max:255|different:source_category',
        ]);

        $targetCategory = $request->target_category;
        
        // Update all food items from source to target category
        $updated = FoodItem::where('category', $sourceCategory)
            ->update(['category' => $targetCategory]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Category '{$sourceCategory}' merged into '{$targetCategory}'. {$updated} items moved.");
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,merge',
            'categories' => 'required|array',
            'target_category' => 'required_if:action,merge|string|max:255',
        ]);

        $categories = $request->categories;
        
        switch ($request->action) {
            case 'delete':
                // Check if any categories have items
                $categoriesWithItems = FoodItem::whereIn('category', $categories)
                    ->select('category')
                    ->distinct()
                    ->pluck('category');
                
                if ($categoriesWithItems->count() > 0) {
                    return back()->with('error', 'Cannot delete categories that contain food items.');
                }
                
                // Since we can't delete categories directly (they're just strings), 
                // we'll just return success as they're effectively "deleted"
                $message = 'Categories marked for deletion.';
                break;
                
            case 'merge':
                $targetCategory = $request->target_category;
                $updated = FoodItem::whereIn('category', $categories)
                    ->update(['category' => $targetCategory]);
                $message = "Categories merged into '{$targetCategory}'. {$updated} items updated.";
                break;
        }

        return back()->with('success', $message);
    }
} 