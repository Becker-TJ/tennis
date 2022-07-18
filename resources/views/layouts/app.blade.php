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
    <link href="https://cdn.datatables.net/rowreorder/1.2.6/css/rowReorder.dataTables.min.css" rel="stylesheet"> {{--for styling dragging table rows --}}
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
        #roster_button {
            float: right !important;
        }
        .card-header {
            font-weight:bold;
            font-size:24px;
            text-align:center;
        }


        tr.border_bottom td {
            border-bottom: 2px solid black;
        }
        tr.position_highlight td{
            background-color:lightblue;
        }
        .btn-group button {
            border: 1px solid black;
            padding: 10px 24px;
            cursor: pointer;
            float: left;
        }

        .btn-group button:hover {
            border: 1px solid black;
            background-color:#AAAAAA;
            color:white !important;
        }

        tr {

        }


        .edit-pen:hover, .delete-trash-can:hover {
            cursor: pointer;
            transform: scale(1.3);
        }

        .reorder-cell {
            cursor: grab;
            text-align:center;
        }

        .reorder-icon {
            width:25px;
            height:25px;
        }

        .btn-boys {
            background-color:#84CDD2;
        }

        .btn-girls {
            background-color:#CDB6B5;
        }

        .invalid-score {
            background-color:#e67073;
        }

        .advanceable {

        }

        .button-crew {
            display: inline;
        }

        .winner {
            background-color:#abffc0;
        }

        .match-complete {
            background-color:#abffc0;
        }

        .accepted-invite, .highlight-player {
            background-color:#abffc0 !important;
        }

        .pending-invite {
            background-color:#FFF36D !important;
        }

        .declined-invite {
            background-color:#ff9999 !important;
        }

        .btn-group button:not(:last-child) {
            border-right: none; /* Prevent double borders */
        }

        .bracket-button {

        }

        /* Clear floats (clearfix hack) */
        .btn-group:after {
            content: "";
            clear: both;
            display: table;
        }

        .score-input {
            text-align:center;
        }

        .center-align{
            text-align:center;
        }

        .left-align{
            text-align:left;
        }

        .bracket-button {

        }

        /* Add a background color on hover */
        /*.btn-group button:hover {*/
        /*    background-color: #000000;*/
        /*}*/

        .selected-button, .show-roster-enabled {
            background-color:#28474B !important;
            color:white;
        }

        #boys-roster {
            margin-right: 30px;
            background-color:dodgerblue;
        }
        #girls-roster {
            margin-left: 30px;
            background-color:lightpink;
            color:black;
        }

        .selected-roster-button {
            transform: scale(1.2);
            border-color:black;
            border-width:3px;
        }

        #invitesTable {
            width:100%;
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
<script
    src="https://code.jquery.com/jquery-3.4.1.js"
    integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" ></script> {{--for table sorting--}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> {{--for dropdown sorting--}}
<script src="https://cdn.datatables.net/rowreorder/1.2.6/js/dataTables.rowReorder.min.js"></script>
<script src="{{asset('js/tennisDataTables.js')}}" type="text/javascript"></script>
@yield('javascript')
</html>
