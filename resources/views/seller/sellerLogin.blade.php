<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/style/seller.css">
    <title>Login</title>
</head>
<body>
    <form action="/login" method="POST">
        @csrf
       
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br>
        <label for="password">password:</label><br>
        <input type="password" id="password" name="password"><br><br>
        <button type="submit">Save Seller</button>
    </form>
    
</body>
</html>