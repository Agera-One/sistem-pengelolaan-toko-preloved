<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;
use App\Http\Requests\PenjualanRequest;
use App\Services\PenjualanService;

class PenjualanController extends Controller
{
    public function __construct(
        protected PenjualanService $penjualanService,
    ) {}

    public function index(Request $request)
    {
        return view('pages.penjualan.index', $this->penjualanService->index($request->query()));
    }

    public function show(Penjualan $penjualan)
    {
        return view('pages.penjualan.detail', compact('penjualan'));
    }

    public function create()
    {
        return view('pages.penjualan.create', $this->penjualanService->create());
    }

    public function store(PenjualanRequest $request)
    {
        $this->penjualanService->store($request->validated(), auth()->id());
        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil di tambahkan');
    }

    public function edit(Penjualan $penjualan)
    {
        return view('pages.penjualan.edit', $this->penjualanService->edit($penjualan));
    }

    public function update(PenjualanRequest $request, Penjualan $penjualan)
    {
        $this->penjualanService->update($penjualan, $request->validated());
        return redirect()->route('penjualan.index')->with('success', "Data penjualan berhasil di perbarui");
    }

    public function destroy(Penjualan $penjualan)
    {
        $this->penjualanService->destroy($penjualan);
        return redirect()->route('penjualan.index')->with('success', "Data penjualan berhasil di hapus");
    }
}
