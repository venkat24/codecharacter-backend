function acceptInvite(event) {
    var body = {
        'teamName'   : event.target.id,
        'userEmail'  : USER_DATA.userEmail,
    };
    var request = $.ajax({
        url: SITE_BASE_URL + '/api/confirm_invite',
        type: 'POST',
        data: body,
    });
    request.done(function (data) {
        alert(data.message);
        location.reload();
    });
}
