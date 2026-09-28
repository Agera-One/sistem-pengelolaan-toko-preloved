<?php

namespace App\Http\Controllers\Pembelian;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\Supplier;
use App\Services\CodeGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PesananControler extends Controller
{
    public function index(Request $request)
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
            ->with(['supplier', 'user'])
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $supplier = Supplier::orderBy('nama')->get();

        return view('pages.pembelian.pesanan-index', compact('pembelian', 'supplier'));
    }

    public function create(CodeGeneratorService $codeGenerator)
    {
        $kode = $codeGenerator->generate(new Pembelian(), 'kode', 'BEL');
        $kodeBarang = $codeGenerator->generate(new Barang(), 'kode', 'BRG');

        $supplier = Supplier::orderBy('nama')->get();

        return view('pages.pembelian.pesanan-create', compact('kode', 'kodeBarang', 'supplier'));
    }

    public function show(string $id)
    {
        $pembelian = Pembelian::with(['supplier', 'user', 'barang'])->findOrFail($id);

        return view('pages.pembelian.pesanan-detail', compact('pembelian'));
    }

    public function store(Request $request, CodeGeneratorService $codeGenerator)
    {
        $items = collect($request->input('items', []))
            ->filter(fn ($row) => is_array($row) && collect($row)->contains(fn ($v) => filled($v)))
            ->map(function (array $row) {
                foreach (['harga_beli', 'harga_jual'] as $field) {
                    $row[$field] = isset($row[$field]) ? preg_replace('/\D/', '', (string) $row[$field]) : null;
                }
                return $row;
            });

        $request->merge(['items' => $items->all()]);

        $validator = Validator::make($request->all(), [
            'tanggal'            => 'required|date',
            'supplier_id'        => 'required|exists:supplier,id',
            'items'              => 'required|array|min:1',
            'items.*.nama'       => 'required|string|max:255',
            'items.*.kategori'   => 'required|string|max:255',
            'items.*.lingkar'    => 'required|numeric|min:0',
            'items.*.panjang'    => 'required|numeric|min:0',
            'items.*.harga_beli' => 'required|numeric|min:1',
            'items.*.harga_jual' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->route('pembelian.pesanan.create')
                ->withErrors($validator)
                ->withInput();
        }

        $rows = $items->values();

        try {
            $pembelian = DB::transaction(function () use ($request, $rows, $codeGenerator) {
                $kodePo = $codeGenerator->generate(new Pembelian(), 'kode', 'BEL');
                $kodeBarang = $codeGenerator->generateBatch(new Barang(), 'kode', 'BRG', $rows->count());

                $pembelian = Pembelian::create([
                    'kode'        => $kodePo,
                    'tanggal'     => $request->tanggal,
                    'total'       => $rows->sum(fn ($r) => (int) $r['harga_beli']),
                    'status'      => 'Belum Bayar',
                    'supplier_id' => $request->supplier_id,
                    'user_id'     => auth()->id(),
                ]);

                foreach ($rows as $i => $row) {
                    Barang::create([
                        'kode'         => $kodeBarang[$i],
                        'nama'         => $row['nama'],
                        'kategori'     => $row['kategori'],
                        'lingkar'      => $row['lingkar'],
                        'panjang'      => $row['panjang'],
                        'harga_beli'   => $row['harga_beli'],
                        'harga_jual'   => $row['harga_jual'],
                        'status'       => 'Tersedia',
                        'pembelian_id' => $pembelian->id,
                    ]);
                }

                return $pembelian;
            });

            return redirect()->route('pembelian.pesanan.index')
                ->with('success', "Pesanan {$pembelian->kode} berhasil disimpan dengan {$rows->count()} barang.");
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('pembelian.pesanan.create')
                ->withInput()
                ->with('error', 'Gagal menyimpan pesanan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        $pembelian = Pembelian::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'tanggal'     => 'required|date',
            'total'       => 'required|numeric|min:1',
            'supplier_id' => 'required|exists:supplier,id',
        ], [
            'tanggal.required'     => 'Tanggal wajib diisi.',
            'total.required'       => 'Harga beli wajib diisi.',
            'total.numeric'        => 'Harga beli harus berupa angka.',
            'total.min'            => 'Total harga minimal 1.',
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'supplier_id.exists'   => 'Supplier tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('pembelian.pesanan.index')
                ->with('error', $validator->errors()->first());
        }

        try {
            $pembelian->update([
                'tanggal'     => $request->tanggal,
                'total'       => $request->total,
                'supplier_id' => $request->supplier_id,
            ]);

            return redirect()->route('pembelian.pesanan.index');
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('pembelian.pesanan.index')
                ->with('error', 'Gagal memperbarui data pembelian.');
        }
    }

    public function destroy(string $id)
    {
        $pembelian = Pembelian::findOrFail($id);
        $pembelian->delete();

        return redirect()->route('pembelian.pesanan.index');
    }
}
