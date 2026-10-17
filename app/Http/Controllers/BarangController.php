<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Http\Requests\BarangRequest;
use App\Enums\BarangStatus;
use App\Enums\KategoriBarang;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $barang = Barang::search($request->q)
            ->filter($request->query())
            ->latest('id')
            ->paginate(10)
            ->withQueryString();


        $statuses = BarangStatus::cases();
        $kategori = KategoriBarang::cases();

        return view('pages.barang', compact('barang', 'statuses', 'kategori'));
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
