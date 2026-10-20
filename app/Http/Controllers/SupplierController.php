<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Services\SupplierService;
use Illuminate\Http\Request;
use App\Http\Requests\SupplierRequest;

class SupplierController extends Controller
{
    public function __construct(protected SupplierService $supplierService) {}

    public function index(Request $request)
    {
        return view('pages.supplier', $this->supplierService->index($request->query()));
    }

    public function store(SupplierRequest $request)
    {
        $this->supplierService->store($request->validated());
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
