<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\RabbitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function __construct(
        private readonly RabbitService $rabbitService
    )
    {
    }

    public function index(): View
    {
        $products = Cache::remember('products', 10, function () {
            return Product::all();
        });

        return view('product.index', compact('products'));
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150|min:3',
        ]);

        if ($validator->fails())
        {
            return response()->json(
                [
                    'errors' => $validator->errors()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $product = new Product();
        $product->setName($request->get('name'));
        $product->save();

        $message = json_encode([
            'id' => $product->getId(),
            'name' => $product->getName()
        ]);

        $this->rabbitService->sendMessage(
            'product_exchange',
            'product_queue',
            'product_key',
            $message,
        );

        return response()->json([
            'message' => 'Product created.',
            'data' => $product
        ], Response::HTTP_CREATED);
    }
}
