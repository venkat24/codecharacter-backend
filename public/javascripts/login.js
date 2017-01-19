function login() {
	var route = PRAGYAN_BASE_URL + '/events/login';
	var method = 'POST';
	var username = $("#username").val();
	var password = $("#password").val();
	var body = {
		"user_email": username,
		"user_pass": password,
		"event_id" : EVENT_ID,
		"event_secret" : EVENT_SECRET,
	};
	//The event ID and event secret are currently insecure. 
	//Move this request to the backend later
	var request = $.ajax({
		url: route,
		type: method,
		data: body
	});
	request.done(function(data){
		if(data.status_code == 200) {
			location.href = SITE_BASE_URL + "/admin/home";
		} else {
			alert("Sorry! Login failed.");
		}
	});
}

