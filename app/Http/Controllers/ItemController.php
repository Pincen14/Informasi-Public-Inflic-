<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | API - Get All Items (dari kode lama kamu)
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return Item::with('user')->get();
    }

    /*
    |--------------------------------------------------------------------------
    | USER DASHBOARD - Tampil Barang Approved
    |--------------------------------------------------------------------------
    */
    public function userDashboard(Request $request)
    {
        $query = Item::where('status', 'approved');

        // Fitur search
        if ($request->has('search') && $request->search != '') {
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
    | ADMIN DASHBOARD - Kelola Semua Laporan
    |--------------------------------------------------------------------------
    */
    public function adminDashboard()
    {
        $pendingCount = Item::where('status', 'pending')->count();
        $approvedCount = Item::where('status', 'approved')->count();
        $takenCount = Item::where('status', 'taken')->count();

        $items = Item::with('user')->latest()->paginate(20);

        return view('admin.dashboard', compact('items', 'pendingCount', 'approvedCount', 'takenCount'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORM LAPOR BARANG DITEMUKAN
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('items.create');
    }

    /*
    |--------------------------------------------------------------------------
    | SUBMIT LAPORAN (dari kode lama kamu + tambahan kolom baru)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'nama_item' => 'required',
            'description' => 'nullable|string',
            'image' => 'required|image',
            'location_found' => 'required',
            'date_found' => 'required|date',
            'time_found' => 'required',
            'finder_name' => 'required|string|max:255',
            'finder_contact' => 'required|string|max:255',
        ]);

        // Upload gambar 
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('items'), $imageName);

        Item::create([
            'nama_item' => $request->nama_item,
            'description' => $request->description,
            'image' => $imageName,
            'location_found' => $request->location_found,
            'date_found' => $request->date_found,
            'time_found' => $request->time_found,
            'finder_name' => $request->finder_name,
            'finder_contact' => $request->finder_contact,
            'admin_contact' => null,
            'status' => 'pending',
            'user_id' => auth()->id()
        ]);


        // Request dari form web
        return redirect()->route('dashboard.user')
            ->with('success', 'Laporan berhasil dikirim! Menunggu verifikasi admin.');

        // Request dari API
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Item created']);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL BARANG (User View) - TIDAK tampil finder_contact
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $item = Item::where('status', 'approved')->findOrFail($id);

        // User TIDAK bisa lihat finder_contact
        return view('items.show', compact('item'));
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL BARANG (Admin View) - BISA lihat finder_contact
    |--------------------------------------------------------------------------
    */
    public function adminShow($id)
    {
        $item = Item::with('user', 'claim')->findOrFail($id);

        // Admin BISA lihat finder_contact
        return view('admin.show', compact('item'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORM KLAIM BARANG
    |--------------------------------------------------------------------------
    */
    public function claimForm($id)
    {
        $item = Item::where('status', 'approved')->findOrFail($id);
        return view('items.claim', compact('item'));
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN - EDIT ITEM (Isi admin_contact)
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $item = Item::findOrFail($id);
        return view('admin.edit', compact('item'));
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN - UPDATE ITEM
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'admin_contact' => 'nullable|string|max:255',
        ]);

        $item->update([
            'admin_contact' => $request->admin_contact,
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Kontak admin berhasil diupdate!');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN - APPROVE (dari kode lama kamu)
    |--------------------------------------------------------------------------
    */
    public function approve($id)
    {
        Item::findOrFail($id)->update(['status' => 'approved']);

        // Kalau request dari API
        if (request()->expectsJson()) {
            return response()->json(['message' => 'Item approved']);
        }

        // Kalau request dari form web
        return redirect()->route('admin.dashboard')
            ->with('success', 'Laporan berhasil disetujui!');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN - REJECT (Delete)
    |--------------------------------------------------------------------------
    */
    public function reject($id)
    {
        $item = Item::findOrFail($id);

        // Hapus gambar
        if ($item->image && file_exists(public_path('items/' . $item->image))) {
            unlink(public_path('items/' . $item->image));
        }

        $item->delete();

        // Kalau request dari API
        if (request()->expectsJson()) {
            return response()->json(['message' => 'Item rejected']);
        }

        // Kalau request dari form web
        return redirect()->route('admin.dashboard')
            ->with('success', 'Laporan ditolak dan dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN - MARK AS TAKEN
    |--------------------------------------------------------------------------
    */
    public function markAsTaken($id)
    {
        Item::findOrFail($id)->update(['status' => 'taken']);

        // Kalau request dari API
        if (request()->expectsJson()) {
            return response()->json(['message' => 'Item marked as taken']);
        }

        // Kalau request dari form web
        return redirect()->route('admin.dashboard')
            ->with('success', 'Barang ditandai sudah diambil.');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN - DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        // Hapus gambar
        if ($item->image && file_exists(public_path('items/' . $item->image))) {
            unlink(public_path('items/' . $item->image));
        }

        $item->delete();

        // Kalau request dari API
        if (request()->expectsJson()) {
            return response()->json(['message' => 'Item deleted']);
        }

        // Kalau request dari form web
        return redirect()->route('admin.dashboard')
            ->with('success', 'Item berhasil dihapus!');
    }
}
