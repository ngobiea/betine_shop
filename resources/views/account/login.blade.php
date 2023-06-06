<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/style/account.css">
    <title>Login</title>
</head>
<body>

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
    <div class="form-container">
        <form action="@if($user ==='seller'){{'/sellerLogin'}}@elseif($user==='buyer'){{url('/buyerLogin')}}@elseif($user==='admin'){{url('/adminLogin')}}@endif" method="POST">
            @csrf
              <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" id="email" name="email"  required>
              </div>
              <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <div class="btnLog">
                <button type="submit" class="btn btn-primary">Login</button>
            
                <span class="txt1">
                    Don’t have an account?
                    <a class="txt2" href="@if($user ==='seller'){{url('/sellerSignup')}}@elseif($user==='buyer'){{url('/buyerSignup')}}@elseif($user==='admin'){{url('/adminSignup')}}@endif">
                        Sign Up
                    </a>
                </span>
        
            </div>
      </form>
    </div>
</body>
</html>