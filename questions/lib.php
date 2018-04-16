<?php

/**
 *  add navigation block
 *  Here are some snippets of what I've done so far.
 *  This is experimentation and I'm new to PHP, so take it for what it's worth.
  function custom_nav_extends_navigation(global_navigation $navigation) {
  // get the "Home" node
  $nodeHome = $navigation->children->get('1')->parent;

  // Rename it
  $nodeHome->title= 'Foo';
  $nodeHome->key='Foo';
  $nodeHome->text = 'Foo';

  // Create a child node
  $nodeAwesome = $navigation->add('10 Reasons We're Awesome');

  // Add children to nodeAwesome. Pretend we have a list "$myList" of links to add.
  for ($i = 0; $i <= count($myList); $i += 1)
  $nodeAwesome->add($myList[$i], new moodle_url('/path/to/file/'.$myList[$i]), null, null, $myList[$i]);

  //force the node open
  $nodeAwesome->forceopen = true;
  }
 */
//echo "local_questions_extends_navigation!!!!!";
function local_questions_extend_navigation(global_navigation $navigation) {
    //         local_questions_extend_navigation
    //echo "local_questions_extends_navigation!!!!!";
    $is_questions_admin = has_capability('local/questions:view', context_system::instance());
    //echo "is_questions_admin=$is_questions_admin  is_questions_user=$is_questions_user";
    if ($is_questions_admin ) {
        //echo (" is_questions_admin !!!!");
        $nodeFoo = $navigation->add(get_string('questions', 'local_questions'));
    }


    
    if ($is_questions_admin) {
        $nodeBar = $nodeFoo->add(get_string('feedbacks', 'local_questions'), new moodle_url('/local/questions/list.php'));
        //$nodeBar = $nodeFoo->add(get_string('Report Waiting', 'local_questions'), new moodle_url('/local/questions/control/reportwaiting.php'));
        //$nodeBar = $nodeFoo->add(get_string('Report Enrolled', 'local_questions'), new moodle_url('/local/questions/control/reportenrolled.php'));
    }
}


