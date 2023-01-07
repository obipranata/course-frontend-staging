<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\HttpClient;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        $getMapel = HttpClient::fetch("GET", HttpClient::apiUrl()."mapel");
        $getGuru = HttpClient::fetch("GET", HttpClient::apiUrl()."guru");
        $getPaket = HttpClient::fetch("GET", HttpClient::apiUrl()."paket");
        $mapel = count($getMapel['data']);
        $guru = count($getGuru['data']);
        $paket = count($getPaket['data']);
        return view('index', compact('mapel', 'guru', 'paket'));
    }

    public function profile()
    {
        $id_user = session()->get('idUser');
        $getMe = HttpClient::fetch(
            "GET",
            HttpClient::apiUrl()."ortu/".$id_user
        );
        $data = $getMe['data'];
        $data['route'] = 'updateProfile';
        return view('profile', compact('data'));
    }

    public function updateProfile(Request $request)
    {
        $payload = $request->all();

        $payload['name'] = $request->input('nama');

        $id_user = session()->get('idUser');

        $getMe = HttpClient::fetch(
            "GET",
            HttpClient::apiUrl()."ortu/".$id_user
        );

        // update password
        $user = HttpClient::fetch(
            "POST",
            HttpClient::apiUrl()."user/".$id_user."/update",
            $payload
        );

        $ortu = HttpClient::fetch(
            "POST",
            HttpClient::apiUrl()."ortu/".$getMe['data']['id']."/edit",
            $payload
        );

        if ($ortu['status']) {
            $pesan = "data berhasil diupdate";
            $status = "success";
        } else {
            $pesan = "gagal!!";
            $status = "error";
        }

        return redirect()->back()->with($status, $pesan);
    }
}
