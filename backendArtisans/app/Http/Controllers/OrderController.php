<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function addToCart(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $order = Order::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'pending'],
            ['total_price' => 0]
        );

        $order->products()->attach($product->id, [
            'quantity' => $request->quantity,
            'price_at_purchase' => $product->price
        ]);

        // Mise à jour du total de la commande
        $order->total_price += $product->price * $request->quantity;
        $order->save();

        return response()->json(['message' => 'Product added to cart', 'order' => $order]);
    }
}
