<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Services\CodeGeneratorService;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index(Request $request, CodeGeneratorService $codeGenerator)
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

        $kode = $codeGenerator->generate(
        new Supplier(),
            'kode',
            'SPL'
        );

        return view('pages.supplier', compact('supplier', 'kode'));
    }

    public function store(Request $request, CodeGeneratorService $codeGenerator)
    {
        $validator = Validator::make($request->all(), [
            'nama'          => 'required',
            'nomor_telepon' => 'required|max:15',
            'kota'        => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('supplier.index');
        }

        $kode = $codeGenerator->generate(new Supplier(), 'kode', 'SPL');

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
                ->with('error', 'Gagal menyimpan data supplier.');
        }
    }

    public function update(Request $request, string $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama'          => 'required',
            'nomor_telepon' => 'required|max:15',
            'kota'          => 'required',
        ], [
            'nama.required'          => 'Nama lengkap wajib diisi.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'nomor_telepon.max'      => 'Nomor telepon maksimal 15 karakter.',
            'kota.required'          => 'kota wajib diisi.',
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

    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return redirect()->route('supplier.index');
    }
}
