$(document).ready( function () {
    var playerStatsTable = $('#playerStatsTable').DataTable( {
        paging:false,
        searching:true,
        bInfo:false,
        "lengthChange": false,

        'columnDefs': [
            { targets: [0,1], visible: false },
            { targets: [2,3,4,5,6,7], orderable: true, "className": "center-align"},
        ],

        'columns': [
            { data: 'seq' }, /* index = 0 */
            { data: 'id' }, /* index = 1 */
            { data: 'bracket'}, /*index = 2 */
            { data: 'home_team'}, /*index = 3*/
            { data: 'opponent_name' }, /* index = 4 */
            { data: 'opponent_school'},/* index = 5 */
            { data: 'score' }, /* index = 6 */
            { data: 'win/loss' }, /* index = 7 */
        ],

    } );

    $('.playerModalToggle').click(function(e) {
        e.preventDefault();

        var currentRow = $(this).closest('tr');
        var tableGender = $('#playerForStatsModal').attr('data-player-table');

        var data = $('#' + tableGender).DataTable().row(currentRow).data();
        var playerID = (data[1] === undefined) ? data['id'] : data[1];

        var playerNameElement = (data[4] === undefined) ? data['name'] : data[4];

        var wrapper= document.createElement('div');
        wrapper.innerHTML= playerNameElement;
        var div= wrapper.firstChild;
        playerName = div.innerHTML;

        console.log(playerName);
        $('#playerStatsModalTitle').html('Match History - ' + playerName);
        $('#playerForStatsModal').attr('data-player-id', playerID);
        $('#playerStatsModal').modal('toggle');
        $('#playerStatsTable').DataTable().columns([3]).visible(true);
    });

    $('#playerStatsModal').on('shown.bs.modal', function (e) {
        var playerID = $('#playerForStatsModal').attr('data-player-id');
        $playerStatsTable = $("#playerStatsTable").DataTable();
        $playerStatsTable.clear().draw();
        $increment = 1;
        $singles = $('#playerForStatsModal').attr('data-singles');
        if($singles === 'true') {
            $url = '/getPlayerStats';
        } else {
            $url = 'getDoublesStats';
        }
        $.ajax({
            type:'POST',
            url:$url,
            data:{playerID:playerID},
            success:function(matches){

                matches.forEach(function($match, $index) {
                    var row = $playerStatsTable.row.add({
                        'seq': $increment,
                        'id': $increment,
                        'bracket': $match.bracket,
                        'home_team': $match.home_team,
                        'opponent_school': $match.opponent_school,
                        'opponent_name': $match.opponent,
                        'score': $match.score,
                        'win/loss': $match.winOrLoss
                    }).draw().node();
                    $increment++;
                });

            }
        });
    });
});
