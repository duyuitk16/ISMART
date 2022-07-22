<?php

namespace App\Http\Controllers;

use App\Order;
use  Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmMail;
use App\Product;
use App\ProductCat;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class CheckoutController extends Controller
{
    function show()
    {
        foreach (Cart::content() as $row) {
            $product = Product::where([['slug', $row->options->slug], ['status', 'on']])->first();
            if ($product) {
                if ($row->qty > $product->amount) {
                    Alert::html('Cảnh báo', "Số lượng của <strong>$product->name</strong> chỉ còn: $product->amount sản phẩm. Vui lòng điều chỉnh số lượng để được chuyển sang trang đặt hàng", 'warning');
                    return redirect()->back();
                }
            } else {
                Alert::html('Cảnh báo', "Sản phẩm <strong>$row->name</strong> đã hết hàng. Vui lòng xóa khỏi giỏ hàng để được chuyển sang trang đặt hàng!", 'warning');
                return redirect()->back();
            }
        }
        $product_cats = ProductCat::where('status', 'on')->get();
        $product_cats = $this->data_tree($product_cats);
        return view('client.checkout.show', compact('product_cats'));
    }
    function data_tree($data, $parent_id = 0, $level = 0)
    {
        $result = [];
        foreach ($data as $item) {
            if ($item['parent_id'] == $parent_id) {
                $item['level'] = $level;
                $result[] = $item;
                $child = $this->data_tree($data, $item['id'], $level + 1);
                $result = array_merge($result, $child);
            }
        }
        return $result;
    }
    function order(Request $request)
    {
        if (Cart::count() > 0) {
            foreach (Cart::content() as $row) {
                $product = Product::where([['slug', $row->options->slug], ['status', 'on']])->first();
                if ($product) {
                    if ($row->qty > $product->amount) {
                        Alert::html('Cảnh báo', "Số lượng của <strong>$product->name</strong> chỉ còn: $product->amount sản phẩm. Vui lòng điều chỉnh số lượng để tiếp tục đặt hàng", 'warning');
                        return redirect('gio-hang');
                    }
                } else {
                    Alert::html('Cảnh báo', "Sản phẩm <strong>$row->name</strong> đã hết hàng. Vui lòng xóa khỏi giỏ hàng để tiếp tục đặt hàng!", 'warning');
                    return redirect('gio-hang');
                }
            }
            $request->validate([
                'fullname' => 'required|max:200',
                'email' => 'required|max:200',
                'address' => 'required|max:200',
                'phone' => 'required|regex:/^[0-9]+$/|max:11|min:10',
                'checkout_method' => 'required'
            ], [
                'required' => ':Attribute bắt buộc',
                'max' => 'Độ dài tối đa :max kí tự',
                'min' => 'Độ dài tối thiểu :min kí tự',
                'regex' => ':Attribute phải là số',
                'unique' => ':Attribute đã tồn tại trước đó',
            ], [
                'fullname' => 'Họ và tên',
                'email' => 'Email',
                'address' => 'Địa chỉ',
                'phone' => 'Điện thoại',
                'checkout_method' => 'Phương thức thanh toán',
            ]);
            $orders = '';
            $total = 0;
            foreach (Cart::content() as $product) {
                $orders .= ($product->name . ' x ' . "<span style='color:#d63031;font-weight:bold;'>" . $product->qty . "</span>" . '<br>');
                $total += $product->total;
            }
            $checkConfirm = Str::random(20);
            $code = Str::random(12);
            Order::create([
                'fullname' => $request->fullname,
                'email' => $request->email,
                'address' =>  $request->address,
                'phone' =>  $request->phone,
                'checkout_method' => $request->checkout_method,
                'orders' => $orders,
                'note' => $request->note,
                'bill' => $total,
                'status' => 'confirming',
                'checkConfirm' => $checkConfirm,
                'code' => $code,
            ]);
            $this_order = Order::where('checkConfirm', $checkConfirm)->first();
            $data = [
                'order' => $this_order,
            ];
            // return $data;
            Mail::to($request->email)->send(new ConfirmMail($data));
            return redirect('thanh-toan')->with('info', "Chúng tôi vừa gửi thông tin đơn hàng vào '<u>'$request->email'</u>'. Vui lòng xác nhận để được xử lí nhanh nhất");
        } else {
            return redirect('thanh-toan')->with('warning', "Không có sản phẩm nào để đặt hàng");
        }
    }
    function confirm(Request $request)
    {
        if ($request->confirm) {
            $order = Order::where([['checkConfirm', $request->confirm], ['status', '!=', 'deleting']])->first();
            if (!$order) {
                Alert::html('Cảnh báo', "Email xác nhận đã hết hạn hoặc đơn hàng của bạn đã bị hủy!", 'error');
                return redirect('thanh-toan');
            } else {
                if ($order->status == 'solving') {
                    Alert::html('Cảnh báo', "Email xác nhận đã hết hạn hoặc đơn hàng của bạn đã bị hủy!", 'error');
                    return redirect('thanh-toan');
                }
            }
        }
        if (Cart::count() > 0) {
            foreach (Cart::content() as $row) {
                $product = Product::where([['slug', $row->options->slug], ['status', 'on']])->first();
                if ($product) {
                    if ($row->qty > $product->amount) {
                        Alert::html('Rất tiếc', "Số lượng của <strong>$product->name</strong> chỉ còn: $product->amount sản phẩm. Vui lòng điều chỉnh số lượng!", 'error');
                        return redirect('gio-hang');
                    }
                } else {
                    Alert::html('Cảnh báo', "Sản phẩm <strong>$row->name</strong> đã hết hàng. Vui lòng xóa khỏi giỏ hàng để tiếp tục đặt hàng!", 'error');
                    return redirect('gio-hang');
                }
            }
            $order = Order::where('checkConfirm', $request->confirm)->first();
            if ($request->action == 'confirm') {
                if ($order) {
                    if ($order->status == 'confirming') {
                        $order->update([
                            'status' => 'solving',
                        ]);
                        //Cập nhật lại dữ số lượng của sản phẩm
                        foreach (Cart::content() as $row) {
                            $product = Product::where('slug', $row->options->slug)->first();
                            $amount = $product->amount - $row->qty;
                            if ($amount == 0) {
                                $product->update([
                                    'status' => 'off',
                                ]);
                            }
                            $product->update([
                                'amount' => $amount,
                            ]);
                        }
                        Cart::destroy();
                        return redirect('thanh-toan')->with('success', "Đơn hàng của '<strong>'$order->fullname'</strong>' đã xác nhận ");
                    } else if ($order->status == 'solving') {
                        return redirect('thanh-toan')->with('success', "Đơn hàng này đã được xác nhận trước đó");
                    }
                }
                return redirect('thanh-toan')->with('warning', "Email xác nhận đơn hàng đã hết hạn");
            } else if ($request->action == 'delete') {
                if ($order) {
                    Order::where('checkConfirm', $request->confirm)->forceDelete();
                    Cart::destroy();
                    return redirect('thanh-toan')->with('success', "Đơn hàng đã được hủy thành công");
                }
                return redirect('thanh-toan')->with('warning', "Email xác nhận đơn hàng đã hết hạn");
            }
        } else {
            Alert::html('Cảnh báo', "Không có sản phẩm nào trong giỏ hàng để xác nhận thanh toán. Vui lòng click<a href='http://localhost/unitop.vn/back-end/laravel/Admin_Unimart/san-pham'> vào đây </a>để mua hàng", 'error');
            return redirect('gio-hang');
        }
    }
}
