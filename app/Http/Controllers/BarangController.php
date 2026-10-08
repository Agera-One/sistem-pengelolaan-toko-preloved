<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function update(Request $request, Barang $barang)
    {
        $validator = Validator::make($request->all(), [
            'nama'          => 'required|string|max:255',
            'lingkar'       => 'required|numeric|min:1',
            'panjang'       => 'required|numeric|min:1',
            'kategori'      => 'required|string|max:255',
            'harga_jual'    => 'required|numeric|min:1|gt:harga_beli',
        ], [
            'nama.required'         => 'Nama barang wajib diisi.',
            'nama.max'              => 'Nama barang maksimal 255 karakter.',
            'lingkar.required'      => 'Lingkar wajib diisi.',
            'lingkar.numeric'       => 'Lingkar harus berbentuk angka.',
            'panjang.required'      => 'Panjang wajib diisi.',
            'panjang.numeric'       => 'Panjang harus berbentuk angka.',
            'kategori.required'     => 'Kategori wajib diisi.',
            'kategori.max'          => 'Kategori barang maksimal 255 karakter.',
            'harga_jual.required'   => 'Harga jual wajib diisi.',
            'harga_jual.numeric'    => 'Harga jual harus berbentuk angka.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('barang.index')
                ->with('error', $validator->errors()->first());
        }

        try {
            $barang->update([
                'nama'          => $request->nama,
                'lingkar'       => $request->lingkar,
                'panjang'       => $request->panjang,
                'kategori'      => $request->kategori,
                'harga_jual'    => $request->harga_jual,
            ]);

            return redirect()->route('barang.index');
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('barang.index')
                ->with('error', 'Gagal memperbarui data barang.');
        }
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index');
    }
}
