@extends('layouts.app')

@section('title','MailerLite | Subscribers List')

@push('styles')
    <link rel="stylesheet" href="{{ url('css/datatables.min.css') }}">
@endpush

@section('content')
    <div class="container text-center mt-5">
        <h1 class="mb-4">Subscribers List</h1>

        @if(!empty($message))
            <div class="alert alert-danger" role="alert">
                {{ $message }}
            </div>
        @endif

        <div class="table-container text-left">
            <table id="tbl-subscribers" class="table table-striped text-left" style="width:100%">
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
            var tbl = $('#tbl-subscribers').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ url('subscribers') }}",
                    "dataType": "json",
                    "type": "POST"
                },
                "columns": [
                    { "data": "#" },
                    { "data": "email" },
                    { "data": "name" },
                    { "data": "country" },
                    { "data": "date" },
                    { "data": "time" },
                    {
                        "data": "id",
                        "className": "dt-center",
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
                    },
                    {
                        targets: [6],
                        render: function ( data, type, row, meta ) {
                            if(type === 'display'){
                                var deleteBtn = '<button class="fs-6 text-danger btn btn-link" data-id='+ data +'>delete</button>';

                                var editBtn = '<a href="/subscribers/' + row.id + '/edit" class="fs-6 text-primary">edit</a>';

                                data = deleteBtn + editBtn;
                            }

                            return data;
                        }
                    }
                ]
            } );
        } );
    </script>
@endpush

