<h2 class="center semibold heading">The Noroton Fire Department Calendar</h2>
<div id="calendar">

</div>

<script>
    jQuery(document).ready(function ($) {
        $('#calendar').fullCalendar({
            events: <?= json_encode($calendar_events) ?>
        });
    });
</script>