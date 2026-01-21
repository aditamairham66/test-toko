<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        return Product::where('store_id', auth('api')->user()->store_id)
            ->when(
                $request->search,
                fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
            )
            ->paginate(10);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric'
        ]);

        Product::create([
            'store_id' => auth('api')->user()->store_id,
            'name' => $request->name,
            'price' => $request->price
        ]);

        return response()->json(['message' => 'Product created']);
    }
}
