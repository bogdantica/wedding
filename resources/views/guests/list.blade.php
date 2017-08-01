@extends('layouts.app')


@section('content')

    <div class="container white-bg">

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="btn-group pull-right " style="margin-top: 1em">
                    <button type="button" class="btn btn-success add">Adauga</button>
                </div>
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
                    <tr data-id="0" class="newGuests" style="display: none;">
                        <td contenteditable="true" data-name=""
                            class="">Invitat Nou</td>
                        <td contenteditable="true" data-table="" class="">Masa </td>
                    </tr>

                    @foreach($guests as $guest)
                        <tr data-id="{{ $guest->id }}">
                            <td contenteditable="true" data-name="{{ $guest->name }}"
                                class="editable">{{ $guest->name }}</td>
                            <td contenteditable="true" data-table="Masa {{ $guest->table }}" class="editable">
                                Masa {{ $guest->table }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>

@stop


@push('scripts')

<script>

    var $newGuest = $('.newGuests');

    $(document).on('click', '.add', function () {
        var $button = $(this);

        if ($button.hasClass('opened')) {
            submit();
        }

        $button.addClass('opened');

        $newGuest.show();
    });


    function submit() {

        var newName = $newGuest.find('[data-name]').text();
        var newTable = $newGuest.find('[data-table]').text();


        $.ajax({
            headers: {
                'X-CSRF-TOKEN': window.laravel.csrf_token
            },
            type: 'POST',
            data: {
                name: newName,
                table: newTable
            },
            success: function (r) {
                location.reload();
            },
            error: function (r, e, x) {
                var response = r.responseJSON;
                for (var i in response) {
                    for (var j in response[i]) {
                        new PNotify({
                            text: response[i][j],
                            type: 'error'
                        });
                    }
                }
            }
        });


    }


    $(document).on('input', '.editable', function () {
        finalEvent($(this).closest('tr'));
    });

    var lastEvent;

    function finalEvent(param) {
        clearInterval(lastEvent);
        lastEvent = setTimeout(function () {
            update(param);
        }, 600);
    }


    function update($row) {

        var newName = $row.find('[data-name]').text();
        var newTable = $row.find('[data-table]').text();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': window.laravel.csrf_token
            },
            type: 'POST',
            data: {
                id: $row.data('id'),
                name: newName,
                table: newTable
            },
            success: function (r) {

                var name = $row.find('[data-name]');

                var table = $row.find('[data-table]');

                name.attr('contenteditable', 'false');
                table.attr('contenteditable', 'false');

                setTimeout(function () {
                    name.attr('contenteditable', 'true');
                    table.attr('contenteditable', 'true');
                }, 1000);

                name.text(r.name);
                table.text(r.table);

                new PNotify({
                    text: 'Modificat',
                    type: 'success'
                });

            },
            error: function (r, e, x) {

                var name = $row.find('[data-name]');

                var table = $row.find('[data-table]');

                name.attr('contenteditable', 'false');
                table.attr('contenteditable', 'false');

                setTimeout(function () {
                    name.attr('contenteditable', 'true');
                    table.attr('contenteditable', 'true');
                }, 500);

                name.text(name.attr('data-name'));
                table.text(table.attr('data-table'));

                var response = r.responseJSON;

                for (var i in response) {

                    for (var j in response[i]) {
                        new PNotify({
                            text: response[i][j],
                            type: 'error'
                        });

                    }

                }

            }
        });

    }

</script>

@endpush
