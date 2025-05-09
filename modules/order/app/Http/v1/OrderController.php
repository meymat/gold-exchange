<?php

namespace Modules\order\app\Http\v1;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\core\app\Http\Controllers\ModelController;
use Modules\core\app\Http\Requests\StoreOrderRequest;
use Modules\order\app\Models\Order;
use Modules\order\app\Resources\OrderResource;
use Modules\order\app\Services\OrderService;

class OrderController extends ModelController
{
    private OrderService $orderService;

    public function __construct(Request $request, Order $model, OrderService $orderService)
    {
        parent::__construct($request, $model);
        $this->orderService = $orderService;
    }

    public function buy(StoreOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->createBuyOrder(array_merge(
            $request->validated(),
            ['user_id' => auth()->id()]
        ));
        return response()->json(new OrderResource($order), 201);
    }

    public function sell(StoreOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->createSellOrder(array_merge(
            $request->validated(),
            ['user_id' => auth()->id()]
        ));
        return response()->json(new OrderResource($order), 201);
    }

}
