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

        <div class="col-md-12">
            <!-- Vertically centered Modal -->
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal">
                <button type="button" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i>
                    Nouveau Objectif
                </button></a>
            <div class="modal fade" id="largeModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        @if (isset($objective))
                            <form action="{{ route('ObjectiveUpdate', $objective) }}" method="post">
                                @method('PUT')
                            @else
                                <form action="{{ route('ObjectiveStore') }}" method="post">
                        @endif
                        @csrf
                        <div class="modal-header">
                            @if (isset($objective))
                            <h5 class="modal-title">Modification de l'Objectif</h5>    
                            @else
                            <h5 class="modal-title">Ajout d'un nouveau Objectif</h5>
                            @endif
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="row mb-3">
                                <label for="inputNumber" class="col-sm-2 col-form-label">Objectif</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="label" value=" {{ isset($objective)  ? $objective->label : old('label')}} ">
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
                        <th scope="col">intitulé</th>
                        <th scope="col">Action</th>

                    </tr>
                </thead>
                <tbody>

                    @forelse ($objectives as $objective)
                        <tr>
                            <th scope="row" class="fs-3 fw-bold">{{ $objective->id }} </th>
                            <td class="fs-3 fw-bold">{{ $objective->label }} </td>
                            <td>
                                <a href="{{ route('edit.objective', $objective->id) }}" class="btn badge btn-info" data-bs-toggle="modal" data-bs-target="#largeModal">Editer</a>
                                <a href=" {{ route('delete.objective', $objective->id) }} "
                                    class="btn badge btn-danger">Supprimer</a>
                            </td>
                        </tr>
                    @empty
                        <h1></h1>
                    @endforelse
                </tbody>
            </table>
            <!-- End Table with hoverable rows -->
            {{ $objectives->links() }}
        </div>
    </div>
@endsection
