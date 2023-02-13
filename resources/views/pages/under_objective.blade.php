@extends('layouts.home')
@section('content')
    <center>
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
    </center>
    <div class="container d-flex">
        <div class="col-md-12">
            <!-- Vertically centered Modal -->
            <a href="#" class="btn btn-primary float-end me-3" data-bs-toggle="modal" data-bs-target="#largeModal">
                <button type="button" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i>
                    Nouveau Sous Objectif
                </button></a>
            <div class="modal fade" id="largeModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('UnderObjectiveStore') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Ajout d'un Sous Objectif</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <div class="row mb-3">
                                    <label for="inputNumber" class="col-sm-2 col-form-label">Sous Objectif</label>
                                    <div class="col-md-10 my-3">
                                        <input class="form-control fs-5" type="text" name="label">
                                    </div>
                                    <label for="inputNumber" class="col-sm-2 col-form-label">Objectif</label>
                                    <div class="col-md-10">
                                        <select id="floatingSelect" name="objective_id" class="form-select fs-5" required>
                                            <option value="" selected hidden>Choix de l'objectif global</option>
                                            @foreach ($objectives as $objective)
                                                <option value="{{ $objective->id }}"> {{ $objective->label }} </option>
                                            @endforeach
                                        </select>
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

            <!-- Table with hoverable rows -->
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">N°</th>
                        <th scope="col">N° Objectif</th>
                        <th scope="col">Intitulé</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($under_objectives as $under_objective)
                        <tr>
                            <th scope="row"> {{ $under_objective->id }} </th>
                            <td> {{ $under_objective->objective_id }}</td>
                            <td class="fs-3 fw-bold"> {{ $under_objective->label }}</td>
                            <td>
                                <a href="#" class="btn badge btn-info">Editer</a>
                                <a href=" {{ route('delete.under_objective', $under_objective->id) }} "
                                    class="btn badge btn-danger">Supprimer</a>
                            </td>
                        </tr>
                    @empty
                    <h1>Pas de Sous objectif enregistré</h1>
                    @endforelse


                </tbody>
            </table>
            <!-- End Table with hoverable rows -->

        </div>
    </div>
@endsection
