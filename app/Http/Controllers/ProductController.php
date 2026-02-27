<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    // menambahkan produk baru
    public function store(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'name' => 'required|string|min:1|max:255',
                'price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'category_id' => 'nullable|exists:categories,id',
            ]);

            $product = Product::create($validatedData);
            return response()->json([ 'data' => $product ], 201);
        } catch (\Exception $e) {

            $error_code = $e->getCode();
            $error_message = $e->getMessage();

            Log::error("Error message: " . $error_message);

            if($error_code == 22003) {

                return response()->json([
                    'message' => 'Harga atau jumlah stok melebihi batas yang diizinkan.'
                 ], 400);

            } else if($error_message == "The selected category id is invalid.") {
                return response()->json([
                    'message' => 'Kategori dengan id tersebut tidak ditemukan.'
                ], 400);
            } else {
                return response()->json([
                    'message' => 'Terjadi kesalahan saat membuat produk.'
                ], 500);

            }
        }
    }

    // mengambil semua produk
    public function index()
    {
        try {
            $products = Product::all();
            return response()->json([ 'data' => $products ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil produk.'
            ], 500);
        }
    }

    // mengambil detail produk berdasarkan ID
    public function show($id)
    {
        try {
             $product = Product::find($id);

            if (!$product) {
                return response()->json([ 'message' => 'Produk Tidak ditemukan.' ], 404);
            }

            return response()->json([ 'data' => $product ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil produk.'
            ], 500);
        }


    }

    // memperbarui produk berdasarkan ID
    public function update(Request $request, $id)
    {

        try {
            $product = Product::find($id);

            if (!$product) {
                return response()->json([ 'message' => 'Produk tidak ditemukan' ], 404);
            }

            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|min:1|max:255',
                'price' => 'sometimes|required|numeric|min:0',
                'stock_quantity' => 'sometimes|required|integer|min:0',
            ]);

            $product->update($validatedData);

            return response()->json([ 'data' => $product  ], 200);
        } catch (\Exception $e) {


            $error_code = $e->getCode();
            $error_message = $e->getMessage();

            Log::error("Error message: " . $error_message);
            Log::error("Error code: " . $error_code);

            if($error_code == 22003) {

                return response()->json([
                    'message' => 'Harga atau jumlah stok melebihi batas yang diizinkan.'
                 ], 400);

            } else if($error_message == "The selected category id is invalid.") {
                return response()->json([
                    'message' => 'Kategori dengan id tersebut tidak ditemukan.'
                ], 400);

            } else {
                return response()->json([
                    'message' => 'Terjadi kesalahan saat memperbarui produk.'
                ], 500);

            }

        }
    }

    // menghapus produk berdasarkan ID
    public function destroy($id)
    {
        try {

            $product = Product::find($id);

            if (!$product) {
                return response()->json([ 'message' => 'Produk tidak ditemukan.'  ], 404);
            }

            $product->delete();

            return response()->json([ 'message' => 'Produk berhasil dihapus.' ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus produk.'
            ], 500);
        }

    }

    public function searchProductsByNameOrCategory(Request $request)
    {
        try {
            $product_name_param = $request->query('name');
            $category_id_param = $request->query('category_id');

            // mencari produk berdasarkan nama produk atau kategori dan jika tidak ada parameter yang diberikan maka akan mengembalikan semua produk
            $products = Product::where('name', 'like', '%' . $product_name_param . '%')
                ->orWhere('category_id', $category_id_param)
                ->get();

            return response()->json([ 'data' => $products ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mencari kategori dan produk.'
            ], 500);
        }
    }

    public function updateProductStock(Request $request)
    {
        try {


            $validatedData = $request->validate([
                'product_id' => 'required|integer|exists:products,id',
                'quantity_sold' => 'required|integer|min:0',
            ]);


            $product = Product::find($validatedData['product_id']);

            if (!$product) {
                return response()->json([ 'message' => 'Produk tidak ditemukan.' ], 404);
            }

            $product->stock_quantity -= $validatedData['quantity_sold'];

            if ($product->stock_quantity < 0) {
                return response()->json([
                    'message' => 'Jumlah stok setelah dikurangi tidak boleh negatif.'
                ], 400);
            }else{
                $product->save();
                return response()->json([ 'data' => $product ], 200);
            }


        } catch (\Exception $e) {

            $error_code = $e->getCode();


            if($error_code == 22003) {
                return response()->json([
                    'message' => 'Jumlah stok melebihi batas yang diizinkan.'
                 ], 400);
            }

            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui jumlah stok produk.'
            ], 500);

        }
    }

    public function getTotalInventoryValue()
    {
        try {
            $total_value = DB::table('products')
                ->select(DB::raw('SUM(price * stock_quantity) as total_value'))
                ->value('total_value');

            return response()->json([ 'data' => ["total_inventaris" => $total_value] ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghitung total nilai inventory.'
            ], 500);
        }
    }
}
