@extends('layouts.home')
@section('content')

<center>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
</center>
    <div class="titre">
        <h1>Activités</h1>
        <!-- Large Modal -->
        <button type="button" class="btn btn-primary text-light float-end" data-bs-toggle="modal" data-bs-target="#largeModal">
            Ajouter
        </button>

        <div class="modal fade" id="largeModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajout d'activité</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form action="{{ route('ActivitiesStore') }}" method="post" >
                            @csrf
                            <div class="container">
                                <header>Formulaire</header>
                                <div class="row ">
                                    <div class="col-6 my-2">
                                        <select id="inputState" class="form-select" name="objective_id">
                                            <option value="" selected hidden>Choix de l'objectif global</option>
                                            @foreach ($objectives as $objective)
                                                <option value="{{ $objective->id }}"> {{ $objective->label }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <select id="inputState" class="form-select" name="under_objective_id">
                                            <option value="" selected hidden>Choix de sous objectif</option>
                                            @foreach ($underobjectives as $underobjective)
                                                <option value="{{ $underobjective->id }}"> {{ $underobjective->label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <select id="inputState" class="form-select" name="service_id">
                                            <option value="" selected hidden>Service appartenant</option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}"> {{ $service->label }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Intitulé" name="label">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="indicateur" name="indicator">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Cible" name="target">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" placeholder="Montant" name="price">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Source de financement" name="source_of_funding">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Structure responsable" name="structure">
                                    </div>

                                    <div class="col-md-6">
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Commentaire" name="commentary"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- End Large Modal-->
        <div class="container-fluid ">
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
    @endsection