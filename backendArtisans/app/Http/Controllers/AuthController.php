<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'in:artisan,client' // Seuls artisan et client peuvent être choisis lors de l'inscription
        ]);

        $roleName = $request->input('role', 'user'); // Défaut au rôle 'user' si aucun rôle n'est spécifié
        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            return response()->json(['error' => 'Invalid role'], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

    public function login(Request $request)
        {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            / Vérifier si un panier existe dans la session
                if (Session::has('cart')) {
                    $cart = Session::get('cart');

                    // Associer le panier à l'utilisateur
                    $order = $cart['order'];
                    $order->user_id = $user->id;
                    $order->save();

                    // Sauvegarder les produits associés à l'ordre
                    foreach ($cart['items'] as $item) {
                        $order->products()->attach($item['product_id'], [
                            'quantity' => $item['quantity'],
                            'price_at_purchase' => $item['price_at_purchase']
                        ]);
                    }

                    // Effacer le panier de la session
                    Session::forget('cart');
                }

            return response()->json([
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

        public function logout(Request $request)
        {
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logout successful']);
        }

}
