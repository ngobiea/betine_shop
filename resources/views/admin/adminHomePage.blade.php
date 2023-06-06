<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="/style/home.css">
    <style>
        .btn.danger {
            color: red;
            border-color: red;
        }

        .btn.danger:hover,
        .btn.danger:active {
            background: red;
            color: white;
        }
    </style>
    <title>Admin DashBoard</title>
</head>

<body>
    <header>
        <div class="logo">
            <a href="/admin" class="logo">
                <h1>BETINE</h1>
            </a>

        </div>
        <form>
            <div class="search-container">
                <input type="text" placeholder="Search for products..." id="searchTerm" onkeyup="search()">
                <button id="searchBtn" type="submit"><span class="material-icons">
                        search
                    </span></button>
            </div>
        </form>
        <nav>
            <ul>
                <li><a href="/admin">Products</a></li>
                <li><a href="/getSeller">Sellers</a></li>
                <li><a href="/getBuyer">Buyers</a></li>
                <li style="@if (Session::get('isLogin')) display:none; @endif"><a
                        href="{{ url('/adminLogin') }}">Login</a></li>

                <li class="main-header__item">
                    <form action="/adminLogout" method="post">
                        @csrf
                        <button @if (!Session::get('isLogin')) {{ 'hidden' }} @endif
                            type="submit">Logout</button>
                    </form>
                </li>
                <li style="@if (Session::get('isLogin')) {{ 'display:none;' }} @endif"><a
                        href="{{ url('/adminSignup') }}">Sign Up</a></li>
            </ul>
        </nav>
    </header>
    <h1>{{ $admin }}</h1>
    <main>
        <div class="product-grid">
            @foreach ($products as $product)
                <div class="product">
                    <img src="{{ 'images/' . $product->imageUrl }}" alt="Product Image" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title">{{ $product->title }}</h3>
                        <div class="product-price">${{ $product->price }}</div>
                        <p class="product-description">{{ $product->description }}</p>
                        <div class="product-actions">
                            <form action="/adminDeleteProduct/{{ $product->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn danger" type="submit">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </main>

</body>

</html>
