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
$PAGE->set_pagelayout('base');
$PAGE->set_title(get_string('Report subspeciality waiting','local_variatives'));
$PAGE->set_heading( get_string('Report subspeciality waiting','local_variatives'));


echo $OUTPUT->header();


$varsubspecialityblockid=(int)$_REQUEST['varsubspecialityblockid'];
$subspecialityblock=  variatives_subspecialityblock_get($varsubspecialityblockid);
echo "<h2>".get_string('subspecialityblock','local_variatives').": {$subspecialityblock->varsubspecialityblockname}</h2>";




$list=array_values(variatives_subspeciality_report_waiting($varsubspecialityblockid));

$senderUserId=$GLOBALS['USER']->id;

// echo "<pre>";print_r($CFG);echo "</pre>";

// echo "<pre>"; print_r($list); echo "</pre>";
foreach($list as $row){
    
    $fullmessagehtml=str_replace(
            Array(
                '{chooseURL}',
                '{siteName}',
                '{username}',
                '{deadlineDate}'
            ),
            Array(
                "{$CFG->wwwroot}/local/variatives/public/choose.php",
                 $CFG->wwwroot,
                "{$row->lastname} {$row->firstname}",
                 date('d.m.Y',$varblock->varsubspecialityblocktimemax)
            ),
            get_string('notification_subspecialityreminder_body','local_variatives')
    );
    $smallmessage=str_replace(
            Array(
                '{chooseURL}',
                '{siteName}',
                '{username}',
                '{deadlineDate}'
            ),
            Array(
                "{$CFG->wwwroot}/local/variatives/public/choose.php",
                 $CFG->wwwroot,
                "{$row->lastname} {$row->firstname}",
                 date('d.m.Y',$varblock->varsubspecialityblocktimemax)
            ),
            get_string('notification_subspecialityreminder_body_small','local_variatives')
    );
    //echo '<hr>'.htmlspecialchars($fullmessagehtml).'<hr>'.htmlspecialchars($smallmessage).'<hr>';
    //exit();
    
    $message = new \core\message\message();
    $message->component = 'moodle';
    $message->name = 'instantmessage';
    $message->userfrom = $senderUserId;
    $message->userto = $row->userid;
    $message->subject = get_string('notification_reminder_subject','local_variatives');
    $message->fullmessage = $fullmessagehtml;
    $message->fullmessageformat = FORMAT_PLAIN;
    $message->fullmessagehtml = $fullmessagehtml;
    $message->smallmessage = $smallmessage;
    $message->notification = '0';
    $message->contexturl = "{$CFG->wwwroot}/local/variatives/public/choose.php";
    $message->contexturlname = get_string('Choose variative courses','local_variatives');
    $message->replyto = $CFG->supportemail;
    // $content = array('*' => array('header' => ' test ', 'footer' => ' test ')); // Extra content for specific processor
    // $message->set_additional_content('email', $content);

    $messageid = message_send($message);
    echo " {$row->userid} {$row->lastname} {$row->firstname};<br>\n";
}



echo $OUTPUT->footer();