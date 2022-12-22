<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

    <style>
        body {
            background-color: #e1e5eb
        }

        .height-100 {
            height: 500px
        }

        .sidebar {
            position: relative;
            overflow: hidden;
            opacity: 1;
        }

        .form-data {
            width: 100%;
            margin-bottom: 10px
        }

        .main-heading {
            font-size: 30px
        }

        .form-data label {
            font-size: 13px;
            margin-left: 2px
        }

        .form-data input {
            height: 50px;
            border: 2px solid #eee;
            transition: all 1s;
            border-radius: 5px
        }

        .form-data input:focus {
            outline: none;
            border: 2px solid #000;
            box-shadow: none
        }

        .social-list {
            list-style: none;
            display: flex
        }

        .social-list li {
            margin: 5px;
            background-color: #b7c2d4;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 5px;
            transition: all 0.5s;
            cursor: pointer;
            color: #fff
        }

        .forgot-text {
            color: red
        }

        .social-list li:nth-child(1) {
            background-color: #1828fe
        }

        .social-list li:nth-child(2) {
            background-color: red
        }

        .social-list li:nth-child(3) {
            background-color: #1828fe
        }

        .signin-btn .btn {
            width: 100%;
            height: 46px;
            margin-top: 10px
        }
    </style>
    <div class="container">
        <div class="row g-0 mt-5 mb-5 height-100">
            <div class="alert m-0">
                @if (Session::get('error'))
                    <div class="alert alert-danger fs-2">
                        {{ Session::get('error') }}
                    </div>
                @endif
            </div>

            <div class="m-auto col-md-6">
                <div class="bg-white p-4 h-100">
                    <div class="p-3 d-flex justify-content-center flex-column align-items-center"> <span
                            class="main-heading">Connexion</span>
                        <form action="{{ route('login.post') }}" method="post">
                            @csrf
                            <ul class="social-list mt-3 ">
                                <img src="{{ asset('assets/img/icon-uo.png') }}" alt="" width="200px">
                            </ul>
                            <div class="form-data fw-bold"> <label>Email</label>
                                <input type="email" placeholder="Email" class="form-control w-100 @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email">

                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="form-data  fw-bold"> <label>Mot de passe</label>
                                <input type="password" placeholder="Mot de passe"
                                    class="form-control w-100 @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">

                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between w-100 align-items-center">

                                <div class="ms-3"><a class="text-decoration-none forgot-text" href="#">Mot de
                                        passe oublié?</a></div>

                            </div>
                            <div class="signin-btn w-100 mt-2">
                                <button class="btn btn-danger btn-block" type="submit">Connexion</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
