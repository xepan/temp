$.each({
	campaigncalendar: function(obj, options, url, calendar_name){
		option_extended = $.extend({
			droppable: true, // this allows things to be dropped onto the calendar
			eventReceive: function(new_event) {
				// is the "remove after drop" checkbox checked?
				if ($('#drop-remove').is(':checked')) {
					// if so, remove the element from the "Draggable Events" list
					$(this).remove();
				}

				var on_date = new_event._start.format('YYYY-MM-DD');
				// do some ajax call to add this event in database
				var param = {};
				param[calendar_name+'_add_event']=new_event._nid;
				param[calendar_name+'_ondate']= on_date;
				param[calendar_name+'_event_id']= new_event._id;
				
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
					eval(ret);
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
				
			},
			eventDrop: function(event, delta, revertFunc){
				console.log(event);
				console.log(delta);
			}


		},options);

		$(obj).fullCalendar(option_extended);
	}
}, $.univ._import);