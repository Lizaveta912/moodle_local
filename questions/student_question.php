<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  $(".js-example-basic-single").select2();
});
</script>

<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet"/>
<!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<?php 

  if (file_exists('../../config.php'))
    require_once('../../config.php');
else
    die;

require_once(dirname(__FILE__) . '/../../config.php');
// require_once($CFG->libdir.'/adminlib.php');
// require_once($CFG->libdir.'/tablelib.php');
//? admin_externalpage_setup('managedepartments');
require_once($CFG->dirroot . "/local/searchusersprofile/lib.php");
include '/../../_ldap/ldap.php';

require_login();

if (!has_capability('local/searchusersprofile:manage', context_system::instance())) {
    print_error('badpermissions');
}

$mysqli = new mysqli($CFG->dbhost, $CFG->dbuser, $CFG->dbpass, $CFG->dbname);
if ($mysqli->connect_errno) {
    echo "DB connection error: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}



$PAGE->set_url('/local/searchusersprofile/student_question.php', array(/* 'id' => 11111 */));
$PAGE->set_pagelayout('base');
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('Student questions', 'local_searchusersprofile'));
$PAGE->set_heading(get_string('Student questions', 'local_searchusersprofile'));

echo $OUTPUT->header();
?>



<!DOCTYPE html>
<html>
<head>
	<title>Stud</title>
</head>
<body>

<h3>
<form action=\"lll.php\" method=\"post\">

<div>
	<label class="has-float-label">Дата народження:</label>
	<select class="form-control">
		<option>1</option>
		<option>2</option>
		<option>3</option>
		<option>4</option>
		<option>5</option>
		<option>6</option>
		<option>7</option>
	</select>
</div>

<div>
	<label class="has-float-label">Курс</label>
	<select class="form-control">
		<option>1 курс</option>
		<option>2 курс</option>
		<option>3 курс</option>
		<option>4 курс</option>
		<option>магістри 1 курс</option>
		<option>магістри 2 курс</option>
	</select>
</div>

</form>
</h3>
</body>
</html>



<?php
echo $OUTPUT->footer();
?>