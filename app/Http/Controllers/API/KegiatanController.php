<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\KegiatanRequest;
use App\Interfaces\KegiatanRepositoryInterface;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{

    private KegiatanRepositoryInterface $KegiatanRepository;

    public function __construct(KegiatanRepositoryInterface $KegiatanRepository)
    {
        $this->KegiatanRepository = $KegiatanRepository;
    }

    public function createData(KegiatanRequest $request)
    {
        $userToken = $request->user();

        if ($userToken->roles_id != 1) {
            return response()->json([
                'body' => "Hanya mahasiswa yang dapat mengakses fitur ini"
            ], 401);
        }
        $request->validated();

        return response()->json([
            'body' => $this->KegiatanRepository->createKegiatan($request->all())
        ]);
    }

    public function getData()
    {

        // return Kegiatan::all();
        $data = Kegiatan::get();

        return response()->json([
            'body' => $data
        ]);
    }

    public function updateData($id, KegiatanRequest $request)
    {
        $request->validated();

        //melakukan update data berdasarkan id
        $kegiatan              = Kegiatan::find($id);
        $kegiatan->pkl_id      = $request->pkl_id;
        $kegiatan->capaian     = $request->capaian;
        $kegiatan->sub_capaian = $request->sub_capaian;
        $kegiatan->jam         = $request->jam;
        $kegiatan->status      = $request->status;

        //jika berhasil maka simpan data dengan method $post->save()
        if ($kegiatan->save()) {
            return response()->json(['Post Berhasil Disimpan', 'data' => $kegiatan]);
        } else {
            return response()->json('Post Gagal Disimpan');
        }
    }

    public function deleteData($id, Request $request)
    {
        $userToken = $request->user();

        if ($userToken->roles_id != 1) {
            return response()->json([
                'body' => "Hanya mahasiswa yang dapat mengakses fitur ini"
            ], 401);
        }
        $kegiatan = Kegiatan::findOrFail($id);

        if ($kegiatan->delete()) {
            return response([
                'Berhasil Menghapus Data'
            ]);
        } else {
            //response jika gagal menghapus
            return response([
                'Tidak Berhasil Menghapus Data'
            ]);
        }
    }
}
