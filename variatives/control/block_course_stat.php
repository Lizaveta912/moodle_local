<?php

function variatives_userblockcourse_get($varblockid) {
    global $DB, $CFG;
	$sql = "SELECT {$CFG->prefix}course.id, {$CFG->prefix}course.fullname, IFNULL({$CFG->prefix}var_userblockcourse_1.n,0) AS n1, IFNULL({$CFG->prefix}var_userblockcourse_2.n,0) AS n2,
IFNULL ({$CFG->prefix}var_userblockcourse_3.n,0) AS n3
FROM {$CFG->prefix}course


LEFT JOIN  (SELECT id, courseid, COUNT(*) AS n
FROM {$CFG->prefix}var_userblockcourse
WHERE {$CFG->prefix}var_userblockcourse.varblockid = $varblockid AND {$CFG->prefix}var_userblockcourse.varuserblockcourserating IN(1)
GROUP BY {$CFG->prefix}var_userblockcourse.courseid) AS {$CFG->prefix}var_userblockcourse_1 ON {$CFG->prefix}course.id={$CFG->prefix}var_userblockcourse_1.courseid

LEFT JOIN (SELECT id, courseid, COUNT(*) AS n
FROM {$CFG->prefix}var_userblockcourse
WHERE {$CFG->prefix}var_userblockcourse.varblockid = $varblockid AND {$CFG->prefix}var_userblockcourse.varuserblockcourserating IN(2)
GROUP BY {$CFG->prefix}var_userblockcourse.courseid) AS {$CFG->prefix}var_userblockcourse_2 ON {$CFG->prefix}course.id={$CFG->prefix}var_userblockcourse_2.courseid

LEFT JOIN  (SELECT id, courseid, COUNT(*) AS n
FROM {$CFG->prefix}var_userblockcourse
WHERE {$CFG->prefix}var_userblockcourse.varblockid = $varblockid AND {$CFG->prefix}var_userblockcourse.varuserblockcourserating IN(3)
GROUP BY {$CFG->prefix}var_userblockcourse.courseid) AS {$CFG->prefix}var_userblockcourse_3 ON {$CFG->prefix}course.id={$CFG->prefix}var_userblockcourse_3.courseid

WHERE {$CFG->prefix}course.id  In ( select courseid from {$CFG->prefix}var_userblockcourse where varblockid=$varblockid)
ORDER BY n1 DESC, n2 DESC, n3 DESC;";

    $list = array_values($DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0));
    return $list;
}




require_once(dirname(__FILE__) . '/../../../config.php');
// require_once($CFG->libdir.'/adminlib.php');
// require_once($CFG->libdir.'/tablelib.php');
//? admin_externalpage_setup('managedepartments');
require_once($CFG->dirroot."/local/variatives/locallib.php");


require_login();

if(! has_capability('local/variatives:manage', context_system::instance()) ){
   print_error('badpermissions'); 
}


$PAGE->set_url('/local/variatives/control/block.php', array(/* 'id' => 11111 */));
$PAGE->set_pagelayout('base');
$PAGE->set_title(get_string('Course_stats','local_variatives'));
$PAGE->set_heading( get_string('Course_stats','local_variatives'));


echo $OUTPUT->header();

//$varblockid=9;
$varblockid=(int)$_REQUEST['varblockid'];

$varblock = variatives_block_get($varblockid);
echo "<h2>" . get_string('Block', 'local_variatives') . ": {$varblock->varblockname}</h2>";

$userblockcourse= variatives_userblockcourse_get($varblockid);
//echo "<h2>".get_string('userblockcourse','local_variatives').": {$userblockcourse->varsubspecialityblockname}</h2>";

	echo "<table border=2 align=center >\n";
	echo "<tr><td><b>courseid</b></td><td><b>Название курса</b></td><td id=n><b>Первое место (кол-во человек)</b></td><td><b>Второе место (кол-во человек)</b></td><td><b>Третье место (кол-во человек)</b></td></tr>";
foreach ($userblockcourse AS $row) {	
	echo "<tr>"."<td>".$row->id ."</td>"."<td id=fullname>".$row->fullname."</td>"."<td>".$row->n1."</td>"."<td>".$row->n2."</td>"."<td>".$row->n3."</td>"."</tr>";
}	
	echo "</table>\n";
echo $OUTPUT->footer(); 	
?>
<style type="text/css">
TD {
    text-align: center;
}	
#n{
	word-wrap: break-word;
}
#fullname{
	text-align: left;
		width: 65%;
}
	
</style>