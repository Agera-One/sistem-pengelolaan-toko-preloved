<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembayaranPembelian;
use Illuminate\Http\Request;
use App\Services\CodeGeneratorService;

class PembayaranPembelianControler extends Controller
{
    private const STATUS_SUDAH_BAYAR = 'Sudah Bayar';
    private const STATUS_BELUM_BAYAR = 'Belum Bayar';

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

        $pembelian = Pembelian::where('status', self::STATUS_BELUM_BAYAR)->with('supplier')->latest('id')->get();

        $kodeBayar = $codeGenerator->generate(new PembayaranPembelian(), 'kode', 'KLR');

        return view('pages.pembayaran-pembelian', compact('pembayaran', 'pembelian', 'kodeBayar'));
    }

    public function store(Request $request, CodeGeneratorService $codeGenerator)
    {
        $data = $this->validasi($request);
        $data['kode'] = $codeGenerator->generate(new PembayaranPembelian(), 'kode', 'KLR');

        $pembayaran = PembayaranPembelian::create($data);
        $this->sinkronStatus($pembayaran->pembelian_id);

        return response()->json(['ok' => true]);
    }

    public function update(Request $request, string $id)
    {
        $pembayaran = PembayaranPembelian::findOrFail($id);
        $data = $this->validasi($request, $pembayaran);
        $pembelianLamaId = $pembayaran->pembelian_id;

        $pembayaran->update($data);

        $this->sinkronStatus($pembelianLamaId);
        $this->sinkronStatus($pembayaran->pembelian_id);

        return response()->json(['ok' => true]);
    }

    private function validasi(Request $request, ?PembayaranPembelian $pembayaran = null): array
    {
        $data = $request->validate([
            'pembelian_id' => ['required', 'exists:pembelian,id'],
            'tanggal' => ['required', 'date'],
            'metode_pembayaran' => ['required', 'in:Tunai,Transfer'],
        ], [
            'pembelian_id.required' => 'Pilih pembelian yang dibayar.',
            'tanggal.required' => 'Tanggal bayar wajib diisi.',
            'metode_pembayaran.required' => 'Pilih metode pembayaran.',
        ]);

        $pembelian = Pembelian::findOrFail($data['pembelian_id']);
        $data['nominal'] = $pembelian->total;

        return $data;
    }

    private function sinkronStatus(int|string $pembelianId): void
    {
        $sudah_bayar = PembayaranPembelian::where('pembelian_id', $pembelianId)->exists();

        Pembelian::whereKey($pembelianId)->update([
            'status' => $sudah_bayar ? self::STATUS_SUDAH_BAYAR : self::STATUS_BELUM_BAYAR,
        ]);
    }

    public function destroy(string $id)
    {
        $pembelian = PembayaranPembelian::findOrFail($id);
        $pembelian->delete();

        return redirect()->route('pembayaran-pembelian.index');
    }
}
