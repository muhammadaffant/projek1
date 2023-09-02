<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        // Memeriksa apakah pengguna memiliki token JWT yang valid
        // if (!Auth::check()) {
        //     return response()->json([
        //         'status' => 401,
        //         'message' => 'Unauthorized. Token is missing or invalid.'
        //     ], 401);
        // }

        $students = Student::all();

        if ($students->count() > 0) {
            return response()->json([
                'status' => 200,
                'students' => $students
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No record Found'
            ], 404);
        }
    }


    public function store(Request $request)
    {
        // $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'jurusan' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|string|max:13',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' =>$validator->messages()
            ], 422);
        }else{
            $student = Student::create([
                'name' => $request->name,
                'jurusan' => $request->jurusan,
                'email' => $request->email,
                'phone' => $request->phone,
                // 'user_id' => $user->id,
            ]);

            if($student){
                return response()->json([
                    'status' => 200,
                    'message' => "Data berhasil ditambahkan"
                ],200);
            }else{
                return response()->json([
                    'status' => 500,
                    'message' => "Terjadi Kesalahan"
                ],500);

            }
        }
    }
    public function show($id)
    {
        $student = Student::find($id);
        if($student){
            return response()->json([
                'status' => 200,
                'student' => $student
            ],200);

        }else{
            return response()->json([
                'status' => 400,
                'message' => "Tidak ada data"
            ],400);
        }
    }
    public function edit($id)
    {
        $student = Student::find($id);
        if($student){
            return response()->json([
                'status' => 200,
                'student' => $student
            ],200);

        }else{
            return response()->json([
                'status' => 404,
                'message' => "Tidak ada data"
            ],400);
        }
    }
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'jurusan' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|string|max:13',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' =>$validator->messages()
            ], 422);
        }else{

            $student = Student::find($id);
            if($student){

                $student ->update([
                    'name' => $request->name,
                    'jurusan' => $request->jurusan,
                    'email' => $request->email,
                    'phone' => $request->phone
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => "Data Berhasil Diupdate!"
                ],200);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => "Tidak Dapat Mengupdate Data!"
                ],404);

            }
        }
    }

    public function destroy($id)
    {
        $student = Student::find($id);
        if($student){
            $student->delete();

            return response()->json([
                'status' => 200,
                'message' => "Data berhasil dihapus"
            ],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => "Tidak Ada Data!"
            ],404);

        }
    }
}

