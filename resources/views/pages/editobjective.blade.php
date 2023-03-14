@extends('layouts.home')
@section('content')
    <div class="container">
        <form action="{{ route('ObjectiveUpdate', $objective->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="modal-header mb-5">
                <h5 class="modal-title">Modification de l'Objectif</h5>
                
            </div>
            <div class="modal-body mt-5">

                <div class="row mb-3">
                    <label for="inputNumber" class="col-sm-2 col-form-label">Objectif</label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="label"
                            value=" {{$objective->label }} ">
                    </div>
                </div>

            </div>
            <div class="modal-footer my-2">
                <button type="submit" class="btn btn-primary">Modifier</button>
            </div>
        </form>
    </div>
@endsection