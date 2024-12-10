<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Distributor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    public function index()
    {
        $data = DB::table('distributors')
            ->join('products', 'distributors.id', '=', 'products.id_distributor')
            ->select('distributors.*', 'products.*')
            ->get();

        confirmDelete('Hapus Data!', 'Apakah Anda yakin ingin menghapus data ini?');

        return view('pages.admin.product.index', compact('data'));
    }

    public function create()
    {
        $distributor = Distributor::all();

        return view('pages.admin.product.create', compact('distributor'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_distributor' => 'required|numeric',
            'name' => 'required',
            'price' => 'numeric|required',
            'category' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:png,jpeg,jpg',
            'discount' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal!', 'Pastikan semua terisi dengan benar!');
            return redirect()->back();
        }

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move('images/', $imageName);
        }

        $product = Product::create([
            'id_distributor' => $request->id_distributor,
            'name' => $request->name,
            'price' => $request->price,
            'category' => $request->category,
            'description' => $request->description,
            'image' => $imageName,
            'discount' => $request->discount ?? 0,
        ]);

        if ($product) {
            Alert::success('Berhasil!', 'Produk berhasil ditambahkan!');
            return redirect()->route('admin.product');
        } else {
            Alert::error('Gagal', 'Produk gagal ditambahkan!');
            return redirect()->back();
        }
    }

    public function detail($id)
    {
        $data = DB::table('distributors')
            ->join('products', 'distributors.id', '=', 'products.id_distributor')
            ->select('products.*', 'distributors.*')
            ->where('products.id', '=', $id)
            ->first();

        return view('pages.admin.product.detail', compact('data'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $distributor = Distributor::all();

        return view('pages.admin.product.edit', compact('product', 'distributor'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_distributor' => 'required|numeric',
            'name' => 'required',
            'price' => 'numeric|required',
            'category' => 'required',
            'description' => 'required',
            'image' => 'nullable|mimes:png,jpeg,jpg',
            'discount' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal!', 'Pastikan semua terisi dengan benar!');
            return redirect()->back();
        }

        $product = Product::findOrFail($id);

        $imageName = $product->image;
        if ($request->hasFile('image')) {
            $oldPath = public_path('images/' . $product->image);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move('images/', $imageName);
        }

        $product->update([
            'id_distributor' => $request->id_distributor,
            'name' => $request->name,
            'price' => $request->price,
            'category' => $request->category,
            'description' => $request->description,
            'image' => $imageName,
            'discount' => $request->discount ?? 0,
        ]);

        if ($product) {
            Alert::success('Berhasil!', 'Produk berhasil diperbarui!');
            return redirect()->route('admin.product');
        } else {
            Alert::error('Gagal!', 'Produk gagal diperbarui!');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            $oldPath = public_path('images/' . $product->image);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }

            $product->delete(); 

            return response()->json([
                'success' => true,
                'message' => 'Distributor berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus distributor: ' . $e->getMessage(),
            ], 500);
        }
    }

}
