<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderStatusHistory;

class OrderController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found.'
            ], 404);
        }

        $currentStatus = $order->status;
        $newStatus = $request->status;

        $statuses = [
            'pending',
            'confirmed',
            'shipped',
            'delivered'
        ];

        // Cancel is allowed only from pending or confirmed
        if ($newStatus === 'cancelled') {

            if (!in_array($currentStatus, ['pending', 'confirmed'])) {
                return response()->json([
                    'message' => 'Order can only be cancelled if it is pending or confirmed.'
                ], 422);
            }

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'from_status' => $currentStatus,
                'to_status' => 'cancelled',
                'changed_by' => 1,
                'note' => $request->note,
            ]);

            $order->update([
                'status' => 'cancelled'
            ]);

            return response()->json([
                'message' => 'Order cancelled successfully.',
                'order' => $order
            ]);
        }

        // Check if new status is valid
        if (!in_array($newStatus, $statuses)) {
            return response()->json([
                'message' => 'Invalid status.'
            ], 422);
        }

        $currentIndex = array_search($currentStatus, $statuses);
        $newIndex = array_search($newStatus, $statuses);

        // Cannot move backwards
        if ($newIndex < $currentIndex) {
            return response()->json([
                'message' => 'Cannot move to a previous status.'
            ], 422);
        }

        // Cannot skip status
        if ($newIndex > $currentIndex + 1) {
            return response()->json([
                'message' => 'Cannot skip order status.'
            ], 422);
        }

        // Same status
        if ($newIndex == $currentIndex) {
            return response()->json([
                'message' => 'Order is already in this status.'
            ], 422);
        }

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'from_status' => $currentStatus,
            'to_status' => $newStatus,
            'changed_by' => 1,
            'note' => $request->note,
        ]);

        $order->update([
            'status' => $newStatus
        ]);

        return response()->json([
            'message' => 'Order status updated successfully.',
            'order' => $order
        ]);
    }

    public function history($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found.'
            ], 404);
        }

        $history = $order->histories()->oldest()->get();

        return response()->json([
            'order_id' => $order->id,
            'current_status' => $order->status,
            'history' => $history
        ]);
    }
}
