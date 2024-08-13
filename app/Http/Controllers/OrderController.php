<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // fungsi index order
    public function index(Request $request) {

        $allowedRoles = [1, 2, 3, 4]; // Role ID yang diizinkan
        if (!in_array($request->user()->role_id, $allowedRoles)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order = Order::select('id', 'customer_name', 'table_no', 'status', 'total', 'created_at', 'waitress_id', 'chasier_id')
        ->with(['waitress:id,name', 'chasier:id,name'])
        ->get();
        return response()->json([
            'data' => $order
        ]);
    }

    // fungsi show order
    public function show(Request $request, $id) {

        $allowedRoles = [1, 2, 3, 4]; // Role ID yang diizinkan
        if (!in_array($request->user()->role_id, $allowedRoles)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order = Order::findOrFail($id);
        return response()->json([
            'data' => $order
            ->loadMissing(
                ['orderStatus:order_id,price,item_id,qty',
                'orderStatus.item:name,category,id',
                'waitress:id,name', 'chasier:id,name'
                ]
            ),
        ]);
    }

    // fungsi create order
    public function store(Request $request) {
        // Validasi input
        $validated = $request->validate([
            'customer_name' => 'required|max:100',
            'table_no' => 'required',
            'items' => 'required|array', // Pastikan items adalah array dan tidak kosong
            'items.*.id' => 'exists:items,id', // Validasi bahwa setiap item ada di tabel items
            'items.*.qty' => 'required|integer|min:1' // Pastikan setiap item memiliki quantity
        ]);

        try {
            DB::beginTransaction();

            // Ambil data dari request
            $data = $request->only('customer_name', 'table_no');
            $data['status'] = 'ordered';
            $data['total'] = 0; // Inisialisasi total dengan 0
            $data['waitress_id'] = auth()->user()->id;

            // Membuat order baru
            $order = Order::create($data);

            // Mendapatkan item dari request
            $items = $request->input('items', []);
            $orderStatuses = [];
            $totalPrice = 0;

            // Ambil semua item dari database dalam satu query
            if (!empty($items)) {
                $foodDrinks = Item::whereIn('id', array_column($items, 'id'))->get()->keyBy('id');

                // Proses setiap item
                foreach ($items as $itemData) {
                    $itemId = $itemData['id'];
                    $qty = $itemData['qty'];

                    if (isset($foodDrinks[$itemId])) {
                        $foodDrink = $foodDrinks[$itemId];
                        $price = $foodDrink->price;
                        $total = $price * $qty;

                        $orderStatuses[] = [
                            'order_id' => $order->id,
                            'item_id' => $itemId,
                            'price' => $price,
                            'qty' => $qty,
                            'total' => $total,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];

                        // Tambahkan harga item ke total
                        $totalPrice += $total;
                    }
                }
            }

            // Bulk insert OrderStatus
            if (!empty($orderStatuses)) {
                OrderStatus::insert($orderStatuses);
            }

            // Update total order setelah memproses item
            $order->total = $totalPrice;
            $order->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage()
            ], 500); // Memberikan kode status HTTP 500 untuk kesalahan server
        }

        return response()->json([
            'data' => $order
        ], 201); // Memberikan kode status HTTP 201 untuk berhasil dibuat
    }

    // fungsi setAsDone
    public function setAsDone($id) {
        $order = Order::findOrFail($id);

        if ($order->status != 'ordered') {
            return response()->json([
                'message' => 'Order cannot be set to done because the status is not ordered'
            ], 403);
        }

        $order->status = 'done';
        $order->save();

        return response()->json([
            'data' => $order->loadMissing([
                'orderStatus:order_id,price,item_id,qty',
                'orderStatus.item:id,name,category',
                'waitress:id,name',
                'chasier:id,name'
            ]),
        ]);
    }

    // fungsi payment order
    public function payment($id) {
        $order = Order::findOrFail($id);

        if($order->status != 'done') {
            return response()->json([
                'message' => 'payment cannot be done because the status is not done'
            ], 403);
        }

        $order->status = 'paid';
        $order->chasier = auth()->user()->id; // Pastikan 'chasier' menyimpan ID kasir
        $order->save();

        return response()->json([
            'data' => $order
            ->loadMissing(
                ['orderStatus:order_id,price,item_id,qty',
                'orderStatus.item:name,category,id',
                'waitress:id,name', 'chasier:id,name'
                ]
            ),
        ]);
    }

}
