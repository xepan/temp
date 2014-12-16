// Day Widget

// Event Widget
jQuery.widget('ui.xepan_subscriptionday',{
	_create: function(){

	},

	render: function(){
		console.log('day rendered '+ this.options.duration);
	}
});


// Event Widget
jQuery.widget('ui.xepan_subscriptionevent',{
	_create: function(){

	},

	render: function(){
		this.schedular.text(this.num);
	}
});

// Subscription Calander Widget
jQuery.widget("ui.xepan_subscriptioncalander",{
	num:1 ,
	add_day_btn: undefined,
	add_day_inp: undefined,
	schedular: undefined,
	days: {},

	_create: function(){
		var self=this;
		this.add_day_inp = $('<input type="text"/>').appendTo(this.element);//.spinner();
		this.add_day_btn = $('<button>Add Day</button>').appendTo(this.element);
		this.schedular = $('<div></div>').appendTo(this.element);
		this.schedular.addClass('well');
		this.addDay(0);
		this.addDay(15);
		this.addDay(10);
		this.render();
	},

	addDay: function(duration, name){
		// console.log(previous_day);
		this.days[duration] = $('<div id="day'+duration+'">sdfsdf</div>').appendTo(this.element).xepan_subscriptionday({duration : duration});
		// this.days[duration].xepan_subscriptionday({day_number: duration});
		// console.log(this);
	},

	render: function(){
		var self=this;
		console.log(this.days);
		// this.schedular.html('');
		jQuery.each(this.days, function(index, day) {
			// console.log(day);
			$('#day'+index).data('xepan_subscriptionday').render();
		});
	}

});

