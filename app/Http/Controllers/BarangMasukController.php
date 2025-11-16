<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Asset;
use App\Models\BarangMasuk;

class BarangMasukController extends Controller
{
    public function index()
    {
        $this->createTableIfNotExists();
        return BarangMasuk::with('asset')->orderBy('tanggal', 'desc')->get();
    }

    public function store(Request $request)
    {
        $this->createTableIfNotExists();

        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string'
        ]);

        $barang = BarangMasuk::create($request->all());

        $barang->load('asset');

        return response()->json($barang);
    }

        public function update(Request $request, $id)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'tanggal'  => 'required|date',
            'jumlah'   => 'required|integer|min:1',
        ]);

        $barang = BarangMasuk::findOrFail($id);
        $barang->update($request->all());
        $barang->load('asset');
        return response()->json($barang);
    }

    public function destroy($id)
    {
        $barang = BarangMasuk::findOrFail($id);
        $barang->delete();
        return response()->json(['message' => 'Barang masuk berhasil dihapus']);
    }

    private function createTableIfNotExists()
    {
        if (!Schema::hasTable('barang_masuk')) {
            Schema::create('barang_masuk', function (Blueprint $table) {
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
        }
    }
}
