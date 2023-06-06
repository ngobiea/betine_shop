<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/style/product.css">
    <link rel="stylesheet" href="/style/home.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>{{ $product->title }}</title>
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
                        <button @if (!Session::get('isLogin')) {{ 'hidden' }} @endif
                            type="submit">Logout</button>

                    </form>
                </li>

                <li style="@if (Session::get('isLogin')) {{ 'display:none;' }} @endif"><a
                        href="{{ url('/buyerSignup') }}">Sign Up</a></li>
            </ul>
        </nav>
    </header>
    <main>
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        <div class="product">
            <div class="image-container">
                <img src="/images/{{ $product->imageUrl }}" width="80%" class="mb-4">
            </div>
            <div class="text-container">
                <p class="seller" style="color: #ff7d1b;">{{ $product->name }}</p>
                <h1 class="title" ><b>{{ $product->title }}</b> </h1>
                <p class="description">{{ $product->description }}</p>
                <h3 class="price" style="display: inline;"><b>${{ $product->price }}</b></h3>
            
                <p class="quantity">Quantity:<b>{{ $product->quantity }}</b></p>
                
                <a href=""></a>
                <form action="/addToCart" method="POST">
                    @csrf
                    <label for="userQuanity">Enter Quantity</label>
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <input type="number" id="quantity" name="quantity"><br>
                    <button class="btn sub" type="submit">Add to Cart</button>
                    <input type="hidden" name="productId" value="{{ $product->id }}" />
                </form>
            </div>
        </div>
    </main>
</body>

</html>
