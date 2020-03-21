function mobileScheduler(showDate,create_event_url,events){
	var str="";
	str+="<div class='mobileScheduler'>";
	str+="<div class='calendar_plan'>";
	str+="<div class='schedulerHeader'><div class='cl_title'>Today</div>";
	str+="<div class='cl_copy'>"+moment(showDate,"YYYY-MM-DD").format('DD MMM YYYY')+"</div><div class='cl_add'>";
	str+="<a href='"+create_event_url+"'><i class='fas fa-plus fa-2x'></i></a></div></div></div>";
	str+="<div class='calendar_events'><p class='ce_title'>Upcoming Events</p>";
	$.each(events, function(event_id,event_detail) {
		str+="<div class='event_item'>";
		str+="<div class='ei_Dot dot_active'></div>";
		str+="<div class='ei_Title'>"+event_detail.fromTime+"</div>";
		str+="<div class='ei_Copy'>"+event_detail.title+"</div>";
		str+="</div>";
	});	
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
function get_is_blocked(showDate,resource_id,current,resources_blocked_times){
	var is_blocked = false;
	$.each(resources_blocked_times, function(id,blocked_date) {
		var startObj = moment(showDate + " " + blocked_date.fromTime,"YYYY-MM-DD HH:mm");
		var endObj = moment(showDate + " " + blocked_date.toTime,"YYYY-MM-DD HH:mm");

		if(blocked_date.resource == resource_id){
			if(current >= startObj && current < endObj){
				console.log(blocked_date);
				is_blocked = true;
			}
		}
	});
	return is_blocked;
}
function desktopScheduler(showDate,startTime,endTime,interval,create_event_url,resources,events,resources_blocked_times,is_blocked_date){

	var str="";
	str+="<table class='st_scheduler_desktop'><thead><tr>";
	str+="<th>Time</th>";
	$.each(resources, function(resource_id,resource) {
	  str+="<th data-resource_id='"+resource_id+"'>";
	  str+=resource.title;
	  str+="</th>"
	});
	str+="</tr></thead><tbody>";

	var startObj = moment(showDate + " " + startTime,"YYYY-MM-DD HH:mm");
	var endObj = moment(showDate + " " + endTime,"YYYY-MM-DD HH:mm");
	for(var current = startObj; current < endObj;current=startObj.add(interval,'minute')){
		


		str+="<tr>";
		str+="<td class='st_Time'>"+moment(current).format('HH:mm')+"</td>";
		$.each(resources, function(resource_id,resource) {
			add_event_url = prepare_create_event_url(create_event_url,showDate,current,resource_id);
			if (is_blocked_date.length === 0) {
				is_date_blocked = false;
				add_event_url = "<a class='createEvent' href='"+add_event_url+"'></a>";
			}else{
				is_date_blocked = true;
				add_event_url = is_blocked_date;
			}
		  is_blocked = get_is_blocked(showDate,resource_id,current,resources_blocked_times);

		  str+="<td data-resource_id='"+resource_id+"' ";
		  if(is_blocked || is_date_blocked){
			str+=" class='stBlockedTime' >";
		  }else{
			str+=" >";
		  }
		  str+=cell_content(showDate,resource_id,current,add_event_url,events);
		  str+="</td>"
		});
		str+="</tr>";
	}

	str+="</tbody></table>";
	return str;
}


function prepare_create_event_url(create_event_url,showDate,current,resource_id){
	create_event_url = create_event_url.replace("[show_date]", showDate);
	current = moment(current).format('HH:mm');
	create_event_url = create_event_url.replace("[appointment_time]", current);
	create_event_url = create_event_url.replace("[resource]", resource_id);
	return create_event_url;
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
			create_event_url:'',
			events: [],
			resources: [],
			resources_blocked_times : [],
			is_blocked_date : [],
			showDate : today};

            opt = $.extend(defaults, options);

			events = opt.events;
			resources_blocked_times = opt.resources_blocked_times;
			showDate = opt.showDate;
			startTime  = opt.startTime;
			endTime  = opt.endTime;
			interval  = opt.interval;
			resources  = opt.resources;
			create_event_url  = opt.create_event_url;
			is_blocked_date  = opt.is_blocked_date;



			calender=mobileScheduler(showDate,create_event_url,events);
			calender+=desktopScheduler(showDate,startTime,endTime,interval,create_event_url,resources,events,resources_blocked_times,is_blocked_date);

			$(this).html(calender);
        }
    });
})(jQuery);
