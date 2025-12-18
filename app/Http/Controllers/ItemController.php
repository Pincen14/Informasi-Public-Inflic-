<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        return Item::with('user')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_item' => 'required',
            'image' => 'required|image',
            'location_found' => 'required',
            'date_found' => 'required|date',
        ]);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('items'), $imageName);

        Item::create([
            'nama_item' => $request->nama_item,
            'description' => $request->description,
            'image' => $imageName,
            'location_found' => $request->location_found,
            'date_found' => $request->date_found,
            'status' => 'pending',
            'user_id' => auth()->id()
        ]);

        return response()->json(['message' => 'Item created']);
    }

    public function approve($id)
    {
        Item::findOrFail($id)->update(['status' => 'approved']);
        return response()->json(['message' => 'Item approved']);
    }
}
