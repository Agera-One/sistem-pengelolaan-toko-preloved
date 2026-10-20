<?php

namespace App\Http\Controllers;

use App\Enums\KategoriBarang;
use App\Models\Pembelian;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Requests\Pembelian\StoreRequest;
use App\Http\Requests\Pembelian\UpdateRequest;
use App\Services\PembelianService;

class PembelianController extends Controller
{
    public function __construct(protected PembelianService $pembelianService) {}

    public function index(Request $request)
    {
        return view('pages.pembelian.index', $this->pembelianService->index($request->query()));
    }

    public function show(Pembelian $pembelian)
    {
        return view('pages.pembelian.detail', compact('pembelian'));
    }

    public function create()
    {
        return view('pages.pembelian.create', $this->pembelianService->create());
    }

    public function store(StoreRequest $request)
    {
        $this->pembelianService->store($request->validated(), auth()->id());
        return redirect()->route('pembelian.index')->with('success', 'Data pembelian berhasil di tambahkan');
    }

    public function edit(Pembelian $pembelian)
    {
        return view('pages.pembelian.edit', $this->pembelianService->edit($pembelian));
    }

    public function update(UpdateRequest $request, Pembelian $pembelian)
    {
        $this->pembelianService->update($pembelian, $request->validated());
        return redirect()->route('pembelian.index')->with('success', 'Data pembelian berhasil di perbarui');
    }

    public function destroy(Pembelian $pembelian)
    {
        $pembelian->delete();
        return redirect()->route('pembelian.index');
    }
}
