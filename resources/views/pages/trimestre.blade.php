@extends('layouts.home')
@section('content')
@foreach ($trimestres as $trimestre=1)
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
                    <a href="{{ asset ('docs/'.$guide->fichier) }}" class="text-white" target="_blank">Guide</a>
               </button> </td>
                <td> {{ $guide->created_at }}</td>
                <td> {{ $guide->update_at }}</td>
                <td> <a href="#" class="btn badge btn-info">Editer</a></td>
                <td> <a href=" {{route ('delete.guide',$guide->id) }} " class="btn badge btn-danger">Supprimer</a></td>
            </tr>
</tbody>
</table>
@endforeach
@endsection
