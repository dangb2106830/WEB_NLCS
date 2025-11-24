<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('admin.brand.brand_list', compact('brands'));
    }

    
    public function create()
    {
        return view('admin.brand.brand_add');
    }
    
    public function store(Request $request)
    {
        $brand = new Brand();
        $data = $request->except('_token');
        $messages = [
            'name_bra.required' => "Không được để trống tên",
            'name_bra.unique' => "Trùng tên thương hiệu",
            'image.required' => "Chưa chọn ảnh",
        ];

        $validator = Validator::make($data, [
            'name_bra' => 'required|unique:brands,name_bra',
            'image' => 'required',
        ], $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->with('errors', $errors);
        } else {
            $brand->name_bra = $request->name_bra;
            $brand->slug = Str::slug($request->name_bra);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $name_file = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();

                if (
                    strcasecmp($ext, 'jpg') === 0
                    || strcasecmp($ext, 'png') === 0
                    || strcasecmp($ext, 'jepg') === 0
                ) {
                    $image = Str::random(5) . '_' . $name_file;
                    while (file_exists('uploads/images/brand/' . $image)) {
                        $image = Str::random(5) . '_' . $name_file;
                    }
                }
                $file->move('uploads/images/brand', $image);
                $brand->image = $image;
            }

            $brand->save();
            return redirect()->route('admin.brand.index')->with('success', 'Thêm mới thành công');
        }
    }
   
    public function edit($id)
    {
        $brand = Brand::find($id);
        $viewdata =[
            'brand' => $brand,
        ];
        return view('admin.brand.brand_edit', $viewdata);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);
        $brand->name_bra = $request->name_bra;
        $brand->slug = Str::slug($request->name_bra);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name_file = $file->getClientOriginalName();
            $ext = $file->getClientOriginalExtension();

            if (
                strcasecmp($ext, 'jpg') === 0
                || strcasecmp($ext, 'png') === 0
                || strcasecmp($ext, 'jepg') === 0
            ) {
                $image = Str::random(5) . '_' . $name_file;
                while (file_exists('uploads/images/brand/' . $image)) {
                    $image = Str::random(5) . '_' . $name_file;
                }
            }
            $file->move('uploads/images/brand', $image);
            $brand->image = $image;
        }

        $brand->save();
        return redirect()->route('admin.brand.index')->with('success', 'Sửa thành công');
    }

    public function destroy($id)
    {
        $product = Product::where('brand_id',$id)->first();
        if($product == null){
            $brand = Brand::find($id);
            File::delete('uploads/images/brand/'.$brand->image);
            $brand->delete();
            return redirect()->route('admin.brand.index')->with('success', 'Xóa thành công');
        }
        else{
            return redirect()->route('admin.brand.index')->with('false', 'Xóa không thành công. Thương hiệu còn chứa các sản phẩm');
        }
    }

}
