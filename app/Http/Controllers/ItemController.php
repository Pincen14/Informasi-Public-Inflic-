<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | API - Get All Items
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return Item::with('user')->get();
    }

    /*
    |--------------------------------------------------------------------------
    | USER DASHBOARD - Barang Approved
    |--------------------------------------------------------------------------
    */
    public function userDashboard(Request $request)
    {
        $query = Item::where('status', 'approved');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_item', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('location_found', 'LIKE', "%{$search}%")
                    ->orWhere('finder_name', 'LIKE', "%{$search}%");
            });
        }

        $items = $query->latest()->paginate(12);

        return view('items.index', compact('items'));
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN DASHBOARD
    |--------------------------------------------------------------------------
    */
    public function adminDashboard(Request $request)
    {
        $pendingCount = Item::where('status', 'pending')->count();
        $approvedCount = Item::where('status', 'approved')->count();
        $takenCount = Item::where('status', 'taken')->count();

        // Query dengan filter
        $query = Item::with('user');

        // Filter berdasarkan status (kalau ada)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $items = $query->latest()->paginate(20);

        return view('admin.dashboard', compact('items', 'pendingCount', 'approvedCount', 'takenCount'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORM LAPOR BARANG
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('items.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE ITEM
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'nama_item'       => 'required|string|max:255',
            'description'     => 'nullable|string',
            'image'           => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'location_found'  => 'required|string|max:255',
            'date_found'      => 'required|date',
            'time_found'      => 'required',
            'finder_name'     => 'required|string|max:255',
            'finder_contact'  => 'required|string|max:255',
        ]);

        $imagePath = $request->file('image')->store('items', 'public');

        Item::create([
            'nama_item'      => $request->nama_item,
            'description'    => $request->description,
            'image'          => $imagePath,
            'location_found' => $request->location_found,
            'date_found'     => $request->date_found,
            'time_found'     => $request->time_found,
            'finder_name'    => $request->finder_name,
            'finder_contact' => $request->finder_contact,
            'status'         => 'pending',
            'user_id'        => auth()->id(),
        ]);

        return redirect()
            ->route('dashboard.user')
            ->with('success', 'Laporan berhasil dikirim dan menunggu verifikasi admin.');
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL ITEM - USER
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $item = Item::where('status', 'approved')->findOrFail($id);
        return view('items.show', compact('item'));
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL ITEM - ADMIN
    |--------------------------------------------------------------------------
    */
    public function adminShow($id)
    {
        $item = Item::with('user', 'claim')->findOrFail($id);
        return view('admin.show', compact('item'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORM CLAIM
    |--------------------------------------------------------------------------
    */
    public function claimForm($id)
    {
        $item = Item::where('status', 'approved')->findOrFail($id);
        return view('items.claim', compact('item'));
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN EDIT
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        return view('admin.edit', [
            'item' => Item::findOrFail($id)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $request->validate([
            'admin_contact' => 'nullable|string|max:255',
        ]);

        Item::findOrFail($id)->update([
            'admin_contact' => $request->admin_contact,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Kontak admin berhasil diupdate.');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN APPROVE
    |--------------------------------------------------------------------------
    */
    public function approve($id)
    {
        Item::findOrFail($id)->update(['status' => 'approved']);

        return request()->expectsJson()
            ? response()->json(['message' => 'Item approved'])
            : redirect()->route('admin.dashboard')->with('success', 'Item disetujui.');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN REJECT (DELETE + IMAGE)
    |--------------------------------------------------------------------------
    */
    public function reject($id)
    {
        $item = Item::findOrFail($id);

        if ($item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return request()->expectsJson()
            ? response()->json(['message' => 'Item rejected'])
            : redirect()->route('admin.dashboard')->with('success', 'Item ditolak dan dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN MARK AS TAKEN
    |--------------------------------------------------------------------------
    */
    public function markAsTaken($id)
    {
        Item::findOrFail($id)->update(['status' => 'taken']);

        return request()->expectsJson()
            ? response()->json(['message' => 'Item marked as taken'])
            : redirect()->route('admin.dashboard')->with('success', 'Barang ditandai sudah diambil.');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN DELETE (FINAL)
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        if ($item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Item berhasil dihapus.');
    }
}
