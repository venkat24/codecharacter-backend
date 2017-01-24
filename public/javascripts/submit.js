$(document).ready(function () {
    getSubmissionStatus();
});
function submit() {
	var route = SITE_BASE_URL + '/api/submit';
	var method = 'POST';
  var body = new FormData($('#code-submit-form')[0]);
	var request = $.ajax({
		url: route,
		type: "POST",
		processData: false,
		contentType: false,
		enctype: 'multipart/form-data',
		data: body
	});
  console.log(body);
  console.log(request);
	request.done(function(data){
		if(data.status_code == 200) {
		  console.log(data);
		} else {
		  console.log(data);
		}
	});
}
function getSubmissionStatus() {
	var route = '/api/check_job_status';
	var method = 'GET';
  var body = {
      'teamName' : USER_DATA.teamName,
  };
	var request = $.ajax({
		url: route,
		type: method,
		data: body
	});
	request.done(function(data){
		if(data.status_code != 500) {
		    console.log(data);
		    $('#submission-status').html(data.message);
		} else {
	        console.log(data);
		}
	});
}
