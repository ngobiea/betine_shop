<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/style/home.css">
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/cart.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


    <title>Shopping Cart</title>
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
        @if (count($products) > 0)
            <ul class="cart__item-list">
                @foreach ($products as $p)
                    <li class="cart__item">
                        <h1>{{ $p->title }}</h1>
                        <h2>Quantity: {{ $p->quantity }}</h2>
                        <form action="/deleteCartItem/{{$p->id}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn danger" type="submit">Delete</button>
                        </form>
                    </li>
                @endforeach
            </ul>
            <hr>
            <br>
            <div class="order-total">
            <p class="total">Total:  ${{$total}}</p>

                <form action="/addToOrder" method="POST">
                @csrf
                <input  type="hidden" id="total" name="total" value="{{$total}}">
                <button type="submit" class="btn">Order Now!</button>
            </form>
                {{-- <a class="btn" href="/check">Order Now!</a> --}}
            </div>
        @else
            <h1>No Products in Cart!</h1>
        @endif
    </main>

</body>

</html>
