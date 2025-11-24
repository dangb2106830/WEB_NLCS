<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.category.cate_list', compact('categories'));
    }
    
    public function create()
    {
        return view('admin.category.cate_add');
    }
    
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name_cate' => 'required|unique:categories,name_cate',
            ],
            [
                'name_cate.required' => "Không được để trống",
                'name_cate.unique' => "Trùng tên thương hiệu"
            ]
        );
        
        $slug = Str::slug($request->name_cate);

        $checkSlug = Category::where('slug', $slug)->first();
        while ($checkSlug) {
            $slug = $checkSlug->slug . "_" . Str::random(length: 2);
        }

        Category::create([
            'name_cate' => $request->name_cate,
            'slug' => $slug
        ]);

        return redirect()->route('admin.category.index')->with('success', 'Thêm mới thành công');
    }

    public function edit($id)
    {
        $category = Category::find($id);
        $viewdata =[
            'category' => $category,
        ];
        return view('admin.category.cate_edit', $viewdata);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        $category->name_cate = $request->name_cate;

        $category->save();
        return redirect()->route('admin.category.index')->with('success', 'Sửa thành công');
    }

    public function destroy($id)
    {
        $product =Product::where('category_id', $id)->first();
        if( $product == null ){
            $category = Category::find($id);
            $category->delete();
            return redirect()->route('admin.category.index')->with('success', 'Xóa thành công');
        }
        else{
            return redirect()->route('admin.category.index')->with('false', 'Xóa không thành công. Danh mục còn chứa các sản phẩm');
        }
    }

  
}
