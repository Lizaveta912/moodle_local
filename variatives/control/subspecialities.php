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


$PAGE->set_url('/local/variatives/control/speciality.php', array(/*'id' => 11111*/));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('Subspecialities','local_variatives'));
$PAGE->set_heading( get_string('Subspecialities','local_variatives'));


$PAGE->requires->js('/local/variatives/control/subspecialities.js');

echo $OUTPUT->header();


// -----------------------------------------------------------------------------


$varspecialityid       = optional_param('varspecialityid', 0, PARAM_INT);
$varspecialityinfo=  variatives_speciality_get($varspecialityid);
echo '<h4>'.get_string('varsubspeciality_varspecialityid','local_variatives')." {$varspecialityinfo->varspecialityname}</h4>";
//var_dump($varspecialityinfo);


// show list of departments as editablegrid

// 
$list=  variatives_subspeciality_list($varspecialityid);
$PAGE->requires->data_for_js('list', $list);

$headers=Array(
    '',
    get_string('id','local_variatives'),
    get_string('varsubspecialitytitle','local_variatives'),
    get_string('varsubspecialityurl','local_variatives')
);

$PAGE->requires->data_for_js('colHeaders', $headers);


$config=Array(
    'varspecialityid'=>$varspecialityid
);
$PAGE->requires->data_for_js('config', $config);

$i18n=Array(
    'DeletesubSpeciality'=>get_string('DeletesubSpeciality','local_variatives')
);
$PAGE->requires->data_for_js('i18n', $i18n);


// print_r();
echo '<link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/local/variatives/control/handsontable.full.css" />';
echo '<script type="text/javascript" src="'.$CFG->wwwroot.'/local/variatives/control/handsontable.full.js"></script>';
echo '<div id=subspectable></div>';







// -----------------------------------------------------------------------------

echo $OUTPUT->footer();