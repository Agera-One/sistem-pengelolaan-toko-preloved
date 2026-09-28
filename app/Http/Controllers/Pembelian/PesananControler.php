<?php

namespace App\Http\Controllers\Pembelian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CodeGeneratorService;
use Illuminate\Support\Facades\Validator;
use App\Models\Pembelian;
use App\Models\Supplier;

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
            ->with(['supplier', 'user'])
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $kode = $codeGenerator->generate(
        new Pembelian(),
            'kode',
            'BEL'
        );

        $supplier = Supplier::orderBy('nama')->get();

        return view('pages.pembelian.pesanan', compact('pembelian', 'kode', 'supplier'));
    }

    public function store(Request $request, CodeGeneratorService $codeGenerator)
    {
        $validator =  Validator::make($request->all(),[
            'tanggal'      => 'required|date',
            'total'        => 'required|numeric|min:1',
            'supplier_id'  => 'required|exists:supplier,id',
        ], [
            'tanggal.required'      => 'Tanggal wajib diisi.',
            'total.required'        => 'Harga beli wajib diisi.',
            'total.numeric'         => 'Harga beli harus berupa angka.',
            'total.min'             => 'Total harga minimal 1.',
            'supplier_id.required'  => 'Supplier wajib dipilih.',
            'supplier_id.exists'    => 'Supplier tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('pembelian.pesanan.index');
        }

        $kode = $codeGenerator->generate(new Pembelian(), 'kode', 'BEL');

        try {
            Pembelian::create([
                'kode'          => $kode,
                'tanggal'       => $request->tanggal,
                'total'         => $request->total,
                'status'        => 'Belum Bayar',
                'supplier_id'   => $request->supplier_id,
                'user_id'       => auth()->id(),
            ]);

            return redirect()->route('pembelian.pesanan.index');
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('pembelian.pesanan.index')
                ->with('error', 'Gagal memperbarui data pesanan.');
        }
    }

    public function update(Request $request, string $id)
    {
        $pembelian = Pembelian::findOrFail($id);

        $validator =  Validator::make($request->all(),[
            'tanggal'      => 'required|date',
            'total'        => 'required|numeric|min:1',
            'supplier_id'  => 'required|exists:supplier,id',
        ], [
            'tanggal.required'      => 'Tanggal wajib diisi.',
            'total.required'        => 'Harga beli wajib diisi.',
            'total.numeric'         => 'Harga beli harus berupa angka.',
            'total.min'             => 'Total harga minimal 1.',
            'supplier_id.required'  => 'Supplier wajib dipilih.',
            'supplier_id.exists'    => 'Supplier tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('pembelian.pesanan.index')
                ->with('error', $validator->errors()->first());
        }

        try {
            $pembelian->update([
                'tanggal'       => $request->tanggal,
                'total'         => $request->total,
                'supplier_id'   => $request->supplier_id,
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
