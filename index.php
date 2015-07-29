<?php debug_backtrace() || die ("Direct access not permitted."); ?>

<?php
// error_reporting(0);
require_once 'main/init.php';

$user_type = user_type();

if($user_type == 'admin' || $user_type == 'official') {
	require_once 'main/main.php';
} elseif($user_type == 'teacher') {
	require_once 'main/teachers/main.php';
} elseif($user_type == 'principal') {
	require_once 'main/teachers/main.php';
} elseif($user_type == 'student') {
	require_once 'main/students/main.php';
} elseif($user_type == 'parent') {
	require_once 'main/parents/main.php';
}

//adding css
add_css([
	get_plugin_dir().'/css/style.css',
	get_plugin_dir().'/css/datepicker.css',
]);

//adding js into header

add_js_header(get_plugin_dir().'/js/functions.js');

//adding js into footer
add_js_footer([
	get_plugin_dir().'/js/script.js',
	get_plugin_dir().'/js/datepicker.js',
]);