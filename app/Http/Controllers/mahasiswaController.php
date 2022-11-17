<?php

namespace App\Http\Controllers;

use App\Models\mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class mahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $katakunci =$request->katakunci;
        $jumlahbaris =4;
        if(strlen($katakunci)){
            $data = mahasiswa::where('npm','like',"%$katakunci%")
                ->orwhere('nama','like',"%$katakunci%")
                ->orwhere('jurusan','like',"%$katakunci%")
                ->paginate($jumlahbaris);
        }else{
            $data =mahasiswa::orderBy('npm','desc')->paginate($jumlahbaris);
        }
        return view ('mahasiswa.index')->with('data',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('mahasiswa.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Session ::flash('npm', $request->npm);
        Session ::flash('nama', $request->nama);
        Session ::flash('jurusan', $request->jurusan);
    
        $request->validate([
            'npm' => 'required|numeric|unique:mahasiswa,npm',
            'nama' => 'required',
            'jurusan' => 'required',
        ], [
            'npm.required' => 'NPM wajib diisi',
            'npm.numeric' => 'NPM wajib dalam angka',
            'npm.unique' => 'NPM yang diisikan sudah ada dalam database',
            'nama.required' => 'Nama wajib diisi',
            'jurusan.required' => 'Jurusan wajib diisi',
        ]);
        $data = [
            'npm' => $request->npm,
            'nama' => $request->nama,
            'jurusan' => $request->jurusan,
        ];
        mahasiswa::create($data);
        return redirect()->to('mahasiswa')->with('success','Berhasil menambahkan data');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = mahasiswa::where('npm',$id)->first();
        return view('mahasiswa.edit')->with('data',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'jurusan' => 'required',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'jurusan.required' => 'Jurusan wajib diisi',
        ]);
        $data = [
            'nama' => $request->nama,
            'jurusan' => $request->jurusan,
        ];
        mahasiswa::where('npm',$id)->update($data);
        return redirect()->to('mahasiswa')->with('success','Berhasil melakukan update data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        mahasiswa::where('npm',$id)->delete();
        return redirect()->to('mahasiswa')->with('success','Berhasil melakukan delete data');
    }
}
