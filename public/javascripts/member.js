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

