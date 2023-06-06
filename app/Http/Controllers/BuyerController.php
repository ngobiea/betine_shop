<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;




class BuyerController extends Controller
{
    public function index()
    {
        $buyer = 'Buyer Home Page';

        $products = DB::table('products')->get();
        return view('homePage', compact('products', 'buyer'));
    }
    public function getSignup()
    {

        $user = 'buyer';
        return view('account/signup', compact('user'));
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
        $buyer = Buyer::where('email', '=', $request->email)->first();

        if ($buyer) {
            return redirect()->back()->withInput()->with('error', 'Email already exist');
        } else {
            DB::table('buyers')->insert([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);
            return redirect('/buyerLogin')->with('status', 'Buyer account created successfully! Please login.');
        }
    }

    public function getLogin()
    {
        $user = 'buyer';
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

        $buyer = Buyer::where('email', $email)->first();


        if ($buyer && Hash::check($password, $buyer->password)) {
            session()->put('name', $buyer->name);
            session()->put('email', $email);
            session()->put('userType', 'buyer');
            session()->put('isLogin', true);
            return redirect('/buyer');
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

    public function getProduct($id)
    {
        $product = DB::table('products')
            ->join('seller_products', 'products.id', '=', 'seller_products.product_id')
            ->join('sellers', 'sellers.email', '=', 'seller_products.seller_email')
            ->select('products.*', 'sellers.name')
            ->where('products.id', '=', $id)
            ->get()->first();
        if ($product) {
            return view('/buyer/product', compact('product'));
        }
    }
    public function getCart()
    {
        if (Session::get('isLogin')) {
            $products = DB::table('carts')
                ->join('cart_items', 'cart_items.cart_id', '=', 'carts.id')
                ->join('products','cart_items.product_id','=','products.id')
                ->select('products.title', 'products.price', 'cart_items.id', 'cart_items.quantity')
                ->where('carts.buyer_email', '=', Session::get('email'))
                ->get();

                // return $products;
        
            $total = $products->map(function ($product) {
                return $product->price * $product->quantity;
            })->sum();

            return view('/buyer/cart', compact('products', 'total'));
        } else {
            return redirect('/buyerLogin')->with('message', 'Please login to add items to your cart');
        }
    }

    public function addToCart(Request $request)
    {
        // Check if user is logged in
        if (Session::has('isLogin')) {
            // Get buyer's email from session
            $buyerEmail = Session::get('email');

            // Get product ID and quantity from request
            $productId = $request->input('id');
            $requestedQuantity = $request->input('quantity');

            // Get the available quantity of the product from products table
            $product = DB::table('products')->where('id', $productId)->first();
            $availableQuantity = $product->quantity;

            // Compare the requested quantity with the available quantity
            if ($requestedQuantity > $availableQuantity) {
                return redirect()->back()->with('error', 'The requested quantity is higher than the available quantity. Please enter a lower quantity');
            } else {
                // Check if product ID is already in the cart_items table
                $cart = DB::table('carts')
                    ->join('buyers','buyers.email','=','carts.buyer_email')
                    ->join('cart_items','cart_items.cart_id','=','carts.id')
                    ->select('cart_items.product_id')
                    ->where('cart_items.product_id','=', $productId)
                    ->get()->first();

                    // return $cart;
                    
                // If product ID is already in the cart_items table, update the quantity
                if ($cart) {
                    DB::table('cart_items')
                        ->where('product_id', $cart->product_id)
                        ->increment('quantity',$requestedQuantity);
                        // ->update(['quantity' => $cart->quantity + $requestedQuantity]);
                } else {
                    // Otherwise, create a new cart_item
                    $cart_id = DB::table('carts')->insertGetId([
                        'buyer_email' => $buyerEmail,
                    ]);
                    DB::table('cart_items')->insert([
                        'cart_id'=>$cart_id,
                        'product_id' => $productId,
                        'quantity' => $requestedQuantity
                    ]);
                }
                // Update the available quantity in products table
                DB::table('products')->where('id', $productId)->update(['quantity' => $availableQuantity - $requestedQuantity]);
                // // Redirect to cart view
                return redirect()->back()->with('status', 'Successfully Added To Cart');

                // return redirect('/getProduct/' . $productId);
            }
        } else {
            // Redirect to login page if user is not logged in
            return redirect('/buyerLogin')->with('message', 'Please login to add items to your cart');
        }
    }

    public function deleteCartItem($id)
    {
        if (Session::get('isLogin')) {
            $cartItem = DB::table('cart_items')->where('id', $id)->first();
            if (!$cartItem) {
                return redirect()->back()->with('error', 'Cart item not found!');
            }
            // Find the product 
            $product = DB::table('products')->where('id', $cartItem->product_id)->first();
            if (!$product) {
                return redirect()->back()->with('error', 'Product not found!');
            }
            // Increase the product's quantity 
            DB::table('products')->where('id', $product->id)->increment('quantity', $cartItem->quantity);
            // Delete the cart item
            DB::table('cart_items')->where('id', $id)->delete();

            return redirect('/getCart');
        } else {
            return redirect('/buyerLogin')->with('message', 'Please login to add items to your cart');
        }
    }
    public function addToOrder(Request $request)
    {
        // Check if user is logged in
        if (!Session::get('email')) {
            return redirect()->back()->with('error', 'You must be logged in to place an order.');
        }

        // Get all items in the cart for the current user
        $cart_items = DB::table('carts')
                    ->join('cart_items','cart_items.cart_id','=','carts.id')
                    ->select('cart_items.product_id','cart_items.quantity')
                    ->where('carts.buyer_email','=',Session::get('email'))
                    ->get();
                    // return $cart_items;
        
        // Create an order item for each item in the cart
        foreach ($cart_items as $item) {
            $product = DB::table('products')
                            ->where('id','=',$item->product_id)
                            ->select('title','price')
                            ->get()
                            ->first();
        
           $order_id = DB::table('orders')->insertGetId([
                'buyer_email' => Session::get('email'),
                'order_date' => now(),
            ]);
            DB::table('order_items')->insert([
                'order_id'=> $order_id,
                'product_title' => $product->title,
                'quantity' => $item->quantity,
                'total_price'=> $item->quantity * $product->price,
            ]);
        }
        // Delete all items from the cart_items table for the current user
        DB::table('cart_items')->truncate();
        return redirect('/getCart')->with('success', 'Order placed successfully.');
    }

    public function getOrder()
    {
        if (Session::get('isLogin')) {
            $products = DB::table('orders')
                ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->select('order_items.product_title','order_items.quantity','order_items.total_price', 'orders.order_date', )
                ->where('orders.buyer_email', '=', Session::get('email'))
                ->get();
            // return $products;
            // $total = $products->map(function ($product) {
            //     return $product->price * $product->quantity;
            // })->sum();

            // return $products;

            return view('/buyer/order', compact('products',));
        } else {
            return redirect('/buyerLogin')->with('message', 'Please login to add items to your cart');
        }
    }

   
}
