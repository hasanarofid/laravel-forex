@extends('layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@section('content')
<style>
    <style>
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
                    <table class="table mt-3" id="dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.datatable") }}',
        columns: [
            { data: 'id', name: 'id' },
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
