<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // menambahkan kategori baru
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|min:1|max:255|unique:categories,name',
            ]);

            $category = Category::create($validatedData);

            return response()->json([
                'data' => $category
            ], 201);

        } catch(\Exception $e) {
            $error_message = $e->getMessage();

                Log::error("Error message: " . $error_message);

            if($error_message == "The name has already been taken.") {
                return response()->json([
                    'message' => 'Kategori dengan nama tersebut sudah ada.'
                ], 400);
            } else {
                return response()->json([
                    'message' => 'Terjadi kesalahan saat membuat kategori.'
                ], 500);
            }
        }

    }

    // mengambil semua kategori
    public function index()
    {
        try {
            $categories = Category::all();
            return response()->json([ 'data' => $categories ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil kategori.'
            ], 500);
        }
    }

}
