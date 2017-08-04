@extends('layouts.app')


@section('content')

    <div class="container white-bg">

        <div class="row">

            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <h3>Lista este Corecta ?</h3>

                <form action="{{ route('validate') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                    <button type="submit" class="btn btn-success import" name="import" value="replace">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> Da, importa lista noua si sterge lista
                        veche
                    </button>
                    <button type="submit" class="btn btn-warning import" name="import" value="append">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> Da, adauga la lista veche
                    </button>
                </form>

                <button type="button" class="btn btn-danger import" data-toggle="modal" href="#import"
                        style="margin-top:0.5em">
                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Nu, Incarc alt fisier
                </button>
            </div>


            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <table class="table table-hover guestsTable">
                    <thead>
                    <tr>
                        <th>Nume</th>
                        <th>Masa</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($guests as $guest)
                        <tr>
                            <td>{{ $guest['name'] }}</td>
                            <td>
                                Masa {{ $guest['table'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>

    @include('guests.importModal')


@stop
