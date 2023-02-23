@extends('layouts.home')
@section('content')
<center>
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session()->get('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</center>
    <div class="container d-flex">


        <div class="col-md-5 card m-5 ">
            <a href=" {{route ('register') }} " class="btn btn-info">
                <i class="bi bi-plus-circle"></i>
                <button type="button" class="btn btn-info">Ajouter un membre</button>

            </a>
        </div>

        <div class="col-md-5 card m-5">
            <!-- Vertically centered Modal -->
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#verticalycentered">
            <button type="button" class="btn btn-primary" >
                <i class="bi bi-plus-circle"></i>
                Nouveau service
            </button></a>
            <div class="modal fade" id="verticalycentered" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('ajoutservice') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Ajout d'un nouveau service</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <div class="row mb-3">
                                    <label for="inputNumber" class="col-sm-2 col-form-label">service</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="texte" name="service">
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">enregistrer</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- End Vertically centered Modal-->
        </div>
    </div>
@endsection
