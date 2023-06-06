<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="/style/home.css">
    <title>BETINE SHOPPING</title>
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
                {{-- <input type="search" id="search" name="search" class="form-control rounded" placeholder="Search" /> --}}

                <input type="text" id="search" placeholder="Search products">


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

    
    <div class="col-md-8">
        <div class="card mycard m-2 p-2" style="width: 18rem;">

        </div>
    </div>
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
                            <a href="/getProduct/{{ $product->id }}" class="btn">Details</a>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>






    </main>

    <div id="result"></div>

  

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>



<script type="text/javascript">
    $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
</script>


<script>

    $(document).ready(function () {
        $('#search').on('keyup', function(){
            var value = $(this).val();
            $.ajax({
                type: "get",
                url: "/search",
                data: {'search':value},
                success: function (data) {
                    $('.card').html(data);
                }
            });


        });
        $("#search").focus(function(){
            $(".col-md-8").css("display", "block");
        });
        $("#search").blur(function(){
            $(".col-md-8").css("display", "none");
        });

    });

</script>
</body>

</html>
