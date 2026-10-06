<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembayaranPembelian;
use Illuminate\Http\Request;
use App\Services\CodeGeneratorService;

class PembayaranPembelianControler extends Controller
{
    public function index(Request $request, CodeGeneratorService $codeGenerator)
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

        $kodeBayar = $codeGenerator->generate(new PembayaranPembelian(), 'kode', 'KLR');

        return view('pages.pembayaran-pembelian', compact('pembayaran', 'pembelian', 'kodeBayar'));
    }

    public function store(Request $request, CodeGeneratorService $codeGenerator)
    {
        $data = $this->validasi($request);
        $data['kode'] = $codeGenerator->generate(new PembayaranPembelian(), 'kode', 'KLR');

        PembayaranPembelian::create($data);

        return response()->json(['ok' => true]);
    }

    public function update(Request $request, PembayaranPembelian $pembayaranPembelian)
    {
        $data = $this->validasi($request, $pembayaranPembelian);
        $pembayaranPembelian->update($data);
        return response()->json(['ok' => true]);
    }

    private function validasi(Request $request, ?PembayaranPembelian $pembayaran = null): array
    {
        $data = $request->validate([
            'pembelian_id'          => 'required|exists:pembelian,id',
            'tanggal'               => 'required|date',
            'metode_pembayaran'     => 'required|in:Tunai,Transfer',
        ], [
            'pembelian_id.required'         => 'Pilih pembelian yang dibayar.',
            'tanggal.required'              => 'Tanggal bayar wajib diisi.',
            'metode_pembayaran.required'    => 'Pilih metode pembayaran.',
        ]);

        $pembelian = Pembelian::findOrFail($data['pembelian_id']);
        $data['nominal'] = $pembelian->total;

        return $data;
    }

    public function destroy(PembayaranPembelian $pembayaranPembelian)
    {
        $pembayaranPembelian->delete();
        return redirect()->route('pembayaran-pembelian.index');
    }
}
