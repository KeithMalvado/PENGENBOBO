<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User | EvenTara</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Poppins', sans-serif;
        }
        .container {
            display: flex;
            min-height: 100vh;
        }
        .left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
        }
        .left img {
            max-width: 80%;
            height: auto;
        }
        .right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        .form-wrapper {
            width: 100%;
            max-width: 400px;
        }
        .form-wrapper h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group input {
            width: 100%;
            padding: 14px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: 'Poppins', sans-serif;
        }
        .form-group input[type="password"] {
            letter-spacing: 1px;
        }
        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: #F4A825;
            border: none;
            color: #fff;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.2s;
            font-family: 'Poppins', sans-serif;
        }
        .btn-submit:hover {
            background-color: #d9931f;
        }

        .alert-danger {
            background-color: #f8d7da; 
            color: #721c24;
            border: 1px solid #f5c6cb; 
            padding: 10px 15px; 
            margin-bottom: 20px; 
            border-radius: 4px; 
            text-align: center; 
            font-size: 14px;
        }
        .alert-danger ul {
            list-style: none; 
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left">
            <img src="{{ asset('images/registeruser.png') }}" alt="Login User">
        </div>

        <div class="right">
            <div class="form-wrapper">
                <h3>Login Account</h3>
                <form action="{{ route('user.login.submit') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                        <div class="alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn-submit">Login →</button>

                    <p style="text-align: center; margin-top: 15px; font-size: 14px;">
                        Belum punya akun? <a href="{{ route('user.register') }}" style="color: #F4A825; text-decoration: none;">Register</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>