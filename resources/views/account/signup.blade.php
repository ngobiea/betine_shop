<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/style/account.css">
    <title>Sign Up</title>
</head>
<body>  
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    <div class="form-container">

        <form method="POST" action="@if ($user == 'buyer') {{ '/buyerSignup' }} @elseif($user == 'seller'){{ '/sellerSignup' }} @elseif($user == 'admin'){{ '/adminSignup' }} @else{{ '/' }} @endif">

            @csrf
            <!-- form fields go here -->
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email"  required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="btnLog">
                <button type="submit" class="btn btn-primary">Sign up</button>
                @if (session('error'))
                    <a class="txt2" href="@if($user ==='seller'){{url('/sellerLogin')}}@elseif($user==='buyer'){{url('/buyerLogin')}}@elseif($user==='admin'){{url('/adminLogin')}}@endif">
                        Login Here
                    </a>
            @endif
            </div>
        </form>
    </div>
</body>

</html>
