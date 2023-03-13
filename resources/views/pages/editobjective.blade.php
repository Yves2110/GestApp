@extends('layouts.home')
@section('content')
    <div class="container">
        <form action="{{ route('ObjectiveUpdate', $objective->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Modification de Objectif</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row mb-3">
                    <label for="inputNumber" class="col-sm-2 col-form-label">Objectif</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="label"
                            value=" {{$objective->label }} ">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">enregistrer</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </form>
    </div>
@endsection