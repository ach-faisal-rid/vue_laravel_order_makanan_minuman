<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ItemController extends Controller
{
    // fungsi create item
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|integer',
            'image_file' => 'nullable|mimes:jpg,jpeg,png'
        ]);

        if($request->file('image_file')) {
            // process upload
            // Ambil file yang diunggah
            $file = $request->file('image_file');
            // Generate nama file yang unik
            $fileName = Carbon::now()->timestamp . '_'
            . $file->getClientOriginalName();
            // Tentukan path penyimpanan
            $path = $file->storeAs('public/uploads/items', $fileName);
        }

        // Simpan data ke dalam database
        $item = new Item();

        // Simpan data dan kembalikan respons
        if ($item->save()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $item->name,
                    'price' => $item->price,
                    'image_file' => $item->image,
                    'tgl_buat' =>date('Y-m-d H:i:s', strtotime($item->timestamps)),
                ],
                'message' => 'Data berhasil disimpan',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data',
            ], 500);
        }
    }
}
