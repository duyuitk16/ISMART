<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'user']);
            return $next($request);
        });
    }
    function show(Request $request)
    {
        $keyword = '';
        $listAction = ['delete' => 'Xóa Tạm Thời'];
        $status = $request->status;
        if ($status == 'trash') {
            $users = User::onlyTrashed()->paginate(10);
            $listAction = ['restore' => 'Khôi Phục', 'forceDelete' => 'Xóa Vĩnh Viễn'];
        } else {
            if ($request->keyword) {
                $keyword = $request->keyword;
            }
            $users = User::where('name', 'like', "%{$keyword}%")->paginate(10);
        }
        $count_active = User::count();
        $count_trash = User::onlyTrashed()->count();
        $count = [$count_active, $count_trash];
        // dd($request);
        return view('admin.user.show', compact('users', 'count', 'listAction', 'status'));
    }
    function add()
    {
        $roles = Role::all();
        return view('admin.user.add', compact('roles'));
    }
    function delete($id)
    {
        if (Auth::id() != $id) {
            User::find($id)->delete();
            return redirect('admin/user/list')->with('status', 'Đã xóa thành công');
        } else {
            return redirect('admin/user/list')->with('warning', 'Bạn không thể xóa chính mình');
        }
    }
    function action(Request $request)
    {
        $listCheck = $request->listCheck;
        if ($listCheck) {
            foreach ($listCheck as $k => $id) {
                if (Auth::id() == $id) {
                    unset($listCheck[$k]);
                }
            }
            $action = $request->action;
            if ($action) {
                if ($listCheck) {
                    if ($action == 'delete') {
                        User::whereIn('id', $listCheck)->delete();
                        return redirect('admin/user/list')->with('status', 'Đã xóa tạm thời');
                    }
                    if ($action == 'restore') {
                        User::onlyTrashed()->whereIn('id', $listCheck)->restore();
                        return redirect('admin/user/list')->with('status', 'Đã khôi phục thành công');
                    }
                    if ($action == 'forceDelete') {
                        User::onlyTrashed()->whereIn('id', $listCheck)->forceDelete();
                        return redirect('admin/user/list')->with('status', 'Đã xóa vĩnh viễn');
                    }
                }
                return redirect('admin/user/list')->with('warning', 'Bạn không thể xóa chính mình');
            }
            return redirect('admin/user/list')->with('warning', 'Bạn cần chọn tác vụ');
        }
        return redirect('admin/user/list')->with('warning', 'Bạn cần chọn thành viên để thực hiện');
    }
    function create(Request $request)
    {

        $request->validate([
            'name' => 'required | string | max:255',
            'email' => 'required| string| max:255| unique:users',
            'password' => 'required | string | min:8 ',
            'role' => 'required ',
            'password-confirm' => 'required | string | min:8 | same:password',
        ], [
            'required' => ':Attribute bắt buộc',
            'unique' => ':Attribute đã tồn tại trong hệ thống',
            'max' => 'Độ dài tối đa :max kí tự',
            'min' => 'Độ dài tối thiểu :min kí tự',
            'same' => 'Không giống mật khẩu đã nhập trước đó',
            'email' => 'Phải có định dạng email'
        ], [
            'password' => 'Mật khẩu',
            'password-confirm' => 'Xác nhận mật khẩu',
            'email' => 'Email',
            'role' => 'Nhóm quyền',
            'name' => 'Họ và tên',
        ]);
        // return $request->all();
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role,
            'password' => Hash::make($request->password),
        ]);
        return redirect('admin/user/list')->with('status', 'Đã thêm thành viên thành công');
    }
    function update($id)
    {
        $roles = Role::all();
        $user = User::withTrashed()->find($id);
        return view('admin.user.update', compact('user', 'roles'));
    }
    function edit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required | string | max:255',
            'password' => 'required | string | min:8 ',
            'password-confirm' => 'required | string | min:8 | same:password',
        ], [
            'required' => ':Attribute bắt buộc',
            'max' => 'Độ dài tối đa :max kí tự',
            'min' => 'Độ dài tối thiểu :min kí tự',
            'same' => 'Không giống mật khẩu đã nhập trước đó',
        ], [
            'password' => 'Mật khẩu',
            'password-confirm' => 'Xác nhận mật khẩu',
            'name' => 'Họ và tên',
        ]);
        if ($request->role) {
            User::withTrashed()->where('id', $id)->update([
                'role_id' => $request->role,
            ]);
        }
        User::withTrashed()->where('id', $id)->update([
            'name' => $request->name,
            'password' => Hash::make($request->password),
        ]);
        return redirect('admin/user/list')->with('status', 'Đã cập nhật thành viên thành công');
    }
    function forceDelete($id)
    {
        User::where('id', $id)->forceDelete();
        return redirect('admin/user/list')->with('status', 'Đã xóa vĩnh viễn');
    }
}
