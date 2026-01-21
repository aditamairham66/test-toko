<?php

namespace App\Http\Controllers\Api\Sale;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'payment' => 'required|numeric'
        ]);

        try {
            $sale = DB::transaction(function () use ($request) {

                $products = Product::whereIn('id', $request->products)->get();

                $total = $products->sum('price');

                if ($request->payment < $total) {
                    throw new \Exception('Payment insufficient');
                }

                $sale = Sale::create([
                    'store_id'   => auth('api')->user()->store_id,
                    'cashier_id' => auth('api')->id(),
                    'total_price' => $total,
                    'payment'    => $request->payment,
                    'change'     => $request->payment - $total
                ]);

                foreach ($products as $product) {
                    SaleItem::create([
                        'sale_id'   => $sale->id,
                        'product_id' => $product->id,
                        'price'     => $product->price
                    ]);
                }

                return $sale;
            });

            return response()->json($sale, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function index()
    {
        return Sale::where('store_id', auth('api')->user()->store_id)
            ->paginate(10);
    }
}
