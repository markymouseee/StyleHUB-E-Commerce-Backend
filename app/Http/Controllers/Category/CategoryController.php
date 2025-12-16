<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\HandleJsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use HandleJsonResponse;

    public function index()
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        return Category::create($validated) ?
            $this->success([], 'Category created successfully')
            :
            $this->error(['error' => 'Failed to add category']);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        return $category->delete() ?
            $this->success([], 'Category deleted successfully')
            :
            $this->error(['error' => 'Failed to delete category']);
    }
}
