<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\Supplier;
use App\Services\CodeGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PembelianControler extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->query('q', ''));
        $tanggalMulai = $request->query('tanggal_mulai');
        $tanggalSelesai = $request->query('tanggal_selesai');

        $isValidDate = fn ($value) => is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value);

        $tanggalMulai   = $isValidDate($tanggalMulai) ? $tanggalMulai : null;
        $tanggalSelesai = $isValidDate($tanggalSelesai) ? $tanggalSelesai : null;

        $pembelian = Pembelian::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $like = '%' . addcslashes($keyword, '%_\\') . '%';

                $query->where(function ($q) use ($like) {
                    $q->where('kode', 'like', $like)
                    ->orWhere('status', 'like', $like)
                    ->orWhereHas('supplier', fn ($s) => $s->where('nama', 'like', $like));
                });
            })
            ->when($tanggalMulai, fn ($q) => $q->where('tanggal', '>=', $tanggalMulai . ' 00:00:00'))
            ->when($tanggalSelesai, fn ($q) => $q->where('tanggal', '<=', $tanggalSelesai . ' 23:59:59'))
            ->with(['supplier', 'user'])
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $supplier = Supplier::orderBy('nama')->get();

        return view('pages.pembelian.index', compact('pembelian', 'supplier'));
    }

    public function create(CodeGeneratorService $codeGenerator)
    {
        $kode = $codeGenerator->generate(new Pembelian(), 'kode', 'BEL');
        $kodeBarang = $codeGenerator->generate(new Barang(), 'kode', 'BRG');

        $supplier = Supplier::orderBy('nama')->get();

        return view('pages.pembelian.create', compact('kode', 'kodeBarang', 'supplier'));
    }

    public function show(string $id)
    {
        $pembelian = Pembelian::with(['supplier', 'user', 'barang'])->findOrFail($id);

        return view('pages.pembelian.detail', compact('pembelian'));
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
            return redirect()->route('pembelian.create')
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

            return redirect()->route('pembelian.index')
                ->with('success', "Pesanan {$pembelian->kode} berhasil disimpan dengan {$rows->count()} barang.");
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('pembelian.create')
                ->withInput()
                ->with('error', 'Gagal menyimpan pesanan: ' . $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        $pembelian = Pembelian::with('barang')->findOrFail($id);
        $supplier = Supplier::orderBy('nama')->get();

        return view('pages.pembelian.edit', compact('pembelian', 'supplier'));
    }

    public function update(Request $request, string $id)
    {
        $pembelian = Pembelian::findOrFail($id);

        $lockedBarang = $pembelian->barang()->where('status', '!=', 'Tersedia')->get()->keyBy('id');
        $lockedIds = $lockedBarang->keys()->all();

        $items = collect($request->input('items', []))
            ->filter(fn ($row) => is_array($row)
                && collect($row)->except('id')->contains(fn ($v) => filled($v)))
            ->map(function (array $row) use ($lockedBarang) {
                foreach (['harga_beli', 'harga_jual'] as $field) {
                    $row[$field] = isset($row[$field]) ? preg_replace('/\D/', '', (string) $row[$field]) : null;
                }

                $locked = $lockedBarang->get((int) ($row['id'] ?? 0));
                if ($locked) {
                    $row['harga_beli'] = (string) (int) $locked->harga_beli;
                }

                return $row;
            })
            ->values();

        $request->merge(['items' => $items->all()]);

        $validator = Validator::make($request->all(), [
            'tanggal'            => 'required|date',
            'supplier_id'        => 'required|exists:supplier,id',
            'items'              => $lockedIds ? 'nullable|array' : 'required|array|min:1',
            'items.*.id'         => 'nullable|integer',
            'items.*.nama'       => 'required|string|max:255',
            'items.*.kategori'   => 'required|string|max:255',
            'items.*.lingkar'    => 'required|numeric|min:0',
            'items.*.panjang'    => 'required|numeric|min:0',
            'items.*.harga_beli' => 'required|numeric|min:1',
            'items.*.harga_jual' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->route('pembelian.edit', $pembelian->id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $items, $pembelian, $lockedBarang) {
                $editable = $pembelian->barang()->where('status', 'Tersedia')->get()->keyBy('id');

                $baru = $items->filter(fn ($r) => blank($r['id'] ?? null));
                $kodeBaru = $baru->isNotEmpty()
                    ? app(CodeGeneratorService::class)->generateBatch(new Barang(), 'kode', 'BRG', $baru->count())
                    : [];
                $n = 0;

                $keptIds = [];

                foreach ($items as $row) {
                    $data = Arr::only($row, ['nama', 'kategori', 'lingkar', 'panjang', 'harga_beli', 'harga_jual']);

                    if (filled($row['id'] ?? null)) {
                        $id = (int) $row['id'];

                        if ($locked = $lockedBarang->get($id)) {
                            $locked->update(Arr::except($data, ['harga_beli']));
                            continue;
                        }

                        $barang = $editable->get($id);
                        if (!$barang) {
                            throw new \RuntimeException('Barang tidak valid.');
                        }
                        $barang->update($data);
                        $keptIds[] = $barang->id;
                    } else {
                        $barang = Barang::create($data + [
                            'kode'         => $kodeBaru[$n++],
                            'status'       => 'Tersedia',
                            'pembelian_id' => $pembelian->id,
                        ]);
                        $keptIds[] = $barang->id;
                    }
                }

                $editable->except($keptIds)->each->delete();

                $pembelian->update([
                    'tanggal'     => $request->tanggal,
                    'supplier_id' => $request->supplier_id,
                    'total'       => $pembelian->barang()->sum('harga_beli'),
                ]);
            });

            return redirect()->route('pembelian.index')
                ->with('success', "Pesanan {$pembelian->kode} berhasil diperbarui.");
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('pembelian.edit', $pembelian->id)
                ->withInput()
                ->with('error', 'Gagal memperbarui pesanan: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $pembelian = Pembelian::findOrFail($id);
        $pembelian->delete();

        return redirect()->route('pembelian.index');
    }
}
