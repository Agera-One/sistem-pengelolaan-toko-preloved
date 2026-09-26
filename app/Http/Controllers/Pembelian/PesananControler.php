<?php

namespace App\Http\Controllers\Pembelian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CodeGeneratorService;
use Illuminate\Support\Facades\DB;
use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\Barang;

class PesananControler extends Controller
{
    public function index(Request $request, CodeGeneratorService $codeGenerator)
    {
        $keyword = trim((string) $request->query('q', ''));
        $tanggalMulai = $request->query('tanggal_mulai');
        $tanggalSelesai = $request->query('tanggal_selesai');

        $isValidDate = fn ($value) => is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value);

        if (!$isValidDate($tanggalMulai) || !$isValidDate($tanggalSelesai)) {
            $tanggalMulai = null;
            $tanggalSelesai = null;
        }

        $pembelian = Pembelian::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $like = '%' . addcslashes($keyword, '%_\\') . '%';

                $query->where(function ($q) use ($like) {
                    $q->where('kode', 'like', $like)
                      ->orWhere('status', 'like', $like);
                });
            })
            ->when($tanggalMulai && $tanggalSelesai, function ($query) use ($tanggalMulai, $tanggalSelesai) {
                $query->whereBetween('tanggal', [
                    $tanggalMulai . ' 00:00:00',
                    $tanggalSelesai . ' 23:59:59',
                ]);
            })
            ->with(['supplier', 'user', 'detailPembelian.barang'])
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $kode = $codeGenerator->generate(
        new Pembelian(),
            'kode',
            'BEL'
        );

        $supplier = Supplier::orderBy('nama')->get();
        $barang = Barang::where('status', 'draft')->orderBy('nama')->get();

        return view('pages.pembelian.pesanan', compact('pembelian', 'kode', 'supplier', 'barang'));
    }

    public function store(Request $request, CodeGeneratorService $codeGenerator)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'supplier_id' => ['required', 'exists:supplier,id'],
            'barang_id' => ['required', 'array', 'min:1'],
            'barang_id.*' => ['required', 'exists:barang,id'],
            'harga_beli' => ['required', 'array'],
            'harga_beli.*' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'array'],
            'harga_jual.*' => ['required', 'numeric', 'min:0'],
        ], [
            'tanggal.required' => 'Tanggal wajib diisi.',
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'supplier_id.exists' => 'Supplier tidak valid.',
            'barang_id.required' => 'Tambahkan minimal satu barang.',
            'barang_id.min' => 'Tambahkan minimal satu barang.',
            'barang_id.*.required' => 'Barang wajib dipilih.',
            'barang_id.*.exists' => 'Barang tidak valid.',
            'harga_beli.*.required' => 'Harga beli wajib diisi.',
            'harga_beli.*.numeric' => 'Harga beli harus berupa angka.',
            'harga_beli.*.min' => 'Harga beli tidak boleh negatif.',
            'harga_jual.*.required' => 'Harga jual wajib diisi.',
            'harga_jual.*.numeric' => 'Harga jual harus berupa angka.',
            'harga_jual.*.min' => 'Harga jual tidak boleh negatif.',
        ]);

        DB::transaction(function () use ($validated, $codeGenerator) {
            $pembelian = Pembelian::create([
                'kode' => $codeGenerator->generate(new Pembelian(), 'kode', 'BEL'),
                'tanggal' => $validated['tanggal'],
                'status' => 'Belum Bayar',
                'total' => 0,
                'user_id' => auth()->id(),
                'supplier_id' => $validated['supplier_id'],
            ]);

            $total = 0;

            foreach ($validated['barang_id'] as $i => $barangId) {
                $hargaBeli = (int) $validated['harga_beli'][$i];
                $hargaJual = (int) $validated['harga_jual'][$i];

                $pembelian->detailPembelian()->create([
                    'barang_id' => $barangId,
                    'harga_beli' => $hargaBeli,
                    'harga_jual' => $hargaJual,
                ]);

                $total += $hargaBeli;
            }

            $pembelian->update(['total' => $total]);
        });

        return redirect()
            ->route('pembelian.pesanan.index')
            ->with('success', 'Pesanan pembelian berhasil ditambahkan.');
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
