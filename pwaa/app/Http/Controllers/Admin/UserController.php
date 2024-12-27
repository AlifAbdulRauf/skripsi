<?php

namespace App\Http\Controllers\Admin;

use Session;
use App\Models\User;
use App\Models\Dokter;
use App\Models\Lokasi;
use App\Models\DataDokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $user = [
            'user' => $this->user->allData(),
        ];

        return view('admin.datauser', $user);
    }

    public function add()
    {
        return view('admin.datauser_add');
    }

    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required',


        ],[
            'name.required' => 'Nama harus diisi!',
            'email.required' => 'email harus diisi!',
            'password.required' => 'role harus diisi!',
            'role.required' => 'role harus diisi!',
  
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => $request->role,

        ];

        $this->user->addData($data);
        Alert::success('Berhasil!', 'Data user berhasil ditambahkan!');
        return redirect('/datauser');
    }

    public function destroy($id)
    {
        // Hapus entri terkait di tabel detail_jadwal terlebih dahulu
        DB::table('users')->where('id', $id)->delete();

        // Menampilkan alert sukses dan mengarahkan kembali ke halaman data user
        Alert::success('Berhasil!', 'Data user berhasil dihapus!');
        return redirect('/datauser');
    }
    
    
    
    public function add_dokter()
    {
        $lokasi = Lokasi::all();
        return view('admin.datauserdokter_add', compact('lokasi'));
    }

    public function insert_dokter(Request $request)
    {
        
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'nomor_hp' => 'required',
            'lokasi_id'=> 'required',
            'email' => 'required',
            'password' => 'required',
        ],[
            'nama.required' => 'Nama harus diisi!',
            'alamat.required' => 'alamat harus diisi!',
            'nomor_hp.required' => 'nomor_hp harus diisi!',
            'lokasi_id.required' => 'lokasi praktek harus diisi',
            'email.required' => 'email harus diisi!',
            'password.required' => 'role harus diisi!',
        ]);

        $data = [
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => 'Dokter',
        ];
        
        $id_user = $this->user->addData($data);
        
        Dokter::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'nomor_hp' => $request->nomor_hp,
            'lokasi_id' => $request->lokasi_id,
            'user_id' => $id_user
        ]);

        Alert::success('Berhasil!', 'Data akun dokter berhasil ditambahkan!');
        return redirect('/datauser');
    }
    

    public function edit($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        return view('admin.datauser_edit', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        DB::table('users')->where('id', $id)->update($data);
        Alert::success('Berhasil!', 'Data user berhasil diupdate!');
        return redirect('/datauser');
    }
}
