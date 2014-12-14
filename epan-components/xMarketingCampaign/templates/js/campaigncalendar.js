$.each({
	campaigncalendar: function(obj, options, url, calendar_name, campaign_id){
		option_extended = $.extend({
			droppable: true, // this allows things to be dropped onto the calendar
				
			eventReceive: function(new_event) {
				// External Object Dropped
				var on_date = new_event._start.format('YYYY-MM-DD');
				// console.log(on_date);
				// do some ajax call to add this event in database
				var param = {};
				param[calendar_name+'_event_type']=new_event._eventtype;
				param[calendar_name+'_event_act']='Add';
				param[calendar_name+'_event_id']=new_event._nid;
				param[calendar_name+'_event_jsid']= new_event._id;
				param[calendar_name+'_ondate']= on_date;
				param['campaign_id']= campaign_id;
				
				$.ajax({
					url: url,
					type: 'GET',
					data:  param,
				})
				.done(function(ret) {
					eval(ret);
					console.log("success");
				})
				.fail(function(ret) {
					$(obj).fullCalendar('removeEvents',[new_event._id]);
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
				
			},
			eventDrop: function(event, delta, revertFunc){
				// Internal Event Moved
				var param = {};
				param[calendar_name+'_event_type']=event._eventtype;
				param[calendar_name+'_event_act']='Move';
				param[calendar_name+'_event_id']=event._nid;
				param[calendar_name+'_event_jsid']= event._id;
				param[calendar_name+'_ondate']= event.start.format('YYYY-MM-DD');

				$.ajax({
					url: url,
					type: 'GET',
					data:  param,
				})
				.done(function(ret) {
					eval(ret);
					console.log("success");
				})
				.fail(function(ret) {
					revertFunc();
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});

			},
			eventDragStop: function(event,jsEvent) {
				// Remove on trash test
				var trashEl = jQuery(obj+'-trash');
				var ofs = trashEl.offset();

				var x1 = ofs.left;
				var x2 = ofs.left + trashEl.outerWidth(true);
				var y1 = ofs.top;
				var y2 = ofs.top + trashEl.outerHeight(true);

				if (jsEvent.pageX >= x1 && jsEvent.pageX<= x2 &&
				    jsEvent.pageY>= y1 && jsEvent.pageY <= y2) {
					if(confirm("Are You Sure")){
						var param = {};
						param[calendar_name+'_event_type']=event._eventtype;
						param[calendar_name+'_event_act']='Remove';
						param[calendar_name+'_event_id']=event._nid;
						param[calendar_name+'_event_jsid']= event._id;
						param[calendar_name+'_ondate']= event.start.format('YYYY-MM-DD');

						$.ajax({
							url: url,
							type: 'GET',
							data: param,
						})
						.done(function(ret) {
							eval(ret);
							console.log("success");
						})
						.fail(function(ret) {
							eval(ret);
							console.log("error");
						})
						.always(function() {
							console.log("complete");
						});
						
					    $(obj).fullCalendar('removeEvents', [event._id]);
					}
				}
			},

			eventClick: function(event, jsEvent, view) {

		        // alert('Event: ' + calEvent.title);
		        // alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
		        // alert('View: ' + view.name);

		        // // change the border color just for fun
		        // $('.fc-event').css('border-color', 'red');

		        var param = {};
				param[calendar_name+'_event_type']=event._eventtype;
				param[calendar_name+'_event_act']='Clicked';
				param[calendar_name+'_event_id']=event._nid;
				param[calendar_name+'_event_jsid']= event._id;
				param[calendar_name+'_ondate']= event.start.format('YYYY-MM-DD');

				$.ajax({
					url: url,
					type: 'GET',
					data: param,
				})
				.done(function(ret) {
					eval(ret);
					console.log("success");
				})
				.fail(function(ret) {
					eval(ret);
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});


		    },

		    eventMouseover: function(event, jsEvent, view){
		    	$(obj+'-title').html(event.title);
		    },
		    eventMouseout: function(event, jsEvent, view){
		    	$(obj+'-title').html("");
		    },

		    dayClick: function(date, allDay, jsEvent, view) {
			    $('#calendar').fullCalendar('gotoDate', date);
			},   



		},options);

		$(obj).fullCalendar(option_extended);

		// $(obj+'-trash').droppable({
		// 	 drop: function( event, ui ) {
		// 		$( this )
		// 		.addClass( "ui-state-highlight" )
		// 		.html( "Dropped!" );
		// 		}
		// });

	}
}, $.univ._import);