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
$PAGE->set_title(get_string('Report waiting', 'local_variatives'));
$PAGE->set_heading(get_string('Report waiting', 'local_variatives'));


echo $OUTPUT->header();


$varblockid = (int) $_REQUEST['varblockid'];

$varblock = variatives_block_get($varblockid);
echo "<h2>" . get_string('Block', 'local_variatives') . ": {$varblock->varblockname}</h2>";

$list = array_values(variatives_report_waiting($varblockid));
// print_r($list);
$vardepartmentname = '';
$varformname = '';
$varlevelname = '';
$varspecialityname = '';
$vargroupcode = '';

echo "<ol>";

$nPositive = 0;
$nNegative = 0;
foreach ($list as $row) {
    // print_r($row);
    if ($vardepartmentname != $row->vardepartmentname) {
        $vardepartmentname_h = "<h2>" . get_string('Department', 'local_variatives') . ": {$row->vardepartmentname}</h2>";
        $vardepartmentname = $row->vardepartmentname;
        $varformname = '';
        $varlevelname = '';
        $varspecialityname = '';
        $vargroupcode = '';
    } else {
        $vardepartmentname_h = "";
    }
    if ($varformname != $row->varformname) {
        $varformname_h = " <b>{$row->varformname}</b>&nbsp; ";
        $varformname = $row->varformname;
        $varlevelname = '';
        $varspecialityname = '';
        $vargroupcode = '';
    } else {
        $varformname_h = "";
    }
    if ($varlevelname != $row->varlevelname) {
        $varlevelname_h = " <b>{$row->varlevelname}</b>&nbsp; ";
        $varlevelname = $row->varlevelname;
        $varspecialityname = '';
        $vargroupcode = '';
    } else {
        $varlevelname_h = "";
    }
    if ($varspecialityname != $row->varspecialityname) {
        $varspecialityname_h = " <b>{$row->varspecialityname}</b>&nbsp; ";
        $varspecialityname = $row->varspecialityname;
        $vargroupcode = '';
    } else {
        $varspecialityname_h = "";
    }
    if ($vargroupcode != $row->vargroupcode) {
        $vargroupcode_h = "<div><b>" . get_string('Group', 'local_variatives') . " {$row->vargroupcode}</b></div>";
        $vargroupcode = $row->vargroupcode;
    } else {
        $vargroupcode_h = "";
    }


    $header = "{$vardepartmentname_h}{$varformname_h}{$varlevelname_h}{$varspecialityname_h}{$vargroupcode_h}";
    if (strlen($header) > 0) {
        echo "</ol><div>$header</div><ol>";
    }

    if ($row->rating_exists) {
        $nPositive++;
        echo "<li class=\"rating_exists\">{$row->userlastname} {$row->userfirstname} <a href=\"studrate.php?userid={$row->userid}&varblockid=$varblockid\" target=\"_blank\">view</a></li>";
    } else {
        $nNegative++;
        echo "<li class=\"rating_not_exists\">{$row->userlastname} {$row->userfirstname}</li>";
    }
    
}
echo "</ol>";

echo "Total " . count($list) . ": {$nPositive} rated;  {$nNegative} not rated. ".round(100.0*$nPositive/($nPositive+$nNegative))."% completed";
?>
<style type="text/css">
    .rating_exists:before{
        content:"+";
        background-color:green;
        color:white;
        display:inline-block;
        width:20px;
        text-align:center;
        margin-right:5px;
    }
    .rating_not_exists:before{
        content:"--";
        background-color:red;
        color:white;
        display:inline-block;
        width:20px;
        text-align:center;
        margin-right:5px;
    }
    .rating_exists a{
        display:none;
    }
    .rating_exists:hover a{
        display:inline;
    }
</style>
<?php

echo $OUTPUT->footer();
