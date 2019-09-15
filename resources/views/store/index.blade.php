@extends('layouts.backend')
@section('title', 'Store')
@section('content')
    <div class="row row-cards-pf">
        <div class="row-cards-pf card-pf">
            <ol class="breadcrumb">
                <li>
                    <span class="pficon pficon-home"></span>
                    <a href="{{url('home')}}">Dashboard</a>
                </li>
                <li>
                    <span class="fa fa-building"></span>
                    <a href="{{url('store')}}">Store</a>
                </li>
                <li class="active">
                    <strong>List Store</strong>
                </li>
            </ol>
        </div>
    </div><!-- /row -->

    <div class="row row-cards-pf">
        <!-- Important:  if you need to nest additional .row within a .row.row-cards-pf, do *not* use .row-cards-pf on the nested .row  -->
        <div class="col-xs-12">
            <div class="card-pf card-pf-accented card-pf-view">
                <div class="card-pf-heading">
                    <h1>
                        <span class="fa fa-building"></span>
                        Store
                        <small>List</small>
                    </h1>
                </div>
                <div class="card-pf-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <!-- Table HTML -->
                                <table id="storeTable" class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Store Code</th>
                                        <th>Initial</th>
                                        <th>Store Name</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Store Code</th>
                                        <th>Initial</th>
                                        <th>Store Name</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /row -->
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('#storeTable tfoot th').each(function() {
                var title = $(this).text();
                if (title != '') {
                    $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" style="width: 100%;" />');
                }
                if (title == 'Created At') {
                    $(this).html('<input type="text" class="datepicker form-control" placeholder="Search ' + title + '" style="width: 100%;" />');
                }
                if (title == 'Updated At') {
                    $(this).html('<input type="text" class="datepicker form-control" placeholder="Search ' + title + '" style="width: 100%;" />');
                }
            });

            // DataTable Config
            var table = $("#storeTable").DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                stateDuration: 5,
                lengthMenu: [[10, 50, 75, -1], [10, 50, 75, "All"]],
                pageLength: 10,
                order: [[0, 'asc']],
                dom: '<"top"l>rt<"bottom"ip><"clear">',
                ajax: {
                    "url": "/datatable/stores",
                    "type": "POST"
                },
                language: {
                    "decimal": ",",
                    "thousands": "."
                },
                columns: [{
                    data: 'store_code',
                    name: 'store_code'
                },
                {
                    data: 'initials_code',
                    name: 'initials_code'
                },
                {
                    data: 'store_desc',
                    name: 'store_desc'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                }, {
                    data: 'updated_at',
                    name: 'updated_at'
                }]
            });

            /* Ketika Value pada Input di TFOOT berubah, Maka Search Sesuai Kolom */
            table.columns().every(function() {
                var that = this;
                $('input', this.footer()).on('keyup', function() {

                    // Cancel the default action, if needed
                    event.preventDefault();

                    // Number 13 is the "Enter" key on the keyboard
                    if (event.keyCode === 13) {
                        var keyword = this.value;

                        if (this.placeholder == 'Search Published') {
                            keyword = keyword.toUpperCase();
                            if (keyword == 'TRUE' || keyword == 'YA' || keyword == 'YES' || keyword == 'Y' || keyword == '1') {
                                keyword = 1;
                            } else {
                                keyword = 0;
                            }
                        }

                        if (that.search() !== keyword) {
                            that
                                .search(keyword)
                                .draw();
                        }
                    }
                });
            });

            $("tfoot .datepicker").datepicker({
                autoclose: true,
                endDate: "0d",
                format: "yyyy-mm-dd",
                todayHighlight: true,
                weekStart: 1,
            });
        });
    </script>
@endsection