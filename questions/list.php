<?php
function questions_id() {
    global $DB, $CFG;

$query_id = " SELECT id, name, course
FROM {$CFG->prefix}feedback
WHERE course =1
;";
 $list = array_values($DB->get_records_sql($query_id, $params = null, $limitfrom = 0, $limitnum = 0));
    return $list;
}


require_once(dirname(__FILE__) . '/../../config.php');
// require_once($CFG->libdir.'/adminlib.php');
// require_once($CFG->libdir.'/tablelib.php');
//? admin_externalpage_setup('managedepartments');
require_once($CFG->dirroot."/local/questions/lib.php");


require_login();
 
if(! has_capability('local/questions:view', context_system::instance()) ){
   print_error('badpermissions'); 
}

$PAGE->set_url('/local/questions/questions.php', array(/*'id' => 11111*/));
$PAGE->set_pagelayout('base');
//$PAGE->set_title(get_string('Report subspeciality enrolled','local_questions'));
//$PAGE->set_heading( get_string('Report subspeciality enrolled','local_questions'));
$PAGE->set_title("Перелік опитувань");
$PAGE->set_heading("Перелік опитувань");


echo $OUTPUT->header();

$list = questions_id();
echo "<table border='1px' cellpadding='4px'>\n";
foreach ($list as $row)
{
	echo "<tr>
			<td>{$row -> id}</td>
			<td><a href=\"questions_copy.php?id={$row -> id}\">{$row -> name}</a> </td>
          </tr>";
	}
	echo "</table>\n";

echo $OUTPUT->footer();
?>