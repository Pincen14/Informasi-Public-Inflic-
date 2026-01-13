<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Item;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    public function store(Request $request, $itemId)
    {
        $request->validate([
            'nama_pengambil' => 'required',
            'NIMorKTP' => 'required',
            'phone_pengambil' => 'required',
            'foto_pengambil' => 'required|image',
            'tgl_ambil' => 'required|date'
        ]);

        $foto = time() . '.' . $request->foto_pengambil->extension();
        $request->foto_pengambil->move(public_path('claims'), $foto);

        Claim::create([
            'item_id' => $itemId,
            'nama_pengambil' => $request->nama_pengambil,
            'NIMorKTP' => $request->NIMorKTP,
            'phone_pengambil' => $request->phone_pengambil,
            'foto_pengambil' => $foto,
            'tgl_ambil' => $request->tgl_ambil
        ]);

        Item::find($itemId)->update(['status' => 'taken']);

        return redirect()
            ->route('dashboard.user')
            ->with('success', 'Klaim berhasil dikirim! Admin akan menghubungi Anda segera.');
    }
}
