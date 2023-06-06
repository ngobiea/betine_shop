<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/style/home.css">
    <link rel="stylesheet" href="/style/account.css">
    
    <title>
        @if ($edit)
            Update Product
        @else
            Add Product
        @endif
    </title>
</head>

<body>
    <header>
        <div class="logo">
            <a href="{{ url('/seller') }}">
                <h1>BETINE</h1>

            </a>
        </div>
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
    <h1>Seller Add Product Form</h1>

    <div class="form-container">
        <form action="@if ($edit) /updateProduct @else /addProduct @endif" method="POST" enctype="multipart/form-data">
            @csrf
    
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="@if($edit){{$product->title}}@else{{''}}@endif" required>
            </div>
    
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price" value="@if($edit){{$product->price}}@else{{''}}@endif" required>
            </div>
    
            <div class="form-group">
                <label for="quantity">Quantity:</label><br>
                <input type="number" class="form-control" name="quantity" id="quantity" value="@if($edit){{$product->quantity}}@else{{''}}@endif" required>
            </div>
    
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" class="form-control" name="description">@if($edit){{ $product->description }}@else{{ '' }}@endif</textarea>
            </div>      
       
            @if ($edit)
                <input type="hidden" name="id" value="{{ $product->id }}">
            @endif
    
            <button type="submit" class="btn btn-primary">
                @if ($edit)
                    Update Product
                @else
                    Add Product
                @endif
            </button>
    
        </form>
    
    </div>
 


</body>

</html>
