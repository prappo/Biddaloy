<?php
global $schoolManagement, $pdo;
if(isset($_POST['addNewStudent'])) {
    
    $userConfig = [
    'type'      => 'student',
    ];
    
    $config = [
        'table' => 'vor_admin',
        'where' => ['username' => $_POST['student_username']]
    ];

    if($schoolManagement->is_row_exists($config)) {
        $config = [
            'table' => 'vor_admin',
            'where' => ['username' => $_POST['parent_username']]
        ];

        if($schoolManagement->is_row_exists($config))
            echo '<script>userNameExists()</script>';
    } else {
        //students
        $image_path = get_plugin_dir().'/images/students/';
        $config = [
            'action'      => 'add',
            'type'        => 'student',
            'image_path'  => $image_path,
            'file'        => $_FILES,
            'username'    => $_POST['student_username'],
        ];

        $_POST['full_name'] = $_POST['student_full_name'];
        $_POST['username']  = $_POST['student_username'];
        $_POST['password']  = $_POST['student_username'];
        $_POST['email']     = $_POST['student_email'];

        $schoolManagement
        ->setStudentData($_POST)
        ->sendProfileData($config)
        ->setUserDataField($_POST, $userConfig)
        ->createUser();
        
        //parents
        $image_path = get_plugin_dir().'/images/parents/';
        $config = [
            'action'      => 'add',
            'type'        => 'parent',
            'image_path'  => $image_path,
            'file'        => $_FILES,
            'username'    => $_POST['parent_username'],
        ];

        $_POST['full_name'] = $_POST['parent_full_name'];
        $_POST['username']  = $_POST['parent_username'];
        $_POST['password']  = $_POST['parent_username'];
        $_POST['email']     = $_POST['parent_email'];

        $schoolManagement
        ->setParentData($_POST)
        ->sendProfileData($config)
        ->setUserDataField($_POST, $userConfig)
        ->createUser();
        
        if($schoolManagement->status) {
            echo '<script>profileaAdded()</script>';
            echo "<meta http-equiv='refresh' content='1;url='>";
        } else {
            echo '<script>error()</script>';
        }
    }
}
?>
<div class="row">
    <div class="col-lg-12">
        <form class="form-horizontal" role="form" method="post"
                    enctype="multipart/form-data">
            <section class="panel">
                <header class="panel-heading">Student information</header>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="student-username">Username:</label>
                        <div class="col-lg-8">
                            <input class="form-control" id="student-username" type="text"
                            name="student_username" placeholder="Enter username" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="student-password">password:</label>
                        <div class="col-lg-8">
                            <input class="form-control" id="student-password" type="password"
                            name="student_password" placeholder="Enter password" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="student-full-name">Name:</label>
                        <div class="col-lg-8">
                            <input class="form-control" id="student-full-name" type="text"
                            name="student_full_name" placeholder="Enter name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="student-class">Class:</label>
                        <div class="col-lg-8">
                            <input class="form-control" id="student-class" type="text"
                            name="student_class" placeholder="Enter class" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="student-roll">Class:</label>
                        <div class="col-lg-8">
                            <input class="form-control" id="student-roll" type="text"
                            name="student_roll" placeholder="Enter roll" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="student-gender">Gender:</label>
                        <div class="col-lg-4">
                            <select name="student_gender" class="form-control" id="student-gender">
                                <optgroup label="Select Gender">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="student-address">Address:</label>
                        <div class="col-lg-8">
                            <input class="form-control" id="student-address" type="text"
                            name="student_address" placeholder="Enter address" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="student-date-of-birth">Date of Birth:</label>
                        <div class="col-lg-8" id="datepick">
                            <input class="form-control" id="student-date-of-birth" type="text"
                            name="student_date_of_birth" placeholder="Select Date of birth" required>
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="student-mobile">Mobile:</label>
                        <div class="col-md-8">
                            <input class="form-control" id="student-mobile" type="text"
                            name="student_mobile" placeholder="Enter mobile number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="student-email">Email:</label>
                        <div class="col-md-8">
                            <input class="form-control" id="student-email" type="text"
                            name="student_email" placeholder="Enter email address">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="student-photo">Photo:</label>
                        <div class="col-md-8">
                            <input type="file" name="add-student-image" class="file-pos"
                            id="student-photo" accept="image/*">
                        </div>
                    </div>
                </div>
            </section>
            <section class="panel">
                <header class="panel-heading">Parent information</header>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="parent-username">Username:</label>
                        <div class="col-lg-8">
                            <input class="form-control" id="parent-username" type="text"
                            name="parent_username" placeholder="Enter username" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="parent-password">password:</label>
                        <div class="col-lg-8">
                            <input class="form-control" id="parent-password" type="password"
                            name="parent_password" placeholder="Enter password" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="parent-full-name">Name:</label>
                        <div class="col-lg-8">
                            <input class="form-control" id="parent-full-name" type="text"
                            name="parent_full_name" placeholder="Enter name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="parent-gender">Gender:</label>
                        <div class="col-lg-4">
                            <select name="parent-gender" class="form-control" id="parent-gender">
                                <optgroup label="Select Gender">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="parent-address">Address:</label>
                        <div class="col-lg-8">
                            <input class="form-control" id="parent-address" type="text"
                            name="parent_address" placeholder="Enter address" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="parent-mobile">Mobile:</label>
                        <div class="col-md-8">
                            <input class="form-control" id="parent-mobile" type="text"
                            name="parent_mobile" placeholder="Enter mobile number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="parent-email">Email:</label>
                        <div class="col-md-8">
                            <input class="form-control" id="parent-email" type="text"
                            name="parent_email" placeholder="Enter email address">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="parent-photo">Photo:</label>
                        <div class="col-md-8">
                            <input type="file" name="add-parent-image" class="file-pos"
                            id="parent-photo" accept="image/*">
                        </div>
                    </div>
                </div>
            </section>
            <button type="submit" class="btn btn-full form-control"
            name="addNewStudent">Add new student</button>
        </form>
    </div>
</div>