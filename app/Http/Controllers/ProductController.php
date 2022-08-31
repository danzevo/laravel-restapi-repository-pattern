<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use File;
use DB;
use Validator;

class ProductController extends Controller
{
    public function index() {
        $category = Category::all();
        return view('pages/product/index', compact('category'));
    }

    public function getData() {
        try {
            $data = Product::with(['category'])->latest()->
                    where('user_id', auth()->user()->id)->latest()->get();

            return datatables()->of($data)
            ->addColumn('category_nama', function ($data) {
                return isset($data->category->nama)? $data->category->nama : '';
            })
            ->addColumn('raw_harga', function ($data) {
                return $data->harga ? 'Rp. '.number_format($data->harga, 0, ',', '.') : '';
            })
            ->addIndexColumn()
            ->make(true);
        }catch(\Throwable $e) {
            $message = array();
            $message['message'] = $e->getMessage();
            return response()->json($message)->setStatusCode(400);
        }
    }

    public function store(Request $req){
        DB::BeginTransaction();
        try{
            $validated = Validator::make($req->all(), [
                'nama' => 'required|unique:products|max:50',
                'deskripsi' => 'required',
                'harga' => 'required',
                'category_id' => 'required|integer',
                'image' => 'required',
            ]);

            if ($validated->fails()) {
                $message = array();
                $message['message'] = implode(',',$validated->errors()->all());

                return response()->json($message)->setStatusCode(400);
            }

            $item = array(
                'nama'      => $req->nama,
                'deskripsi' => $req->deskripsi,
                'harga'     => str_replace('.', '', $req->harga),
                'category_id' => $req->category_id,
                'user_id'     => auth()->user()->id,
            );

            if ($req->hasFile('image')) {
                if(file_exists(public_path('temp_product/avatar.jpg'))) {
                    $filename = $req->image->getClientOriginalName(); // getting file extension
                    $filename = preg_replace('/\s/', '-', $filename);

                    $nama_baru = time() . "_" . $filename;
                    $item['image'] = $nama_baru;
                    File::move(public_path('temp_product/avatar.jpg'), public_path('image_product/' . $nama_baru));
                }
            }

            $product = Product::create($item);

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
        try{
            $validated = Validator::make($req->all(), [
                'nama' => 'required|unique:products,nama,'.$id.'|max:50',
                'deskripsi' => 'required',
                'harga' => 'required',
                'category_id' => 'required|integer',
                'image' => 'required',
            ]);

            if ($validated->fails()) {
                $message = array();
                $message['message'] = implode(',',$validated->errors()->all());

                return response()->json($message)->setStatusCode(400);
            }

            $product = Product::find($id);
            if(!$product) {
                $message = array();
                $message['message'] = 'Data not found';

                return response()->json($message)->setStatusCode(400);
            }

            $item = array(
                'nama'      => $req->nama,
                'deskripsi' => $req->deskripsi,
                'harga'     => str_replace('.', '', $req->harga),
                'category_id' => $req->category_id,
                'user_id'     => auth()->user()->id,
            );

            if ($req->hasFile('image')) {
                $nama_lama = $product->image;

                if($nama_lama != $req->file('image')->getClientOriginalName() && file_exists(public_path('temp_product/avatar.jpg'))) {
                    $filename = $req->image->getClientOriginalName(); // getting file extension
                    $filename = preg_replace('/\s/', '-', $filename);

                    $nama_baru = time() . "_" . $filename;
                    $item['image'] = $nama_baru;
                    File::move(public_path('temp_product/avatar.jpg'), public_path('image_product/' . $nama_baru));
                }
            }

            $data = $product->update($item);

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

	public function destroy($id){
        DB::BeginTransaction();
        try {
            $product = Product::where('id', $id)->first();
            if(!$product) {
                $message = array();
                $message['message'] = 'Data not found';

                return response()->json($message)->setStatusCode(400);
            }

            $product->delete();

            $message = array();
            $message['message'] = 'Data deleted successfully';

            DB::commit();
            return response()->json($message)->setStatusCode(200);
		} catch(\Throwable $e) {
            DB::rollback();
            $message = array();
            $message['message'] = $e->getMessage();
            return response()->json($message)->setStatusCode(400);
        }
	}

    public function upload_product(Request $request)
    {
        try{
            $file = new Filesystem;
            $file->cleanDirectory(public_path('temp_product'));
            if ($request->hasFile('avatar')) {

                $filename = $request->avatar->getClientOriginalName(); // getting file extension
                $filename = preg_replace('/\s/', '-', $filename);

                $destinationPath = 'temp_product'; // upload path
                $extension = $request->avatar->getClientOriginalExtension(); // getting file extension

                $nama_baru = $filename;

                $request->avatar->move(public_path("$destinationPath"), $nama_baru);
            }
        } catch(\Throwable $e) {
            $message = array();
            $message['message'] = $e->getMessage();
            return response()->json($message)->setStatusCode(400);
        }
    }

    public function katalog() {
        $product = Product::with('category')->latest()->
                    where('user_id', auth()->user()->id)->get();

        return view('pages/katalog/index', compact('product'));
    }
}
