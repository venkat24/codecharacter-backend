
Vue.component('team-member', {
	props: ['member'],
	template: '<div> <br /><input class="memberName" :value="member.name" placeholder="name" disabled /> <input class="memberEmail" :value="member.email" placeholder="email" /> <button class="inviteButton" onclick="invite(this)">Send Invite</button> </div>'
});


var teamData = new Vue({
	el: "#team-info",
	data: {
		buttonText: 'Create',
		teamMemberSeen: false,
		buttonSeen: false,
		teamMembers: [
			{name: 'testName', email: 'email@pragyan.com'}
		]
	},
	methods: {
		create: function() {
			this.buttonText = 'Rename';
			this.teamMemberSeen = true;
			this.buttonSeen = true;
			setTimeout(function() {
				document.getElementsByClassName('inviteButton')[0].style.visibility = 'hidden';
				document.getElementsByClassName('memberEmail')[0].disabled = true;
			}, 10);
			console.log('CREATE');
		},
		add: function() {
			this.buttonSeen = false;
			if (this.teamMembers.length < 3)
				this.teamMembers.push({});
			console.log('ADD');
		}
	}
});

function invite(button) {
	var teamName = document.getElementById('team-name').value;
	var emails = document.getElementsByClassName('memberEmail');
	var email = emails[emails.length-1].value;
	$.ajax({
		url: '/api/sendInvite',
		data: {teamName: teamName, email: email}
	}).done(function(data) {
		if (data.statusCode == 200){
			if (teamData.teamMembers.length < 3)
				teamData.buttonSeen = true;
			email = document.getElementsByClassName('memberEmail');
			email[email.length - 1].disabled = true;
			names = document.getElementsByClassName('memberName');
			// names[names.length-1].value = data.user.name; // IDK HOW I GET THE NAME
			button.disabled = true;
			console.log('INVITE');
		}
	}).fail(function(jqXHR, textStatus, err) {
		return console.log(err.toString());
	});
}
