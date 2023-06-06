<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/style/home.css">
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/orders.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <title>Orders</title>
</head>
<body>
    <header>
        <div class="logo">
            <a href="/" class="logo">
                <h1>BETINE</h1>
            </a>
        </div>
        <form>
            <div class="search-container">
                <input type="text" id="searchTerm" name="searchTerm" placeholder="Search for products...">
                <button id="searchBtn" type="submit"><span class="material-icons">
                    search
                    </span></button>
            </div>
        </form>

        <nav>
            <ul>
                <li><a
                        href="@if (Session::get('isLogin')) {{ url('/getCart') }}@else{{ url('/buyerLogin') }} @endif">Cart</a>
                </li>
                <li><a
                        href="@if (Session::get('isLogin')) {{ url('/getOrder') }}@else{{ url('/buyerLogin') }} @endif">Orders</a>
                </li>
                <li><a href="{{ url('/sellerLogin') }}">Sell With Us</a></li>
                <li style="@if (Session::get('isLogin')) display:none; @endif"><a
                        href="{{ url('/buyerLogin') }}">Login</a></li>
              
                <li class="main-header__item">
                    <form action="/buyerLogout" method="post">
                        @csrf
                    <button @if(!Session::get('isLogin')){{'hidden'}} @endif type="submit">Logout</button>

                    </form>
                </li>
                <li style="@if (Session::get('isLogin')) {{ 'display:none;' }} @endif"><a
                        href="{{ url('/buyerSignup') }}">Sign Up</a></li>
            </ul>
        </nav>
    </header>
    <p class="message" id="mesg">{{Session::get('message')}}</p>
    <main>
        @if (count($products) <= 0)
            <h1>Nothing there!</h1>
        @else
            <ul class="orders">
                @foreach ($products as $product)
                    <li class="orders__item">
                        <h1>Order - # {{ $product->order_date }} </h1>
                        <ul class="orders__products">
                            {{-- @foreach ($products as $p) --}}
                                <li class="orders__products-item">
                                    <div class="title">{{ $product->product_title }} ({{ $product->quantity }})</div>
                                    <div class="total">${{$product->total_price}}</div>
                                    </li>
                                
                            {{-- @endforeach --}}
                        </ul>
                    </li>
                @endforeach
            </ul>
        @endif
    </main>
    
</body>
</html>