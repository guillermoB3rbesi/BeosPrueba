<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductPriceRequest;
use App\Http\Resources\PriceResource;
use App\Models\Product;
use App\Models\Price;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Product Prices",
 *     description="APIs relacionadas con los precios de los productos"
 * )
 */

/**
 * @OA\Schema(
 *     schema="ProductPrice",
 *     type="object",
 *     required={"price", "currency_id"},
 *     @OA\Property(property="id", type="integer", description="ID único del precio"),
 *     @OA\Property(property="price", type="number", format="float", description="Precio del producto"),
 *     @OA\Property(property="currency_id", type="integer", description="ID de la divisa asociada"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Fecha de creación"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha de actualización")
 * )
 */

class ProductPriceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products/{productId}/prices",
     *     tags={"Product Prices"},
     *     summary="Obtener los precios de un producto",
     *     description="Devuelve una lista de los precios asociados a un producto.",
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de precios del producto",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ProductPrice")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     )
     * )
     */
    public function index($productId)
    {
        $product = Product::findOrFail($productId);
        return response()->json(PriceResource::collection($product->prices));
    }

    /**
     * @OA\Post(
     *     path="/api/products/{productId}/prices",
     *     tags={"Product Prices"},
     *     summary="Crear un nuevo precio para un producto",
     *     description="Crea un nuevo precio asociado a un producto.",
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductPrice")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Precio creado",
     *         @OA\JsonContent(ref="#/components/schemas/ProductPrice")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     )
     * )
     */
    public function store(ProductPriceRequest $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $price = $product->prices()->create($request->all());

        return response()->json(PriceResource::make($price), 201);
    }
}
