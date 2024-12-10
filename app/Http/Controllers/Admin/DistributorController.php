<?php

namespace App\Http\Controllers\Admin;

use App\Imports\DistributorImport;
use App\Http\Controllers\Controller;
use App\Models\Distributor;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;

class DistributorController extends Controller
{
    public function index()
    {
        confirmDelete('Hapus Data!', 'Apakah Anda yakin ingin menghapus data ini?');

        $distributor = Distributor::all();

        return view('pages.admin.distributor.index', compact('distributor'));
    }

    public function create()
    {
        return view('pages.admin.distributor.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_distributor' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'kontak' => 'required|string|max:20',
            'email' => 'required|email|unique:distributors,email',
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal!', 'Pastikan semua terisi dengan benar!');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $distributor = Distributor::create([
            'nama_distributor' => $request->nama_distributor,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'kontak' => $request->kontak,
            'email' => $request->email,
        ]);

        if ($distributor) {
            Alert::success('Berhasil!', 'Distributor berhasil ditambahkan!');
            return redirect()->route('admin.distributor');
        } else {
            Alert::error('Gagal!', 'Distributor gagal ditambahkan!');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $distributor = Distributor::findOrFail($id);
        return view('pages.admin.distributor.edit', compact('distributor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_distributor' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'kontak' => 'required|string|max:20',
            'email' => 'required|email|unique:distributors,email,' . $id,
        ]);

        $distributor = Distributor::findOrFail($id);
        $distributor->update($request->all());
        Alert::success('Berhasil!', 'Distributor berhasil diperbarui!');
        return redirect()->route('admin.distributor');
    }

    public function export()
    {
        $distributor = Distributor::all();

        $pdf = Pdf::loadView('pages.admin.distributor.export', compact('distributor'))
                  ->setPaper('a4', 'landscape');
        return $pdf->download('distributor.pdf');
    }

    public function import(Request $request)
    {
        try {
            $file = $request->file('file');
            Excel::import(new DistributorImport, $file);

            Alert::success('Berhasil!', 'Data berhasil diimport!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $messages = '';
            foreach ($failures as $failure) {
                $messages .= 'Kesalahan pada baris ' . $failure->row() . ': ' . 
                             implode(', ', $failure->errors()) . '. ';
            }
            Alert::error('Gagal!', 'Validasi Gagal: ' . $messages);
        } catch (\Exception $e) {
            Alert::error('Gagal!', 'Pastikan format dan isi sudah benar! Error: ' 
                         . $e->getMessage());
        } finally {
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $distributor = Distributor::findOrFail($id); 

            $distributor->delete(); 

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
