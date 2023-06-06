<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/style/seller.css">

    <title>Sign Up</title>
</head>
<body>
    <form action="/signup" method="POST">
        @csrf
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name"><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br>
        <label for="phone">Phone:</label><br>
        <input type="text" id="phone" name="phone"><br><br>
        <label for="password" id="password" name="password">Password</label><br>
        <input type="password" name="password" id="password"><br><br>
        <button type="submit">Save Seller</button>
    </form>
    
</body>
</html>