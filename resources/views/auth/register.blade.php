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
    <link rel="stylesheet" href=" {{ asset('assets/css/register.css') }} ">
</head>

<body>
    <center>
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session()->get('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </center>
    <div class="wrapper rounded bg-white">

        <div class="h3">Inscription</div>
        <form action=" {{route('enregistrement')}} " method="post">
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
                    <div class="col-md-6 mt-md-0 mt-3 role">
                        <select id="sub" id="sub" name="service_id" required>
                            <option value="" selected hidden>Choix du Service</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}"> {{ $service->label }} </option>
                            @endforeach
                        </select>
                        @if ($errors->has('service_id'))
                            <span class="text-danger">{{ $errors->first('service_id') }}</span>
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
                        <input type="number" class="form-control" id="number" name="number" required>

                        @if ($errors->has('number'))
                            <span class="text-danger">{{ $errors->first('number') }}</span>
                        @endif
                    </div>

                    <div class="col-md-6 mt-md-0 mt-3 role">
                        <select id="sub" name="role_id" required>
                            <option value="" selected hidden>Choix du Role</option>
                            @foreach ($roles as $role )
                                <option value="{{ $role->id }}"> {{ $role->label }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class=" my-md-2 my-3"></div>

                <button type="submit">
                    <div class="btn btn-primary">Enregistrer</div>
                </button>
                <button type="button" class="btn btn-secondary" value="RETOUR"
                    onclick="history.back();">Fermer</button>
            </div>
        </form>
    </div>

</body>

</html>
