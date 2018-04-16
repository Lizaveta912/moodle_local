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


$PAGE->set_url('/local/variatives/control/blocks.php', array(/*'id' => 11111*/));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('Blocks','local_variatives'));
$PAGE->set_heading( get_string('Blocks','local_variatives'));


$PAGE->requires->js('/local/variatives/control/blocks.js');

echo $OUTPUT->header();
// -----------------------------------------------------------------------------

$list = variatives_block_list();
$PAGE->requires->data_for_js('list', $list);


$headers=Array(
    "",
    get_string('id','local_variatives'),
    get_string('varblockname','local_variatives'),
    get_string('vargroupformname','local_variatives'),
    get_string('vargrouplevelname','local_variatives'),
    get_string('vargroupyear','local_variatives'),
    get_string('varblocktimestampfrom','local_variatives'),
    get_string('varblocktimestampto','local_variatives'),
    get_string('varblockisarchive','local_variatives'),
);
$PAGE->requires->data_for_js('colHeaders', $headers);

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
    'Block Properties'=>get_string('Block Properties','local_variatives'),
    'Report waiting'=>get_string('Report waiting','local_variatives'),
    'Report enrolled'=>get_string('Report enrolled','local_variatives'),
    'Auto_assignment'=>get_string('Auto_assignment','local_variatives'),
    'Delete block'=>get_string('Delete block','local_variatives'),
    'notification_reminder_subject'=>get_string('notification_reminder_subject','local_variatives'),
    'Block Create Copy'=>get_string('block_create_copy','local_variatives'),
    'Course_stats'=>get_string('Course_stats','local_variatives'),
);
$PAGE->requires->data_for_js('i18n', $i18n);

echo '<link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/local/variatives/control/handsontable.full.css" />';
echo '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">';
echo '<link rel="stylesheet" href="'.$CFG->wwwroot.'/local/variatives/control/control.css">';
echo '<script type="text/javascript" src="'.$CFG->wwwroot.'/local/variatives/control/handsontable.full.js"></script>';

echo '<div>'
   . '<a href="'.new moodle_url('/local/variatives/control/block.php').'">'.get_string('createBlock','local_variatives').'</a> &nbsp;&nbsp;&nbsp; '
   . '<a href="javascript:void(window.startEnrollment())">'.get_string('StartEnrollment','local_variatives').'</a>  &nbsp;&nbsp;&nbsp; '
   . '<a href="javascript:void(window.reportEnrolled())">'.get_string('Report enrolled','local_variatives').'</a>  &nbsp;&nbsp;&nbsp; '
       . '<br><br><span>'.get_string('vargrouplevelname','local_variatives').'</span>'
       .' <select id=filter_varlevelid class=filter><option value=""></option>'.
         variatives_draw_options('', variatives_level_options())
        .'</select>'
       . '&nbsp; <span>'.get_string('vargroupformname','local_variatives').'</span>'
       .' <select id=filter_varformid class=filter><option value=""></option>'.
         variatives_draw_options('', variatives_form_options())
        .'</select>'
       . '&nbsp; <span>'.get_string('vargroupyear','local_variatives').'</span>'
       .' <input id=filter_vargroupyear class=filter size=5 type=text>'
       . '&nbsp; <span>'.get_string('varblockname','local_variatives').'</span>'
       .' <input id=filter_varblockname class=filter size=15 type=text>'
   . '</div>';

echo '<div id=blocktable style="margin-bottom:30px;"></div>';



// -----------------------------------------------------------------------------

echo $OUTPUT->footer();