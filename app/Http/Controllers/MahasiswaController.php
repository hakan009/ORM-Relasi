<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Mahasiswa_MataKuliah;
use App\Models\Matakuliah;
use App\Models\Kelas;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //fungsi eloquent menampilkan data menggunakan pagination
        $mahasiswas = Mahasiswa::paginate(5); // Mengambil semua isi tabel
        $posts = Mahasiswa::orderBy('Nim', 'desc')->paginate(5);
        return view('mahasiswas.index', compact('mahasiswas'))
        ->with('i', (request()->input('page', 1) - 1) * 5); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kelas = Kelas::all(); //Mendapatkan data dari tabel kelas
        return view('mahasiswas.create',['kelas' => $kelas]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //melakukan validasi data
            $request->validate([
                'Nim' => 'required',
                'Nama' => 'required',
                'Kelas' => 'required',
                'Jurusan' => 'required',
                'No_Handphone' => 'required',
                'Email' => 'required',
                'Tanggal_Lahir' => 'required',
                ]);
    //fungsi eloquent untuk menambah data
        //Mahasiswa::create($request->all());

    //fungsi eloquent untuk menambah data Prak 9
        $mahasiswa =  new Mahasiswa;
        $mahasiswa -> Nim =$request->get('Nim');
        $mahasiswa -> Nama =$request->get('Nama');
        $mahasiswa -> Jurusan =$request->get('Jurusan');
        $mahasiswa -> No_Handphone =$request->get('No_Handphone');
        $mahasiswa -> Email =$request->get('Email');
        $mahasiswa -> Tanggal_Lahir =$request->get('Tanggal_Lahir');

    //fungsi eloquent untuk menambah data dengan relasi belongs to
        $kelas = new Kelas;
        $kelas->id = $request->get('Kelas');

        $mahasiswa->kelas()->associate($kelas);
        $mahasiswa->save();
    //jika data berhasil ditambahkan, akan kembali ke halaman utama
        return redirect()->route('mahasiswa.index')
        ->with('success', 'Mahasiswa Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($Nim)
    {
        //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
            $Mahasiswa = Mahasiswa::find($Nim);
            return view('mahasiswas.detail', compact('Mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($Nim)
    {
        //menampilkan detail data dengan menemukan berdasarkan Nim Mahasiswa untuk diedit
            $Mahasiswa = Mahasiswa::find($Nim);
            $Kelas = Kelas::all();
            return view('mahasiswas.edit', compact('Mahasiswa', 'Kelas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $Nim)
    {
        //melakukan validasi data
        $request->validate([
        'Nim' => 'required',
        'Nama' => 'required',
        'Kelas' => 'required',
        'Jurusan' => 'required',
        'No_Handphone' => 'required',
        'Email' => 'required',
        'Tanggal_Lahir' => 'required',
        ]);
        //fungsi eloquent untuk mengupdate data inputan kita
            //Mahasiswa::find($Nim)->update($request->all());

        //fungsi eloquent untuk mengupdate data inputan kita Prak 9
            $mahasiswa = Mahasiswa::find($Nim);
            $mahasiswa -> Nim =$request->get('Nim');
            $mahasiswa -> Nama =$request->get('Nama');
            $mahasiswa -> Jurusan =$request->get('Jurusan');
            $mahasiswa -> No_Handphone =$request->get('No_Handphone');
            $mahasiswa -> Email =$request->get('Email');
            $mahasiswa -> TTL =$request->get('TTL');        

        //fungsi eloquent untuk menambah data dengan relasi belongs to
            $kelas = new Kelas;
            $kelas->id = $request->get('Kelas');

            $mahasiswa->kelas()->associate($kelas);
            $mahasiswa->save();
        //jika data berhasil diupdate, akan kembali ke halaman utama
            return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($Nim)
    {
        //fungsi eloquent untuk menghapus data
            Mahasiswa::find($Nim)->delete();
            return redirect()->route('mahasiswa.index')
            -> with('success', 'Mahasiswa Berhasil Dihapus');
    }

    public function search(Request $request)
    {
        $keyword = $request->search;
        $mahasiswas = Mahasiswa::where('Nama', 'like', "%" . $keyword . "%")->paginate(1);
        return view('mahasiswas.index', compact('mahasiswas'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    // public function detailnilai($Nim)
    // {
    //     // menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
    //     $Mahasiswa = Mahasiswa::with('matakulias')->where('Nim', $Nim)->first();
    //     $nilai = DB::table('mahasiswa_matakuliah')
    //         ->join('matakuliah', 'matakuliah.id', '=', 'mahasiswa_matakuliah.matakuliah_id')
    //         ->where('mahasiswa_matakuliah.mahasiswa_id')
    //         ->select('nilai')
    //         ->get();
    //     return view('mahasiswas.detailnilai', ['Mahasiswa' => $Mahasiswa,'nilai' => $nilai]);
    // }

    public function nilai($Nim)
    {
        //$Mahasiswa = Mahasiswa::find($nim);
        $Mahasiswa = Mahasiswa::find($Nim);
        $Matakuliah = Matakuliah::all();
        //$MataKuliah = $Mahasiswa->MataKuliah()->get();
        $Mahasiswa_MataKuliah = Mahasiswa_MataKuliah::where('mahasiswa_id','=',$Nim)->get();
        return view('mahasiswas.detailnilai',['Mahasiswa' => $Mahasiswa],['Mahasiswa_MataKuliah' => $Mahasiswa_MataKuliah],['Matakuliah' => $Matakuliah], compact('Mahasiswa_MataKuliah'));
    }
};
