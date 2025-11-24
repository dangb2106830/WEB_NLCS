<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::all();
        return view('admin.blog.blog_list', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blog.blog_add');
    }

    public function store(Request $request)
    {
        $blog = new Blog();
        $data = $request->except('_token');
        $messages = [
            'title.required' => "Nhập tiêu đề",
            'title.unique' => "Trùng tiêu đề",
            'intro.required' => "Hãy nhập mở đầu",
            'content.required' => "Hãy nhập nội dung",
            'image.required' => "Hãy nhập hình ảnh",
            'author.required' => "Hãy nhập tên người viết",
            'created_date.required' => "Hãy nhập ngày đăng"
        ];

        $validator = Validator::make($data, [
            'title' => 'required|unique:blogs,title',
            'intro' => 'required',
            'content' => 'required',
            'image' => 'required',
            'created_date' => 'required',
            'author' => 'required',
        ], $messages);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->with('errors', $errors);
        } else {
            $blog->title = $request->title;
            $blog->intro = $request->intro;
            $blog->content = $request->content;
            $blog->created_date = $request->created_date;
            $blog->author = $request->author;
            $blog->slug = Str::slug($request->title);

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
                    while (file_exists('uploads/images/blog/' . $image)) {
                        $image = Str::random(5) . '_' . $name_file;
                    }
                }
                $file->move('uploads/images/blog', $image);
                $blog->image = $image;
            }

            $blog->save();
            return redirect()->route('admin.blog.index')->with('success', 'Thêm mới thành công');
        }
    }

    public function edit($id)
    {
        $blog = Blog::find($id);
        $viewdata = [
            'blog' => $blog,
        ];
        return view('admin.blog.blog_edit', $viewdata);
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::find($id);
        $data = $request->except('_token');
        $messages = [
            'created_date.required' => "Hãy nhập ngày đăng"
        ];

        $validator = Validator::make($data, [
            'created_date' => 'required',
        ], $messages);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->with('errors', $errors);
        } else {
            $blog->title = $request->title;
            $blog->intro = $request->intro;
            $blog->content = $request->content;
            $blog->created_date = $request->created_date;
            $blog->author = $request->author;
            $blog->slug = Str::slug($request->title);

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
                    while (file_exists('uploads/images/blog/' . $image)) {
                        $image = Str::random(5) . '_' . $name_file;
                    }
                }
                $file->move('uploads/images/blog', $image);
                $blog->image = $image;
            }

            $blog->save();
            return redirect()->route('admin.blog.index')->with('success', 'Cập nhật thành công');
        }
    }

    public function destroy($id)
    {
        $blog = Blog::find($id);
        $blog->delete();
        return redirect()->route('admin.blog.index')->with('success', 'Xóa thành công');
    }
}
