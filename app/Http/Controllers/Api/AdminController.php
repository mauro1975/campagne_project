<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $totalOrders  = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalProducts = Product::count();

        $salesByMonth = Order::where('payment_status', 'paid')
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total) as revenue, COUNT(*) as count')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
            ->limit(12)
            ->get();

        $topProducts = DB::table('order_items')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->selectRaw('products.id, products.name, SUM(order_items.quantity) as total_sold, SUM(order_items.price * order_items.quantity) as revenue')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $salesByCategory = DB::table('order_items')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->selectRaw('categories.name, SUM(order_items.quantity) as total_sold')
            ->groupBy('categories.id', 'categories.name')
            ->get();

        return response()->json(compact(
            'totalRevenue', 'totalOrders', 'pendingOrders', 'totalProducts',
            'salesByMonth', 'topProducts', 'salesByCategory'
        ));
    }

    public function orders(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return OrderResource::collection($query->paginate(20));
    }

    public function updateOrder(Request $request, Order $order)
    {
        $data = $request->validate([
            'status'         => 'in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'in:pending,paid,failed,refunded',
        ]);

        $order->update($data);

        return new OrderResource($order->load('items'));
    }

    public function products()
    {
        $products = Product::with('category', 'variants')->latest()->paginate(20);

        return ProductResource::collection($products);
    }

    public function updateProduct(Request $request, Product $product)
    {
        $data = $request->validate([
            'price'            => 'numeric|min:0',
            'discount_percent' => 'integer|min:0|max:100',
            'is_active'        => 'boolean',
            'is_best_seller'   => 'boolean',
            'is_featured'      => 'boolean',
        ]);

        $product->update($data);

        return new ProductResource($product->load('category', 'variants'));
    }
}
