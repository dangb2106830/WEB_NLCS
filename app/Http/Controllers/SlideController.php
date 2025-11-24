<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class SlideController extends Controller
{
    public function index()
    {
        $slides = Slide::all();
        return view('admin.slide.slide_list', compact('slides'));
    }

    public function create()
    {
        return view('admin.slide.slide_add');
    }

    public function store(Request $request)
    {
        $slide = new Slide();
        $data = $request->except('_token');
        $messages = [
            'name_slide.required' => "Nhập tiêu đề",
            'name_slide.unique' => "Trùng tiêu đề",
            'content.required' => "Hãy nhập nội dung",
            'image.required' => "Hãy nhập hình ảnh",
        ];

        $validator = Validator::make($data, [
            'name_slide' => 'required|unique:slides,name_slide',
            'content' => 'required',
            'image' => 'required',
        ], $messages);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->with('errors', $errors);
        } else {
            $slide->name_slide = $request->name_slide;
            $slide->content = $request->content;
            $slide->slug = Str::slug($request->name_slide);

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
                    while (file_exists('uploads/images/slide/' . $image)) {
                        $image = Str::random(5) . '_' . $name_file;
                    }
                }
                $file->move('uploads/images/slide', $image);
                $slide->image = $image;
            }

            $slide->save();
            return redirect()->route('admin.slide.index')->with('success', 'Thêm mới thành công');
        }
    }

    public function edit($id)
    {
        $slide = Slide::find($id);
        $viewdata = [
            'slide' => $slide,
        ];
        return view('admin.slide.slide_edit', $viewdata);
    }

    public function update(Request $request, $id)
    {
        $slide = Slide::find($id);
        $slide->name_slide = $request->name_slide;
        $slide->content = $request->content;
        $slide->slug = Str::slug($request->name_slide);

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
                while (file_exists('uploads/images/slide/' . $image)) {
                    $image = Str::random(5) . '_' . $name_file;
                }
            }
            $file->move('uploads/images/slide', $image);
            $slide->image = $image;
        }

        $slide->save();
        return redirect()->route('admin.slide.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        $slide = Slide::find($id);
        $slide->delete();
        return redirect()->route('admin.slide.index')->with('success', 'Xóa thành công');
    }
}
