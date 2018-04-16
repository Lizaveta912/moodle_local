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


$PAGE->set_url('/local/variatives/control/subspecialityblocks.php', array(/*'id' => 11111*/));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('SubspecialityBlocks','local_variatives'));
$PAGE->set_heading( get_string('SubspecialityBlocks','local_variatives'));


$PAGE->requires->js('/local/variatives/control/subspecialityblocks.js');

echo $OUTPUT->header();
// -----------------------------------------------------------------------------

$list = variatives_subspecialityblock_list();
$PAGE->requires->data_for_js('list', $list);


$headers=Array(
    "", //action
    get_string('id','local_variatives'),
    get_string('varsubspecialityblocktimemin','local_variatives'),
    get_string('varsubspecialityblockname','local_variatives'),
    get_string('varformname','local_variatives'),
    get_string('varlevelname','local_variatives'),
    get_string('vardepartmentname_ext','local_variatives'),
    get_string('varspecialityname_ext','local_variatives'),
    get_string('vargroupyear','local_variatives'),
    // get_string('varsubspecialityblocktimemax','local_variatives'),
    // get_string('varsubspecialityblockisarchive','local_variatives'),
);
$PAGE->requires->data_for_js('colHeaders', $headers);

$colWidth=Array(
    150, // actions
    35,  // id
    80,  // varsubspecialityblocktimemin
    200, // varsubspecialityblockname
    60,  // varformname
    80,  // varlevelname
    120,  // vardepartmentname_ext
    120, // varspecialityname_ext
    50, // vargroupyear
    //50,  // varsubspecialityblocktimemax
    //50,  // varsubspecialityblockisarchive
    );
$PAGE->requires->data_for_js('colWidth', $colWidth);

?>
<style type="text/css">
    .blocklistbtn{
        margin-right:5px;
        border:1px solid white;
    }
    .blocklistbtn:hover{
        border-color:#e0e0e0;
    }
</style>
<?php
$i18n=Array(
    'subspecialityblock_properties'=>get_string('subspecialityblock_properties','local_variatives'),
    'Report subspeciality waiting'=>get_string('Report subspeciality waiting','local_variatives'),
    'Report subspeciality enrolled'=>get_string('Report subspeciality enrolled','local_variatives'),
    'Auto_subspeciality_assignment'=>get_string('Auto_subspeciality_assignment','local_variatives'),
    'Delete subspeciality block'=>get_string('Delete subspeciality block','local_variatives'),
    'subspeciality_reminder_subject'=>get_string('subspeciality_reminder_subject','local_variatives')
);
$PAGE->requires->data_for_js('i18n', $i18n);

echo '<link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/local/variatives/control/handsontable.full.css" />';
echo '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">';
echo '<link rel="stylesheet" href="'.$CFG->wwwroot.'/local/variatives/control/control.css">';
echo '<script type="text/javascript" src="'.$CFG->wwwroot.'/local/variatives/control/handsontable.full.js"></script>';

echo '<div>'
   . '<a href="'.new moodle_url('/local/variatives/control/subspecialityblock.php').'">'.get_string('createSubspecialityBlock','local_variatives').'</a><br><br>'
       . '<span>'.get_string('vargrouplevelname','local_variatives').'</span>'
       .' <select id=filter_varlevelid class=filter><option value=""></option>'.
         variatives_draw_options('', variatives_level_options())
        .'</select>'
       . '&nbsp; <span>'.get_string('vargroupformname','local_variatives').'</span>'
       .' <select id=filter_varformid class=filter><option value=""></option>'.
         variatives_draw_options('', variatives_form_options())
        .'</select>'
       . '&nbsp; <span>'.get_string('vargroupyear','local_variatives').'</span>'
       .' <input id=filter_vargroupyear class=filter size=5 type=text>'
       . '&nbsp; <span>'.get_string('vardepartmentname_ext','local_variatives').'</span>'
       .' <select id=filter_vardepartmentid class=filter><option value=""></option>'.
         variatives_draw_options('', variatives_department_options())
        .'</select>'
       . '<br><span>'.get_string('varspecialityname_ext','local_variatives').'</span>'
       .' <select id=filter_varspecialityid class=filter style="max-width:80%"><option value=""></option>'.
         variatives_draw_options('', variatives_speciality_options())
        .'</select>'
   . '</div>';

echo '<div id=blocktable style="margin-bottom:30px;"></div>';

// -----------------------------------------------------------------------------

echo $OUTPUT->footer();