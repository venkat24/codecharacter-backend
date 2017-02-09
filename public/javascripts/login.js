function enter(event) {
	if (event.keyCode == 13)
		login();
}

function login() {
	var route = SITE_BASE_URL + '/api/login';
	var method = 'POST';
	var username = $("#username").val();
	var password = $("#password").val();
	$('#login-button').prop('disabled', true);
	var body = {
		"pragyanEmail": username,
		"password": password,
	};
	//The event ID and event secret are currently insecure. 
	//Move this request to the backend later
	var request = $.ajax({
		url: route,
		type: method,
		data: body
	});
	request.done(function(data){
	  console.log(data);
		if(data.status_code == 200) {
			location.href = SITE_BASE_URL + "/";
		} else {
			alert("Sorry! Login failed.");
            $('#login-button').prop('disabled', false);
		}
	});
}

function logout() {
  var route = SITE_BASE_URL + '/api/logout';
	var method = 'POST';
	var request = $.ajax({
		url: route,
		type: method,
	});
	request.done(function(data){
    console.log(data);
		if(data.status_code == 200) {
			location.href = SITE_BASE_URL + "/";
		} else {
			alert("Sorry! Logout failed.");
		}
	});
}
