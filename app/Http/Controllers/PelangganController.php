<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Services\CodeGeneratorService;
use App\Http\Requests\Pelanggan\PelangganRequest;


class PelangganController extends Controller
{
    public function __construct(protected CodeGeneratorService $codeGenerator) {}

    public function index(Request $request)
    {
        $pelanggan = Pelanggan::search($request->query('q'))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $kode = $this->codeGenerator->pelanggan();

        return view('pages.pelanggan', compact('pelanggan', 'kode'));
    }

    public function store(PelangganRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = $this->codeGenerator->pelanggan();
        Pelanggan::create($validated);
        return redirect()->route('pelanggan.index')->with('success', 'Data pelanggan berhasil di tambahkan');
    }

    public function update(PelangganRequest $request, Pelanggan $pelanggan)
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
