<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\HttpClient;

class PemesananController extends Controller
{
    public function store(Request $request)
    {
        $ortu = HttpClient::fetch(
            "GET",
            HttpClient::apiUrl()."ortu/".$request->input('id_user')
        );

        $payload['id_orang_tua'] = $ortu['data']['id'];
        $payload['id_harga_paket'] = $request->input('id_harga_paket');
        $payload['status'] = 0;

        $pemesanan = HttpClient::fetch(
            "POST",
            HttpClient::apiUrl()."pemesanan",
            $payload,
        );

        if ($pemesanan['status']) {
            $pesan = "berhasil menambahkan";
            $status = "success";
        } else {
            $pesan = "gagal!!";
            $status = "error";
        }
        return redirect("/order")->with($status, $pesan);
    }
}
