<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use DB;
use Validator;

class CategoryController extends Controller
{
    public function index() {
        return view('pages/category/index');
    }

    public function getData() {
        try{
            $data = Category::orderBy('nama')->get();

            return datatables()->of($data)
            ->addIndexColumn()
            ->make(true);
        }catch(\Throwable $e) {
            DB::rollback();
            $message = array();
            $message['message'] = $e->getMessage();
            return response()->json($message)->setStatusCode(400);
        }
    }

    public function store(Request $req){
        DB::BeginTransaction();
        try {
            $validated = Validator::make($req->all(), [
                'nama' => 'required|unique:categories|max:25',
            ]);

            if ($validated->fails()) {
                $message = array();
                $message['message'] = $validated->errors();

                return response()->json($message)->setStatusCode(400);
            }

            $category = Category::create(['nama' => $req->nama]);

			$message = array();
            $message['message'] = 'Data saved successfully';

            DB::commit();
            return response()->json($message)->setStatusCode(200);
		}catch(\Throwable $e) {
            DB::rollback();
            $message = array();
            $message['message'] = $e->getMessage();
            return response()->json($message)->setStatusCode(400);
        }
	}

    public function update($id, Request $req){
        DB::BeginTransaction();
        try {
            $validated = Validator::make($req->all(), [
                'nama' => 'required|unique:categories,nama,'.$id.'|max:25',
            ]);

            if ($validated->fails()) {
                $message = array();
                $message['message'] = $validated->errors();

                return response()->json($message)->setStatusCode(400);
            }

            $category = Category::find($id);

            if(!$category) {
                $message = array();
                $message['message'] = 'Data not found';

                return response()->json($message)->setStatusCode(400);
            }

            $category->update(['nama' => $req->nama]);

			$message = array();
            $message['message'] = 'Data updated successfully';

            DB::commit();
            return response()->json($message)->setStatusCode(200);
		}catch(\Throwable $e) {
            DB::rollback();
            $message = array();
            $message['message'] = $e->getMessage();
            return response()->json($message)->setStatusCode(400);
        }
	}

	public function destroy($id){
        DB::BeginTransaction();
        try {
            $category = Category::where('id', $id)->first();

            if(!$category) {
                $message = array();
                $message['message'] = 'Data not found';

                return response()->json($message)->setStatusCode(400);
            }

            $category->delete();
			$message = array();
            $message['message'] = 'Data deleted successfully';

            DB::commit();
            return response()->json($message)->setStatusCode(200);
		}catch(\Throwable $e) {
            DB::rollback();
            $message = array();
            $message['message'] = $e->getMessage();
            return response()->json($message)->setStatusCode(400);
        }
	}
}
