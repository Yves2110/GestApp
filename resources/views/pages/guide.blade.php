@extends('layouts.home')
@section('content')
    <div class="container-fluid">
        <div class="col-md-10">

            <center>
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
            </center>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Table with stripped rows</h5>

                    <!-- Table with stripped rows -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Fichier</th>
                                {{-- <th scope="col">Intutiler</th> --}}
                                <th scope="col">Date de mise en service</th>
                                <th scope="col">Date de mise a jour</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($guides as $guide)
                                <tr>
                                    <th scope="row"> {{ $guide->id }} </th>
                                    {{-- <td> {{ $guide->file }}</td> --}}
                                  <td> <button class="btn btn-lg btn-success ">
                                        <a href="{{ asset ('docs/'.$guide->file) }}" class="text-white" target="_blank">Guide</a>
                                   </button> </td>
                                    <td> {{ $guide->created_at }}</td>
                                    <td> {{ $guide->update_at }}</td>
                                    <td> <a href="#" class="btn badge btn-info">Editer</a></td>
                                    <td> <a href=" {{route ('delete.guide',$guide->id) }} " class="btn badge btn-danger">Supprimer</a></td>
                                </tr>
                 </tbody>
                    </table>
                    <!-- End Table with stripped rows -->

                @empty
                    <h1 class="text-dark">Aucun</h1>
                    @endforelse
                </div>
            </div>

        </div>
        <div class="col-md-2">

            <!-- Vertically centered Modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#verticalycentered">
                Nouveau
            </button>
            <div class="modal fade" id="verticalycentered" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('guide') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Ajout d'un nouveau guide</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <div class="row mb-3">
                                    <label for="inputNumber" class="col-sm-2 col-form-label">Fichier</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" type="file" id="formFile" name="fichier">
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">enregistrer</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- End Vertically centered Modal-->


            {{--
            <div class="btn btn-secondary badge-outline-info">
                Nouveau
            </div> --}}
        </div>
    </div>
@endsection
