<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Http\Requests\Barang\UpdateBarangRequest;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $keyword  = trim((string) $request->query('q', ''));
        $kategori = $request->query('kategori');
        $status   = $request->query('status');

        $barang = Barang::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $like = '%' . addcslashes($keyword, '%_\\') . '%';

                $query->where(function ($q) use ($like) {
                    $q->where('kode', 'like', $like)
                    ->orWhere('nama', 'like', $like);
                });
            })
            ->when($kategori, function ($query) use ($kategori) {
                $query->where('kategori', $kategori);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('pages.barang', compact('barang'));
    }

    public function update(UpdateBarangRequest $request, Barang $barang)
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
