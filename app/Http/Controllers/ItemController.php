<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    // fungsi create item
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|integer',
            'image_file' => 'nullable|mimes:jpg,jpeg,png|max:2048' // 2 MB max
        ]);

        // Initialize the data array with validated data
        $data = [
            'name' => $validated['name'],
            'price' => $validated['price'],
        ];

        if ($request->hasFile('image_file')) {
            // Process upload
            $file = $request->file('image_file');
            // Generate unique file name
            $fileName = Carbon::now()->timestamp . '_' . $file->getClientOriginalName();
            // Determine storage path
            $path = $file->storeAs('public/uploads/items', $fileName);
            // Save the path to the data array
            $data['image'] = $path;
        }

        // Save the item to the database
        $item = Item::create($data);

        if ($item) {
            $imageUrl = $item->image ? Storage::url($item->image) : null;

            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $item->name,
                    'price' => $item->price,
                    'image_url' => $imageUrl,
                    'tgl_buat' => $item->created_at->format('Y-m-d H:i:s'),
                ],
                'message' => 'Data berhasil disimpan',
            ], 201);
        } else {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data',
            ], 500);
        }
    }
}
