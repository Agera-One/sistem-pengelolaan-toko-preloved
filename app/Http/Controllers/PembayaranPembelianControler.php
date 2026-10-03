<?php

namespace App\Http\Controllers;

use App\Models\PembayaranPembelian;
use Illuminate\Http\Request;

class PembayaranPembelianControler extends Controller
{
     public function index(Request $request)
    {
        $keyword = trim((string) $request->query('q', ''));
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
                    ->orWhere('metode_pembayaran', 'like', $like)
                    ->orWhereHas('pembelian', fn ($s) => $s->where('kode', 'like', $like));
                });
            })
            ->when($tanggalMulai, fn ($q) => $q->where('tanggal', '>=', $tanggalMulai . ' 00:00:00'))
            ->when($tanggalSelesai, fn ($q) => $q->where('tanggal', '<=', $tanggalSelesai . ' 23:59:59'))
            ->with('pembelian')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('pages.pembayaran-pembelian', compact('pembayaran'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
