<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\KehadiranRequest;
use App\Interfaces\KehadiranRepositoryInterface;
use App\Models\Kehadiran;
use Illuminate\Http\Request;

class KehadiranController extends Controller
{
    private KehadiranRepositoryInterface $KehadiranRepository;

    public function __construct(KehadiranRepositoryInterface $KehadiranRepository){
        $this->KehadiranRepository = $KehadiranRepository;
    }

    public function indexKehadiran(KehadiranRequest $request){
        $request->validated();

        return response()->json([
            'body' => $this->KehadiranRepository->createKehadiran($request->all())
        ]);
    }

    public function getKehadiran(){
        $data = Kehadiran::get();

        return response()->json([
            'body' => $data
        ]);
    }

    public function updateKehadiran($id, KehadiranRequest $request)
    {
        $request->validated();

        //melakukan update data berdasarkan id
        $kehadiran              = Kehadiran::find($id);
        $kehadiran->pkl_id      = $request->pkl_id;
        $kehadiran->tanggalwaktu = $request->tanggalwaktu;
        $kehadiran->kehadiran   = $request->kehadiran;
        $kehadiran->keterangan  = $request->keterangan;
        $kehadiran->status      = $request->status;

        //jika berhasil maka simpan data dengan method $post->save()
        if ($kehadiran->save()) {
            return response()->json(['Post Berhasil Disimpan', 'data' => $kehadiran]);
        } else {
            return response()->json('Post Gagal Disimpan');
        }
    }

    public function deleteKehadiran($id)
    {

        $kegiatan = Kehadiran::findOrFail($id);

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
