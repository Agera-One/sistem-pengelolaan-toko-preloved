<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Services\CodeGeneratorService;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index(Request $request, CodeGeneratorService $codeGenerator)
    {
        $keyword = trim((string) $request->query('q', ''));

        $barang = Barang::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $like = '%' . addcslashes($keyword, '%_\\') . '%';

                $query->where(function ($q) use ($like) {
                    $q->where('kode', 'like', $like)
                      ->orWhere('nama', 'like', $like)
                      ->orWhere('kategori', 'like', $like)
                      ->orWhere('status', 'like', $like);
                });
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $kode = $codeGenerator->generate(
        new barang(),
            'kode',
            'BRG'
        );

        return view('pages.barang', compact('barang', 'kode'));
    }

    public function store(Request $request, CodeGeneratorService $codeGenerator)
    {
        $validator = Validator::make($request->all(), [
            'nama'       => 'required|string|max:255',
            'lingkar'    => 'required|numeric',
            'panjang'    => 'required|numeric',
            'kategori'   => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('barang.index');
        }

        $kode = $codeGenerator->generate(new barang(), 'kode', 'BRG');

        try {
            Barang::create([
                'kode'       => $kode,
                'nama'       => $request->nama,
                'lingkar'    => $request->lingkar,
                'panjang'    => $request->panjang,
                'harga_jual' => $request->harga_jual,
                'kategori'   => $request->kategori,
                'status'     => 'Draft',
            ]);

            return redirect()->route('barang.index');
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('barang.index')
                ->with('error', 'Gagal memperbarui data barang.');
        }
    }

    public function update(Request $request, string $id)
    {
        $barang = Barang::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama'       => 'required|string|max:255',
            'lingkar'    => 'required|numeric',
            'panjang'    => 'required|numeric',
            'kategori'   => 'required|string|max:255',
        ], [
            'nama.required'     => 'Nama barang wajib diisi.',
            'lingkar.required'  => 'Lingkar wajib diisi.',
            'lingkar.numeric'   => 'Lingkar harus berbentuk angka.',
            'panjang.required'  => 'Panjang wajib diisi.',
            'panjang.numeric'   => 'Panjang harus berbentuk angka.',
            'kategori.required' => 'Kategori wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('barang.index')
                ->with('error', $validator->errors()->first());
        }

        try {
            $barang->update([
                'nama'       => $request->nama,
                'lingkar'    => $request->lingkar,
                'panjang'    => $request->panjang,
                'harga_jual' => $request->harga_jual,
                'kategori'   => $request->kategori,
            ]);

            return redirect()->route('barang.index');
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('barang.index')
                ->with('error', 'Gagal memperbarui data barang.');
        }
    }

    public function destroy(string $id)
    {
        $barang = barang::findOrFail($id);
        $barang->delete();
        return redirect()->route('barang.index');
    }
}
