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
$PAGE->set_title(get_string('Auto_assignment_stage_3_finish','local_variatives'));
$PAGE->set_heading( get_string('Auto_assignment_stage_3_finish','local_variatives'));


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
sort($varblockids);

$assignment = new variatives_assignment();
$assignment->load_best_from_queue($varblockids);






if($assignment->assignment){
    // echo '<pre>'; print_r($assignment); echo '</pre>';  exit();
    set_time_limit(600);
    // enroll students to courses

    $userids=array_map(function($e){return $e['userid'];},$assignment->assignment['users']);
    
    // echo '<pre>'; print_r($userids); echo '</pre>';  exit();
    

    $users=variatives_get_users($userids);

    //echo '<pre>'; print_r($users); echo '</pre>';  
    //echo '<pre>'; print_r($assignment->assignment); echo '</pre>';  
    // draw short report
    echo "<h2>Courses</h2>";
    foreach($assignment->assignment['courses'] as $course){
        echo "{$course['course_fullname']}  - ".count($course['users'])." students<br>";
    }

    // draw long report
    $keys=array_keys($assignment->assignment['users']);
    foreach($assignment->assignment['courses'] as $course){
        echo "<h3>{$course['course_fullname']}</h3>".count($course['users'])." students";
        
        $rows=[];
        foreach($course['users'] as $userid){
            //echo "<li>{$userid} </li>";
            $row="<li>";  
            $row.=" {$users[$userid]->lastname}, {$users[$userid]->firstname} ";
            foreach($keys as $key){
                if($assignment->assignment['users'][$key]['userid']==$userid){
                    $row.=", {$assignment->assignment['users'][$key]['vargroupcode']} ";
                }
            }
            $row.="</li>";  
            $rows[]=$row;
        }
        sort($rows);
        echo "<ol>".join(' ',$rows)."</ol>";
    }
    
    
    // do real enrollment
    if(isset($_REQUEST['approve'])){
        
        // ========== do real enrollment = begin ===============================
        $senderUserId=$GLOBALS['USER']->id;
        $keys=array_keys($assignment->assignment['users']);
        foreach($keys as $key){
            $userid=$assignment->assignment['users'][$key]['userid'];
            $varblockid=$assignment->assignment['users'][$key]['varblockid'];
            foreach($assignment->assignment['users'][$key]['assignedcourses'] as $courseid) {
                if(isset($assignment->assignment['courses'][$courseid])){
                    echo "variatives_enroll_to_course varblockid=$varblockid courseid=$courseid, userid=$userid, varblockcoursegroup={$assignment->assignment['courses'][$courseid]['varblockcoursegroup']})<br>";
                    variatives_enroll_create(Array(
                        'varblockid'=>$varblockid,
                        'userid'=>$userid,
                        'courseid'=>$courseid,
                        'varblockcoursegroup'=>$assignment->assignment['courses'][$courseid]['varblockcoursegroup']
                    ));
                }
            }
            
            
            // ============ compose message = begin ============================
            // list of course names
            $courseText='';
            foreach($assignment->assignment['users'][$key]['assignedcourses'] as $courseid) {
                if(isset($assignment->assignment['courses'][$courseid])){
                    $courseText.="{$assignment->assignment['courses'][$courseid]['course_fullname']};\n";
                }
            }

            $fullmessagehtml=str_replace(
                    Array(
                        '{courses}',
                        '{siteName}',
                        '{username}',
                        '{deadlineDate}'
                    ),
                    Array(
                        $courseText,
                        $CFG->wwwroot,
                       "{$users[$userid]->lastname} {$users[$userid]->firstname}",
                        date('d.m.Y',$varblock->varblocktimestampto)
                    ),
                    get_string('assigned_courses_long','local_variatives')
            );
            $smallmessage=str_replace(
                    Array(
                        '{courses}',
                        '{siteName}',
                        '{username}'
                    ),
                    Array(
                        $courseText,
                        $CFG->wwwroot,
                       "{$users[$userid]->lastname} {$users[$userid]->firstname}"
                    ),
                    get_string('assigned_courses_short','local_variatives')
            );
            //echo '<hr>'.$smallmessage.'<hr>';

            // ========== do real enrollment = end =============================
            //echo '<hr>'.htmlspecialchars($fullmessagehtml).'<hr>'.htmlspecialchars($smallmessage).'<hr>';
            // exit();

            $message = new \core\message\message();
            $message->component = 'moodle';
            $message->name = 'instantmessage';
            $message->userfrom = $senderUserId;
            $message->userto = $userid;
            $message->subject = get_string('assigned_courses_subject','local_variatives');
            $message->fullmessage = $fullmessagehtml;
            $message->fullmessageformat = FORMAT_PLAIN;
            $message->fullmessagehtml = $fullmessagehtml;
            $message->smallmessage = $smallmessage;
            $message->notification = '0';
            $message->contexturl = "{$CFG->wwwroot}";
            $message->contexturlname = 'ZNU.Moodle';
            $message->replyto = $CFG->supportemail;
            // $content = array('*' => array('header' => ' test ', 'footer' => ' test ')); // Extra content for specific processor
            // $message->set_additional_content('email', $content);
            //echo '<pre>'; print_r($message);echo '</pre>';
//////////            $messageid = message_send($message);
            echo "Notify {$users[$userid]->id} {$users[$userid]->lastname} {$users[$userid]->firstname} - done {$messageid};<br>\n";
            
        }
        echo "
         <script type=\"application/javascript\">
            setTimeout('window.location.href=\"reportenrolled.php?varblockid=".join(',',$varblockids)."\";',10000)
         </script>
        ";

    }else{
        echo "<div><a style=\"display:inline-block;font-size:200%;padding:1em 2em; background-color:#e0e0ff;\" href=\"./assign-finish.php?varblockid=".join(',',$varblockids)."&approve=yes\">Accept</a></div>";
    }
    //exit('<hr>0009');
}else{
    echo get_string('Solution not found','local_variatives');
}

echo $OUTPUT->footer();