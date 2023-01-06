<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">
    <link href=" {{ asset('assets/img/logo1.png') }} "rel="icon">
    <link href=" {{ asset('assets/img/logo1.png') }} "rel="apple-touch-icon">
</head>

<body>
    <style>
        /* Importing fonts from Google */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

        /* Reseting */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(50deg, #fdfcfc, #a7caf1);
            min-height: 100vh;
        }

        body::-webkit-scrollbar {
            display: none;
        }

        .wrapper {
            max-width: 800px;
            margin: 50px 25% auto;
            padding: 30px 45px;
            box-shadow: 5px 25px 35px #3535356b;
        }

        .wrapper label {
            display: block;
            padding-bottom: 0.2rem;
        }

        .wrapper .form .row {
            padding: 0.6rem 0;
        }

        .wrapper .form .row .form-control {
            box-shadow: none;
        }

        .wrapper .form .option {
            position: relative;
            padding-left: 20px;
            cursor: pointer;
        }


        .wrapper .form .option input {
            opacity: 0;
        }

        .wrapper .form .checkmark {
            position: absolute;
            top: 1px;
            left: 0;
            height: 20px;
            width: 20px;
            border: 1px solid #bbb;
            border-radius: 50%;
        }

        .wrapper .form .option input:checked~.checkmark:after {
            display: block;
        }

        .wrapper .form .option:hover .checkmark {
            background: #f3f3f3;
        }

        .wrapper .form .option .checkmark:after {
            content: "";
            width: 10px;
            height: 10px;
            display: block;
            background: linear-gradient(45deg, #ce1e53, #8f00c7);
            position: absolute;
            top: 50%;
            left: 50%;
            border-radius: 50%;
            transform: translate(-50%, -50%) scale(0);
            transition: 300ms ease-in-out 0s;
        }

        .wrapper .form .option input[type="radio"]:checked~.checkmark {
            background: #fff;
            transition: 300ms ease-in-out 0s;
        }

        .wrapper .form .option input[type="radio"]:checked~.checkmark:after {
            transform: translate(-50%, -50%) scale(1);
        }

        #sub {
            display: block;
            width: 100%;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            color: #333;
        }

        #sub:focus {
            outline: none;
        }

        @media(max-width: 768.5px) {
            .wrapper {
                margin: 30px;
            }

            .wrapper .form .row {
                padding: 0;
            }
        }

        @media(max-width: 400px) {
            .wrapper {
                padding: 25px;
                margin: 20px;
            }
        }
    </style>

    <div class="wrapper rounded bg-white">

        <div class="h3">Inscription</div>
        <form action="{{ route('register.post') }}" method="post">
            @csrf
            <div class="form">
                <div class="row">
                    <div class="col-md-6 mt-md-0 mt-3">
                        <label>Nom</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" required>

                        @if ($errors->has('firstname'))
                            <span class="text-danger">{{ $errors->first('firstname') }}</span>
                        @endif

                    </div>
                    <div class="col-md-6 mt-md-0 mt-3">
                        <label>Prénom</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" required>

                        @if ($errors->has('lastname'))
                            <span class="text-danger">{{ $errors->first('lastname') }}</span>
                        @endif

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mt-md-0 mt-3">
                        <label>Date de naissance</label>
                        <input type="date" class="form-control" id="birthday" name="birthday" required>

                        @if ($errors->has('birthday'))
                            <span class="text-danger">{{ $errors->first('birthday') }}</span>
                        @endif

                    </div>

                    <div class="col-md-6 mt-md-0 mt-3">
                        <label>Services</label>
                        <select id="sub" id="sub" name="sub" required>
                            <option value="" selected hidden>Choix Option</option>
                            <option value="Maths">DSI</option>
                            <option value="Science">DEPS</option>
                            <option value="Social">DAF</option>
                            <option value="Hindi">UFR/SDS</option>
                        </select>

                        @if ($errors->has('sub'))
                            <span class="text-danger">{{ $errors->first('sub') }}</span>
                        @endif

                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6 mt-md-0 mt-3">
                        <label>Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>

                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif

                    </div>

                    <div class="col-md-6 mt-md-0 mt-3">
                        <label>Numéro</label>
                        <input type="tel" class="form-control" id="tel" name="tel" required>

                        @if ($errors->has('tel'))
                            <span class="text-danger">{{ $errors->first('tel') }}</span>
                        @endif
                    </div>
                </div>

                <div class=" my-md-2 my-3">

                </div>
                <button type="submit">
                    <div class="btn btn-primary">Enregistrer</div>
                </button>
                <button type="button" class="btn btn-secondary" value="RETOUR" onclick="history.back();">Fermer</button>
            </div>
        </form>
    </div>

</body>

</html>
