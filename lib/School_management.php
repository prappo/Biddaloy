<?php
class SchoolManagement {
	private $data;
	public $status = true
			,$username
			,$user_type;
	
	public function __construct() {
		$this->user_type = user_type();
		$this->user_name = $_SESSION['username'];
	}
	
	public function main_dashboard_menu($user) {
		add_menu('school_management','School Management','certificate');
		return $this;
	}
	public function principal_menu($user) {
		add_menu('school_principal','School Principal','star');
		return $this;
	}

	public function teachers_menu($user) {
		add_menu('school_teachers','School Teachers','users');
		return $this;
	}

	public function students_menu($user) {
		global $pdo;
		if($user == 'student') {
			add_menu('profile','My Profile','user');
		} elseif($user == 'parent') {
			menu_start('','Students','user');
				$this->get_parents_student();
			menu_end();
		} else {
			$classes = $pdo
				->query("SELECT `class` FROM `scl_students` GROUP BY `class`")
			  	->fetchAll(PDO::FETCH_OBJ);
			
			menu_start('','School Students','user');
			  ($user == 'admin' || $user == 'official')
			  ? add_menu('admit_student','Admit New Student','plus-circle')
			  : NULL;
			  
			  foreach($classes as $class) {
			    !empty($class->class)
			    ? add_menu('school_students?class='.$class->class, 'Class '.$class->class, 'dot-circle-o')
			    : NULL;
			  }
			menu_end();
		}
		return $this;
	}

	public function get_parents_student() {
		global $pdo;
		$parent_username = $_SESSION['username'];

		$students = $pdo
			->query("SELECT `students_username` FROM `scl_parents` WHERE `username` = '{$parent_username}'")
			->fetch(PDO::FETCH_OBJ);

		$students = $students->students_username;
		$students = explode(',', $students);
		
		foreach($students as $student) {
			$students_info = $pdo
				->query("SELECT * FROM `vor_admin` WHERE `username` = '{$student}'")
				->fetchAll(PDO::FETCH_OBJ);

			foreach($students_info as $student_info) {
				add_menu('view_student_info?student='.
					$student_info->username, $student_info->full_name, 'dot-circle-o');
			}
		}
	}

	public function parents_menu($user) {
		if($user == 'admin' || $user == 'official') {
			add_menu('school_parents', 'School Parents', 'users');
		}
		return $this;
	}

	public function notice_menu($user) {
		add_menu('school_notices','School Notices','file');
		return $this;
	}

	public function class_routine_menu($user) {
		add_menu('class_routine','Class Routine','calendar');
		return $this;
	}

	public function exam_routine_menu($user) {
		add_menu('exam_routine','Exam Routine','align-left');
		return $this;
	}

	public function make_main_menu() {
		add_option('menu', function() {
			$this
		  	->main_dashboard_menu('admin')
		  	->principal_menu('admin')
		  	->teachers_menu('admin')
			->students_menu('admin')
			->parents_menu('admin')
			->class_routine_menu('admin')
			->exam_routine_menu('admin')
			->notice_menu('admin');
		});
	}

	public function make_teachers_menu() {
		add_option('menu', function() {
			$this
		  	->main_dashboard_menu('teacher')
		  	->principal_menu('teacher')
		  	->teachers_menu('teacher')
			->students_menu('teacher')
			->parents_menu('teacher')
			->class_routine_menu('teacher')
			->exam_routine_menu('teacher')
			->notice_menu('teacher');
		});
	}

	public function make_students_menu() {
		add_option('menu', function() {
			$this
		  	->main_dashboard_menu('student')
		  	->principal_menu('student')
		  	->teachers_menu('student')
			->students_menu('student')
			->parents_menu('student')
			->class_routine_menu('student')
			->exam_routine_menu('student')
			->notice_menu('student');
		});
	}

	public function make_parents_menu() {
		add_option('menu', function() {
			$this
		  	->main_dashboard_menu('parent')
		  	->principal_menu('parent')
		  	->teachers_menu('parent')
			->students_menu('parent')
			->parents_menu('parent')
			->class_routine_menu('parent')
			->exam_routine_menu('parent')
			->notice_menu('parent');
		});	
	}

	public function is_row_exists($config) {
		if(count(db_get_where($config['table'], $config['where'])) != 0)
			return true;
		else 
			return false;
	}

	public function setUserDataField($request, $type) {
		$this->data = [
			'full_name'			=> $request['full_name'],
			'username'			=> $request['username'],
			'type'				=> $type,
			'email'				=> $request['email'],
			'registration_date' => date("Y-m-d"),
		];

		(isset($request['password']) && $request['password'] != NULL)
			? $this->data['password'] = $request['password']
			: NULL;
		return $this;
	}

	public function updateUser() {
		db_update('vor_admin', $this->data, [
			'username' => $this->data['username']
		]);
		return $this;
	}

	public function createUser() {
		db_insert('vor_admin', $this->data);
		return $this;
	}

	public function admin_data() {
		$this->data = db_get('vor_admin');
		return $this;
	}

	public function principal_data() {
		$data = db_get('scl_principal');
		if(!empty($data)) {
			$this->data = $data[0];
		}
		return $this;
	}

	public function teachers_data() {
		$this->data = db_get('scl_teachers');
		return $this;
	}

	public function students_data() {
		$this->data = db_get('scl_students');
		return $this;
	}

	public function parents_data() {
		$this->data = db_get('scl_parents');
		return $this;
	}

	public function notices_data() {
		$this->data = db_get('scl_notices');
		return $this;
	}

	public function get() {
		return $this->data;
	}

	public function count() {
		if(is_assoc_array($this->data))
			return 1;
		else
			return count($this->data);
	}

	public function setPrincipalData($request) {
		$this->data = $fields = [
			'username'      => $request['username'],
			'full_name'     => $request['full_name'],
			'gender'        => $request['gender'],
	    	'address'       => $request['address'],
	    	'date_of_birth' => $request['date_of_birth'],
	    	'mobile'        => $request['mobile'],
			'email'         => $request['email'],
		];

	  return $this;
	}

	public function setTeacherData($request) {
		$this->data = $fields = [
			'username'      => $request['username'],
			'full_name'     => $request['full_name'],
			'gender'        => $request['gender'],
	    	'date_of_birth' => $request['date_of_birth'],
	    	'address'       => $request['address'],
	    	'mobile'        => $request['mobile'],
			'email'         => $request['email'],
		];

	  return $this;
	}

	public function setStudentData($request) {
		$this->data = $fields = [
			'full_name'		  => $request['student_full_name'],
			'class'			  => $request['student_class'],
			'roll'			  => $request['student_roll'],
			'gender'          => $request['student_gender'],
	    	'address'  		  => $request['student_address'],
	    	'date_of_birth'   => $request['student_date_of_birth'],
	    	'mobile' 		  => $request['student_mobile'],
			'email'           => $request['student_email'],
			'username'		  => $request['student_username'],
			'parent_username' => $request['parent_username'],
		];

	  return $this;
	}

	public function setParentData($request) {
		$this->data = $fields = [
			'full_name'		  => $request['parent_full_name'],
			'gender'          => $request['parent_gender'],
	    	'address'  		  => $request['parent_address'],
	    	'mobile' 		  => $request['parent_mobile'],
			'email'           => $request['parent_email'],
			'username'		  => $request['parent_username'],
			'child_username'  => $request['student_username'],
		];

	  return $this;
	}

	private function saveProfileImage($config) {
		$img_file = $config['file'][key($config['file'])];

		if(is_uploaded_file($img_file['tmp_name'])) {
			$mime = getimagesize($tmp_name);
			$allowed = ['jpg', 'jpeg', 'png', 'gif'];

			$image_name = $img_file['name'];
			$tmp_name   = $img_file['tmp_name'];

			$img = $config['username'].'.'.pathinfo($image_name)['extension'];
			$image = $config['image_path'].$img;

			if(in_array(pathinfo($image_name)['extension'], $allowed)) {
				if(in_array('image', explode('/', $mime['mime']))) {
					move_uploaded_file($tmp_name, $image);
					$resize = new imageResize($image);
					$resize->resize(140, 140);
					$resize->save();
					return true;
		    	} else {
		      		return false;
		    	}
		  	} else {
			    return false;
			}
		}
	}
	
	public function sendProfileData($config) {
		if($config['action'] == 'add') {
			$this->saveProfileImage($config);

			if($this->status)
				$this->status = db_insert($config['table'], $this->data);
			return $this;

		} elseif($config['action'] == 'edit') {
			$this->saveProfileImage($config);

			if($this->status)
				$this->status = db_update($config['table'], $this->data, [
					'username' => $config['username']
				]);
			return $this;
		}
	}

	// public function sendProfileData($config) {
	// 	if($config['action'] == 'add') {			//for adding new profile
	// 		if($config['type'] == 'principal') {		//for adding new principal profile
				
	// 			$this->saveProfileImage($config);		//saving profile image

	// 			if($this->status)
	// 				$this->status = db_insert('scl_principal', $this->data);
	// 			return $this;
	// 		} elseif($config['type'] == 'teacher') {	//for adding new teacher profile
				
	// 			$this->saveProfileImage($config);		//saving profile image

	// 			if($this->status)
	// 				$this->status = db_insert('scl_teachers', $this->data);
	// 			return $this;
	// 		} elseif($config['type'] == 'student') {

	// 			$this->saveProfileImage($config);		//saving profile image

	// 			if($this->status)
	// 				$this->status = db_insert('scl_students', $this->data);
	// 			return $this;
	// 		} elseif($config['type'] == 'parent') {

	// 			$this->saveProfileImage($config);		//saving profile image

	// 			if($this->status)
	// 				$this->status = db_insert('scl_parents', $this->data);
	// 			return $this;
	// 		}
	// 	} elseif($config['action'] == 'edit') {				//for editing profile data
	// 		if($config['type'] == 'principal') {		//for principal's profile
				
	// 			$this->saveProfileImage($config);		//saving profile image
				
	// 			if($this->status)
	// 				$this->status = db_update('scl_principal', $this->data, [
	// 					'username' => $config['username']
	// 				]);
	// 			return $this;
	// 		} elseif($config['type'] == 'teacher') {	//for teacher's profile
				
	// 			$this->saveProfileImage($config);		//saving profile image

	// 			if($this->status)
	// 				$this->status = db_update('scl_teachers', $this->data, [
	// 					'username' => $config['username']
	// 				]);
	// 			return $this;
	// 		} elseif($config['type'] == 'student') {	//for student's profile
				
	// 			$this->saveProfileImage($config);		//saving profile image

	// 			if($this->status)
	// 				$this->status = db_update('scl_students', $this->data, [
	// 					'username' => $config['username']
	// 				]);
	// 			return $this;
	// 		} elseif($config['type'] == 'parent') {	//for parent's profile
				
	// 			$this->saveProfileImage($config);		//saving profile image

	// 			if($this->status)
	// 				$this->status = db_update('scl_parents', $this->data, [
	// 					'username' => $config['username']
	// 				]);
	// 			return $this;
	// 		}
	// 	}
	// }

	public function matchPassword($request) {
		$this->status = ($request['newPassword'] == $request['reTypePassword']) ? true : false;
	}

	public function setPasswordField($request) {
		$this->data = $fields = [
			'password' => $request['newPassword']
		];

	  return $this;
	}

	public function changePassword($username) {
		$this->status = db_update('vor_admin', $this->data, ['username' => $username]);
	}
}