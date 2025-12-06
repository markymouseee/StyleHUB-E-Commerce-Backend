<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function listAll(array $filters = [])
    {
        $query = Product::with('category');

        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        return $query->paginate(12);
    }

    public function find(int $id): Product
    {
        return Product::with('category')->findOrFail($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(int $id, array $data): Product
    {
        $product = Product::findOrFail($id);
        $product->update($data);

        return $product;
    }

    public function delete(int $id): bool
    {
        return Product::destroy($id);
    }
}
