var inviteCount = 0,
	memberCount = 0;

Vue.component('team-member', {
	props: ['member'],
	template: '<div> <br /><input class="memberName inputs" :value="member.name" placeholder="name" disabled /> <input class="memberEmail inputs" :value="member.email" placeholder="email" :disabled="member.status == \'INVITE\'? false: true"/> ' +
		'<button v-if="member.status==\'INVITE\'" class="inviteButton button small" onclick="invite()">Send Invite</button> ' +
		'<button v-if="member.status==\'SENT\'" class="deleteButton button small" onclick="cancel(this.id)" :id="member.invNumber">Cancel Invite</button> ' +
		'<button v-if="member.status==\'ACCEPTED\'" class="removeButton button small" onclick="remove(this.id)" :id="member.memNumber">Remove Member</button> ' +
		'<button v-if="member.status==\'ACCEPTED\'" class="makeLeader button small" onclick="leader(this)">Make Leader</button> '
});

var teamData = new Vue({
	el: "#team-info",
	data: {
		buttonText: 'Create',
		teamMemberSeen: false,
		newTeamName: USER_DATA.teamName,
		buttonSeen: false,
		teamMembers: []
	},
	methods: {
		create: function() {
			var teamName = document.getElementById('team-name').value;
			if (!USER_DATA.teamName) {
				$.ajax({
					url: SITE_BASE_URL + '/api/create_team',
					type: 'POST',
					data: {
						teamName: teamName,
						leaderEmail: USER_DATA.userEmail
					}
				})
				.done(function(data) {
					if (data.status_code == 200) {
						teamData.teamMembers.push({
							name: USER_DATA.userName,
							email: USER_DATA.userEmail,
							status: 'LEADER'
						});
						teamData.buttonText = 'Delete';
						teamData.teamMemberSeen = true;
						teamData.buttonSeen = true;
						document.getElementById('create-button').className += ' alert';
					} else alert(data.message);
				}).fail(function(jqXHR, textStatus, err) {
					return console.log(err.toString());
				});
			}
			else {
                if(confirm("Are you sure you want to delete your team? (YOU WILL LOSE ALL SUBMISSIONS!)")){
                    $.ajax({
                        url: SITE_BASE_URL + '/api/delete_team',
                        type: 'POST',
                        data: {
                            teamName: USER_DATA.teamName
                        },
                    })
                    .done(function(data) {
                        // ADD ALERT CHECKS
                        if (data.status_code == 200)
                            location.reload();
                        else alert(data.message);
                    })
                    .fail(function(jqXHR, textStatus, err) {
                        return console.log(err.toString());
                    });
                }
			}
		},
		add: function() {
			this.buttonSeen = false;
			if (this.teamMembers.length < 3)
				this.teamMembers.push({status: 'INVITE'});
		}
	}
});

function invite() {
	var teamName = document.getElementById('team-name').value;
	var emails = document.getElementsByClassName('memberEmail');
	var email = emails[emails.length-1].value;

	var request = $.ajax({
		url: SITE_BASE_URL + '/api/send_invite',
		type: 'POST',
		data: {
			teamName: teamName,
			email: email
		},
	});

	request.done(function(data) {
		if (data.status_code == 200) {
			if (teamData.teamMembers.length < 3)
				teamData.buttonSeen = true;

			teamData.teamMembers[teamData.teamMembers.length-1] = {
				name: data.message,
				email: emails[emails.length - 1].value,
				status: 'SENT',
				invNumber: inviteCount
			};
			inviteCount++;
		} else alert(data.message);
	});
	request.fail(function(jqXHR, textStatus, err) {
		return console.log(err.toString());
	});
}

function leave() {
	var request = $.ajax({
		url: SITE_BASE_URL + '/api/leave_team',
		type: 'POST',
		data: {
			  teamName: USER_DATA.teamName,
			  userEmail: USER_DATA.userEmail
		},
	});

	request.done(function(data) {
		// ADD ALERT CHECKS
		if (data.status_code == 200)
			console.log(data);
			//location.reload();
		else alert(data.message);
	});
	request.fail(function(jqXHR, textStatus, err) {
		return console.log(err.toString());
	});
}

function cancel(id) {
	var count, email;

	for (var i = 0; i < teamData.teamMembers.length; i++) {
		if (teamData.teamMembers[i].invNumber == id) {
			email = teamData.teamMembers[i].email;
			count = i;
			break;
		}
	}

	var request = $.ajax({
		url: SITE_BASE_URL + '/api/cancel_invite',
		type: 'POST',
		data: {
			  teamName: USER_DATA.teamName,
			  userEmail: email
		},
	});

	request.done(function(data) {
		if (data.status_code == 200) {
			console.log('Invite Cancelled');
			teamData.teamMembers.splice(count, 1);
			if (teamData.teamMembers[teamData.teamMembers.length-1].status != 'INVITE') {
				teamData.buttonSeen = true;
			}
		} else alert(data.message);
	});
	request.fail(function(jqXHR, textStatus, err) {
		return console.log(err.toString());
	});
}

function remove(id) {
	var count, email;

	for (var i = 0; i < teamData.teamMembers.length; i++) {
		if (teamData.teamMembers[i].memNumber == id) {
			email = teamData.teamMembers[i].email;
			count = i;
			break;
		}
	}

	var request = $.ajax({
		url: SITE_BASE_URL + '/api/delete_member',
		type: 'POST',
		data: {
			  teamName: USER_DATA.teamName,
			  userEmail: email
		},
	});

	request.done(function(data) {
		if (data.status_code == 200) {
			alert('Member was deleted');
			teamData.teamMembers.splice(count, 1);
			if (teamData.teamMembers[teamData.teamMembers.length-1].status != 'INVITE') {
				teamData.buttonSeen = true;
			}
		} else alert(data.message);
	});
	request.fail(function(jqXHR, textStatus, err) {
		return console.log(err.toString());
	});
}

function leader(button) {
	var email = button.parentElement.childNodes[3].value;

	var request = $.ajax({
		url: SITE_BASE_URL + '/api/change_leader',
		type: 'POST',
		data: {
			currentLeaderEmail: USER_DATA.userEmail,
			teamName: USER_DATA.teamName,
			newLeaderEmail: email,
		},
	});

	request.done(function(data) {
		if (data.status_code == 200) {
			location.reload();
		} else alert(data.message);
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
		}
	});

	request.done(function(data) {
		if (data.status_code == 200) {
			console.log(data);
			for (i = 0; i < Object.keys(data.message).length; i++) {
				if (data.message[i].status == 'SENT') {
					teamData.teamMembers.push({
						name: data.message[i].name,
						email: data.message[i].emailId,
						status: data.message[i].status,
						invNumber: inviteCount
					});
					inviteCount++;
				} else if (data.message[i].status == 'ACCEPTED'){
					teamData.teamMembers.push({
						name: data.message[i].name,
						email: data.message[i].emailId,
						status: data.message[i].status,
						memNumber: memberCount
					});
					memberCount++;
				} else {
					teamData.teamMembers.push({
						name: data.message[i].name,
						email: data.message[i].emailId,
						status: data.message[i].status
					});
				}
			}

			teamData.buttonText = 'Delete';
			teamData.teamMemberSeen = true;
			document.getElementById('create-button').className += ' alert';
			if (teamData.teamMembers.length < 3)
				teamData.buttonSeen = true;
			else teamData.buttonSeen = false;
		}
	});
	request.fail(function(jqXHR, textStatus, err) {
		return console.log(err.toString());
	});
})();
