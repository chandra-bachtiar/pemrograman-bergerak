<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(): JsonResponse
    {
        $produk = Produk::all();
        return response()->json([
            'success' => true,
            'message' => 'List Semua Produk',
            'data' => $produk
        ], 200);
    }

    public function show($id): JsonResponse
    {
        $produk = Produk::find($id);
        if ($produk) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Produk',
                'data' => $produk
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
                'data' => ''
            ], 404);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'kode_produk' => 'required|unique:produks|max:255',
            'nama_produk' => 'required',
            'harga' => 'required|integer',
        ]);

        $produk = Produk::create([
            'kode_produk' => $request->kode_produk,
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
        ]);

        if ($produk) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil disimpan',
                'data' => $produk
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Produk gagal disimpan',
                'data' => ''
            ], 400);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $produk = Produk::find($id);

        if ($produk) {
            $request->validate([
                'kode_produk' => 'required|unique:produks|max:255',
                'nama_produk' => 'required',
                'harga' => 'required|integer',
            ]);

            $produk = Produk::create([
                'kode_produk' => $request->kode_produk,
                'nama_produk' => $request->nama_produk,
                'harga' => $request->harga,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diupdate',
                'data' => $produk
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Produk gagal diupdate',
                'data' => ''
            ], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        $produk = Produk::find($id);

        if ($produk) {
            $produk->delete();
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus',
                'data' => $produk
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Produk gagal dihapus',
                'data' => ''
            ], 404);
        }
    }
}
