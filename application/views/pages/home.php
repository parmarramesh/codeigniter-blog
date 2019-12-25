<div class="container">
  <div id="calendar"></div>
</div>



<script type="text/javascript">
$(document).ready(function(){
  var calendar = $('#calendar').fullCalendar({
    editable: true,
    header: {
      left: 'prev,next today',
      center: 'title',
      right: 'month,agendaWeek,agendaDay'
    },
    events:"<?php echo base_url(); ?>fullcalendar/load",
    selectable: true,
    selectHelper: true,
    select:function(start, end, allDay){
      var title = prompt('Enter event title');
      if(title){
        var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
        var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
        $.ajax({
          url: "<?php echo base_url(); ?>fullcalendar/insert",
          type: 'POST',
          data: {title: title, start: start, end: end},
          success: function(){
            calendar.fullCalendar('refetchEvents');
            alert('Added successfully');
          }
        });
      }
    },

    editable: true,
    eventResize: function(event){
      var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
      var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
      var title = event.title;
      var id = event.id;
      $.ajax({
        url: "<?php echo base_url(); ?>fullcalendar/update",
        type: 'POST',
        data: {title: title, start: start, end: end, id: id},
        success: function(){
          calendar.fullCalendar('refetchEvents');
          alert('Event updated successfully');
        }
      });
    },

    eventDrop: function(event){
      var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
      var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
      var title = event.title;
      var id = event.id;
      console.log('droped');
      $.ajax({
        url: "<?php echo base_url(); ?>fullcalendar/update",
        type: 'post',
        data: {title: title, start: start, end:end,id: id},
        success: function(){
          calendar.fullCalendar('refetchEvents');
          alert('Event update');
        }
      });
    },

    eventClick: function(event){
      if(confirm('Are you sure you want to remove it')){
        var id = event.id;
        $.ajax({
          url: "<?php echo base_url(); ?>fullcalendar/delete",
          type: 'post',
          data: {id: id},
          success: function(){
            calendar.fullCalendar('refetchEvents');
            alert('Event removed');
          }
        });
      }
    }


  });
});
</script>
