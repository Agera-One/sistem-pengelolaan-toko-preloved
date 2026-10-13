<?php

namespace App\Http\Controllers;

use App\Enums\PembelianStatus;
use App\Models\Pembelian;
use App\Models\Supplier;
use App\Services\CodeGeneratorService;
use Illuminate\Http\Request;
use App\Http\Requests\Pembelian\StoreRequest;
use App\Http\Requests\Pembelian\UpdateRequest;
use App\Services\PembelianService;

class PembelianController extends Controller
{
    public function __construct(
        protected CodeGeneratorService $codeGenerator,
        protected PembelianService $pembelianService,
    ) {}

    public function index(Request $request)
    {
        $pembelian = Pembelian::search($request->q)
            ->filter($request->query())
            ->dateBetween($request->tanggal_mulai, $request->tanggal_selesai)
            ->with(['supplier', 'user'])
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $supplier = Supplier::orderBy('nama')->get();
        $statuses = PembelianStatus::cases();

        return view('pages.pembelian.index', compact('pembelian', 'supplier', 'statuses'));
    }

    public function create()
    {
        $kode = $this->codeGenerator->pembelian();
        $supplier = Supplier::orderBy('nama')->get();
        return view('pages.pembelian.create', compact('kode', 'supplier'));
    }

    public function show(Pembelian $pembelian)
    {
        return view('pages.pembelian.detail', compact('pembelian'));
    }

    public function store(StoreRequest $request)
    {
        $this->pembelianService->create($request->validated(), auth()->id());
        return redirect()->route('pembelian.index')->with('success', 'Data pembelian berhasil di tambahkan');
    }

    public function edit(Pembelian $pembelian)
    {
        $supplier = Supplier::orderBy('nama')->get();
        return view('pages.pembelian.edit', compact('pembelian', 'supplier'));
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
