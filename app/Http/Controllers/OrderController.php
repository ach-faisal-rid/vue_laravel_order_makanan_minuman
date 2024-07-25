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
    public function index() {
        $order = Order::select('id', 'customer_name', 'table_no', 'status', 'total')
        ->get();
        return response()->json([
            'data' => $order
        ]);
    }

    // fungsi show order
    public function show($id) {
        $order = Order::findOrFail($id);
        return response()->json([
            'data' => $order
            ->loadMissing(
                ['orderStatus:order_id,price,item_id',
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
            'items' => 'array', // Validasi bahwa items adalah array
            'items.*' => 'exists:items,id' // Validasi bahwa setiap item ada di tabel items
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

            // Mendapatkan item dari request, jika tidak ada, set sebagai array kosong
            $items = $request->input('items', []);
            $orderStatuses = [];
            $totalPrice = 0;

            // Ambil semua item dari database dalam satu query
            if (!empty($items)) {
                $foodDrinks = Item::whereIn('id', $items)->get()->keyBy('id');

                // Proses setiap item
                foreach ($items as $item) {
                    if (isset($foodDrinks[$item])) {
                        $foodDrink = $foodDrinks[$item];
                        $orderStatuses[] = [
                            'order_id' => $order->id,
                            'item_id' => $item,
                            'price' => $foodDrink->price,
                            'qty' => 1,
                            'total' => 1,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];

                        // Tambahkan harga item ke total
                        $totalPrice += $foodDrink->price;
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

}
