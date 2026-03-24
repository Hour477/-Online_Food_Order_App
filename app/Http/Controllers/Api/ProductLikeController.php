<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Events\ProductLiked;
use App\Events\ProductUnliked;
use App\Models\MenuItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\User;

class ProductLikeController extends Controller
{
    use AuthorizesRequests;

    /**
     * @OA\Post(
     *     path="/api/products/{id}/like",
     *     summary="Like a product",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Product liked successfully"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function like(MenuItem $product): JsonResponse
    {
        $this->authorize('like', $product);

        $user = Auth::user();
        
        if (!$user instanceof User) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        if ($product->isLikedBy($user)) {
            return response()->json(['message' => 'Already liked'], 200);
        }

        $product->like($user);

        event(new ProductLiked($user, $product));

        return response()->json([
            'message' => 'Product liked successfully',
            'likes_count' => $product->likes_count
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}/unlike",
     *     summary="Unlike a product",
     *     tags={"Products"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Product unliked successfully"),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function unlike(MenuItem $product): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user instanceof User) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        if (!$product->isLikedBy($user)) {
            return response()->json(['message' => 'Not liked yet'], 200);
        }

        $product->unlike($user);

        event(new ProductUnliked($user, $product));

        return response()->json([
            'message' => 'Product unliked successfully',
            'likes_count' => $product->likes_count
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/user/liked-products",
     *     summary="Get user's liked products",
     *     tags={"User"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="List of liked products")
     * )
     */
    public function likedProducts(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user instanceof User) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        $products = $user->likedMenuItems()->paginate(10);

        return response()->json($products);
    }
}
