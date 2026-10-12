<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Http\Requests\Barang\BarangRequest;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $barang = Barang::search($request->q)
            ->filter($request->query())
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('pages.barang', compact('barang'));
    }

    public function update(BarangRequest $request, Barang $barang)
    {
        $barang->update($request->validated());
        return redirect()->route('barang.index')->with('success', 'Data barang berhasil di perbarui');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Data barang berhasil di hapus');
    }
}
