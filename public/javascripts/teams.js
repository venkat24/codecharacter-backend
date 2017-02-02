// function check() {
// 	console.log('checking');
// 	var span = document.getElementById('check');
// 	var request = $.ajax({
// 		url: SITE_BASE_URL + '/api/check_if_team_exists',
// 		type: 'GET',
// 		data: {
// 		  teamName: USER_DATA.teamName
// 		},
// 	});
// 	console.log('lol');

// 	request.done(function(data) {
// 		console.log(data);
// 		if (data.status_code == 200) {
// 			if (data.message = "EXISTS")
// 				span.innerHTML = "X";
// 			else span.innerHTML = "✓";
// 		}
// 	});
// 	request.fail(function(jqXHR, textStatus, err) {
// 		return console.log(err.toString());
// 	});	
// }

$(document).ready(function () {
    teamData.lockTeamName();
});

Vue.component('team-member', {
	props: ['member'],
	template: '<div> <br /><input class="memberName" :value="member.name" placeholder="name" disabled /> <input class="memberEmail" :value="member.email" placeholder="email" /> <span class="buttonContainer"><button class="inviteButton" onclick="invite(this)">Send Invite</button></span> </div>'
});

console.log("TEAMNAME : " + USER_DATA.teamName);
var teamData = new Vue({
	el: "#team-info",
	data: {
		buttonText: 'Create',
		teamMemberSeen: false,
		newTeamName: USER_DATA.teamName,
		buttonSeen: false,
		teamMembers: [
			// {name: 'testName', email: 'email@pragyan.com'}
		]
	},
	methods: {
        lockTeamName: function() {
            console.log("Executing Lock..");
            if(this.newTeamName !== "") {
                document.getElementById('team-name').disabled = true;
                console.log("Actual Locking");
            }
        },
		create: function() {
			var teamName = this.newTeamName;
			var sentData = { teamName: teamName, leaderEmail: USER_DATA.userEmail };
			var request = $.ajax({
				url: SITE_BASE_URL + '/api/create_team',
				type: 'POST',
				data: sentData
			});
			console.log("create " + teamName);
			console.log(sentData);

			// else
			// 	var request = $.ajax({
			// 		url: SITE_BASE_URL + '/api/rename_team',
			// 		type: 'POST',
			// 		data: {
			// 		  teamName: teamName
			// 		},
			// 	});

			request.done(function(data) {
				console.log("hi");
				if (data.status_code == 200) {
					console.log("hidone");
					teamData.buttonText = 'Rename';
					teamData.teamMemberSeen = true;
					teamData.buttonSeen = true;
					teamData.teamMembers.push({name: USER_DATA.userName, email: USER_DATA.userEmail});
					USER_DATA.teamName = teamName;
					setTimeout(function() {
						document.getElementsByClassName('inviteButton')[0].style.visibility = 'hidden';
						document.getElementsByClassName('memberEmail')[0].disabled = true;
					}, 10);
                } else if (data.status_code == 400) {
                    alert(data.message);
                }
			});
			request.fail(function(jqXHR, textStatus, err) {
				return console.log(err.toString());
			});
		},
		add: function() {
			this.buttonSeen = false;
			if (this.teamMembers.length < 3)
				this.teamMembers.push({});
		}
	}
});

function invite(button) {
	var teamName = document.getElementById('team-name').value;
	var emails = document.getElementsByClassName('memberEmail');
	var buttons = document.getElementsByClassName('buttonContainer');
	var email = emails[emails.length-1].value;
	var method = 'POST';

	var request = $.ajax({
		url: SITE_BASE_URL + '/api/send_invite',
		type: method,
		data: {
		  teamName: teamName,
		  email: email
		},
	});

	request.done(function(data) {
		if (data.status_code == 200) {
			if (teamData.teamMembers.length < 3)
				teamData.buttonSeen = true;
			email = document.getElementsByClassName('memberEmail');
			email[email.length - 1].disabled = true;
			names = document.getElementsByClassName('memberName');
			names[names.length-1].value = data.message;
			buttons[buttons.length-1].innerHTML = " Invite Sent";
			// button.disabled = true;
		} else if (data.status_code == 400) {
                    alert(data.message);
        }
	});
	request.fail(function(jqXHR, textStatus, err) {
		return console.log(err.toString());
	});
}

(function() {
	var teamName = USER_DATA.teamName;
	var request = $.ajax({
		url: SITE_BASE_URL + '/api/get_team_members',
		type: 'GET',
		data: {
		  teamName: teamName
		},
	});
	// if (USER_DATA.teamName) {
	// 	teamData.teamMembers.push({name: USER_DATA.userName, email: USER_DATA.userEmail});
	// 	teamData.buttonText = 'Rename';
	// 	teamData.teamMemberSeen = true;
	// 	teamData.buttonSeen = true;
	// 	setTimeout(function () {
	// 		document.getElementsByClassName('buttonContainer')[0].innerHTML = '';
	// 		document.getElementsByClassName('memberEmail')[0].disabled = true;
	// 	});
	// }

	request.done(function(data) {
		console.log(data);
		if (data.status_code == 200) {
			console.log("addding");
			for (i = 0; i < data.message.length; i++)
				teamData.teamMembers.push({name: data.message[i].name, email: data.message[i].emailId});

			teamData.buttonText = 'Delete';
			teamData.teamMemberSeen = true;
			teamData.buttonSeen = true;
			setTimeout(function() {
				for (var i = 0; i < data.message.length; i++) {
					if (data.message[i].status == 'ACCEPTED')
						document.getElementsByClassName('buttonContainer')[i].innerHTML = '';
					else document.getElementsByClassName('buttonContainer')[i].innerHTML = ' Invite Sent';
					document.getElementsByClassName('memberEmail')[i].disabled = true;
				}
			}, 10);
		}
	});
	request.fail(function(jqXHR, textStatus, err) {
		return console.log(err.toString());
	});
})();
