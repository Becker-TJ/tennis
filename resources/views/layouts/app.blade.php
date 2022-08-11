<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('images/tennis-ball.png')}}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="theme-color" content="#333" />
    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#333">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#333">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="#333">

    <title>@yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
{{--    <link href="{{ asset('css/navbar.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link id="bootstrap-stylesheet" rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin="anonymous"
    >
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> {{--for table icons, edit pen and trash can--}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" /> {{--for sorting dropdowns--}}
    <link href="https://cdn.datatables.net/rowreorder/1.2.6/css/rowReorder.dataTables.min.css" rel="stylesheet"> {{--for styling dragging table rows --}}

    <style>


        th {
            background-color:#333;
            color:white;
            font-size:20px;
        }

        #tournament-headings, #tournament-headings-second-column {
            font-size:20px;
        }

        .tournament-sub-title {
            font-size: 20px;
            font-weight:bold;
            color: #333;
        }

        .dataTables_filter {
            float: left !important;
        }
        #roster_button {
            float: right !important;
        }

        .card-header {
            font-weight:bold;
            font-size:32px;
            text-align:center;
            background-color: #37c0fb;
            border-bottom: 4px solid #333;
        }

        .card {
            border: 4px solid #333;
            border-radius:20px;
            overflow:hidden;
        }

        #allSchoolsTable tr:first-child, #playerDisplayTable tr:first-child {
            text-align: left;
        }



        tr.border_bottom td {
            border-bottom: 4px solid #333;
        }
        tr.position_highlight td{
            background-color:#37c0fb;
        }
        .btn-group button {
            /*border: 1px solid black;*/
            padding: 10px 24px;
            cursor: pointer;
            float: left;
        }

        /*.btn-group button:hover {*/
        /*    border: 1px solid black;*/
        /*    background-color:#AAAAAA;*/
        /*    color:white !important;*/
        /*}*/

        .position-title-td-highlight {
            font-size:20px;
            font-weight:bold;
        }


        .edit-pen:hover, .delete-trash-can:hover, .add-player-to-tournament-action:hover, .remove-seeded-player:hover {
            cursor: pointer;
            transform: scale(1.3);
        }

        .reorder-cell {
            cursor: grab;
            text-align:center;
        }

        .reorder-icon, .add-player-to-tournament-action, .remove-seeded-player {
            width:25px;
            height:25px;
        }


        .reorder-icon:hover {
            cursor:grab;
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

        .advanceable:hover {
            cursor:pointer;
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

        select[id*='court-select'] {
            font-style:italic;
            text-align:center;
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

        #bracket {
            font-weight:bold;
            font-size:18px !important;
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

        #permanent-navbar, .dropdown-item, .dropdown-menu{
            cursor: default;
            background-color: #333;
            padding: 0;
            text-align:left;
        }

        .dropdown-item:hover {
            background-color:#333 !important;
            cursor:pointer;
        }

        .dropdown-item {
            text-align:center;
        }

        #permanent-navbar a{
            color:white;
            border:none;
        }

        #permanent-navbar a:hover{
            transform: scale(1.2);
        }

        #playerDisplayTable, #seedsTable {
            width:100% !important;
        }



        .navbar-brand {
            padding: 10px;
        }

        #tournamentsTable_filter {
            font-size:20px;
        }

        input[type=search] {
            border-radius:10px;
            margin-left:10px;
            text-align:center;
        }


        #blue-line {
            content: '';
            display: block;
            width: 100%;
            height: .25em;
            background-color: #37c0fb;
            border:none;
            padding:0;
        }

        .navbar-toggler {
            background-color:#37c0fb
        }


        #rosterSelect {
            padding-left:20px;
        }

        .bn3637 {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.7rem 2rem;
            font-family: "Poppins", sans-serif;
            font-weight: 700;
            font-size: 18px;
            text-align: center;
            text-decoration: none;
            color: #000;
            backface-visibility: hidden;
            border: 4px solid black;
            border-radius: 1rem;
            margin-right:20px;

        }
        .bn3637:hover {
            transition: transform 0.2s cubic-bezier(0.235, 0, 0.05, 0.95);
            transform: perspective(1px) scale3d(1.044, 1.044, 1) translateZ(0) !important;
            background-color:#000;
        }


    </style>
    <link href="{{ asset('css/navbar.css') }}" rel="stylesheet">

</head>
<body>
    <div id="app">
        @include('include/navbar')
        <main class="">
            @yield('content')
        </main>
    </div>
</body>
<script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" ></script> {{--for table sorting--}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> {{--for dropdown sorting--}}
<script src="https://cdn.datatables.net/rowreorder/1.2.6/js/dataTables.rowReorder.min.js"></script>
<script src="{{asset('js/tennisDataTables.js')}}" type="text/javascript"></script>
@yield('javascript')
</html>
