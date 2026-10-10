<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Services\CodeGeneratorService;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;

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

    public function store(StoreSupplierRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = $this->codeGenerator->supplier();
        Supplier::create($validated);
        return redirect()->route('supplier.index')->with('success', 'Data supplier berhasil di tambahkan');
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated());
        return redirect()->route('supplier.index')->with('success', 'Data supplier berhasil di perbarui');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('supplier.index')->with('success', 'Data supplier berhasil di hapus');
    }
}
