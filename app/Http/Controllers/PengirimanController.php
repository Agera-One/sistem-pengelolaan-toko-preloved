<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use Illuminate\Http\Request;
use App\Http\Requests\PengirimanRequest;
use App\Services\PengirimanService;

class PengirimanController extends Controller
{
    public function __construct(protected PengirimanService $pengirimanService) {}

    public function index(Request $request)
    {
        return view('pages.pengiriman.index', $this->pengirimanService->index($request->query()));
    }

    public function show(Pengiriman $pengiriman)
    {
        return view('pages.pengiriman.detail', compact('pengiriman'));
    }

    public function create()
    {
        return view('pages.pengiriman.create', $this->pengirimanService->create());
    }

    public function store(PengirimanRequest $request)
    {
        $this->pengirimanService->store($request->validated(), auth()->id());
        return redirect()->route('pengiriman.index')->with('success', 'Data pengiriman berhasil di tambahkan');
    }

    public function edit(Pengiriman $pengiriman)
    {
        return view('pages.pengiriman.edit', $this->pengirimanService->edit($pengiriman));
    }

    public function update(PengirimanRequest $request, Pengiriman $pengiriman)
    {
        $this->pengirimanService->update($pengiriman, $request->validated());
        return redirect()->route('pengiriman.index')->with('success', "Data pengiriman berhasil di perbarui");
    }

    public function destroy(Pengiriman $pengiriman)
    {
        $this->pengirimanService->destroy($pengiriman);
        return redirect()->route('pengiriman.index')->with('success', "Data pengiriman berhasil di hapus");
    }
}
