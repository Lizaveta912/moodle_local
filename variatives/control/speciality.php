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
$PAGE->set_title(get_string('Specialities','local_variatives'));
$PAGE->set_heading( get_string('Specialities','local_variatives'));


$PAGE->requires->js('/local/variatives/control/speciality.js');

echo $OUTPUT->header();


// -----------------------------------------------------------------------------


// show list of departments as editablegrid

// 
$list =  variatives_speciality_list();
$PAGE->requires->data_for_js('list', $list);

$headers=Array(
    "",
    get_string('id','local_variatives'),
    get_string('varspecialitydepartmentname','local_variatives'),
    get_string('varformname','local_variatives'),
    get_string('varlevelname','local_variatives'),
    get_string('varspecialitycode','local_variatives'),
    get_string('varspecialityname','local_variatives'),
    get_string('varspecialityedboid','local_variatives'),
    get_string('varspecialityvisible','local_variatives'),
    get_string('varspecialityobsolete','local_variatives')
);
$PAGE->requires->data_for_js('colHeaders', $headers);

$i18n=Array(
    'varsubspeciality_varspecialityid'=>get_string('varsubspeciality_varspecialityid','local_variatives'),
    'varspeciality_edit'=>get_string('varspeciality_edit','local_variatives')
);
$PAGE->requires->data_for_js('i18n', $i18n);


echo '<link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/local/variatives/control/handsontable.full.css" />';
echo '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">';
echo '<link rel="stylesheet" href="'.$CFG->wwwroot.'/local/variatives/control/control.css">';
echo '<script type="text/javascript" src="'.$CFG->wwwroot.'/local/variatives/control/handsontable.full.js"></script>';

echo '<div><a href="javascript:void(createSpeciality())">'.get_string('createSpeciality','local_variatives').'</a></div>';
echo '<div id=specialitytable style="margin-bottom:30px;"></div>';

echo '
<div id="dialog" title="'.  htmlspecialchars(get_string('varspeciality_edit','local_variatives')).'" style="display:none;">
    <input type=hidden name=rowid class=formel value="">
    <input type=hidden name=id class=formel value="">
    <div>
        <div>'.get_string('varspecialitydepartmentname','local_variatives').'</div>
        <select name=vardepartmentid class=formel><option value=""></option>'.
         variatives_draw_options('', variatives_department_options())
        .'</select>
    </div>
    <div>
        <div>'.get_string('varformname','local_variatives').'</div>
        <select name=varformid class=formel><option value=""></option>'.
         variatives_draw_options('', variatives_form_options())
        .'</select>
    </div>
    <div>
        <div>'.get_string('varlevelname','local_variatives').'</div>
        <select name=varlevelid class=formel><option value=""></option>'.
         variatives_draw_options('', variatives_level_options())
        .'</select>
    </div>
    <div>
        <div>'.get_string('varspecialitycode','local_variatives').'</div>
        <input type=text name=varspecialitycode class=formel value="">
    </div>
    <div>
        <div>'.get_string('varspecialityname','local_variatives').'</div>
        <input type=text name=varspecialityname class=formel value="">
    </div>
    <div>
        <div>'.get_string('varspecialityedboid','local_variatives').'</div>
        <input type=text name=varspecialityedboid class=formel value="">
    </div>
    <div>
           <label><input type=checkbox name=varspecialityvisible class=formel value="">
           '.get_string('varspecialityvisible','local_variatives').'</label>
    </div>
    <div>
        <label><input type=checkbox name=varspecialityobsolete class=formel value="">
        '.get_string('varspecialityobsolete','local_variatives').'</label>
    </div>
    <div>
        <input type=button id=saveupdates value="'.get_string('saveupdates','local_variatives').'">
    </div>
</div>
';



// -----------------------------------------------------------------------------

echo $OUTPUT->footer();