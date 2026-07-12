<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connexion - GestApp</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/icon-uo.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">

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

    @if (Session::get('error'))
        <div class="container mt-3">
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        </div>
    @endif

    <div class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
        <div class="row justify-content-center w-100">
            <div class="col-md-6 col-lg-5">
                <div class="bg-white rounded p-4 shadow">
                    <div class="text-center mb-4">
                        <img src="{{ asset('assets/img/icon-uo.png') }}" alt="GestApp" width="120" class="mb-3">
                        <h1 class="main-heading mb-0">Connexion</h1>
                    </div>

                    <form action="{{ route('login.post') }}" method="post">
                        @csrf

                        <div class="form-data fw-bold">
                            <label for="email">Email</label>
                            <input id="email" type="email" placeholder="Email"
                                   class="form-control w-100 @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-data fw-bold">
                            <label for="password">Mot de passe</label>
                            <input id="password" type="password" placeholder="Mot de passe"
                                   class="form-control w-100 @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end mb-3">
                            <a class="text-decoration-none forgot-text" href="#">Mot de passe oublié ?</a>
                        </div>

                        <div class="signin-btn w-100">
                            <button class="btn btn-danger btn-block" type="submit">Connexion</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
