<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Gestión de Productos API",
 *     description="API para gestionar productos y sus precios",
 *     @OA\Contact(
 *         email="soporte@tudominio.com"
 *     )
 * )
 * 
 * @OA\Tag(
 *     name="Productos",
 *     description="APIs relacionadas con la gestión de productos"
 * )
 *
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     required={"name", "description", "price", "currency_id", "tax_cost", "manufacturing_cost"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Identificador único del producto"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Nombre del producto"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Descripción del producto"
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="float",
 *         description="Precio del producto en la divisa base"
 *     ),
 *     @OA\Property(
 *         property="currency_id",
 *         type="integer",
 *         description="ID de la divisa base"
 *     ),
 *     @OA\Property(
 *         property="tax_cost",
 *         type="number",
 *         format="float",
 *         description="Costo de impuestos del producto"
 *     ),
 *     @OA\Property(
 *         property="manufacturing_cost",
 *         type="number",
 *         format="float",
 *         description="Costo de fabricación del producto"
 *     )
 * )
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Productos"},
     *     summary="Obtener lista de productos",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de productos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Product")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $products = Product::with('currency', 'prices.currency')->get();

        return ProductResource::collection($products);
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Productos"},
     *     summary="Crear un nuevo producto",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Producto creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     )
     * )
     */
    public function store(ProductRequest $request)
    {
        Product::create($request->all());
        return response()->noContent(201);
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"Productos"},
     *     summary="Obtener un producto por ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del producto",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     )
     * )
     */
    public function show($id)
    {
        $product = Product::with('currency', 'prices.currency')->findOrFail($id);

        return ProductResource::make($product);
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     tags={"Productos"},
     *     summary="Actualizar un producto por ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     )
     * )
     */
    public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validate([
            'name' => 'string',
            'description' => 'string',
            'price' => 'numeric',
            'currency_id' => 'exists:currencies,id',
            'tax_cost' => 'numeric',
            'manufacturing_cost' => 'numeric',
        ]);

        $product->update($data);

        return ProductResource::make(ProductResource::make($product));
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     tags={"Productos"},
     *     summary="Eliminar un producto por ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Producto eliminado exitosamente"
     *     )
     * )
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->noContent();
    }
}
