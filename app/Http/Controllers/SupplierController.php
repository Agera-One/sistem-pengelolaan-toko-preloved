<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Services\CodeGeneratorService;
use App\Http\Requests\Supplier\SupplierRequest;

class SupplierController extends Controller
{
    public function __construct(protected CodeGeneratorService $codeGenerator) {}

    public function index(Request $request)
    {
        $supplier = Supplier::search($request->query('q'))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $kode = $this->codeGenerator->supplier();

        return view('pages.supplier', compact('supplier', 'kode'));
    }

    public function store(SupplierRequest $request)
    {
        $validated = $request->validated();
        $validated['kode'] = $this->codeGenerator->supplier();
        Supplier::create($validated);
        return redirect()->route('supplier.index')->with('success', 'Data supplier berhasil di tambahkan');
    }

    public function update(SupplierRequest $request, Supplier $supplier)
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
