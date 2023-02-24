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
    <div class="titre">
        <h1>Activités</h1>
        <!-- Large Modal -->
        <button type="button" class="btn btn-primary text-light float-end" data-bs-toggle="modal"
            data-bs-target="#largeModal">
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

                        <form action="{{ route('ActivitiesStore') }}" method="post">
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
                                    <div class="col-6 my-2">
                                        <select id="inputState" class="form-select" name="under_objective_id">
                                            <option value="" selected hidden>Choix de sous objectif</option>
                                            @foreach ($underobjectives as $underobjective)
                                                <option value="{{ $underobjective->id }}"> {{ $underobjective->label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 my-2">
                                        <select id="inputState" class="form-select" name="service_id">
                                            <option value="" selected hidden>Service appartenant</option>
                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}"> {{ $service->label }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 my-2">
                                        <select id="inputState" class="form-select" name="periode_id">
                                            <option value="" selected hidden>Trimestre</option>
                                            @foreach ($trimestres as $trimestre)
                                                <option value="{{ $trimestre->id }}"> {{ $trimestre->label }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 my-2">
                                        <input type="text" class="form-control" placeholder="Intitulé" name="label">
                                    </div>
                                    <div class="col-md-6 my-2">
                                        <input type="text" class="form-control" placeholder="indicateur"
                                            name="indicator">
                                    </div>
                                    <div class="col-md-6 my-2">
                                        <input type="text" class="form-control" placeholder="Cible" name="target">
                                    </div>
                                    <div class="col-md-6 my-2">
                                        <input type="number" class="form-control" placeholder="Montant" name="price">
                                    </div>
                                    <div class="col-md-6 my-2">
                                        <input type="text" class="form-control" placeholder="Source de financement"
                                            name="source_of_funding">
                                    </div>
                                    <div class="col-md-6 my-2">
                                        <input type="text" class="form-control" placeholder="Structure responsable"
                                            name="structure">
                                    </div>

                                    <div class="col-md-6 my-2">
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Commentaire"
                                            name="commentary"></textarea>
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
                        <th scope="col">N°</th>
                        <th scope="col">N° d'objectif</th>
                        <th scope="col">N° de sous objectif</th>
                        <th scope="col">Intitulé</th>
                        <th scope="col">Périodes</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activities as $activitie)
                        <tr>
                            <th scope="row"> {{ $activitie->id }} </th>
                            <td> {{ $activitie->objective->id }}</td>
                            <td> {{ $activitie->under_objective->id }}</td>
                            <td class="fs-5 fw-bold"> {{ $activitie->label }}</td>
                            <td class="fs-5 fw-bold"> {{ $activitie->periode->label }}</td>
                            <td>
                                @if ($activitie->status == 0)
                                    <div class="btn btn-danger"> Non valide</div>
                                @else
                                    <div class="btn btn-success">valide</div>
                                @endif
                            </td>
                            <td>
                                <a href="#" class="btn badge btn-info">Editer</a>
                                <a href=" {{ route('delete.objective', $objective->id) }} "
                                    class="btn badge btn-danger">Supprimer</a>
                            </td>
                        </tr>
                    @empty
                        <h1>Pas d'activités</h1>
                    @endforelse
                </tbody>
            </table>
            <!-- End Table with hoverable rows -->
            {{ $activities->links() }}
        </div>
    @endsection
