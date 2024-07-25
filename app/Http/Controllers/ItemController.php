<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    // fungsi index
    public function index() {
        $items = Item::select('id', 'name', 'price', 'image', 'category')->get();
        return response()->json([
            'data' => $items
        ]);
    }

    // fungsi create item
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|integer',
            'category' => 'required|max:255',
            'image_file' => 'nullable|mimes:jpg,jpeg,png|max:2048' // 2 MB max
        ]);

        // Initialize the data array with validated data
        $data = [
            'name' => $validated['name'],
            'price' => $validated['price'],
            'category' => $validated['category'],
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
                    'category' => $item->category,
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

    // function update kita perlu cek id
    public function update(Request $request, $id) {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|integer',
            'category' => 'required|max:255',
            'image_file' => 'nullable|mimes:jpg,jpeg,png|max:2048', // 2 MB max
        ]);

        // Initialize the data array with validated data
        $data = [
            'name' => $validated['name'],
            'price' => $validated['price'],
            'category' => $validated['category'],
        ];

        // Find the item to update based on the provided ID
        $item = Item::find($id);

        // Ensure the request includes an image file
        if (!$request->hasFile('image_file')) {
            // Handle missing image (optional)
            // You can return a specific error message here
            return response()->json([
                'success' => false,
                'message' => 'No image file uploaded',
            ], 422); // Unprocessable Entity
        }

        // Handle image upload if present
        try {
            $file = $request->file('image_file');
            // Generate unique file name
            $fileName = Carbon::now()->timestamp . '_' . $file->getClientOriginalName();

            // Validate file size and format (optional)
            // You can add additional validation checks here

            // Determine storage path
            $path = $file->storeAs('public/uploads/items', $fileName);
            // Save the path to the data array
            $data['image'] = $path;

            // Delete the existing image if present
            if ($item->image) {
                Storage::delete($item->image);
            }
        } catch (\Exception $e) {
            // Handle upload error
            Log::error('Image upload failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Image upload failed',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error
        }

        // Update the item with the validated data
        if ($item) {
            $item->update($data);

            $imageUrl = $item->image ? Storage::url($item->image) : null;
            $imageStatus = $item->image ? true : false;

            return response()->json([
                'success' => true,
                'name' => $item->name,
                'price' => $item->price,
                'category' => $item->category,
                'image_file' => $imageUrl,
                'image_status' => $imageStatus,
                'tgl_buat' => $item->created_at->format('Y-m-d H:i:s'),
                'message' => 'Item updated successfully',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Item not found',
            ], 404);
        }
    }

    // function delete
}
