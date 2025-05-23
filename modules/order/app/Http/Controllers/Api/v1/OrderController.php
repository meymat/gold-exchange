<?php

namespace Modules\order\app\Http\Controllers\Api\v1;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\core\app\Http\Controllers\ModelController;
use Modules\core\app\Http\Requests\StoreOrderRequest;
use Modules\order\app\Models\Order;
use Modules\order\app\Resources\OrderCollection;
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
        DB::beginTransaction();
        $order = $this->orderService->createBuyOrder(array_merge(
            $request->validated(),
            ['user_id' => auth()->id()]
        ));
        DB::commit();

        return response()->json(new OrderResource($order), 201);
    }

    public function sell(StoreOrderRequest $request): JsonResponse
    {
        DB::beginTransaction();
        $order = $this->orderService->createSellOrder(array_merge(
            $request->validated(),
            ['user_id' => auth()->id()]
        ));
        DB::commit();

        return response()->json(new OrderResource($order), 201);
    }

    public function history(): JsonResponse
    {
        $orders = $this->orderService->history(auth()->id());

        return (new OrderCollection($orders))
            ->response()
            ->setStatusCode(200);
    }

    public function cancel(Request $request): JsonResponse
    {
        $request->validate(['order_id'=>'required|integer']);
        $this->orderService->cancel($request->order_id, auth()->id());

        return response()->json(['message'=>'Order cancelled']);
    }

}
