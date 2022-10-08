<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryService
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'per_page' => ['bail', 'sometimes', 'integer', 'between:2,10'],
        ]);

        return Category::withCount('products')->paginate($validated['per_page'] ?? 5);
    }
}
