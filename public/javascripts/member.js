function leave(button) {
    if(confirm("Are you SURE you want to leave this team?")) {
        var request = $.ajax({
            url: SITE_BASE_URL + '/api/leave_team',
            type: 'POST',
            data: {
                teamName: USER_DATA.teamName,
                userEmail: USER_DATA.userEmail,
            },
        });

        request.done(function(data) {
            if (data.status_code == 200) {
                alert(data.message);
                location.reload();
            } else alert(data.message);
        });
        request.fail(function(jqXHR, textStatus, err) {
            return console.log(err.toString());
        });
    }
}

(function getTeamMembers() {
    var request = $.ajax({
        url: SITE_BASE_URL + '/api/get_team_members',
        type: 'GET'
    });
    request.done(function(data) {
        console.log(data);
        if(data.status_code == 200) {
            var list = "";
            for(var i=0; i<Object.keys(data).length;i++){
                if(data.message[i].status === 'LEADER') {
                    data.message[i].status = 'Leader';
                }
                if(data.message[i].status === 'ACCEPTED') {
                    data.message[i].status = 'Member';
                }
                if(data.message[i].status === 'SENT') {
                    data.message[i].status = 'Invite Pending';
                }
                list+="<li>"+data.message[i].emailId+" - "+data.message[i].status+"</li>";
            }
            $('#team-members-list').html(list);
        }
    });
})();
