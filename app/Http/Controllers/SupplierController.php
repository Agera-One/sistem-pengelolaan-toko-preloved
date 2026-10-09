<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Services\CodeGeneratorService;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function __construct(protected CodeGeneratorService $codeGenerator) {}

    public function index(Request $request)
    {
        $keyword = trim((string) $request->query('q', ''));

        $supplier = Supplier::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $like = '%' . addcslashes($keyword, '%_\\') . '%';

                $query->where(function ($q) use ($like) {
                    $q->where('kode', 'like', $like)
                      ->orWhere('nama', 'like', $like)
                      ->orWhere('nomor_telepon', 'like', $like)
                      ->orWhere('kota', 'like', $like);
                });
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $kode = $this->codeGenerator->supplier();

        return view('pages.supplier', compact('supplier', 'kode'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'          => 'required|string|max:255',
            'nomor_telepon' => 'required|max:15',
            'kota'          => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('supplier.index');
        }

        $kode = $this->codeGenerator->supplier();

        try {
            Supplier::create([
                'kode'          => $kode,
                'nama'          => $request->nama,
                'nomor_telepon' => $request->nomor_telepon,
                'kota'          => $request->kota,
            ]);

            return redirect()->route('supplier.index');
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('supplier.index')
                ->with('error', 'Nomor telepon tidak boleh sama.');
        }
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validator = Validator::make($request->all(), [
            'nama'          => 'required|string|max:255',
            'nomor_telepon' => 'required|max:15',
            'kota'          => 'required|string|max:255',
        ], [
            'nama.required'          => 'Nama lengkap wajib diisi.',
            'nama.max'               => 'Nama lengkap maksimal 255 karakter.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'nomor_telepon.max'      => 'Nomor telepon maksimal 15 karakter.',
            'kota.required'          => 'kota wajib diisi.',
            'kota.max'               => 'kota maksimal 255 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('supplier.index')
                ->with('error', $validator->errors()->first());
        }

        try {
            $supplier->update([
                'nama'          => $request->nama,
                'nomor_telepon' => $request->nomor_telepon,
                'kota'          => $request->kota,
            ]);

            return redirect()->route('supplier.index');
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('supplier.index')
                ->with('error', 'Gagal memperbarui data Supplier.');
        }
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('supplier.index');
    }
}
