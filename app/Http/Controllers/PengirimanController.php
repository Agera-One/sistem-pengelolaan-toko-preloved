<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use App\Services\PengirimanService;
use Illuminate\Http\Request;
use App\Http\Requests\PengirimanRequest;

class PengirimanController extends Controller
{
    public function __construct(protected PengirimanService $pengirimanService) {}

    public function index(Request $request)
    {
        return view('pages.pengiriman', $this->pengirimanService->index($request->query()));
    }

    public function store(PengirimanRequest $request)
    {
        $this->pengirimanService->store($request->validated());
        return redirect()->route('pengiriman.index')->with('success', 'Data pengiriman berhasil di tambahkan');
    }

    public function update(PengirimanRequest $request, Pengiriman $pengiriman)
    {
        $pengiriman->update($request->validated());
        return redirect()->route('pengiriman.index')->with('success', 'Data pengiriman berhasil di perbarui');
    }

    public function destroy(Pengiriman $pengiriman)
    {
        $pengiriman->delete();
        return redirect()->route('pengiriman.index')->with('success', 'Data pengiriman berhasil di hapus');
    }
}
