namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Session::get('cart', [
            'order' => new Order(['status' => 'pending', 'total_price' => 0]),
            'items' => []
        ]);

        $cart['order']->total_price += $product->price * $request->quantity;
        $cart['items'][$product->id] = [
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price_at_purchase' => $product->price
        ];

        Session::put('cart', $cart);

        return response()->json(['message' => 'Product added to cart', 'cart' => $cart]);
    }
}
