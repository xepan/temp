$.each({

	initaitrack: function(id){
		if(typeof id === 'undefined'){
			id='[component_type=IntelligentBlock]';
		}

		$(id).bind('inview', function (event, visible, topOrBottomOrBoth) {
		  if (visible == true) {
		    // element is now visible in the viewport
		    if($(this).attr('inview')==='yes') return;
			$(this).attr('inview','yes');

			if($(this).attr('data-aitrack')=='Visible') $.univ.aitrack($(this).attr('id'),$(this).attr('data-dimension-id'),'Visible');

			// console.log('in ' + $(this).text());
		    if (topOrBottomOrBoth == 'top') {
		      // top part of element is visible
				if($(this).attr('data-aitrack')=='Top') $.univ.aitrack($(this).attr('id'),$(this).attr('data-dimension-id'),'Top');
		    } else if (topOrBottomOrBoth == 'bottom') {
				if($(this).attr('data-aitrack')=='Bottom') $.univ.aitrack($(this).attr('id'),$(this).attr('data-dimension-id'),'Bottom');
		      // bottom part of element is visible
		    } else {
				if($(this).attr('data-aitrack')=='Top-Bottom') $.univ.aitrack($(this).attr('id'),$(this).attr('data-dimension-id'),'Top-Bottom');
		      // whole part of element is visible
		    }
		  } else {
		    // element has gone out of viewport
		    $(this).attr('inview','no');
		    // ViewOut Not recorded now
		    // $.univ.aitrack($(this).attr('id'),$(this).attr('data-dimension-id'),'View-Out');
		  }
		});

		$(id).on('removeComponent',function(){
			// alert('removed');
		});

		$('body').trigger('checkInView.inview');
	},

	initEngagingText: function(){
		$.atk4.includeJS("./epan-components/xAi/templates/js/jquery.expander.min.js");
		$.atk4(function(){
			$('[component_type=EngagingText]').each(function(index){
				$(this).expander(temp = {

			  slicePoint: $(this).attr("slicepoint"),
			  showWordCount: $(this).attr("showwordcount") == "true"? true:false,
			  widow: $(this).attr("widow"),
			  expandEffect: $(this).attr("expandeffect"),
			  userCollapseText: $(this).attr("usercollapsetext"),
			  expandText: $(this).attr("expandtext"),
			  summaryClass: $(this).attr("summaryclass"),
			  detailClass: $(this).attr("detailclass"),
			  userCollapse: $(this).attr("usercollapse"),
			  collapseTimer: $(this).attr("collapsetimer") === "0"? 0:$(this).attr("collapsetimer"),

			  afterExpand: function(){
			  	var parent_ib = $(this).parent("[component_type=IntelligentBlock]"); 
			  	console.log($(parent_ib).attr("id"));
			  	console.log($(parent_ib).attr("data-dimension-id"));
			  	$.univ.aitrack($(parent_ib).attr("id"),$(parent_ib).attr("data-dimension-id"),"TextEngaged",$(this).attr("id"));
			  }

			});

			$(this).on("editmode",function(ev){
				$(this).expander("destroy");
			});

			$(this).on("execmode",function(ev){
				$(this).expander("destroy");
				$(this).expander(temp);
			});

			$("body").on("beforeSave",function(ev){
				$(this).expander("destroy");
			});

			$("body").on("saveSuccess",function(ev){
				$(this).expander(temp);
			})	
			});	
		});
	},
	
	imgLightBox: function(ib_id,dimension, img_src){
		console.log('imgLightBox');
	},

	readMore:function(ib_id,dimension){
		console.log('Scroll Reached');
	},

	aitrack: function(ib_id_in,dimension_in,eventname_in,value_in){
		console.log('In View '+ ib_id_in);

		if(ai_marked_visited.indexOf(ib_id_in+dimension_in+eventname_in+value_in) != -1) return;
		
		setTimeout(function()
			{
				console.log('Watching ' + ib_id_in);

				if($('#'+ib_id_in).attr('inview') != 'yes') return;
				
				console.log('Logging ' + ib_id_in);

				$.ajax({
					url: 'index.php?page=xAi_page_recordContentVisits',
					type: 'POST',
					// dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
					data: {iblock_id: ib_id_in, dimension_id: dimension_in, eventname: eventname_in, value: value_in},
				})
				.done(function(ret) {
					eval(ret);
				})
				.fail(function(err) {
					console.log("error: "+ err);
				})
				.always(function() {
					ai_marked_visited.push(ib_id_in+dimension_in+eventname_in+value_in);
				});

			}, 6000,ib_id_in,dimension_in,eventname_in,value_in);
		
	}

},$.univ._import);

var ai_marked_visited = [];
var ai_on_page = [];


$('body').on('beforeSave',function(){
	$('[component_type=IntelligentBlock]').attr('inview','no');
});