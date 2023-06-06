<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        if(Session::get('isLogin')){
            $admin = 'Admin';

            $products = DB::table('products')->get();
            return view('/admin/adminHomePage', compact('products', 'admin'));
        }else{
            return redirect('/adminLogin');
        }

    }

    public function getSignup()
    {
        $user = 'admin';

        return view('account/signup', compact('user',));
    }

    public function postSignup(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if (empty($validatedData)) {
            return redirect()->back()->withInput()->with('error', 'All fields are required');
        }
        $admin = Admin::where('email', '=', $request->email)->first();
        if ($admin) {
            return redirect()->back()->withInput()->with('error', 'Email already exist');
        } else {
            DB::table('admins')->insert([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);
            return redirect('/adminLogin')->with('status', 'Admin account created successfully! Please login.');
        }
    }

    public function getLogin()
    {
        $user = 'admin';
        return view('account/login', compact('user'));
    }
    public function postLogin(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $admin = Admin::where('email', $email)->first();


        if ($admin && Hash::check($password, $admin->password)) {
            session()->put('name', $admin->name);
            session()->put('email', $email);
            session()->put('userType', 'admin');
            session()->put('isLogin', true);
            return redirect('/admin');
        } else {
            session()->flash('error', 'Email or password is incorrect');
            return redirect()->back();
        }
    }
    public function logout(Request $request)
    {
        $request->session()->invalidate();
        return redirect('/');
    }

    public function getSeller()
    {
        if (Session::get('isLogin')) {
            $sellers = DB::table('sellers')
                ->select('sellers.*', DB::raw('count(seller_products.product_id) as total_products'))
                ->leftJoin('seller_products', 'sellers.email', '=', 'seller_products.seller_email')
                ->groupBy('sellers.email')
                ->get();
            return view('/admin/seller', compact('sellers'));
        } else {
            return redirect('/adminLogin');
        }
    }
    
    public function deleteSeller($id)
    {
        if (Session::get('email')) {
            $deleted = DB::table('sellers')->where('id', $id)->delete();
            if ($deleted) {
                return redirect('/getSeller')->with(['status' => 'Seller deleted successfully'], 200);
            }
            return redirect('/getSeller')->with(['error' => 'Seller not found'], 404);
        } else {
            return redirect('/adminLogin');
        }
    }

    public function getBuyer()
    {
        if(Session::get('isLogin')){

            $buyers = DB::table('buyers')->select('buyers.*', DB::raw('count(order_items.id) as total_orders'))
            ->leftJoin('orders', 'buyers.email', '=', 'orders.buyer_email')
            ->leftJoin('order_items','order_items.order_id','=','orders.id')
            ->groupBy('buyers.email')
            ->get();
             return view('/admin/buyer',compact('buyers'));
        }else{
            return redirect('/adminLogin');
        }
    
    }
    
    public function deleteBuyer($id)
    {
        if (Session::get('email')) {
            $deleted = DB::table('buyers')->where('id', $id)->delete();
            if ($deleted) {
                return redirect('/getBuyer')->with(['status' => 'Buyer deleted successfully'], 200);
            }
            return redirect('/getBuyer')->with(['error' => 'Buyer not found'], 404);
        } else {
            return redirect('/adminLogin');
        }
    }
    public function deleteProduct($id)
    {
        if(Session::get('isLogin')){
            DB::table('products')->where('id', '=', $id)->delete();
            Session::flash('message', 'Successfully delete product');
            return redirect('/admin');

        }else{

            return redirect('/adminLogin');

        }
      
    }
}
