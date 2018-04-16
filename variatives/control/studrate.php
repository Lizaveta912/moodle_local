<?php


require_once(dirname(__FILE__) . '/../../../config.php');
// require_once($CFG->libdir.'/adminlib.php');
// require_once($CFG->libdir.'/tablelib.php');
//? admin_externalpage_setup('managedepartments');
require_once($CFG->dirroot . "/local/variatives/locallib.php");


require_login();

if (!has_capability('local/variatives:manage', context_system::instance())) {
    print_error('badpermissions');
}


$PAGE->set_url('/local/variatives/control/block.php', array(/* 'id' => 11111 */));
$PAGE->set_pagelayout('base');
$PAGE->set_title(get_string('Student rates', 'local_variatives'));
$PAGE->set_heading(get_string('Student rates', 'local_variatives'));

echo $OUTPUT->header();




$varblockid = (int) $_REQUEST['varblockid'];

$varblock = variatives_block_get($varblockid);
echo "<h3>" . get_string('Block', 'local_variatives') . ": {$varblock->varblockname}</h3>";


global $DB, $CFG;

$userid = (int) $_REQUEST['userid'];
$sql = "SELECT * FROM {$CFG->prefix}user WHERE id=" . ( (int) $userid );
$list = array_values($DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0));
$user=isset($list[0]) ? $list[0] : false;
if(!$user){
   echo "User not found";
   echo $OUTPUT->footer();
}
echo "<h3>{$user->lastname} {$user->firstname} ({$user->username}, {$user->id})</h3>";


$sql="
    SELECT ubc.varuserblockcourserating, ubc.courseid, c.fullname
    FROM mdl_var_userblockcourse AS ubc
        INNER JOIN mdl_course c ON c.id=ubc.courseid
    WHERE userid=".( (int)$userid )." AND varblockid=$varblockid
    ORDER BY varuserblockcourserating ASC
    ";

$list = array_values($DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0));

echo "<ol>";
foreach($list as $ls){
    echo "<li><span style='color:silver;display:inline-block; width:40px;'>{$ls->courseid}</span> {$ls->fullname}</li>";
}
echo "</ol>";


echo $OUTPUT->footer();