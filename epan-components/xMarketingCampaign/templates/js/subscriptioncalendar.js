// Day Widget

var xepan_subscriptionday = function(duration){
	this.duration = duration;
	this.events= {};
	
	this.addEvent= function(evt){
		this.events[evt.event._nid] = evt;// event_html.appendTo($(this.element).closest('.days'));
	};
	this.hasEvent=function(evt){
		return this.events[evt._nid] != undefined;
	}
	this.removeEvent= function(evt){
		this.events.splice(evt._nid, 1);
	};
	this.render= function(parent){
		console.log('day rendered '+ this.duration);
		day_obj = $('<div class="days">'+ this.duration +'</div>').appendTo($(parent)).data('duration',this.duration);
		$.each(this.events, function(index, e) {
			e.render(day_obj);
		});
		return day_obj;
	};
}

var xepan_subscriptionevent = function(evt) {
	this.event = evt;
	this.render= function(parent){
		$('<div class="label label-success added_event">'+this.event.title+'</div>').appendTo(parent).data('event',this.event);
		// console.log('rendering '+ this.event.title);
	};
}

// Subscription Calander Widget
jQuery.widget("ui.xepan_subscriptioncalander",{
	num:1 ,
	add_day_btn: undefined,
	add_day_inp: undefined,
	schedular: undefined,
	trash: undefined,
	days:{}, // Internal storage
	options:{
		events: {} // User send json for all days and events from database as initialization of widget
	},

	_create: function(){
		var self=this;
		this.add_day_inp = $('<input type="number" class="input"/>').appendTo(this.element);//.spinner();
		this.add_day_btn = $('<button class="btn btn-default">Add Day</button>').appendTo(this.element);
		this.trash = $('<div></div>').addClass('fa fa-trash fa-4x').appendTo(this.element);
		this.schedular = $('<div></div>').appendTo(this.element);
		this.schedular.addClass('well');

		this.add_day_btn.bind('click', undefined, function(event) {
			var inp = self.add_day_inp.val();
			if(!inp){
				$(self.add_day_inp).effect('highlight');
				return;
			}

			if(self.days[inp]){
				self.add_day_inp.effect('shake');
				self.days[inp].effect('highlight').effect('bounce');
				return;	
			}

			self.addDay(inp);
			self.render();

		});

		this.trash.droppable({
			drop: function(event,ui){

			}
		});

	},

	_init: function(){
		// Create all days recursively with events coming from database here
		var self= this;
		// console.log(this.options.events);
		self.addDay(0);
		$.each(this.options.events, function(index, evt) {
			 if(self.days[evt.day]==undefined){
			 	self.addDay(evt.day);
			 }
			self.days[evt.day].addEvent(new xepan_subscriptionevent(evt));			 
		});
		console.log(this.days);
		this.render();
	},

	addDay: function(duration, name){
		// day_html = $('<div id="day'+duration+'">'+duration+'</div>');
		// day_html.addClass('panel panel-success');
		// this.days[duration] = $(day_html).appendTo(this.schedular).xepan_subscriptionday({duration : duration});
		return this.days[duration] = new xepan_subscriptionday(duration);//$(day_html).appendTo(this.schedular).xepan_subscriptionday({duration : duration});
	},

	render: function(){
		var self=this;
		self.schedular.html('');
		jQuery.each(this.days, function(index, day) {
			$(day.render(self.schedular)).sortable({
				connectWith: ".days",
				receive: function( event, ui ){
					console.log('newsletter '+ui.item.data('event').title+ ' moved from '+ ui.sender.data('duration') + ' to ' + day.duration );
				}
			}).droppable({
				drop: function(event, ui){
					if(!ui.helper.is('.added_event')){
						if(!day.hasEvent(ui.helper.data('event'))){
							day.addEvent(new xepan_subscriptionevent(ui.helper.data('event')));
							self.render();
						}
					}
				}
			});
		});
	}

});