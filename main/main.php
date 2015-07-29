<?php
// making dashboard menu --START
$schoolManagement->make_main_menu();
// making dashboard menu --END

//adding page --START
$page->a('/school_management', function(){
	require_once 'dashboard.php';
});

$page->a('/admit_student', function(){
	require_once 'admitStudent.php';
});

$page->a('/school_principal', function(){
	require_once 'principal.php';
});

$page->a('/school_teachers', function(){
	require_once 'teachers.php';
});

$page->a('/school_students', function(){
	require_once 'students.php';
});

$page->a('/school_parents', function(){
	require_once 'parents.php';
});

$page->a('/class_routine', function(){
	require_once 'classRoutine.php';
});

$page->a('/exam_routine', function(){
	require_once 'examRoutine.php';
});

$page->a('/school_notices', function(){
	require_once 'notices.php';
});
//adding page --END