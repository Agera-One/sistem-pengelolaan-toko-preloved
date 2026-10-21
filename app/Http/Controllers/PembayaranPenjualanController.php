<?php

namespace App\Http\Controllers;

use App\Services\PembayaranPenjualanService;
use Illuminate\Http\Request;
use App\Models\PembayaranPenjualan;
use App\Http\Requests\PembayaranPenjualanRequest;

class PembayaranPenjualanController extends Controller
{
    public function __construct(protected PembayaranPenjualanService $pembayaranPenjualanService) {}

    public function index(Request $request)
    {
        return view('pages.pembayaran-penjualan', $this->pembayaranPenjualanService->index($request->query()));
    }

    public function store(PembayaranPenjualanRequest $request)
    {
        $this->pembayaranPenjualanService->store($request->validated());
        return response()->json(['ok' => true]);
    }

    public function update(PembayaranPenjualanRequest $request, PembayaranPenjualan $pembayaranPenjualan)
    {
        $this->pembayaranPenjualanService->update($request->validated(), $pembayaranPenjualan);
        return response()->json(['ok' => true]);
    }

    public function destroy(PembayaranPenjualan $pembayaranPenjualan)
    {
        $pembayaranPenjualan->delete();
        return redirect()->route('pembayaran-penjualan.index');
    }
}
