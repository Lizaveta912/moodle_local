<?php
require_once(dirname(__FILE__) . '/../../../config.php');
// require_once($CFG->libdir.'/adminlib.php');
// require_once($CFG->libdir.'/tablelib.php');
//? admin_externalpage_setup('managedepartments');
require_once($CFG->dirroot."/local/variatives/locallib.php");


require_login();

if(! has_capability('local/variatives:manage', context_system::instance()) ){
   print_error('badpermissions'); 
}


$PAGE->set_url('/local/variatives/control/department.php', array(/*'id' => 11111*/));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('Departments','local_variatives'));
$PAGE->set_heading( get_string('Departments','local_variatives'));


$PAGE->requires->js('/local/variatives/control/department.js');

echo $OUTPUT->header();


// -----------------------------------------------------------------------------


// show list of departments as editablegrid

// 
$list=variatives_department_list();
$PAGE->requires->data_for_js('list', $list);

$headers=Array(
    get_string('id','local_variatives'),
    get_string('vardepartmentvisible','local_variatives'),
    get_string('vardepartmentobsolete','local_variatives'),
    get_string('vardepartmentcode','local_variatives'),
    get_string('vardepartmentname','local_variatives')
);

$PAGE->requires->data_for_js('colHeaders', $headers);


// print_r();
echo '<link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/local/variatives/control/handsontable.full.css" />';
echo '<script type="text/javascript" src="'.$CFG->wwwroot.'/local/variatives/control/handsontable.full.js"></script>';
echo '<div id=departmentstable></div>';







// -----------------------------------------------------------------------------

echo $OUTPUT->footer();