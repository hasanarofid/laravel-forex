@extends('layouts.app')

@section('content')
<style>
    .daterangepicker {
        z-index: 9999 !important;
    }
    /* Tambahkan gaya khusus di sini */
    .dataTables_wrapper {
        font-family: Arial, sans-serif;
    }

    .dataTables_wrapper .dataTables_length select {
        width: 75px;
    }

    .dataTables_wrapper .dataTables_filter input {
        margin-left: 0.5em;
        display: inline-block;
        width: 100%;
        max-width: 300px;
    }

    .dataTables_wrapper .dataTables_info {
        margin-top: 0.5em;
    }

    .dataTables_wrapper .dataTables_paginate {
        margin-top: 0.5em;
    }

    table.dataTable {
        border-collapse: collapse;
        width: 100%;
    }

    table.dataTable th,
    table.dataTable td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    table.dataTable th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    table.dataTable tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    table.dataTable tbody tr:hover {
        background-color: #ddd;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background-color: #007bff;
        color: #fff;
        border: 1px solid #007bff;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Data Api</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{ route('admin.fetch-ratios') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Fetch and Save API</button>
                    </form>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <label for="date_range">Date Range:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                    <input type="text" id="date_range" class="form-control">
                                </div>
                            </div>
                        </div>

                        


                    <table class="table mt-3" id="dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Currency</th>
                                <th>Company</th>
                                <th>Buy</th>
                                <th>Sell</th>
                                <th>Last Update</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>



                </div>
            </div>
        </div>
    </div>
</div>
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Include moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<!-- Include daterangepicker.js -->
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>

<!-- Include datatables.net -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<!-- Your custom script -->
<script>
    $(document).ready(function() {
        $('#date_range').daterangepicker({
        opens: 'left', // Adjust the position as needed
        autoApply: true // Automatically apply the date range when selecting
    });
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("admin.datatable") }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'type', name: 'type' },
                { data: 'currency', name: 'currency' },
                { data: 'company', name: 'company' },
                { data: 'buy', name: 'buy' },
                { data: 'sell', name: 'sell' },
                { data: 'last_update22', name: 'last_update22' }
            ]
        });
    });
</script>

@endsection
