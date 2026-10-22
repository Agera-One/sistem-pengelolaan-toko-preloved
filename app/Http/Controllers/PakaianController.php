<?php
namespace App\Http\Controllers;

use App\Models\Pakaian;
use App\Services\PakaianService;
use Illuminate\Http\Request;
use App\Http\Requests\PakaianRequest;

class PakaianController extends Controller
{
    public function index(Request $request, PakaianService $pakaianService)
    {
        return view('pages.pakaian', $pakaianService->index($request->query()));
    }

    public function update(PakaianRequest $request, Pakaian $pakaian)
    {
        $pakaian->update($request->validated());
        return redirect()->route('pakaian.index')->with('success', 'Data pakaian berhasil di perbarui');
    }

    public function destroy(Pakaian $pakaian)
    {
        $pakaian->delete();
        return redirect()->route('pakaian.index')->with('success', 'Data pakaian berhasil di hapus');
    }
}
