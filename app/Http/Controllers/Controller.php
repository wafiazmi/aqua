<?php
// app/Http/Controllers/Controller.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $apiKey = 'aaebc163ef9bdcc1a3429aaa9ad542293e9fa14c1383066d34393c4c07226bf3';
    protected $baseUrl = 'https://api.binderbyte.com/wilayah';

    // Method untuk mengambil Provinsi
    public function getProvinsi()
    {
        $response = Http::get("{$this->baseUrl}/provinsi", [
            'api_key' => $this->apiKey,
        ]);

        return response()->json($response->json());
    }

    // Method untuk mengambil Kabupaten berdasarkan Provinsi
    public function getKabupaten($id_provinsi)
    {
        $response = Http::get("{$this->baseUrl}/kabupaten", [
            'api_key' => $this->apiKey,
            'id_provinsi' => $id_provinsi,
        ]);

        $kabupaten = $response->json()['value'] ?? [];

        // Pastikan data yang dikembalikan dalam format yang benar
        return response()->json($kabupaten);
    }

    // Method untuk mengambil Kecamatan berdasarkan Kabupaten
    public function getKecamatan($id_kabupaten)
    {
        $response = Http::get("{$this->baseUrl}/kecamatan", [
            'api_key' => $this->apiKey,
            'id_kabupaten' => $id_kabupaten,
        ]);

        return response()->json($response->json());
    }

    // Method untuk mengambil Kelurahan berdasarkan Kecamatan
    public function getKelurahan($id_kecamatan)
    {
        $response = Http::get("{$this->baseUrl}/kelurahan", [
            'api_key' => $this->apiKey,
            'id_kecamatan' => $id_kecamatan,
        ]);

        return response()->json($response->json());
    }

    // Fungsi untuk menampilkan form dan provinsi serta kabupaten
    public function showForm(Request $request)
    {
        // Ambil provinsi terlebih dahulu
        $provinsiResponse = Http::get("{$this->baseUrl}/provinsi", [
            'api_key' => $this->apiKey,
        ]);

        // Ambil provinsi sebagai array
        $provinsi = $provinsiResponse->json()['value'] ?? [];

        // Ambil kabupaten berdasarkan provinsi_id yang dikirim (jika ada)
        $kabupaten = [];
        if ($request->has('provinsi_id')) {
            $kabupatenResponse = Http::get("{$this->baseUrl}/kabupaten", [
                'api_key' => $this->apiKey,
                'id_provinsi' => $request->provinsi_id,
            ]);
            $kabupaten = $kabupatenResponse->json()['value'] ?? [];
        }

        // Return ke view dan kirim data provinsi & kabupaten
        return view('yourview', compact('provinsi', 'kabupaten'));
    }

    // Fungsi untuk menyimpan data dengan city_id yang benar
    public function storeData(Request $request)
    {
        // Validasi input
        $request->validate([
            'province_id' => 'required|exists:provinsi,id',
            'city_id' => 'required|exists:kabupaten,id',
        ]);

        // Simpan data user (misal)
        $user = new User();
        $user->province_id = $request->province_id;
        $user->city_id = $request->city_id;
        $user->save();

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }
}
