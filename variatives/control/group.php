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


$PAGE->set_url('/local/variatives/control/group.php', array(/*'id' => 11111*/));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('Groups','local_variatives'));
$PAGE->set_heading( get_string('Groups','local_variatives'));


$PAGE->requires->js('/local/variatives/control/group.js');

echo $OUTPUT->header();
// -----------------------------------------------------------------------------


// 
$list =  variatives_group_list();
$PAGE->requires->data_for_js('list', array_values($list) );

$headers=Array(
    "",
    

    get_string('id','local_variatives'),
    get_string('vargroupcode','local_variatives'),
    get_string('cohortname','local_variatives'),
    get_string('vargroupyear','local_variatives'),
    get_string('vargroupdepartmentname','local_variatives'),
    get_string('vargroupformname','local_variatives'),
    get_string('vargrouplevelname','local_variatives'),
    get_string('vargroupspecialityname','local_variatives'),
    
    get_string('vargroupedbocode','local_variatives'),
    
);

$PAGE->requires->data_for_js('colHeaders', $headers);




// print_r();
echo '<link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/local/variatives/control/handsontable.full.css" />';
echo '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">';
echo '<link rel="stylesheet" href="'.$CFG->wwwroot.'/local/variatives/control/control.css">';
echo '<script type="text/javascript" src="'.$CFG->wwwroot.'/local/variatives/control/handsontable.full.js"></script>';

echo '<div>'
   . '<a href="javascript:void(createGroup())">'.get_string('createGroup','local_variatives').'</a>'
       . '&nbsp; <span>'.get_string('vargroupdepartmentname','local_variatives').'</span>'
       .' <select id=filter_vardepartmentid class=filter><option value=""></option>'.
         variatives_draw_options('', variatives_department_options())
        .'</select>'
       . '&nbsp; <span>'.get_string('vargroupcode','local_variatives').'</span>'
       .' <input id=filter_vargroupcode class=filter size=5>'
   . '</div>';

echo '<div id=grouptable style="margin-bottom:30px;"></div>';


// var_dump(variatives_cohortid_options());

echo '
<div id="dialog" title="'.  htmlspecialchars(get_string('vargroup_edit','local_variatives')).'" style="display:none;">
    <input type=hidden name=rowid class=formel value="">
    <input type=hidden name=id class=formel value="">
    <div>
        <div>'.get_string('vargroupcode','local_variatives').'</div>
        <input type=text name=vargroupcode class=formel value="">
    </div>
    <div>
        <div>'.get_string('vargroupyear','local_variatives').'</div>
        <input type=text name=vargroupyear class=formel value="">
    </div>
    <div>
        <div>'.get_string('cohortname','local_variatives').'</div>
        <select name=cohortid class=formel><option value=""></option>'.
         variatives_draw_options('', variatives_cohortid_options())
        .'</select>
    </div>
    <div>
        <div>'.get_string('vargroupdepartmentname','local_variatives').'</div>
        <select name=vardepartmentid class=formel><option value=""></option>'.
         variatives_draw_options('', variatives_department_options())
        .'</select>
    </div>
    <div>
        <div>'.get_string('vargroupformname','local_variatives').'</div>
        <select name=varformid class=formel><option value=""></option>'.
         variatives_draw_options('', variatives_form_options())
        .'</select>
    </div>
    <div>
        <div>'.get_string('vargrouplevelname','local_variatives').'</div>
        <select name=varlevelid class=formel><option value=""></option>'.
         variatives_draw_options('', variatives_level_options())
        .'</select>
    </div>
    <div>
        <div>'.get_string('vargroupspecialityname','local_variatives').'</div>
        <select name=varspecialityid class=formel><option value=""></option>'.
         variatives_draw_options('', variatives_speciality_options())
        .'</select>
    </div>
    <div>
        <div>'.get_string('vargroupedbocode','local_variatives').'</div>
        <input type=text name=vargroupedbocode class=formel value="">
    </div>
    <div>
        <div>'.get_string('vargroupnotes','local_variatives').'</div>
        <textarea type=text name=vargroupnotes class=formel></textarea>
    </div>
    
    <div>
        <input type=button id=saveupdates value="'.get_string('saveupdates','local_variatives').'">
    </div>
</div>
';

// -----------------------------------------------------------------------------

echo $OUTPUT->footer();