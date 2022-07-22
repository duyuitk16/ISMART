<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;
use Illuminate\Support\Str;


class AdminRoleController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'role']);
            return $next($request);
        });
    }
    function show(Request $request)
    {
        $status = $request->status;
        if ($status == 'trash') {
            $roles = Role::onlyTrashed()->get();
        } else {
            $roles = Role::all();
        }
        $count_active = Role::count();
        $count_trash = Role::onlyTrashed()->count();
        $count = [$count_active, $count_trash];
        return view('admin.role.show', compact('roles', 'count', 'status'));
    }
    function add(Request $request)
    {
        $request->validate(
            [
                'name' => 'required| max:200',
            ],
            [
                'required' => ':Attribute bắt buộc',
                'max' => 'Độ dài tối đa :max kí tự',
            ],
            [
                'name' => 'Tên trang',
            ]
        );
        Role::create([
            'name' => $request->name,
            'user_id' => Auth::id(),
        ]);
        return redirect('admin/role/list')->with('status', 'Đã thêm quyền thành công');
    }
    function delete($id)
    {
        Role::find($id)->delete();
        return redirect('admin/role/list')->with('status', 'Đã xóa thành công');
    }
    function forceDelete($id)
    {
        Role::onlyTrashed()->find($id)->forceDelete();
        return redirect('admin/role/list')->with('status', 'Đã xóa vĩnh viễn');
    }
    function restore($id)
    {
        Role::onlyTrashed()->find($id)->restore();
        return redirect('admin/role/list')->with('status', 'Đã khôi phục thành công');
    }
    function update($id, Request $request)
    {
        $status = $request->status;
        if ($status == 'trash') {
            $roles = Role::onlyTrashed()->get();
        } else {
            $roles = Role::all();
        }
        $count_active = Role::count();
        $count_trash = Role::onlyTrashed()->count();
        $count = [$count_active, $count_trash];
        $role = Role::withTrashed()->find($id);
        return view('admin.role.update', compact('role', 'roles', 'count', 'status'));
    }
    function edit(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required| max:200',
            ],
            [
                'required' => ':Attribute bắt buộc',
                'max' => 'Độ dài tối đa :max kí tự',
            ],
            [
                'name' => 'Tên trang',
            ]
        );
        Role::withTrashed()->where('id', $id)->update([
            'name' => $request->name,
            'user_id' => Auth::id(),
        ]);
        return redirect('admin/role/list')->with('status', 'Đã cập nhật trang thành công');
    }
}
