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
function local_variatives_extend_navigation(global_navigation $navigation) {

    $is_variatives_admin = has_capability('local/variatives:manage', context_system::instance());
    $is_variatives_user = has_capability('local/variatives:choose', context_system::instance());

    //echo "is_variatives_admin=$is_variatives_admin  is_variatives_user=$is_variatives_user";
    
    if ($is_variatives_admin || $is_variatives_user) {

        $nodeFoo = $navigation->add(get_string('Variatives', 'local_variatives'));
    }

    if ($is_variatives_user) {
        $nodeBar = $nodeFoo->add(get_string('Choose variative courses', 'local_variatives'), new moodle_url('/local/variatives/public/choose.php'));
    }
    if ($is_variatives_admin) {
        $nodeBar = $nodeFoo->add(get_string('Departments', 'local_variatives'), new moodle_url('/local/variatives/control/department.php'));
        $nodeBar = $nodeFoo->add(get_string('Specialities', 'local_variatives'), new moodle_url('/local/variatives/control/speciality.php'));
        $nodeBar = $nodeFoo->add(get_string('Groups', 'local_variatives'), new moodle_url('/local/variatives/control/group.php'));
        $nodeBar = $nodeFoo->add(get_string('Blocks', 'local_variatives'), new moodle_url('/local/variatives/control/blocks.php'));
        $nodeBar = $nodeFoo->add(get_string('Enrollments', 'local_variatives'), new moodle_url('/local/variatives/control/enrollment.php'));
        $nodeBar = $nodeFoo->add(get_string('SubspecialityBlocks','local_variatives'), new moodle_url('/local/variatives/control/subspecialityblocks.php'));
        //$nodeBar = $nodeFoo->add(get_string('Report Waiting', 'local_variatives'), new moodle_url('/local/variatives/control/reportwaiting.php'));
        //$nodeBar = $nodeFoo->add(get_string('Report Enrolled', 'local_variatives'), new moodle_url('/local/variatives/control/reportenrolled.php'));
    }
}


