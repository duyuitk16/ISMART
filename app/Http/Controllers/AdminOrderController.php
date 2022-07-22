<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'order']);
            return $next($request);
        });
    }
    function show(Request $request)
    {
        $keyword = '';
        $listAction = [
            'solve' => 'Chuyển sang đang xử lí',
            'delete' => 'Xóa Tạm Thời',
        ];
        $status = $request->status;
        if ($status == 'trash') {
            $orders = Order::onlyTrashed()->orderBy('created_at', 'DESC')->paginate(10);
            $listAction = ['restore' => 'Khôi Phục', 'forceDelete' => 'Xóa Vĩnh Viễn'];
        } else if ($status == 'solve') {
            $orders = Order::where('status', 'solving')->orderBy('created_at', 'DESC')->paginate(10);
            $listAction = [
                'complete' => 'Chuyển sang hoàn thành',
                'delete' => 'Xóa Tạm Thời',
            ];
        } else if ($status == 'confirm') {
            $orders = Order::where('status', 'confirming')->orderBy('created_at', 'DESC')->paginate(10);
            $listAction = [
                'delete' => 'Xóa tạm thời',
            ];
        } else if ($status == 'delete') {
            $orders = Order::where('status', 'deleting')->orderBy('created_at', 'DESC')->paginate(10);
            $listAction = [
                'delete' => 'Xóa tạm thời',
            ];
        } else {
            if ($request->keyword) {
                $keyword = $request->keyword;
                $orders = Order::where([['fullname', 'like', "%{$keyword}%"], ['status', 'completing']])->orWhere([['fullname', 'like', "%{$keyword}%"], ['status', 'solving']])->orWhere([['fullname', 'like', "%{$keyword}%"], ['status', 'confirming']])->orWhere([['fullname', 'like', "%{$keyword}%"], ['status', 'deleting']])->orWhere('code', $keyword)->orderBy('created_at', 'DESC')->paginate(10);
            } else
                $orders = Order::where('status', 'completing')->orderBy('created_at', 'DESC')->paginate(10);
        }
        $count_complete = Order::where('status', 'completing')->count();
        $count_solve = Order::where('status', 'solving')->count();
        $count_confirm = Order::where('status', 'confirming')->count();
        $count_delete = Order::where('status', 'deleting')->count();
        $count_trash = Order::onlyTrashed()->count();
        $count = [$count_complete, $count_solve, $count_confirm, $count_delete, $count_trash];
        return view('admin.order.show', compact('orders', 'count', 'listAction', 'status'));
    }
    function action(Request $request)
    {
        $listCheck = $request->listCheck;
        if ($listCheck) {
            $action = $request->action;
            if ($action) {
                if ($action == 'delete') {
                    Order::whereIn('id', $listCheck)->delete();
                    return redirect('admin/order/list')->with('status', 'Đã hủy tạm thời');
                }
                if ($action == 'restore') {
                    Order::onlyTrashed()->whereIn('id', $listCheck)->restore();
                    return redirect('admin/order/list')->with('status', 'Đã khôi phục thành công');
                }
                if ($action == 'forceDelete') {
                    Order::onlyTrashed()->whereIn('id', $listCheck)->forceDelete();
                    return redirect('admin/order/list')->with('status', 'Đã hủy vĩnh viễn');
                }
                if ($action == 'solve') {
                    Order::whereIn('id', $listCheck)->update([
                        'status' => 'solving',
                    ]);
                    return redirect('admin/order/list')->with('status', 'Đã chuyển về đang xử lí');
                }
                if ($action == 'complete') {
                    Order::whereIn('id', $listCheck)->update([
                        'status' => 'completing',
                    ]);
                    return redirect('admin/order/list')->with('status', 'Đã hoàn thành đơn hàng');
                }
            }
            return redirect('admin/order/list')->with('warning', 'Bạn cần chọn tác vụ');
        }
        return redirect('admin/order/list')->with('warning', 'Bạn cần chọn trang để thực hiện');
    }
    function delete($id)
    {
        Order::find($id)->delete();
        return redirect('admin/order/list')->with('status', 'Đã vô hiệu hóa thành công');
    }
    function destroy($id)
    {
        Order::withTrashed()->find($id)->update([
            'status' => 'deleting'
        ]);
        return redirect('admin/order/list')->with('status', 'Đã hủy đơn hàng thành công');
    }
    function solve($id)
    {
        Order::withTrashed()->find($id)->update([
            'status' => 'solving'
        ]);
        return redirect('admin/order/list')->with('status', 'Đã chuyển về đang xử lí');
    }
    function restore($id)
    {
        Order::onlyTrashed()->find($id)->restore();
        return redirect('admin/order/list')->with('status', 'Đã khôi phục thành công');
    }
    function forceDelete($id)
    {
        $this_post = Order::onlyTrashed()->find($id)->forceDelete();
        return redirect('admin/order/list')->with('status', 'Đã xóa vĩnh viễn');
    }
}
