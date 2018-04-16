<?php
if (file_exists('../../config.php'))
    require_once('../../config.php');
else
    die;
//echo "Change password  >_<  ".(isset($_GET['customint1'])?$_GET['customint1']:'???');

function course_get($customint1) {
    global $DB, $CFG;

    $sql = "SELECT e.courseid, c.fullname, ggg.n
    FROM mdl_enrol AS e, mdl_course AS c
    LEFT JOIN(SELECT COUNT(*) AS n, con.instanceid
    FROM mdl_context AS con, mdl_question AS q, mdl_question_categories AS q_c
    WHERE con.contextlevel=50 
          AND con.id=q_c.contextid 
          AND q_c.id=q.category 
          AND (
               LOCATE('ректор',q_c.name)
          OR   LOCATE('rector',q_c.name) 
          )
          AND (
              LOCATE('контрол',q_c.name)
          OR  LOCATE('тест',q_c.name) 
          OR  LOCATE('control',q_c.name) 
          OR  LOCATE('test',q_c.name) 
          OR  LOCATE('quiz',q_c.name) 
          )
    GROUP BY q.category, con.instanceid) AS ggg
    ON ggg.instanceid=c.`id`
WHERE c.id = e.courseid AND e.enrol= 'cohort' AND e.`customint1`=$customint1 
GROUP BY e.`courseid`           
ORDER BY c.fullname
;";

    $list = array_values($DB->get_records_sql($sql, $params = null, $limitfrom = 0, $limitnum = 0));

    return $list;
}

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot . "/local/contingents/lib.php");


require_login();

global $DB, $CFG;

$canManage = has_capability('local/contingents:manage', context_system::instance());
$canView   = has_capability('local/contingents:view', context_system::instance());

if (!$canManage && !$canView) {
    print_error('badpermissions');
}

$PAGE->set_url('/local/contingents/contingents.php', array(/*'id' => 11111*/));
$PAGE->set_title(get_string('Contingents Group Search', 'local_contingents'));
$PAGE->set_heading(get_string('Contingents Group Search', 'local_contingents'));


echo $OUTPUT->header();


//$customint1=785;
$customint1 = isset($_GET['customint1'])?$_GET['customint1']:'???';
$courses = course_get($customint1);
$ggg = "SELECT c.id, c.`description`
    FROM `mdl_cohort` AS c
    WHERE c.id=$customint1
    ";
$result_ggg= array_values($DB->get_records_sql($ggg, $params = null, $limitfrom = 0, $limitnum = 0));
//$result_ggg = mysqli_query($dbconnect,$ggg);
echo "<h2>{$result_ggg[0]->description}</h2>";
echo "<table border=1 align=center >\n";
	echo "<tr><td><b>id курса</b></td><td><b>Ректорський контроль</b></td><td><b>Назва курса</b></td> ";
	foreach ($courses AS $row) {
    echo "<tr><td>{$row->courseid}</td><td>{$row->n}</td><td><a title='Курс' href = '../../course/view.php?id={$row->courseid}'>{$row->fullname}</a></td>
          </tr>";
}
echo "</table>\n";

echo $OUTPUT->footer();
?>
<style type="text/css">
    
    td {

        padding-left: 20px;
        padding-right: 10px;
    }	

    tbody {
        margin: 0;
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
        font-size: 14px;
        line-height: 20px;
        color: #333;
        background-color: #fff;
    }

</style>