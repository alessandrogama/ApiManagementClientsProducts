<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
/**
 * @OA\Tag(
 *     name="Clientes",
 *     description="Funções relacionadas aos clientes"
 * )
 */
class ClientController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/clients",
     *     summary="Cria um novo cliente",
     *     tags={"Clientes"},
     *     operationId="createClient",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados do cliente a ser criado",
     *         @OA\JsonContent(
     *             required={"name","email"},
     *             @OA\Property(property="name", type="string", example="João Silva", description="Nome do cliente"),
     *             @OA\Property(property="email", type="string", format="email", example="joao@example.com", description="Email único do cliente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer successfully created",
     *         @OA\JsonContent(ref="#/components/schemas/Client")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid data or email already in use"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:clients',
        ]);

        $client = Client::create($validated);

        return response()->json($client, 201);
    }
/**
     * @OA\Get(
     *     path="/api/clients/{id}",
     *     summary="Mostra os detalhes de um cliente",
     *     tags={"Clientes"},
     *     operationId="showClient",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do cliente",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do cliente",
     *         @OA\JsonContent(ref="#/components/schemas/Client")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found"
     *     )
     * )
     */
    public function show(Client $client)
    {
        return response()->json($client);
    }
/**
     * @OA\Put(
     *     path="/api/clients/{id}",
     *     summary="Atualiza um cliente existente",
     *     tags={"Clientes"},
     *     operationId="updateClient",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do cliente",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados atualizados do cliente",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="João Silva Atualizado", description="Novo nome do cliente"),
     *             @OA\Property(property="email", type="string", format="email", example="joao.atualizado@example.com", description="Novo email do cliente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer successfully updated",
     *         @OA\JsonContent(ref="#/components/schemas/Client")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid data"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found"
     *     )
     * )
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:clients,email,' . $client->id,
        ]);

        $client->update($validated);

        return response()->json($client);
    }
    /**
     * @OA\Delete(
     *     path="/api/clients/{id}",
     *     summary="Deleta um cliente",
     *     tags={"Clientes"},
     *     operationId="deleteClient",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do cliente",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Customer successfully deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found"
     *     )
     * )
     */

    public function destroy(Client $client)
    {
        $client->delete();

        return response()->json(null, 204);
    }
}