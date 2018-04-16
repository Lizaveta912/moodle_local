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


//$PAGE->set_url('/local/variatives/control/block.php', array(/*'id' => 11111*/));
//$PAGE->set_pagelayout('popup');
//$PAGE->set_title(get_string('Auto_assignment_stage_2_check_variants','local_variatives'));
//$PAGE->set_heading( get_string('Auto_assignment_stage_2_check_variants','local_variatives'));


//echo $OUTPUT->header();
echo "<!DOCTYPE html><body><h1>".(get_string('Auto_assignment_stage_2_check_variants','local_variatives')).'</h1>';

echo "
    <script type=\"application/javascript\">
        setTimeout('window.location.reload();',90000)
    </script>...
     ";


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
sort($varblockids);


for($i=0; $i<2; $i++){
    $assignment = new variatives_assignment();
    $assignment->maxSearchTreeChildren=2;

    $stat = $assignment->count_queue($varblockids);

    $stat['complete']=$stat['total'] - $stat['waiting'];
    echo "<p> " .round(  $stat['complete'] * 100.0 / ( $stat['total']>0?$stat['total']:1 ) , 2) . "%  ( {$stat['complete']} complete from {$stat['total']}, ".(1*$stat['variants'])." possible variants ) </p>";

    $assignment->load_next_from_queue($varblockids);

    // echo '<pre>'; print_r($assignment); echo '</pre>';  exit();

    if($assignment->assignmentid > 0 ){
        $assignment->processassignment();
    }else{
        break;
    }
}



if($assignment->assignmentid > 0 ){
    echo "
    <script type=\"application/javascript\">
       setTimeout('window.location.href=\"assign.php?varblockid=".join(',',$varblockids)."&t=\"+Math.random();',3000)
    </script>
     ";
}else{
    echo "
    <script type=\"application/javascript\">
        setTimeout('window.location.href=\"assign-finish.php?varblockid=".join(',',$varblockids)."&t=\"+Math.random();',3000)
    </script>
     ";
}


//exit('<hr>0003');




//echo $OUTPUT->footer();
echo "</body></html >";