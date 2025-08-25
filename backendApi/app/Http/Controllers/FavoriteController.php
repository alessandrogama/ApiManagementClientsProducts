<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Product;
/**
 * @OA\Tag(
 *     name="Favoritos",
 *     description="Funções relacionadas aos produtos favoritos dos clientes"
 * )
 */
class FavoriteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/clients/{client}/favorites",
     *     summary="Lista os favoritos de um cliente",
     *     tags={"Favoritos"},
     *     operationId="listFavorites",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="client",
     *         in="path",
     *         required=true,
     *         description="ID do cliente",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de favoritos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found"
     *     )
     * )
     */
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
/**
     * @OA\Post(
     *     path="/api/clients/{client}/favorites",
     *     summary="Adiciona um produto aos favoritos de um cliente",
     *     tags={"Favoritos"},
     *     operationId="addFavorite",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="client",
     *         in="path",
     *         required=true,
     *         description="ID do cliente",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="ID do produto a ser favoritado",
     *         @OA\JsonContent(
     *             required={"product_id"},
     *             @OA\Property(property="product_id", type="integer", example=1, description="ID do produto da Fake Store API")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produto adicionado aos favoritos",
     *         @OA\JsonContent(ref="#/components/schemas/Favorite")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Product already favorited by this customer"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer or product not found"
     *     )
     * )
     */
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
/**
     * @OA\Delete(
     *     path="/api/clients/{client}/favorites/{product_id}",
     *     summary="Remove um produto dos favoritos de um cliente",
     *     tags={"Favoritos"},
     *     operationId="removeFavorite",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="client",
     *         in="path",
     *         required=true,
     *         description="ID do cliente",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="product_id",
     *         in="path",
     *         required=true,
     *         description="ID do produto",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Product removed from favorites"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Favorite not found"
     *     )
     * )
     */
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