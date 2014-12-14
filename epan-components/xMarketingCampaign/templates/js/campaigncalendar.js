$.each({
	campaigncalendar: function(obj, options, url, calendar_name, campaign_id){
		option_extended = $.extend({
			droppable: true, // this allows things to be dropped onto the calendar
			eventReceive: function(new_event) {
				
				var on_date = new_event._start.format('YYYY-MM-DD');
				// do some ajax call to add this event in database
				var param = {};
				param[calendar_name+'_add_event']=new_event._nid;
				param[calendar_name+'_ondate']= on_date;
				param[calendar_name+'_event_id']= new_event._id;
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
					eval(ret);
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
				
			},
			eventDrop: function(event, delta, revertFunc){
				var param = {};
				param[calendar_name+'_move_event']=event._nid;
				param[calendar_name+'_event_id']= event._id;
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
			eventDrag: function(){

			},
			eventDragStop: function(event,jsEvent) {
				var trashEl = jQuery(obj+'-trash');
				var ofs = trashEl.offset();

				var x1 = ofs.left;
				var x2 = ofs.left + trashEl.outerWidth(true);
				var y1 = ofs.top;
				var y2 = ofs.top + trashEl.outerHeight(true);

				if (jsEvent.pageX >= x1 && jsEvent.pageX<= x2 &&
				    jsEvent.pageY>= y1 && jsEvent.pageY <= y2) {
					if(confirm("Are You Sure"))
					    $(obj).fullCalendar('removeEvents', [event._id]);
				}
			}



		},options);

		$(obj).fullCalendar(option_extended);

		$(obj+'-trash').droppable({
			 drop: function( event, ui ) {
				$( this )
				.addClass( "ui-state-highlight" )
				.html( "Dropped!" );
				}
		});

	}
}, $.univ._import);