@extends('layouts.app')

@section('title','MailerLite | Subscribers List')

@push('styles')
    <link rel="stylesheet" href="{{ url('css/datatables.min.css') }}">
@endpush

@section('content')
    <div class="container text-center mt-5">
        <h1 class="mb-4">Subscribers List</h1>

        <div class="alert alert-block col-md-12 d-none">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong class="message"></strong>
        </div>

        <div class="table-container text-left">
            <table id="tbl-subscribers" class="table table-hover table-striped text-left" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Country</th>
                        <th>Subscribe date</th>
                        <th>Subscribe time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var tblSubscribers = $('#tbl-subscribers').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('api.index') }}",
                "columns": [
                    { "data": "#" },
                    { "data": "email" },
                    { "data": "name" },
                    { "data": "country" },
                    { "data": "date" },
                    { "data": "time" },
                    {
                        "data": "delete",
                        "className": "text-center",
                    }
                ],
                "order": [
                    [ 4, 'desc' ]
                ],
                "columnDefs": [
                    {
                        "orderable": false,
                        "targets": [3,5,6]
                    },
                    {
                        targets: [1],
                        "searchable": true
                    }
                ]
            } );

            //Remove subscriber
            $('#tbl-subscribers tbody').on('click','.btn-delete', function () {
                var id = $(this).attr('data-id');

                $.ajax({
                    method : 'DELETE',
                    url: '/subscribers/'+ id,
                    success: function (data) {
                        tblSubscribers.row( $(this).parents('tr') )
                            .remove()
                            .draw();

                        $('.alert').addClass(data.alertClass);
                        $('.alert').removeClass('d-none');
                        $('.alert .message').html(data.alert);
                    },
                    error: function (xhr) {
                        alert(JSON.parse(xhr.responseText).alert);
                    }
                });
            });

        } );
    </script>
@endpush

