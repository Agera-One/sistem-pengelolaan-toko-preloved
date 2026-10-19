<?php

namespace App\Http\Controllers;

use App\Services\PembayaranPembelianService;
use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\PembayaranPembelian;
use App\Http\Requests\PembayaranPembelianRequest;

class PembayaranPembelianController extends Controller
{
    public function __construct(protected PembayaranPembelianService $pembayaranPembelianService) {}

    public function index(Request $request)
    {
        return view('pages.pembayaran-pembelian', $this->pembayaranPembelianService->index($request->query()));
    }

    public function store(PembayaranPembelianRequest $request)
    {
        $this->pembayaranPembelianService->store($request->validated());
        return response()->json(['ok' => true]);
    }

    public function update(PembayaranPembelianRequest $request, PembayaranPembelian $pembayaranPembelian)
    {
        $this->pembayaranPembelianService->update($request->validated(), $pembayaranPembelian);
        return response()->json(['ok' => true]);
    }

    public function destroy(PembayaranPembelian $pembayaranPembelian)
    {
        $pembayaranPembelian->delete();
        return redirect()->route('pembayaran-pembelian.index');
    }
}
