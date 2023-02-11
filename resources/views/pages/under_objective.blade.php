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

{{--
        <div class="col-md-5 card m-5 ">
            <a href=" {{route ('register') }} " class="btn btn-info">
                <i class="bi bi-plus-circle"></i>
                <button type="button" class="btn btn-info">Ajouter un Objectif</button>

            </a>
        </div> --}}

        <div class="col-md-12">
            <!-- Vertically centered Modal -->
            <a href="#" class="btn btn-primary float-end me-3" data-bs-toggle="modal" data-bs-target="#largeModal">
            <button type="button" class="btn btn-primary" >
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
                                            @foreach ($objectives as $objective )
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
            <th scope="col">Activités</th>
            <th scope="col">intitulé</th>
            <th scope="col">Position</th>
            <th scope="col">Age</th>
            <th scope="col">Start Date</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row">1</th>
            <td>Brandon Jacob</td>
            <td>Designer</td>
            <td>28</td>
            <td>2016-05-25</td>
        </tr>
        <tr>
            <th scope="row">2</th>
            <td>Bridie Kessler</td>
            <td>Developer</td>
            <td>35</td>
            <td>2014-12-05</td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td>Ashleigh Langosh</td>
            <td>Finance</td>
            <td>45</td>
            <td>2011-08-12</td>
        </tr>
        <tr>
            <th scope="row">4</th>
            <td>Angus Grady</td>
            <td>HR</td>
            <td>34</td>
            <td>2012-06-11</td>
        </tr>
        <tr>
            <th scope="row">5</th>
            <td>Raheem Lehner</td>
            <td>Dynamic Division Officer</td>
            <td>47</td>
            <td>2011-04-19</td>
        </tr>
    </tbody>
</table>
<!-- End Table with hoverable rows -->

        </div>
    </div>
@endsection
