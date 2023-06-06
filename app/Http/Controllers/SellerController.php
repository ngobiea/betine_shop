<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class SellerController extends Controller
{

    public function index()
    {
        $seller = 'Seller Home Page';
        $products = DB::table('products')
            ->join('seller_products', 'products.id', '=', 'seller_products.product_id')
            ->join('sellers', 'sellers.email', '=', 'seller_products.seller_email')
            ->select('products.*')
            ->where('sellers.email', '=', Session::get('email'))
            ->get();
        return view('seller/sellerHomePage', compact('seller', 'products'));
    }
    public function getSignup()
    {

        $user = 'seller';
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
        $seller = Seller::where('email', '=', $request->email)->first();

        if ($seller) {
            return redirect()->back()->withInput()->with('error', 'Email already exist');
        } else {
            DB::table('sellers')->insert([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);
            return redirect('/sellerLogin')->with('status', 'Seller account created successfully! Please login.');
        }
    }
    public function getLogin()
    {
        $user = 'seller';
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

        $seller = Seller::where('email', $email)->first();


        if ($seller && Hash::check($password, $seller->password)) {
            session()->put('name', $seller->name);
            session()->put('email', $email);
            session()->put('userType', 'seller');
            session()->put('isLogin', true);
            return redirect('/seller');
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
    public function getAddProduct()
    {
        $edit = false;
        return view('/seller/addProduct', compact('edit'));
    }

    public function postAddProduct(Request $request)
    {
        // Get the email from the current session
        $email = Session::get('email');
        // Validate the request data
        $valid = $request->validate([
            'title' => 'required',
            'price' => 'required|integer',
            'description' => 'required',
            'image' => 'required|mimes:jpg,png,jpeg|max:5048',
            'quantity' => 'required|integer',
        ]);
        // Check if the validation passed
        if ($valid) {
            // Create a unique image name
            $imageName = $request->title . time() . '.' . $request->image->extension();
            // Move the image to the public/images folder
            $request->image->move(public_path('images'), $imageName);
            $id = DB::table('products')->insertGetId([
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'imageUrl' => $imageName,
                'price' => $request->get('price'),
                'quantity' => $request->get('quantity'),
            ]);
            DB::table('seller_products')->insert([
                'seller_email' => $email,
                'product_id' => $id
            ]);

            return redirect('/seller');
        } else {
            return redirect()->back()->withInput()->with('error', 'All fields are required');
        }
    }
    public function getUpdateProduct($id)
    {
        $product = DB::table('products')->where('id', $id)->first();
        $edit = true;
        return view('seller/addProduct', compact('product', 'edit'));
    }
    public function patchUpdateProduct(Request $request)
    {
        if (Session::get('isLogin')) {
            DB::table('products')
                ->where('id', $request->id)
                ->update([
                    'title' => $request->title,
                    'price' => $request->price,
                    'quantity' => $request->quantity,
                    'description' => $request->description
                ]);
            return redirect('/seller');
        } else {
            return redirect('/sellerLogin');
        }
    }
    public function deleteProduct($id)
    {
        if (Session::get('isLogin')) {
            DB::table('products')->where('id', '=', $id)->delete();
            Session::flash('message', 'Successfully delete product');
            return redirect('/seller');
        } else {

            return redirect('/sellerLogin');
        }
    }
}
