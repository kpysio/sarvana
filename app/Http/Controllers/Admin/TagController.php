<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $query = Tag::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $tags = $query->orderBy('name')->paginate(15)->withQueryString();
        $categories = Tag::distinct()->pluck('category')->filter()->unique()->values();

        return view('admin.tags.index', compact('tags', 'categories'));
    }

    public function create()
    {
        $categories = Tag::select('category')->distinct()->pluck('category');
        return view('admin.tags.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
            'category' => 'required|string|max:255',
            'custom_category' => 'required_if:category,custom|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        $data = $request->all();
        
        // Handle custom category
        if ($request->category === 'custom' && $request->filled('custom_category')) {
            $data['category'] = $request->custom_category;
        }

        Tag::create($data);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag created successfully.');
    }

    public function show(Tag $tag)
    {
        $tag->load(['foodItems' => function($query) {
            $query->with('provider')->latest();
        }]);
        
        return view('admin.tags.show', compact('tag'));
    }

    public function edit(Tag $tag)
    {
        $categories = Tag::select('category')->distinct()->pluck('category');
        return view('admin.tags.edit', compact('tag', 'categories'));
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
            'category' => 'required|string|max:255',
            'custom_category' => 'required_if:category,custom|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        $data = $request->all();
        
        // Handle custom category
        if ($request->category === 'custom' && $request->filled('custom_category')) {
            $data['category'] = $request->custom_category;
        }

        $tag->update($data);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag)
    {
        // Check if tag is being used
        if ($tag->foodItems()->count() > 0) {
            return back()->with('error', 'Cannot delete tag that is being used by food items.');
        }

        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag deleted successfully.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id'
        ]);

        $tags = Tag::whereIn('id', $request->tags);
        
        // Check if any tags are being used
        $usedTags = $tags->whereHas('foodItems')->count();
        if ($usedTags > 0) {
            return back()->with('error', 'Cannot delete tags that are being used by food items.');
        }

        $tags->delete();

        return back()->with('success', 'Tags deleted successfully.');
    }
} 