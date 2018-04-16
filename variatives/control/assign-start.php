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


$PAGE->set_url('/local/variatives/control/block.php', array(/*'id' => 11111*/));
$PAGE->set_pagelayout('base');
$PAGE->set_title(get_string('Auto_assignment_stage_1','local_variatives'));
$PAGE->set_heading( get_string('Auto_assignment_stage_1','local_variatives'));


echo $OUTPUT->header();


echo "<h2>".get_string('Block','local_variatives')."</h2>";



$varblockids=explode(',',$_REQUEST['varblockid']);
$varblocks=Array();
for($i=0, $cnt=count($varblockids); $i<$cnt; $i++){
    $varblockids[$i]=(int)$varblockids[$i];
    if($varblockids[$i]>0){
        $varblock=variatives_block_get($varblockids[$i]);
        echo "<div>{$varblock->varblockname}</div>";
        $varblocks[]=$varblock;
    }else{
        unset($varblockids[$i]);
    }
}

if(count($varblockids)>0){
    set_time_limit(600);
    $assignment = new variatives_assignment();
    $assignment->initiate($varblockids);
    //echo '<pre>'; print_r($assignment->assignment); echo '</pre>'; 
    $count_students=$assignment->count_students();
    arsort($count_students);
    echo '<pre>'; print_r($count_students); echo '</pre>'; 

    echo '<pre>'; print_r($assignment); echo '</pre>'; 

}



echo get_string('Auto_assignment_stage_1_pre_assign','local_variatives');

echo "
<a href=\"assign.php?varblockid=".join(',',$varblockids)."\">Continue</a>
<script type=\"application/javascript\">
//   window.location.href=\"assign.php?varblockid=".join(',',$varblockids)."\";
</script>
";

echo $OUTPUT->footer();