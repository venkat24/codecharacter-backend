function acceptInvite(event) {
    var request = $.ajax({
        url: SITE_BASE_URL + '/confirm_invite',
        method: 'POST',
        data: {
            'teamName'   : event.target.id,
            'user_email' : USER_DATA.user_email,
        }
    });
    request.done(function (data) {
        console.log(data);
    });
}
