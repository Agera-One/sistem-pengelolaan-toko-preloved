<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\PembayaranPembelian;
use App\Services\CodeGeneratorService;
use App\Http\Requests\PembayaranPembelian\PembayaranPembelianRequest;

class PembayaranPembelianController extends Controller
{
    public function __construct(protected CodeGeneratorService $codeGenerator) {}

    public function index(Request $request)
    {
        $pembayaran = PembayaranPembelian::search($request->q)
            ->filter($request->query())
            ->dateBetween($request->tanggal_mulai, $request->tanggal_selesai)
            ->with('pembelian.supplier')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $pembelian = Pembelian::where('status', 'Belum Bayar')->with('supplier')->latest('id')->get();

        $kode = $this->codeGenerator->pembayaranPembelian();

        return view('pages.pembayaran-pembelian', compact('pembayaran', 'pembelian', 'kode'));
    }

    public function store(PembayaranPembelianRequest $request)
    {
        $validated = $request->validated();
        $pembelian = Pembelian::findOrFail($validated['pembelian_id']);

        $validated['kode']    = $this->codeGenerator->pembayaranPembelian();
        $validated['nominal'] = $pembelian->total;

        PembayaranPembelian::create($validated);
        return response()->json(['ok' => true]);
    }

    public function update(PembayaranPembelianRequest $request, PembayaranPembelian $pembayaranPembelian)
    {
        $validated = $request->validated();
        $validated['nominal'] = Pembelian::findOrFail($validated['pembelian_id'])->total;

        $pembayaranPembelian->update($validated);
        return response()->json(['ok' => true]);
    }

    public function destroy(PembayaranPembelian $pembayaranPembelian)
    {
        $pembayaranPembelian->delete();
        return redirect()->route('pembayaran-pembelian.index');
    }
}
