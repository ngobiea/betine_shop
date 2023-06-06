<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/style/home.css">
    <link rel="stylesheet" href="/css/cart.css">
    <link rel="stylesheet" href="/css/main.css">

    <title>Buyers</title>
</head>

<body>
    <header>
        <div class="logo">
            <a href="/admin" class="logo"><h1>BETINE</h1></a>

        </div>
               
        {{-- <form>
            <div class="search-container">
                <input type="text" placeholder="Search for products..." id="searchTerm" onkeyup="search()">

                <button id="searchBtn" type="submit"><span class="material-icons">
                        search
                    </span></button>
            </div>
        </form> --}}
            <nav>
                <ul>
                    <li><a href="/admin">Products</a></li>
                    <li><a href="/getSeller">Sellers</a></li>
                    <li><a href="/getBuyer">Buyers</a></li>
                    <li style="@if(Session::get('isLogin'))display:none;@endif"><a href="{{url('/adminLogin')}}">Login</a></li>
                    <li class="main-header__item">
                        <form action="/adminLogout" method="post">
                            @csrf
                            <button @if (!Session::get('isLogin')) {{ 'hidden' }} @endif
                                type="submit">Logout</button>
    
                        </form>
                    </li>
                    
                    <li style="@if(Session::get('isLogin')){{'display:none;'}} @endif" ><a  href="{{url('/adminSignup')}}">Sign Up</a></li>
                </ul>
            </nav>
    </header>
    <p class="message" id="mesg">{{Session::get('message')}}</p>


    <main>
        @if (count($buyers) > 0)
            <ul class="cart__item-list">
                @foreach ($buyers as $s)
                    <li class="cart__item">
                        <h1>{{ $s->name }}</h1>
                        <h2>Total Orders: {{ $s->total_orders }}</h2>
                        <form action="/deleteBuyer/{{$s->id}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn danger" type="submit">Delete Buyer</button>
                        </form>
                    </li>
                @endforeach
            </ul>
            <hr>
        @else
            <h1>No Buyer in the system</h1>
        @endif
    </main>

</body>

</html>
