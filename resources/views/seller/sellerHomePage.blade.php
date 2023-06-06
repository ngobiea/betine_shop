<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
    rel="stylesheet">
    <link rel="stylesheet" href="/style/seller.css">
    <title>Seller DashBoard</title>
</head>

<body>
    <header>
        <div class="logo">
            <a href="/seller" class="logo">
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
            <ul class="nav-items">
                <li class="nav-item">
                    <a href="@if(Session::get('isLogin')){{url('/seller')}}@else{{url('/sellerLogin')}}@endif" class="sellers">Products</a>
                </li>
                <li class="nav-item">
                    <a href="@if(Session::get('isLogin')){{url('/addProduct')}}@else{{url('/sellerLogin')}}@endif" class="buyers">Add Product</a>
                </li>
                <li class="main-header__item">
                    <form action="/sellerLogout" method="post">
                        @csrf
                    <button @if(!Session::get('isLogin')){{'hidden'}} @endif type="submit">Logout</button>

                    </form>
                </li>
                
             

                <li style="@if(Session::get('isLogin')){{'display:none;'}} @endif" ><a  href="{{url('/sellerSignup')}}">Sign Up</a></li>
           
                
            </ul>


        </nav>

    </header>
    <h1>{{ $seller }}</h1>
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
                            <a href="/updateProduct/{{ $product->id }}" class="btn">Update</a>
                            <form action="/deleteProduct/{{ $product->id }}" method="POST">
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
