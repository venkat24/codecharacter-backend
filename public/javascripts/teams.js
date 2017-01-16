
Vue.component('team-member', {
	props: ['member'],
	template: '<div> <br /><input :value="member.name" placeholder="name" disabled /> <input class="inviteEmail" :value="member.email" placeholder="email" /> <button class="inviteButton" onclick="invite(this)">Send Invite</button> </div>'
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
				document.getElementsByClassName('inviteEmail')[0].disabled = true;
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
	if (teamData.teamMembers.length < 3)
		teamData.buttonSeen = true;
	email = document.getElementsByClassName('inviteEmail');
	email[email.length - 1].disabled = true;
	button.disabled = true;
	console.log('INVITE');
}
