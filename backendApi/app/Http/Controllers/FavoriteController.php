<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Models\Client;
use GuzzleHttp\Client as HttpClient;

class FavoriteController extends Controller
{
    public function index(Client $client)
    {
        $favorites = $client->favorites;
        $products = [];
        $http = new HttpClient();

        foreach ($favorites as $favorite) {
            try {
                $response = $http->get('https://fakestoreapi.com/products/' . $favorite->product_id);
                if ($response->getStatusCode() === 200) {
                    $product = json_decode($response->getBody());
                    $products[] = [
                        'id' => $product->id,
                        'title' => $product->title,
                        'image' => $product->image,
                        'price' => $product->price,
                        'review' => $product->rating->rate ?? null,
                    ];
                }
            } catch (\Exception $e) {
                return response()->json(['message' => 'Product not found'], 404);
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
        $http = new HttpClient();

        try {
            $response = $http->get('https://fakestoreapi.com/products/' . $productId);
            if ($response->getStatusCode() !== 200) {
                return response()->json(['message' => 'Product not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        if ($client->favorites()->where('product_id', $productId)->exists()) {
            return response()->json(['message' => 'Exist Product Favorited'], 400);
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