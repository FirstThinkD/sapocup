new Vue({
	el: '#inputVue',
});


var personalSwitch = new Vue({
	el: '#personalDetail',
	data: {
		flag: false
	},
	methods: {
		onclick: function() {
			this.flag = !this.flag;
		}
	}
});