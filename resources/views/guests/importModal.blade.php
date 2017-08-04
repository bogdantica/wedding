<div class="modal fade" id="import">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Import Excel</h4>
            </div>

            <form action="{{ route('import') }}" method="post" role="form" enctype="multipart/form-data">

                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                <div class="modal-body">
                    <legend>Incarcare Lista Invitati</legend>

                    <div class="form-group">
                        <label for="importFile">Adauga Fisier Invitati</label>
                        <input type="file" name="importFile" placeholder="Fisier Invitati" id="importFile">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import Now</button>
                </div>
            </form>

        </div>
    </div>
</div>


@if(count($errors))

    @push('scripts')
    <script>
        $('#import').modal('show');
    </script>

    @endpush

@endif