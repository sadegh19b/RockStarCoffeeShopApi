<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::with(['options', 'options.values'])->get();

        return $this->jsonResponse('Success!', ProductResource::collection($products));
    }
}
