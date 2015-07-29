<?php
session_start();
if(isset($_SESSION['username'])) {
	require_once '../../../lib/config.php';
	require_once '../../../lib/function.php';

	if(isset($_POST['action'])) {
		if($_POST['action'] == 'getInfo') {
			$config = [
			    'table' => $_POST['table'],
			    'username' => ['username' => $_POST['username']],
			];

			$profileInfo = db_get_where($config['table'], $config['username']);;

			echo json_encode($profileInfo);
		} elseif( $_POST['action'] == 'deleteProfile') {
			$where = [
			    'username' => $_POST['username']
			];

			$status = db_delete($_POST['table'], $where);
			echo json_encode(($status) ? ['status' => 1] : ['status' => 0]);
		}
	}
} else {
	echo json_encode(['error' => 'you are not authorized']);
}