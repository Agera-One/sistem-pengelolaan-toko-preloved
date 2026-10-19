<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Services\PelangganService;
use Illuminate\Http\Request;
use App\Services\CodeGeneratorService;
use App\Http\Requests\PelangganRequest;

class PelangganController extends Controller
{
    public function __construct(
        protected CodeGeneratorService $codeGenerator,
        protected PelangganService $pelangganService,
    ) {}

    public function index(Request $request)
    {
        return view('pages.pelanggan', $this->pelangganService->index($request->query()));
    }

    public function store(PelangganRequest $request)
    {

        $this->pelangganService->store($request->validated());
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
