<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(): View
    {
        // Get data from the database
        $siswas = DB::table('siswas')
            ->join('users', 'siswas.id_user', '=', 'users.id')
            ->select(
                'siswas.*',
                'users.name',
                'users.email'
            )
            ->paginate(10);

        return view('admin.siswa.index', compact('siswas'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Validate form data
        $validated = $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'nis' => 'required|numeric',
            'tingkatan' => 'required|string',
            'jurusan' => 'required|string',
            'kelas' => 'required|string',
            'hp' => 'required|numeric'
        ]);

        // Upload image
        $image = $request->file('image');
        $image->storeAs('public/siswas', $image->hashName());

        // Insert account and get ID
        $id_akun = $this->insertAccount($request->name, $request->email, $request->password);

        // Create Siswa record
        Siswa::create([
            'id_user' => $id_akun,
            'image' => $image->hashName(),
            'nis' => $request->nis,
            'tingkatan' => $request->tingkatan,
            'jurusan' => $request->jurusan,
            'kelas' => $request->kelas,
            'hp' => $request->hp,
            'status' => 1
        ]);

        // Redirect to index
        return redirect()->route('siswa.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    private function insertAccount(string $name, string $email, string $password): int
    {
        // Create User record
        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'usertype' => 'siswa'
        ]);

        // Get user ID
        $id = DB::table('users')->where('email', $email)->value('id');

        return $id;
    }

    public function show(string $id): View
    {
        // Get Siswa data by ID
        $siswa = DB::table('siswas')
            ->join('users', 'siswas.id_user', '=', 'users.id')
            ->select(
                'siswas.*',
                'users.name',
                'users.email'
            )
            ->where('siswas.id', $id)
            ->first();

        return view('admin.siswa.show', compact('siswa'));
    }
    

}


