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
        $keyword = trim((string) $request->query('q', ''));
        $metode = $request->query('metode');
        $tanggalMulai = $request->query('tanggal_mulai');
        $tanggalSelesai = $request->query('tanggal_selesai');

        $isValidDate = fn ($value) => is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value);

        $tanggalMulai   = $isValidDate($tanggalMulai) ? $tanggalMulai : null;
        $tanggalSelesai = $isValidDate($tanggalSelesai) ? $tanggalSelesai : null;

        $pembayaran = PembayaranPembelian::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $like = '%' . addcslashes($keyword, '%_\\') . '%';

                $query->where(function ($q) use ($like) {
                    $q->where('kode', 'like', $like)
                    ->orWhereHas('pembelian', fn ($s) => $s->where('kode', 'like', $like));
                });
            })
            ->when($metode, function ($query) use ($metode) {
                $query->where('metode_pembayaran', $metode);
            })
            ->when($tanggalMulai, fn ($q) => $q->where('tanggal', '>=', $tanggalMulai . ' 00:00:00'))
            ->when($tanggalSelesai, fn ($q) => $q->where('tanggal', '<=', $tanggalSelesai . ' 23:59:59'))
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
