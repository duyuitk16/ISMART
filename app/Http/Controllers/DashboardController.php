<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'dashboard']);
            return $next($request);
        });
    }
    function show()
    {
        $orders = Order::where('status', 'completing')->orwhere('status', 'solving')->orderBy('created_at', 'DESC')->paginate(10);
        $count_complete = Order::where('status', 'completing')->count();
        $count_solve = Order::where('status', 'solving')->count();
        $count_delete = Order::where('status', 'deleting')->count();
        $count = [$count_complete, $count_solve, $count_delete];
        $total = Order::where('status', 'completing')->sum('bill');
        return view('admin.dashboard', compact('orders', 'count', 'total'));
    }
    function delete($id)
    {
        Order::find($id)->delete();
        return redirect('dashboard')->with('status', 'Đã vô hiệu hóa thành công');
    }
    function destroy($id)
    {
        Order::find($id)->update([
            'status' => 'deleting'
        ]);
        return redirect('dashboard')->with('status', 'Đã hủy đơn hàng thành công');
    }
}
