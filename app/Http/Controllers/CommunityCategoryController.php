<?php

namespace App\Http\Controllers;

use App\Models\Category;  // Make sure this line exists
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommunityCategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
{
    // Check if the request is from admin route
    if (request()->is('admin/*')) {
        $categories = Category::orderBy('name')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }
    
    // Regular user view
    $categories = Category::orderBy('name')->get();
    return view('communities.categories.index', compact('categories'));
}


    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        // Check if the request is from admin route
        if (request()->route()->getPrefix() === 'admin') {
            return view('admin.categories.show', compact('category'));
        }
        
        // Regular user view
        $communities = $category->communities()->paginate(12);
        return view('communities.categories.show', compact('category', 'communities'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم إنشاء التصنيف بنجاح');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم تحديث التصنيف بنجاح');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has communities
        if ($category->communities()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'لا يمكن حذف هذا التصنيف لأنه مرتبط بمجتمعات');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم حذف التصنيف بنجاح');
    }
}