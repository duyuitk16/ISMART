<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminPageController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'page']);
            return $next($request);
        });
    }
    function show(Request $request)
    {
        $keyword = '';
        $listAction = [
            'delete' => 'Xóa Tạm Thời',
            'not_active' => 'Chuyển sang chờ duyệt'
        ];
        $status = $request->status;
        if ($status == 'trash') {
            $pages = Page::onlyTrashed()->paginate(4);
            $listAction = ['restore' => 'Khôi Phục', 'forceDelete' => 'Xóa Vĩnh Viễn'];
        } else if ($status == 'not_active') {
            $pages = Page::where('status', 'off')->paginate(4);
            $listAction = [
                'active' => 'Duyệt',
                'delete' => 'Xóa Tạm Thời',
            ];
        } else {
            if ($request->keyword) {
                $keyword = $request->keyword;
            }
            $pages = Page::where([['name', 'like', "%{$keyword}%"], ['status', 'on ']])->paginate(4);
        }
        $count_active = Page::where('status', 'on')->count();
        $count_not_active = Page::where('status', 'off')->count();
        $count_trash = Page::onlyTrashed()->count();
        $count = [$count_active, $count_not_active, $count_trash];
        // dd($request);
        return view('admin.page.show', compact('pages', 'count', 'listAction', 'status'));
    }
    function delete($id)
    {
        Page::find($id)->delete();
        return redirect('admin/page/list')->with('status', 'Đã xóa thành công');
    }
    function forceDelete($id)
    {
        Page::onlyTrashed()->find($id)->forceDelete();
        return redirect('admin/page/list')->with('status', 'Đã xóa vĩnh viễn');
    }
    function action(Request $request)
    {
        $listCheck = $request->listCheck;
        if ($listCheck) {
            $action = $request->action;
            if ($action) {
                if ($action == 'delete') {
                    Page::whereIn('id', $listCheck)->delete();
                    return redirect('admin/page/list')->with('status', 'Đã xóa tạm thời');
                }
                if ($action == 'restore') {
                    Page::onlyTrashed()->whereIn('id', $listCheck)->restore();
                    return redirect('admin/page/list')->with('status', 'Đã khôi phục thành công');
                }
                if ($action == 'forceDelete') {
                    Page::onlyTrashed()->whereIn('id', $listCheck)->forceDelete();
                    return redirect('admin/page/list')->with('status', 'Đã xóa vĩnh viễn');
                }
                if ($action == 'not_active') {
                    Page::whereIn('id', $listCheck)->update([
                        'status' => 'off',
                    ]);
                    return redirect('admin/page/list')->with('status', 'Đã chuyển đến chờ duyệt');
                }
                if ($action == 'active') {
                    Page::whereIn('id', $listCheck)->update([
                        'status' => 'on',
                    ]);
                    return redirect('admin/page/list')->with('status', 'Đã duyệt thành công');
                }
            }
            return redirect('admin/page/list')->with('warning', 'Bạn cần chọn tác vụ');
        }
        return redirect('admin/page/list')->with('warning', 'Bạn cần chọn trang để thực hiện');
    }
    function add()
    {
        return view('admin.page.add');
    }
    function create(Request $request)
    {
        // return $request->all();
        $request->validate(
            [
                'name' => 'required| max:200',
                'content' => 'required',
                'slug' => 'required| max:200',
                'checkActive' => 'required',
            ],
            [
                'required' => ':Attribute bắt buộc',
                'max' => 'Độ dài tối đa :max kí tự',
            ],
            [
                'name' => 'Tên trang',
                'content' => 'Nội dung trang',
                'slug' => 'Đường dẫn tĩnh',
                'checkActive' => 'Trạng thái trang',
            ]
        );
        Page::create([
            'name' => $request->name,
            'content' => $request->content,
            'status' => $request->checkActive,
            'slug' => Str::slug($request->slug),
            'user_id' => Auth::id(),
        ]);
        return redirect('admin/page/list')->with('status', 'Đã thêm trang thành công');
    }
    function update($id)
    {
        $page = Page::withTrashed()->find($id);
        return view('admin.page.update', compact('page'));
    }
    function edit(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required| max:200',
                'content' => 'required',
                'slug' => 'required| max:200',
                'checkActive' => 'required',
            ],
            [
                'required' => ':Attribute bắt buộc',
                'max' => 'Độ dài tối đa :max kí tự',
            ],
            [
                'name' => 'Tên trang',
                'content' => 'Nội dung trang',
                'slug' => 'Đường dẫn thân thiện',
                'checkActive' => 'Trạng thái trang',
            ]
        );
        Page::withTrashed()->where('id', $id)->update([
            'name' => $request->name,
            'content' => $request->content,
            'status' => $request->checkActive,
            'slug' => Str::slug($request->slug),
            'user_id' => Auth::id(),
        ]);
        return redirect('admin/page/list')->with('status', 'Đã cập nhật trang thành công');
    }
}
