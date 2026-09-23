<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Services\CodeGeneratorService;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    public function index(Request $request, CodeGeneratorService $codeGenerator)
    {
        $keyword = trim((string) $request->query('q', ''));

        $pelanggan = Pelanggan::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $like = '%' . addcslashes($keyword, '%_\\') . '%';

                $query->where(function ($q) use ($like) {
                    $q->where('kode', 'like', $like)
                      ->orWhere('nama', 'like', $like)
                      ->orWhere('nomor_telepon', 'like', $like)
                      ->orWhere('alamat', 'like', $like);
                });
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $kode = $codeGenerator->generate(
        new Pelanggan(),
            'kode',
            'PLG'
        );

        return view('pages.pelanggan', compact('pelanggan', 'kode'));
    }

    public function store(Request $request, CodeGeneratorService $codeGenerator)
    {
        $validator = Validator::make($request->all(), [
            'nama'          => 'required|string|max:255',
            'nomor_telepon' => 'required|max:15',
            'alamat'        => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('pelanggan.index');
        }

        $kode = $codeGenerator->generate(new Pelanggan(), 'kode', 'PLG');

        try {
            Pelanggan::create([
                'kode'          => $kode,
                'nama'          => $request->nama,
                'nomor_telepon' => $request->nomor_telepon,
                'alamat'        => $request->alamat,
            ]);

            return redirect()->route('pelanggan.index');
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('pelanggan.index')
                ->with('error', 'Nomor telepon tidak boleh sama.');
        }
    }

    public function update(Request $request, string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama'          => 'required|string|max:255',
            'nomor_telepon' => 'required|max:15',
            'alamat'        => 'required',
        ], [
            'nama.required'          => 'Nama lengkap wajib diisi.',
            'nama.max'               => 'Nama lengkap maksimal 255 karakter.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'nomor_telepon.max'      => 'Nomor telepon maksimal 15 karakter.',
            'alamat.required'        => 'Alamat lengkap wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('pelanggan.index')
                ->with('error', $validator->errors()->first());
        }

        try {
            $pelanggan->update([
                'nama'          => $request->nama,
                'nomor_telepon' => $request->nomor_telepon,
                'alamat'        => $request->alamat,
            ]);

            return redirect()->route('pelanggan.index');
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('pelanggan.index')
                ->with('error', 'Gagal memperbarui data pelanggan.');
        }
    }

    public function destroy(string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();
        return redirect()->route('pelanggan.index');
    }
}
