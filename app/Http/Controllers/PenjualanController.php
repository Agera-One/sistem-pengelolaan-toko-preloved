<?php

namespace App\Http\Controllers;

use App\Enums\BarangStatus;
use App\Enums\PenjualanStatus;
use App\Models\Barang;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Services\CodeGeneratorService;
use Illuminate\Http\Request;
use App\Http\Requests\PenjualanRequest;
use App\Services\PenjualanService;

class PenjualanController extends Controller
{
    public function __construct(
        protected CodeGeneratorService $codeGenerator,
        protected PenjualanService $penjualanService,
    ) {}

    public function index(Request $request)
    {
        $penjualan = Penjualan::search($request->q)
            ->filter($request->query())
            ->dateBetween($request->tanggal_mulai, $request->tanggal_selesai)
            ->with(['pelanggan', 'user'])
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $pelanggan = Pelanggan::orderBy('nama')->get();
        $statuses = PenjualanStatus::cases();

        return view('pages.penjualan.index', compact('penjualan', 'pelanggan', 'statuses'));
    }

    public function show(Penjualan $penjualan)
    {
        return view('pages.penjualan.detail', compact('penjualan'));
    }

    public function create()
    {
        $barang = Barang::where('status', BarangStatus::TERSEDIA)->latest('id')->get();
        $kode = $this->codeGenerator->penjualan();
        $pelanggan = Pelanggan::orderBy('nama')->get();
        return view('pages.penjualan.create', compact('barang', 'kode', 'pelanggan'));
    }

    public function store(PenjualanRequest $request)
    {
        $this->penjualanService->create($request->validated(), auth()->id());
        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil di tambahkan');
    }

    public function edit(Penjualan $penjualan)
    {
        $barang = Barang::where('status', BarangStatus::TERSEDIA)
            ->orWhereIn('id', $penjualan->barang()->pluck('barang.id'))
            ->latest('id')
            ->get();

        $pelanggan = Pelanggan::orderBy('nama')->get();
        return view('pages.penjualan.edit', compact('penjualan', 'barang', 'pelanggan'));
    }

    public function update(PenjualanRequest $request, Penjualan $penjualan)
    {
        $this->penjualanService->update($penjualan, $request->validated());
        return redirect()->route('penjualan.index')->with('success', "Data penjualan berhasil di perbarui");
    }

    public function destroy(Penjualan $penjualan)
    {
        $penjualan->delete();
        return redirect()->route('penjualan.index')->with('success', "Data penjualan berhasil di hapus");
    }
}
