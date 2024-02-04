@extends('layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

@section('content')
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
                        <hr>
                        <div class="row">
                            <div class="form-group">
                                <label for="date_range">Date Range:</label>
                                <div class="input-group">
                                    <input type="text" class="daterange form-control" />
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="mb-3">
                            <button id="deleteSelected" class="btn btn-danger">Delete All Selected</button>
                        </div>
                        <hr>


                    <table class="table mt-3" id="dataTable">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="check-all" ></th>
                                <th>No</th>
                                <th>Type</th>
                                <th>Currency</th>
                                <th>Company</th>
                                <th>Buy</th>
                                <th>Sell</th>
                                <th>Last Update</th>
                                <th>Action</th>
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

<!-- Include Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Include moment.js -->

<!-- Include daterangepicker.js -->

<!-- Include datatables.net -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="text/javascript">

	$('.daterange').daterangepicker();

$(document).ready(function() {
    $('.check-all').prop('checked', false);
    // Initialize the daterangepicker
    // $('.daterange').daterangepicker();

    // Initialize the DataTable with server-side processing
    var dataTable = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.datatable") }}',
            data: function(d) {
                // Add the selected date range to the AJAX request data
                d.date_range = $('.daterange').val();
            }
        },
        columns: [
            { data: null, orderable: false, searchable: false, // Kolom untuk checkbox
                    render: function(data) {
                        return '<input type="checkbox" class="data-check" value="' + data.id + '">';
                    }
                },
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'type', name: 'type' },
            { data: 'currency', name: 'currency' },
            { data: 'company', name: 'company' },
            { data: 'buy', name: 'buy' },
            { data: 'sell', name: 'sell' },
            { data: 'last_update22', name: 'last_update22' },
            { data: 'action' },
        ]
    });




    // Add event listener to handle changes in the date range selection
    $('.daterange').on('apply.daterangepicker', function(ev, picker) {
        // Reload the DataTable with new data based on the selected date range
        dataTable.ajax.reload();
    });

    // Add event listener for delete button
    $('#deleteSelected').on('click', function() {
    var selectedCheckboxes = $('.data-check:checked');

    if (selectedCheckboxes.length === 0) {
        // Jika tidak ada checkbox yang terpilih, tampilkan pesan kesalahan
        Swal.fire({
            icon: 'error',
            title: 'No Data Selected',
            text: 'Please select at least one record to delete.',
        });
        return; // Menghentikan eksekusi lebih lanjut karena tidak ada data yang dipilih
    }

    Swal.fire({
        title: 'Delete Confirmation',
        text: 'Are you sure you want to delete the selected data?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            var pilihan = [];
            // Loop melalui setiap checkbox yang tercentang dan tambahkan nilainya ke dalam array pilihan
            selectedCheckboxes.each(function() {
                pilihan.push($(this).val());
            });

            // Kirim permintaan AJAX untuk menghapus data yang dipilih
            $.ajax({
                url: '{{ route("admin.deleteSelected") }}', // Ganti dengan URL rute Anda
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Sertakan token CSRF
                    ids: pilihan // Kirim ID dari data yang dipilih
                },
                success: function(response) {
                    if (response.success == 1) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Selected records deleted.',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Reload DataTable
                                $('.check-all').prop('checked', false);
                                dataTable.ajax.reload();

                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete selected records.',
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Tindakan jika terjadi kesalahan saat permintaan AJAX
                    console.error(xhr.responseText);
                }
            });
        }
    });
});


        // Add event listener for checkbox selection
        // $('#dataTable tbody').on('change', '.data-check', function() {
        //     $(this).closest('tr').toggleClass('selected');
        // });

});



$('#dataTable thead').on('click', '.check-all', function() {
    var allChecked = $(this).prop('checked');
    console.log("allChecked "+ allChecked);
    $('#dataTable tbody .data-check').prop('checked', allChecked);
});


var CSRF_TOKEN = '{{ csrf_token() }}';

$('#dataTable').on('click', '.deleteUser', function() {
    var id = $(this).data('id');

    // Use SweetAlert for delete confirmation
    Swal.fire({
        title: 'Delete Confirmation',
        text: 'Are you sure you want to delete the data?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request
            $.ajax({
                url: '{{ route("admin.deleteData") }}',
                type: 'post',
                data: {_token: CSRF_TOKEN, id: id},
                success: function(response) {
                    if (response.success == 1) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Record deleted.',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Reload DataTable
                                $('#dataTable').DataTable().ajax.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Invalid ID.',
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while deleting the record.',
                    });
                }
            });
        }
    });
});

</script>

@endsection
