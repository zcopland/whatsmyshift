$(document).ready(function() {
    
    //query to change all events to allDay = true
    //UPDATE calendar SET allDay = "true"

  var companyID = $('#companyID').val();
  var weatherShow = $('#weatherShow').val();
  var defaultCalView = $('#defaultCalView').val();
  var zip = $('#zip').val();
  var lat = $('#lat').val();
  var lon = $('#long').val();
  var ke = "10449044bf47f3b0347b04a49ea44515";
  var url = "https://api.darksky.net/forecast/" + ke + "/" + lat + "," + lon;
  var proxy = "https://cors-anywhere.herokuapp.com/";
  var isAdmin = $('.isAdmin').text();
  var isEditable;
  if (isAdmin == 1) {
    isEditable = true;
  } else if (isAdmin == 0) {
    isEditable = false;
  }

  var zone = "05:30";  //Change this to your timezone

  $.ajax({
    url: 'db/process.php',
        type: 'POST', // Send post data
        data: 'type=fetch',
        async: false,
        success: function(s){
          json_events = s;
        }
  });


  var currentMousePos = {
      x: -1,
      y: -1
  };
    //track the user's mouse
    jQuery(document).on("mousemove", function (event) {
        currentMousePos.x = event.pageX;
        currentMousePos.y = event.pageY;
    });

    /* initialize the external events
    -----------------------------------------------------------------*/

    $('#external-events .fc-event').each(function() {

      // store data so the calendar knows to render an event upon drop
      $(this).data('event', {
        title: $.trim($(this).text()), // use the element's text as the event title
        stick: true // maintain when user navigates (see docs on the renderEvent method)
      });

      // make the event draggable using jQuery UI
      $(this).draggable({
        zIndex: 999,
        revert: true,      // will cause the event to go back to its
        revertDuration: 0  //  original position after the drag
      });

    });
    
    function getFreshEvents(){
        $.ajax({
          url: 'db/process.php',
              type: 'POST', // Send post data
              data: 'type=fetch',
              //async: false,
              success: function(s){
                freshevents = s;
              }
        });
        //$('#calendar').fullCalendar('addEventSource', JSON.parse(freshevents));
    }

    /* initialize the calendar
    -----------------------------------------------------------------*/

    $('#calendar').fullCalendar({
      events: JSON.parse(json_events),
      //events: [{"id":"14","title":"New Event","start":"2015-01-24T16:00:00+04:00","allDay":false}],
      utc: true,
      header: {
        left: 'prev,next',
        center: 'title',
        right: 'today'
      },
      defaultView: defaultCalView,
      editable: isEditable,
      contentHeight: 800,
      droppable: true, 
      slotDuration: '00:30:00',
      businessHours: {
        start: '4:00',
        end:   '23:30',
        dow: [0, 1, 2, 3, 4, 5, 6]
      },
      eventReceive: function(event){
        var title = event.title;
        var start = event.start.format("YYYY-MM-DD[T]HH:mm:SS");
        $.ajax({
            url: 'db/process.php',
            data: 'type=new&title='+title+'&startdate='+start+'&zone='+zone,
            type: 'POST',
            dataType: 'json',
            success: function(response){
              event.id = response.eventid;
              $('#calendar').fullCalendar('updateEvent',event);
            },
            error: function(e){
              console.log(e.responseText);

            }
          });
        $('#calendar').fullCalendar('updateEvent',event);
        //console.log(event);
      },
      eventDrop: function(event, delta, revertFunc) {
          var title = event.title;
            var start = event.start.format();
            var end = (event.end === null) ? start : event.end.format();
            $.ajax({
          url: 'db/process.php',
          data: 'type=resetdate&title='+title+'&start='+start+'&end='+end+'&eventid='+event.id,
          type: 'POST',
          dataType: 'json',
          success: function(response){
            if(response.status != 'success')                
            revertFunc();
          },
          error: function(e){             
            revertFunc();
            alert('Error processing your request: '+e.responseText);
          }
        });
            
        },
        eventClick: function(event, jsEvent, view) {
          //console.log(event.id);
          if (isEditable) {
            var title = prompt('Shift title:', event.title, { buttons: { Ok: true, Cancel: false} });
              if (title){
                  event.title = title;
                  //console.log('type=changetitle&title='+title+'&eventid='+event.id);
                  $.ajax({
                    url: 'db/process.php',
                    data: 'type=changetitle&title='+title+'&eventid='+event.id,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response){  
                      if(response.status == 'success') {
                          $('#calendar').fullCalendar('updateEvent',event);
                      }              
                    },
                    error: function(e){
                      alert('Error processing your request: '+e.responseText);
                    }
                  });
                }
          }  
      },
      eventResize: function(event, delta, revertFunc) {
        console.log(event);
        var title = event.title;
        var end = event.end.format();
        var start = event.start.format();
            $.ajax({
          url: 'db/process.php',
          data: 'type=resetdate&title='+title+'&start='+start+'&end='+end+'&eventid='+event.id,
          type: 'POST',
          dataType: 'json',
          success: function(response){
            if(response.status != 'success')                
            revertFunc();
          },
          error: function(e){             
            revertFunc();
            alert('Error processing your request: '+e.responseText);
          }
        });
        },
      eventDragStop: function (event, jsEvent, ui, view) {
          if (isElemOverDiv()) {
            var con = confirm('Are you sure to delete this shift permanently?');
            if(con) {
            $.ajax({
                url: 'db/process.php',
                data: 'type=remove&eventid='+event.id,
                type: 'POST',
                dataType: 'json',
                success: function(response){
                  console.log(response);
                  if(response.status == 'success'){
                    $('#calendar').fullCalendar('removeEvents');
                        getFreshEvents();
                        location.reload();
                      }
                },
                error: function(e){ 
                  alert('Error processing your request: '+e.responseText);
                }
              });
          }   
        }
      }
    });
    
    var myEvent, d, text, today;
    var date = new Date();
    date.setDate(date.getDate() - 1);
    
    $.getJSON(proxy + url, function(forecast) {
	    result = forecast;
	    var days = result["daily"]["data"];
	    for (var i = 0; i < days.length; i++) {
		    text = parseWeather(days[i]["summary"]);
		    var high = days[i]["temperatureHigh"];
		    high = Math.round(high);
		    if (text.length > 10) {
    		        text = "~" + text + "~";
		    } else {
    		        text = "~" + text + " | High: " + high + "~";
		    }
		    
		    date.setDate(date.getDate() + 1);
            today = date.toISOString();
            myEvent = {
                title: text,
                allDay: true,
                start: today,
                editable: false,
                color: '#ad42f4'
            };
            if (weatherShow == 1) {
                $('#calendar').fullCalendar('renderEvent', myEvent);
            }
            
	    }
    });
    
    function parseWeather(original) {
        var modified = original;
        var conditions = ["sunny", "sun", "rain", "rain", "cloudy", "clouds", "breezy", "breeze",
            "snowy", "wind", "windy", "overcast", "morning", "clear", "overnight", "drizzle", "evening"];
        original = original.toUpperCase();
        for (var i = 0; i < conditions.length; i++) {
            conditions[i] = conditions[i].toUpperCase();
            if (original.indexOf(conditions[i]) >= 0) {
                modified = modified.substr(0, original.indexOf(conditions[i]) + conditions[i].length);
            }
        }
        
        return modified;
    }
    
    /*
    $.simpleWeather({
        woeid: '2357536', //2357536
        location: zip,
        unit: 'f',
        success: function(weather) {
          for (var i=0;i<weather.forecast.length;i++) {
                text = weather.forecast[i].text;
                text = '~' + text.toString() + '~';
                date.setDate(date.getDate() + 1);
                today = date.toISOString();
                myEvent = {
                    title: text,
                    allDay: true,
                    start: today,
                    editable: false,
                    color: '#ad42f4'
                };
                if (weatherShow == 1) {
                    $('#calendar').fullCalendar('renderEvent', myEvent);
                }
          }
        },
        error: function(error) {
            console.log('Weather error: ' + error);
            location.reload();
        }
    });
    */
  function isElemOverDiv() {
        var trashEl = jQuery('#trash');

        var ofs = trashEl.offset();

        var x1 = ofs.left;
        var x2 = ofs.left + trashEl.outerWidth(true);
        var y1 = ofs.top;
        var y2 = ofs.top + trashEl.outerHeight(true);

        if (currentMousePos.x >= x1 && currentMousePos.x <= x2 &&
            currentMousePos.y >= y1 && currentMousePos.y <= y2) {
            return true;
        }
        return false;
    }

  });