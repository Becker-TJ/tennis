$(document).ready( function () {



    var allSchoolsTable = $('#allSchoolsTable').DataTable( {
        paging:false,
        searching:true,
        rowReorder: {
            selector: '.reorder-cell',
            snapX:true,
        },
        bInfo:false,
        "lengthChange": false,

        'columnDefs': [
            { targets: [0,1,2], orderable: true },
            { targets: [2], "className": "center-align"}
        ]

    } );

    var varsityOrder = [
        '#1 Singles',
        '#2 Singles',
        '#1 Doubles',
        '#1 Doubles',
        '#2 Doubles',
        '#2 Doubles',
    ];

    //this resets the order of the far left column for a school roster after a click and drag table row event(1 singles, 2 singles, etc)


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });



    //this is assigning settings for the sortable tables
    $('#playerDisplayTable').DataTable( {
        paging:false,
        "lengthChange": false,
        'columns': [
            { data: 'seq' }, /* index = 0 */
            { data: 'id' }, /* index = 1 */
            { data: 'name' }, /* index = 2 */
            { data: 'school' }, /* index = 3 */
            { data: 'class' }, /* index = 4 */
            { data: 'rank' }, /* index = 5 */
        ],

        'columnDefs': [
            { targets: [4, 5], "className": "center-align"}
        ],

        order: [[5, 'asc']],

    } );

    $('#tournamentsTable').DataTable( {
        paging:false,
        searching:true,
        bInfo:false,
        "lengthChange": false,
        'columns': [
            { data: 'name' }, /* index = 0 */
            { data: 'location_name' }, /* index = 1 */
            { data: 'date' }, /* index = 2 */
            { data: 'level' }, /* index = 3 */
            { data: 'gender' }, /* index = 4 */
            { data: 'team_count' }, /* index = 5 */

        ],
        "columnDefs": [
            { "type": "date", "targets": 2 },
            { targets: [0, 1], "className": "left-align"},
            { targets: [2, 4], "className": "center-align"}
        ],

        'order': [
        ],



    } );


    function displaySortIcons() {
        var headersWithSortingClass = $('th.sorting');
        headersWithSortingClass.each(function() {
            $(this).append('<img class="sort-icon" src="/images/sort_both.png">');
        })

        var headersWithSortingAscClass = $('th.sorting_asc');
        headersWithSortingAscClass.each(function() {
            $(this).append('<img class="sort-icon" src="/images/sort_asc.png">');
        })

        var headersWithSortingDescClass = $('th.sorting_desc');
        headersWithSortingDescClass.each(function() {
            $(this).append('<img class="sort-icon" src="/images/sort_desc.png">');
        })
    }

    displaySortIcons();

    $('#tournamentsTable, #playerDisplayTable').on('order.dt', function () {
        $('.sort-icon').remove();
        displaySortIcons();
    } );




} );








