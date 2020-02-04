function mobileScheduler(showDate,create_even_url){
	var str="";
	str+="<div class='mobileScheduler'>";
	str+="<div class='calendar_plan'>";
	str+="<div class='schedulerHeader'><div class='cl_title'>Today</div>";
	str+="<div class='cl_copy'>"+moment(showDate,"YYYY-MM-DD").format('DD MMM YYYY')+"</div><div class='cl_add'>";
	str+="<a href='"+create_even_url+"'><i class='fas fa-plus fa-2x'></i></a></div></div></div>";
	str+="<div class='calendar_events'><p class='ce_title'>Upcoming Events</p><div class='event_item'>";
	str+="<div class='ei_Dot dot_active'></div><div class='ei_Title'>10:30 am</div><div class='ei_Copy'>Monday briefing with the team</div>";
	str+="</div><div class='event_item'><div class='ei_Dot'></div>";
	str+="<div class='ei_Title'>12:00 pm</div><div class='ei_Copy'>Lunch for with the besties</div></div>";
	str+="</div></div></div>";
	return str;
}
function cell_content(showDate,resource_id,current,create_event_url,events){
	var event_found = false; 
	$.each(events, function(event_id,event_detail) {
		
	  if(resource_id == event_detail.resource){
		  var startObj = moment(showDate + " " + event_detail.fromTime,"YYYY-MM-DD HH:mm");
		  var endObj = moment(showDate + " " + event_detail.toTime,"YYYY-MM-DD HH:mm");
		  if(current >= startObj && current < endObj){
			event_url = "<a class='schedulerEvent' class='"+event_detail.event_class+"' href='"+event_detail.url+"'>"+event_detail.title+"</a>";
			event_found = true;
		  }
	  }
	}); 
	if(event_found){
		return event_url;
	}else{
		return create_event_url;
	}
}
function desktopScheduler(showDate,startTime,endTime,interval,create_even_url,resources,events){
	
	var str="";
	str+="<table class='st_scheduler_desktop'><thead><tr>";
	str+="<th>Time</th>";
	$.each(resources, function(resource_id,resource) {
	  str+="<th data-resource_id='"+resource_id+"'>";
	  str+=resource.title;
	  str+="</th>"
	}); 
	str+="</tr></thead><tbody>";
	create_even_url = "<a class='createEvent' href='"+create_even_url+"'></a>";
	var startObj = moment(showDate + " " + startTime,"YYYY-MM-DD HH:mm");
	var endObj = moment(showDate + " " + endTime,"YYYY-MM-DD HH:mm");
	for(var current = startObj; current < endObj;current=startObj.add(interval,'minute')){
		str+="<tr>";
		str+="<td class='st_Time'>"+moment(current).format('HH:mm')+"</td>";
		$.each(resources, function(resource_id,resource) {
		  str+="<td data-resource_id='"+resource_id+"'>";
		  str+=cell_content(showDate,resource_id,current,create_even_url,events);
		  str+="</td>"
		});
		str+="</tr>";
	}

	str+="</tbody></table>";
	return str;
}



(function($) {
    $.fn.extend({
        schedule: function(options) {

		//Declare & Initialize variables
		var d = new Date();
		var today = d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate();

		var calendar = "";
		
		//Declare and Initialize Defaults
        var defaults = {
			startTime :'09:00',
			endTime :'18:00',
			interval:'60',//mins
			create_even_url:'',
			events: [],
			resources: [],
			showDate : today};

            opt = $.extend(defaults, options);

			events = opt.events;
			showDate = opt.showDate;
			startTime  = opt.startTime;
			endTime  = opt.endTime;
			interval  = opt.interval;
			resources  = opt.resources;
			create_even_url  = opt.create_even_url;
			
			
			
			calender=mobileScheduler(showDate,create_even_url,events);
			calender+=desktopScheduler(showDate,startTime,endTime,interval,create_even_url,resources,events);

			$(this).html(calender);
        }
    });
})(jQuery);

