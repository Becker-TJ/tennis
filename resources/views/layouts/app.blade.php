<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin="anonymous"
    >
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> {{--for table icons, edit pen and trash can--}}
    <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet"> {{--for table sorting--}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" /> {{--for sorting dropdowns--}}
    <style>
        th {
            background-color:#32383e;
            position:sticky;top:0;
            border-top:3px solid lightblue;
            color:white;
        }
        .dataTables_filter {
            float: left !important;
        }
        .card-header {
            font-weight:bold;
            font-size:24px;
            text-align:center;
        }
    </style>

</head>
<body>
    <div id="app">

        @include('include/navbar')

        <main class="py-4">

            @yield('content')

        </main>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" ></script> {{--for table sorting--}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> {{--for dropdown sorting--}}
<script>
    $(document).ready( function () {

        //related to dropdown sorting
        $('.select2').select2();
        $('.select2').select2({
            width: 'resolve' // need to override the changed default
        });


        //this is assigning settings for the sortable tables
        $('#myTable').DataTable( {
            paging:false,
            "lengthChange": false,
            'columns': [
                { data: 'name' }, /* index = 0 */
                { data: 'address' }, /* index = 1 */
                { data: 'team_count' }, /* index = 2 */
                { data: 'level' }, /* index = 3 */
                { data: 'actions' } /* index = 4 */
            ],
            'columnDefs': [ {
                'targets': [4], /* column index */
                'orderable': false, /* true or false */
            }]
        } );
    } );


    //for addschool view.  enables and disables part of the form.
    $("#not_listed").change(function() {
        if (this.checked) {
            $(".toggle_show").removeAttr('disabled').attr('required', true);
            $("#switch_button_name").html("Add New School");
        } else {
            $(".toggle_show").attr('disabled', true).val('').attr('required', false);
                $("#switch_button_name").html("Tie Existing School");
        }
    });
</script>
</html>
