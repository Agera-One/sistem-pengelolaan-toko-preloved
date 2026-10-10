<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Services\CodeGeneratorService;
use App\Http\Requests\Pelanggan\StorePelangganRequest;
use App\Http\Requests\Pelanggan\UpdatePelangganRequest;


class PelangganController extends Controller
{
    public function __construct(protected CodeGeneratorService $codeGenerator) {}

    public function index(Request $request)
    {
        $keyword = trim((string) $request->query('q', ''));

        $pelanggan = Pelanggan::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $like = '%' . addcslashes($keyword, '%_\\') . '%';

                $query->where(function ($q) use ($like) {
                    $q->where('kode', 'like', $like)
                      ->orWhere('nama', 'like', $like)
                      ->orWhere('nomor_telepon', 'like', $like)
                      ->orWhere('alamat', 'like', $like);
                });
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $kode = $this->codeGenerator->pelanggan();

        return view('pages.pelanggan', compact('pelanggan', 'kode'));
    }

    public function store(StorePelangganRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = $this->codeGenerator->pelanggan();
        Pelanggan::create($validated);
        return redirect()->route('pelanggan.index')->with('success', 'Data pelanggan berhasil di tambahkan');
    }

    public function update(UpdatePelangganRequest $request, Pelanggan $pelanggan)
    {
        $pelanggan->update($request->validated());
        return redirect()->route('pelanggan.index')->with('success', 'Data pelanggan berhasil di perbarui');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();
        return redirect()->route('pelanggan.index')->with('success', 'Data pelanggan berhasil di hapus');
    }
}
