<?php
// app/Http/Controllers/Auth/ProfileController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ambil data provinsi
        $provinsiResponse = Http::get("{$this->baseUrl}/provinsi", [
            'api_key' => $this->apiKey,
        ]);

        $provinces = $provinsiResponse->successful() 
            ? collect($provinsiResponse->json('value'))->pluck('name', 'id')
            : collect();

        // Ambil data kabupaten sesuai province_id user
        $cities = collect();
        if ($user->province_id) {
            $kabupatenResponse = Http::get("{$this->baseUrl}/kabupaten", [
                'api_key' => $this->apiKey,
                'id_provinsi' => $user->province_id,
            ]);

            $cities = $kabupatenResponse->successful() 
                ? collect($kabupatenResponse->json('value'))->pluck('name', 'id')
                : collect();
        }

        return view('frontend.auth.profile', compact('user', 'provinces', 'cities'));
    }

    public function update(Request $request)
    {
        $params = $request->except('_token');
        $user = auth()->user();

        if ($user->update($params)) {
            return redirect('profile')->with([
                'message' => 'Profil berhasil diperbarui',
                'alert-type' => 'success'
            ]);
        }

        return back()->with([
            'message' => 'Gagal memperbarui profil',
            'alert-type' => 'danger'
        ]);
    }
}
