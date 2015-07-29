<?php
$schoolManagement->make_students_menu();

echo realpath(dirname(__FILE__).'/main/profile/student/profile.php');
$page->a('/profile', function(){
	require_once '/../profile/student/profile.php';
});

$page->a('/change_password', function(){
	require '/../profile/student/change_password.php';
});