<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/**
 * @OA\Schema(
 *     schema="Client",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1, description="ID do cliente"),
 *     @OA\Property(property="name", type="string", example="João Silva", description="Nome do cliente"),
 *     @OA\Property(property="email", type="string", format="email", example="joao@example.com", description="Email do cliente"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data de atualização")
 * )
 * @OA\Schema(
 *     schema="Favorite",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1, description="ID do favorito"),
 *     @OA\Property(property="client_id", type="integer", example=1, description="ID do cliente associado"),
 *     @OA\Property(property="product_id", type="integer", example=1, description="ID do produto associado"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data de atualização")
 * )
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1, description="ID do produto"),
 *     @OA\Property(property="title", type="string", example="Produto Teste", description="Título do produto"),
 *     @OA\Property(property="image", type="string", example="https://example.com/image.jpg", description="URL da imagem"),
 *     @OA\Property(property="price", type="number", example=29.99, description="Preço do produto"),
 *     @OA\Property(property="rating", type="number", example=4.5, description="Classificação do produto"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data de atualização")
 * )
 */
class DocsController extends Controller
{
    //
}
