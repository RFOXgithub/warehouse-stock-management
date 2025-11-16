<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use App\Models\Asset;
use App\Models\BarangKeluar;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $this->createTableIfNotExists();
        return BarangKeluar::with('asset')->orderBy('tanggal', 'desc')->get();
    }

    public function store(Request $request)
    {
        $this->createTableIfNotExists();

        Log::info('BarangKeluar.store: Data diterima', $request->all());

        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string'
        ]);

        $asset = Asset::find($request->asset_id);
        Log::info('Asset ditemukan', ['asset' => $asset]);

        if (!$asset) {
            return response()->json(['error' => 'Asset tidak ditemukan'], 404);
        }

        try {
            $barang = BarangKeluar::create($request->all());

            Log::info('Barang keluar berhasil disimpan', ['barang' => $barang]);
            $barang->load('asset');

            return response()->json($barang);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan Barang Keluar', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'Gagal menyimpan barang keluar', 'details' => $e->getMessage()], 500);
        }
    }

        public function update(Request $request, $id)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer|min:1',
        ]);

        $barang = BarangKeluar::findOrFail($id);
        $barang->update($request->all());
        $barang->load('asset');
        return response()->json($barang);
    }

    public function destroy($id)
    {
        $barang = BarangKeluar::findOrFail($id);
        $barang->delete();
        return response()->json(['message' => 'Barang keluar berhasil dihapus']);
    }

    private function createTableIfNotExists()
    {
        if (!Schema::hasTable('barang_keluar')) {
            Schema::create('barang_keluar', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('asset_id');
                $table->date('tanggal');
                $table->integer('jumlah');
                $table->string('keterangan')->nullable();
                $table->timestamps();

                $table->foreign('asset_id')
                    ->references('id')->on('assets')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            });
            Log::info('Tabel barang_keluar berhasil dibuat');
        }
    }
}
