<?php

namespace App\Http\Controllers\Api;

use App\Contracts\OrderServiceInterface;
use App\Contracts\SellerServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderServiceInterface $orderService;
    protected SellerServiceInterface $sellerService;

    public function __construct(OrderServiceInterface $orderService, SellerServiceInterface $sellerService)
    {
        $this->orderService = $orderService;
        $this->sellerService = $sellerService;
    }

    public function store(Request $request): JsonResponse
    {
        $user = auth()->user();

        try {
            if (!$user) {
                return $this->responseError([
                    'message' => 'User not authenticated'
                ], 401);
            }
            $seller = $this->sellerService->createSeller($user);
            $order = $this->orderService->createOrder($seller);

            return $this->responseSuccess([
                'order_id' => $order->id,
            ]);

        } catch (ModelNotFoundException $e) {
            return $this->responseError([
                'message' => 'Order not found',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return $this->responseError([
                'message' => ['An error occurred while calculating price'],
                'error' => $e->getMessage(),
            ], 501);
        }
    }


    public function index(): JsonResponse
    {
        try {
            $orders = $this->orderService->getAllOrder();

            return $this->responseSuccess([
                'orders' => $orders,
            ]);

        } catch (\Exception $e) {
            return $this->responseError([
                'message' => ['An unexpected error occurred'],
                'error' => $e->getMessage(),
            ], 501);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $order = $this->orderService->getOrderById($id);

            return $this->responseSuccess([
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            return $this->responseError([
                'message' => ['An unexpected error occurred'],
                'error' => $e->getMessage(),
            ], 501);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'content' => 'required|string',
            ]);
            $order = $this->orderService->updateOrder($id, $validated);

            return $this->responseSuccess([
                'order' => $order,
            ]);

        } catch (\Exception $e) {
            return $this->responseError([
                'message' => ['An unexpected error occurred'],
                'error' => $e->getMessage(),
            ], 501);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $order = $this->orderService->getOrderById($id);
            $this->orderService->deleteOrder($order->id);

            return response()->json(null, 204);

        } catch (\Exception $e) {
            return $this->responseError([
                'message' => ['An unexpected error occurred'],
                'error' => $e->getMessage(),
            ], 501);
        }
    }



}
