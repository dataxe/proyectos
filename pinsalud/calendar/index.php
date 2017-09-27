<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="calendar-blue.css"  />
<script type="text/javascript" src="calendar.js"></script>
<script type="text/javascript" src="calendar-en.js"></script>
<script type="text/javascript" src="calendar-setup.js"></script>
</head>
<body>
<form action="index2.php" method="get">
<input type="text" name="date" id="f_date_b" /><button type="reset" id="f_trigger_b">...</button>
<input type="submit" id="boton1" value="Enviar"></td>
</form>
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "f_date_b",      // id of the input field
        ifFormat       :    "%m/%d/%Y %I:%M %p",       // format of the input field
        showsTime      :    true,            // will display a time selector
        button         :    "f_trigger_b",   // trigger for the calendar (button ID)
        singleClick    :    true,           
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
</script>
</body>
</html>
