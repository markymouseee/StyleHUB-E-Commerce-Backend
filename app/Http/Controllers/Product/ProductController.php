<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Traits\HandleJsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    use HandleJsonResponse;

    public function __construct(protected readonly ProductRepository $productRepository) {}

    public function index(Request $request)
    {
        $filters = $request->only(['category', 'search']);

        return response()->json($this->productRepository->listAll($filters));
    }

    public function getAll()
    {
        return Product::all();
    }

    public function show($id)
    {
        return response()->json($this->productRepository->find($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/products'), $filename); // move to public folder
            $validated['image_path'] = 'images/products/' . $filename; // save relative path
        }

        return response()->json($this->productRepository->create($validated));
    }

    public function update(Request $request, $id)
    {
        $data = $request->only([
            'name',
            'category_id',
            'price',
            'stock',
            'description',
            'image_path'
        ]);

        return response()->json($this->productRepository->update($id, $data));
    }

    public function destroy($id)
    {
        $this->productRepository->delete($id);

        return $this->success([], 'Product deleted successfully.');
    }
}
