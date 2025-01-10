<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyRequest;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;

/**
 * @OA\Tag(
 *     name="Currencies",
 *     description="APIs relacionadas con las monedas"
 * )
 */

/**
 * @OA\Schema(
 *     schema="Currency",
 *     type="object",
 *     required={"name", "symbol", "exchange_rate"},
 *     @OA\Property(property="id", type="integer", description="ID único de la moneda"),
 *     @OA\Property(property="name", type="string", description="Nombre de la moneda"),
 *     @OA\Property(property="symbol", type="string", description="Símbolo de la moneda"),
 *     @OA\Property(property="exchange_rate", type="number", format="float", description="Tipo de cambio de la moneda"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Fecha de creación"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha de actualización")
 * )
 */

class CurrencyController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/currencies",
     *     tags={"Currencies"},
     *     summary="Obtener todas las monedas",
     *     description="Devuelve una lista de todas las monedas.",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de monedas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Currency")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $currencies = Currency::all();
        return CurrencyResource::collection($currencies);
    }

    /**
     * @OA\Post(
     *     path="/api/currencies",
     *     tags={"Currencies"},
     *     summary="Crear una nueva moneda",
     *     description="Crea una nueva moneda y la devuelve.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Currency")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Moneda creada",
     *         @OA\JsonContent(ref="#/components/schemas/Currency")
     *     )
     * )
     */
    public function store(CurrencyRequest $request)
    {
        $currency = Currency::create($request->all());
        return response()->json(CurrencyResource::make($currency), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/currencies/{id}",
     *     tags={"Currencies"},
     *     summary="Obtener una moneda por ID",
     *     description="Devuelve los detalles de una moneda específica.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la moneda",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la moneda",
     *         @OA\JsonContent(ref="#/components/schemas/Currency")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Moneda no encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        $currency = Currency::findOrFail($id);
        return CurrencyResource::make($currency);
    }

    /**
     * @OA\Put(
     *     path="/api/currencies/{id}",
     *     tags={"Currencies"},
     *     summary="Actualizar una moneda existente",
     *     description="Actualiza los detalles de una moneda existente.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la moneda",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Currency")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Moneda actualizada",
     *         @OA\JsonContent(ref="#/components/schemas/Currency")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Moneda no encontrada"
     *     )
     * )
     */
    public function update(CurrencyRequest $request, $id)
    {
        $currency = Currency::findOrFail($id);
        $currency->update($request->all());
        return response()->json(CurrencyResource::make($currency));
    }

    /**
     * @OA\Delete(
     *     path="/api/currencies/{id}",
     *     tags={"Currencies"},
     *     summary="Eliminar una moneda",
     *     description="Elimina una moneda específica por su ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la moneda",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Moneda eliminada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Moneda no encontrada"
     *     )
     * )
     */
    public function destroy($id)
    {
        $currency = Currency::findOrFail($id);
        $currency->delete();
        return response()->noContent();
    }
}
