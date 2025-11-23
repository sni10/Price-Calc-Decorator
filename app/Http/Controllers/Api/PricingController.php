<?php

namespace App\Http\Controllers\Api;

use App\Contracts\OrderServiceInterface;
use App\Contracts\PriceRuleServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    protected PriceRuleServiceInterface $priceRuleService;
    protected OrderServiceInterface $orderService;

    public function __construct(PriceRuleServiceInterface $priceRuleService, OrderServiceInterface $orderService)
    {
        $this->priceRuleService = $priceRuleService;
        $this->orderService = $orderService;
    }

    public function calculatePrice(Request $request): JsonResponse
    {
        $user = auth()->user();

        try {
            if (!$user) {
                return $this->responseError([
                    'message' => 'User not authenticated'
                ], 401);
            }

            $validated = $request->validate([
                'order_id' => 'required',
                'category_id' => 'required',
                'location_id' => 'required',
                'quantity' => 'required',
                'base_price' => 'required',
                'apply_seller_discount' => 'required|boolean',
            ]);

            $order = $this->orderService->getOrderById($validated['order_id']);
            $order = $this->orderService->updateOrder($order->id, $validated);
            $finalPrice = $this->priceRuleService->calculateFinalPrice($order);

            return $this->responseSuccess([
                'final_price' => $finalPrice,
                'order_id' => $order->id,
                'order' => $order,
                'applied_rules' => $order->appliedRules()->get(['rule_name', 'discount_type', 'discount_value','condition_type', 'condition_value', 'description']),
                'bapplied_rules' => $order->appliedBuiltRules()->get(['rule_name', 'discount_type', 'discount_value', 'description'])
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

}
