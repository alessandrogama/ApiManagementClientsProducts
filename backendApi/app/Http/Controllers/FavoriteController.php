<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Product;

class FavoriteController extends Controller
{
    public function index(Client $client)
    {
        $favorites = $client->favorites;
        $products = [];
        foreach ($favorites as $favorite) {
            $product = Product::find($favorite->product_id);
            if ($product) {
                $products[] = [
                    'id' => $product->id,
                    'title' => $product->title,
                    'image' => $product->image,
                    'price' => $product->price,
                    'review' => $product->rating ?? null,
                ];
            }
        }

        return response()->json($products);
    }

    public function store(Request $request, Client $client)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
        ]);

        $productId = $validated['product_id'];

        if (!Product::find($productId)) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        if ($client->favorites()->where('product_id', $productId)->exists()) {
            return response()->json(['message' => 'Product already favorited'], 400);
        }

        $favorite = $client->favorites()->create(['product_id' => $productId]);

        return response()->json($favorite, 201);
    }

    public function destroy(Client $client, $productId)
    {
        $favorite = $client->favorites()->where('product_id', $productId)->first();

        if (!$favorite) {
            return response()->json(['message' => 'Favorite not found'], 404);
        }

        $favorite->delete();

        return response()->json(null, 204);
    }
}