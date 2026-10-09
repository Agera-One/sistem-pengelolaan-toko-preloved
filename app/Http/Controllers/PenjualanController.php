<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\Pelanggan;
use App\Services\CodeGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PenjualanController extends Controller
{
    private const BARANG_TERSEDIA = 'Tersedia';
    private const BARANG_TERJUAL  = 'Terjual';


    public function index(Request $request)
    {
        $keyword        = trim((string) $request->query('q', ''));
        $tanggalMulai   = $request->query('tanggal_mulai');
        $tanggalSelesai = $request->query('tanggal_selesai');
        $status         = $request->query('status');

        $isValidDate = fn ($value) => is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value);

        $tanggalMulai   = $isValidDate($tanggalMulai) ? $tanggalMulai : null;
        $tanggalSelesai = $isValidDate($tanggalSelesai) ? $tanggalSelesai : null;

        $penjualan = Penjualan::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $like = '%' . addcslashes($keyword, '%_\\') . '%';

                $query->where(function ($q) use ($like) {
                    $q->where('kode', 'like', $like)
                    ->orWhereHas('pelanggan', fn ($s) => $s->where('nama', 'like', $like));
                });
            })
            ->when($tanggalMulai, fn ($q) => $q->where('tanggal', '>=', $tanggalMulai . ' 00:00:00'))
            ->when($tanggalSelesai, fn ($q) => $q->where('tanggal', '<=', $tanggalSelesai . ' 23:59:59'))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->with(['pelanggan', 'user'])
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $pelanggan = Pelanggan::orderBy('nama')->get();

        return view('pages.penjualan.index', compact('penjualan', 'pelanggan'));
    }

    public function show(Penjualan $penjualan)
    {
        return view('pages.penjualan.detail', compact('penjualan'));
    }

    public function create(CodeGeneratorService $codeGenerator)
    {
        $barang = Barang::where('status', self::BARANG_TERSEDIA)
            ->orderBy('kode')
            ->get(['id', 'kode', 'nama', 'kategori', 'lingkar', 'panjang', 'harga_jual']);

        $kode = $codeGenerator->generate(new Penjualan(), 'kode', 'JUA');

        $pelanggan = Pelanggan::orderBy('nama')->get();

        return view('pages.penjualan.create', compact('barang', 'kode', 'pelanggan'));
    }

    public function store(Request $request, CodeGeneratorService $codeGenerator)
    {
        $request->merge(['ongkir' => $request->input('ongkir') === '' ? 0 : $request->input('ongkir')]);

        $data = $request->validate([
            'tanggal'            => ['required', 'date'],
            'pelanggan_id'       => ['required', 'exists:pelanggan,id'],
            'ongkir'             => ['required', 'integer', 'min:0'],
            'items'              => ['required', 'array', 'min:1'],
            'items.*.barang_id'  => ['required', 'distinct', 'exists:barang,id'],
            'items.*.harga_jual' => ['required', 'integer', 'min:1'],
        ], [
            'tanggal.required'            => 'Tanggal penjualan wajib diisi.',
            'pelanggan_id.required'       => 'Pelanggan wajib dipilih.',
            'items.required'              => 'Tambahkan minimal satu barang.',
            'items.min'                   => 'Tambahkan minimal satu barang.',
            'items.*.barang_id.required'  => 'Pilih barang pada setiap baris.',
            'items.*.barang_id.distinct'  => 'Ada barang yang dipilih lebih dari satu kali.',
            'items.*.harga_jual.required' => 'Harga jual wajib diisi.',
            'items.*.harga_jual.min'      => 'Harga jual harus lebih dari 0.',
        ]);

        $penjualan = DB::transaction(function () use ($data, $codeGenerator) {
            $kode = $codeGenerator->generate(new Penjualan(), 'kode', 'JUA');

            $subtotal = collect($data['items'])->sum('harga_jual');
            $ongkir   = (int) $data['ongkir'];

            $penjualan = Penjualan::forceCreate([
                'tanggal'      => $data['tanggal'],
                'kode'         => $kode,
                'pelanggan_id' => $data['pelanggan_id'],
                'subtotal'     => $subtotal,
                'ongkir'       => $ongkir,
                'total'        => $subtotal + $ongkir,
                'user_id'      => auth()->id(),
            ]);

            DB::table('detail_penjualan')->insert(
                collect($data['items'])->map(fn ($item) => [
                    'penjualan_id' => $penjualan->getKey(),
                    'barang_id'    => $item['barang_id'],
                    'harga_jual'   => $item['harga_jual'],
                ])->all()
            );

            return $penjualan;
        });

        return redirect()
            ->route('penjualan.index')
            ->with('success', "Penjualan {$penjualan->kode} berhasil disimpan.");
    }


    public function edit(Penjualan $penjualan)
    {
        $pelanggan = Pelanggan::orderBy('nama')->get();
        return view('pages.penjualan.edit', compact('penjualan', 'pelanggan'));
    }

    public function update(Request $request, Penjualan $penjualan)
    {
        $lockedBarang = $penjualan->barang()->where('status', '!=', 'Tersedia')->get()->keyBy('id');
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
            'pelanggan_id'        => 'required|exists:pelanggan,id',
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
            return redirect()->route('penjualan.edit', $penjualan->id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $items, $penjualan, $lockedBarang) {
                $editable = $penjualan->barang()->where('status', 'Tersedia')->get()->keyBy('id');

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
                            'penjualan_id' => $penjualan->id,
                        ]);
                        $keptIds[] = $barang->id;
                    }
                }

                $editable->except($keptIds)->each->delete();

                $penjualan->update([
                    'tanggal'     => $request->tanggal,
                    'pelanggan_id' => $request->pelanggan_id,
                    'total'       => $penjualan->barang()->sum('harga_beli'),
                ]);
            });

            return redirect()->route('penjualan.index')
                ->with('success', "Pesanan {$penjualan->kode} berhasil diperbarui.");
        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('penjualan.edit', $penjualan->id)
                ->withInput()
                ->with('error', 'Gagal memperbarui pesanan: ' . $e->getMessage());
        }
    }

    public function destroy(Penjualan $penjualan)
    {
        $penjualan->delete();
        return redirect()->route('penjualan.index');
    }
}
