<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Size;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'size_id' => 'required|array|min:1',
            'size_id.*' => 'exists:sizes,id',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'integer|min:1',
            'price' => 'required|numeric',
        ], [
            'customer_id.exists' => 'The selected customer does not exist.',
        ]);
    
        return DB::transaction(function () use ($request) {
            $user = Auth::user();
    
            $order = new Order([
                'customer_id' => $request->input('customer_id'),
                'price' => $request->input('price'), 
            ]);
    
            $order->user_id = $user->id;
            $order->save();
    
            // Loop through the sizes and quantities and create order items
            foreach ($request->input('size_id') as $index => $sizeId) {
                $quantity = $request->input('quantity')[$index];
    
                $orderItem = new OrderItem([
                    'order_id' => $order->id,
                    'size_id' => $sizeId,
                    'quantity' => $quantity,
                    'price' => $request->input('price'), // Save the price for this item from the reques
                ]);
    
                $orderItem->save();
            }
    
            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order,
            ], 201);
        });
    }
    

    public function index()
    {
        $orders = Order::with('customer', 'orderItem.size')
            ->where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    
        $transformedOrders = $orders->getCollection()->map(function ($order) {
            return [
                'id' => $order->id,
                'customer_name' => $order->customer->name,
                'size' => $order->orderItem->map(function ($item) {
                    return [
                        'id' => $item->size->id,
                        'quantity' => $item->quantity,
                        'size_name' => $item->size->size_name,
                        'collar_size' => $item->size->collar_size,
                        'chest_size' => $item->size->chest_size,
                        'sleeve_length' => $item->size->sleeve_length,
                        'cuff_size' => $item->size->cuff_size,
                        'shoulder_size' => $item->size->shoulder_size,
                        'waist_size' => $item->size->waist_size,
                        'shirt_length' => $item->size->shirt_length,
                        'legs_length' => $item->size->legs_length,
                        'description' => $item->size->description,
                        'category' => $item->size->category,
                    ];
                }),
                'price' => $order->price,
                'status' => $order->status,
                'created_at' => $order->created_at->format('Y-m-d'), // Include the 'created_at' field in the response
            ];
        });
    
        $orders->setCollection($transformedOrders);
    
        return response()->json(['orders' => $orders]);
    }
    

    public function show(Request $request, $id)
    {
        $order = Order::with('customer', 'orderItem.size')->find($id);

        if (Auth::user()->id !== $order->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        if (!$order) {
            return response()->json([
                'message' => 'Order not found.',
            ], 404);
        }
        
        $response = [
            'id' => $order->id,
            'customer_name' => $order->customer->name,
            'size' => $order->orderItem->map(function ($item) {
                return [
                    'id' => $item->size->id,
                    'quantity' => $item->quantity,

                    'size_name' => $item->size->size_name,
                    'collar_size' => $item->size->collar_size,
                    'chest_size' => $item->size->chest_size,
                    'sleeve_length' => $item->size->sleeve_length,
                    'cuff_size' => $item->size->cuff_size,
                    'shoulder_size' => $item->size->shoulder_size,
                    'waist_size' => $item->size->waist_size,
                    'shirt_length' => $item->size->shirt_length,
                    'legs_length' => $item->size->legs_length,
                    'description' => $item->size->description,
                    'category' => $item->size->category,
                ];
            }),
            'price' => $order->price,
            'status' => $order->status, // Assuming you have a 'status' field in your 'orders' table
        ];
    
        return response()->json($response, 200);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'size_id' => 'required|array|min:1',
            'size_id.*' => 'exists:sizes,id',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'integer|min:1',
            'price' => 'required|numeric',
            'status' => 'required|string|in:pending,delivered',
        ], [
            'customer_id.exists' => 'The selected customer does not exist.',
        ]);
    
        // Find the order by its ID
        $order = Order::find($id);
    
        if (!$order) {
            return response()->json([
                'message' => 'Order not found.',
            ], 404); 
        }
    
        $order->price = $request->input('price');
        $order->save();
    
        // Loop through the size IDs and quantities
        for ($i = 0; $i < count($request->input('size_id')); $i++) {
            $sizeId = $request->input('size_id')[$i];
            $quantity = $request->input('quantity')[$i];
    
            // Find the existing order item by size ID and order ID
            $existingOrderItem = OrderItem::where('order_id', $order->id)
                ->where('size_id', $sizeId)
                ->first();
    
            if ($existingOrderItem) {
                // Update the existing order item with new quantity
                $existingOrderItem->quantity = $quantity;
                $existingOrderItem->save();
            } else {
                // Create a new order item if it doesn't exist
                $orderItem = new OrderItem([
                    'order_id' => $order->id,
                    'size_id' => $sizeId,
                    'quantity' => $quantity,
                    'price' => $request->input('price'), 
                    'status' => $request->input('status'), 
                ]);
    
                // Save the order item
                $orderItem->save();
            }
        }
    
        $user = Auth::user();
        $order->user_id = $user->id;
    
        $order->status = $request->input('status');
    
        $order->save();
    
        return response()->json([
            'message' => 'Order updated successfully',
            'order' => $order,
        ], 200);
    }
    

    public function setStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,delivered'
        ]);

            // Find the order by its ID
            $order = Order::find($id);
    
            if (!$order) {
                return response()->json([
                     'message' => 'Order not found.',
                ], 404); 
            }

            $order->fill($request->all());

            $user = Auth::user();
            $order->user_id = $user->id;

            $order->save();

                return response()->json([
            'message' => 'Order updated successfully',
            'order' => $order,
        ], 200);
    }
    
    public function delete($id)
    {
 
        $order = Order::find($id);

        if (Auth::user()->id !== $order->user_id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
} 
