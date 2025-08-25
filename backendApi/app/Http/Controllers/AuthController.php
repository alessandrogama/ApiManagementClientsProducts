<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
/**
 * @OA\Info(
 *     title="API Gerenciamento de Clientes e Produtos Favoritos",
 *     description="Documentação da API para gerenciar autenticação, clientes e seus produtos favoritos.",
 *     version="1.0.0",
 *     @OA\Contact(
 *         name="Suporte",
 *         email="suporte@example.com"
 *     )
 * )
 */
class AuthController extends Controller
{
/**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registra um novo usuário",
     *     tags={"Autenticação"},
     *     operationId="register",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados necessários para criar um novo usuário",
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="Test User", description="Nome do usuário"),
     *             @OA\Property(property="email", type="string", format="email", example="test@example.com", description="Email único do usuário"),
     *             @OA\Property(property="password", type="string", format="password", example="password123", description="Senha do usuário (mínimo 8 caracteres)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="1|seu_token_aqui", description="Token de autenticação gerado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados inválidos ou email já em uso"
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token], 201);
    }
/**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Autentica um usuário existente",
     *     tags={"Autenticação"},
     *     operationId="login",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Credenciais para autenticação",
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="test@example.com", description="Email do usuário"),
     *             @OA\Property(property="password", type="string", format="password", example="password123", description="Senha do usuário")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success authentication",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="1|seu_token_aqui", description="Token de autenticação gerado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($validated)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token]);
    }
}