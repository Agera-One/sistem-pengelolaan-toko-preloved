<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Services\BarangService;
use Illuminate\Http\Request;
use App\Http\Requests\BarangRequest;

class BarangController extends Controller
{
    public function index(Request $request, BarangService $barangService)
    {
        return view('pages.barang', $barangService->index($request->query()));
    }

    public function update(BarangRequest $request, Barang $barang)
    {
        $barang->update($request->validated());
        return redirect()->route('barang.index')->with('success', 'Data barang berhasil di perbarui');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Data barang berhasil di hapus');
    }
}
