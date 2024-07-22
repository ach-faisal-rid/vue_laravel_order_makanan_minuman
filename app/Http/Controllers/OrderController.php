<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // fungsi create order
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'no' => 'required|integer',

        ]);

        try {
            DB::beginTransaction();

            $data =  $request->only('name', 'no');

            // Map form field names to model field names
            $data['customer_name'] = $data['name'];
            unset($data['name']);  // Remove the original 'name' key from $data

            $data['table_no'] = $data['no'];
            unset($data['no']);    // Remove the original 'no' key from $data

            $data['order_date'] = date('Y-m-d');
            $data['order_time'] = date('H:i:s');
            $data['status'] = 'ordered';
            $data['total'] = 1;
            $data['waitress_id'] = auth()->user()->role_id;
            $data['chasier_id'] = null;
            $data['items'] = $request->item;

            $order = Order::create($data);

            collect($data['items'])->map(function ($item) use ($order) {
                $foodDrinks = Item::where('id', $item)->first();
                OrderStatus::create([
                    'order_id' => $order->id,
                    'item_id' => $item,
                ]);
            });


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response($e);
        }

        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'order berhasil dibuat',
        ], 200);
    }
}
