<?php
global $schoolManagement, $pdo;
$teachers_data = $schoolManagement->teachers_data()->get();
$plugin_url = 'http://'.dirname($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']).'/plugins/'.get_plugin_name();
$image_path = get_plugin_dir().'/images/teachers/';

$config = [
    'table'       => 'scl_teachers',
    'image_path'  => $image_path,
    'type'        => 'teacher',
];

if(isset($_POST['editTeacherData'])) {
    $configg = [
        'action'      => 'edit',
        'file'        => $_FILES,
        'username'    => $_POST['username'],
    ];

    $config = array_merge($configg, $config);

    $schoolManagement
    ->setTeacherData($_POST)
    ->sendProfileData($config)
    ->setUserDataField($_POST, $config['type'])
    ->updateUser();

    if($schoolManagement->status) {
        echo '<script>profileUpdated()</script>';
        echo "<meta http-equiv='refresh' content='1;url='>";
    } else {
        echo '<script>error()</script>';
    }
} elseif(isset($_POST['editPassword'])) {
    $configg = [
        'username' => $_POST['username'],
    ];

    $config = array_merge($configg, $config);

    $schoolManagement->matchPassword($_POST);
    
    if($schoolManagement->status) {
        $schoolManagement
        ->setPasswordField($_POST)
        ->changePassword($config['username']);

        if($schoolManagement->status) {
            echo '<script>passwordChanged()</script>';
        } else {
            echo '<script>error()</script>';
        }
    } else {
        echo '<script>passNotMatched()</script>';
    }
} elseif(isset($_POST['addNewTeacher'])) {
    $configg = [
        'table' => 'vor_admin',
        'where' => ['username' => $_POST['username']]
    ];

    $config = array_merge($configg, $config);
    
    if($schoolManagement->is_row_exists($config)) {
        echo '<script>userNameExists()</script>';
    } else {
        $configg = [
            'action'      => 'add',
            'file'        => $_FILES,
            'username'    => $_POST['username'],
        ];

        $config = array_merge($configg, $config);

        $schoolManagement
        ->setTeacherData($_POST)
        ->sendProfileData($config)
        ->setUserDataField($_POST, $config['type'])
        ->createUser();
        if($schoolManagement->status) {
            echo '<script>profileaAdded()</script>';
            echo "<meta http-equiv='refresh' content='1;url='>";
        } else {
            echo '<script>error()</script>';
        }
    }
} elseif(isset($_POST['addTeacherCSV'])) {
    $csv = new parseCSV($_FILES['teacher-csv']['tmp_name']);
    $teachersData = $csv->data;
    
    foreach($teachersData as $teacherData) {
        $date = strtotime($teacherData['date_of_birth']);
        $teacherData['date_of_birth'] = date('Y-m-d', $date);
        $configg = [
            'table' => 'vor_admin',
            'where' => ['username' => $teacherData['username']]
        ];
        
        $config = array_merge($configg, $config);

        if($schoolManagement->is_row_exists($config)) {
            echo '<script>userNameExists()</script>';
        } else {
            $configg = [
                'action'      => 'add',
                'type'        => 'teacher',
                'file'        => NULL,
                'username'    => $teacherData['username'],
            ];

            $config = array_merge($configg, $config);

            $schoolManagement
            ->setTeacherData($teacherData)
            ->sendProfileData($config)
            ->setUserDataField($teacherData, $config['type'])
            ->createUser();
            
            if($schoolManagement->status) {
                echo '<script>profileaAdded()</script>';
                echo "<meta http-equiv='refresh' content='1;url='>";
            } else {
                echo '<script>error()</script>';
            }
        }
    }
}
?>
<div>
    <h3 style="margin:10px 10px 20px 15px; color:#818da1; font-weight:200;">
    <i class="fa fa-arrow-circle-right"></i> Manage Teacher's data</h3>
</div>
<div class="col-md-12">
    <div class="panel">
        <div class="panel-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#teachersProfile" role="tab" data-toggle="tab">Profile</a></li>
                <li><a href="#addNewTeacher" role="tab" data-toggle="tab">Add new teacher</a></li>
                <li><a href="#CSV" role="tab" data-toggle="tab">CSV</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="teachersProfile">
                    <div class="col-lg-12 col-sm-12">
                        <div class="panel" id="printableArea">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover text-center" id="example">
                                        <thead>
                                            <tr>
                                                <th>Photo</th>
                                                <th>Name</th>
                                                <th>Mobile</th>
                                                <th>Email</th>
                                                <th>Options</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach($teachers_data as $teacher_data) :
                                            $image = (!empty($teacher_data['image']))
                                            ? 'plugins/'.get_plugin_name().'/images/teachers/'
                                            .$teacher_data['image']
                                            : 'plugins/'.get_plugin_name().'/images/default_image.png' ;
                                            ?>
                                            <tr>
                                                <td><img class="dataTableAvatar img-circle" src="<?php echo $image; ?>"
                                                alt="avatar"></td>
                                                <td><?php echo $teacher_data['full_name']; ?></td>
                                                <td><?php echo $teacher_data['mobile']; ?></td>
                                                <td><?php echo $teacher_data['email']; ?></td>
                                                
                                                <td>
                                                    <div class="btn-group">
                                                        <button data-toggle="dropdown" class="btn dropdown-toggle btn-sm" type="button">
                                                        Action<span class="caret"></span>
                                                        </button>
                                                        <ul role="menu" class="dropdown-menu">
                                                            <li><a id="viewTeacherProfile"
                                                                data-username="<?php echo $teacher_data['username']; ?>"
                                                                data-url="<?php echo $plugin_url; ?>">
                                                            <i class="fa fa-table"></i> View profile</a>
                                                        </li>
                                                        <li class="divider"></li>
                                                        <li><a id="editTeacherProfile"
                                                            data-username="<?php echo $teacher_data['username']; ?>"
                                                            data-url="<?php echo $plugin_url; ?>">
                                                        <i class="fa fa-edit"></i> Edit profile</a>
                                                    </li>
                                                    <li class="divider"></li>
                                                    <li>
                                                        <a id="editTeacherPassword"
                                                            data-username="<?php echo $teacher_data['username']; ?>"
                                                            data-url="<?php echo $plugin_url; ?>">
                                                            <i class="fa fa-edit"></i>
                                                            Password
                                                        </a>
                                                    </li>
                                                    <li class="divider"></li>
                                                    <li>
                                                        <a id="deleteTeacherProfile"
                                                            data-username="<?php echo $teacher_data['username']; ?>"
                                                            data-url="<?php echo $plugin_url; ?>">
                                                            <i class="fa fa-trash-o"></i>
                                                            Delete
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                    endforeach;
                                    ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Options</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- view profile modal --START -->
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel"
            aria-hidden="true" id="viewProfileModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header hide" id="viewModalHeader">
                        <button type="button" class="close" data-dismiss="modal" aria-label=
                        "Close"><span aria-hidden="true">&#215;</span></button>
                        <h4 class="modal-title" id="viewModalLabel"></h4>
                    </div>
                    <div id="viewModalLoadingImg">
                        <img src="<?php echo 'plugins/'.get_plugin_name().'/images/loading.gif'; ?>">
                    </div>
                    <div class="modal-body" id="viewProfileModalBody">
                        <div class="col-md-3 col-lg-3 profile_image" align="center">
                            <img src="" class="img-circle" height="140px" width="140px" id="viewProfile_img"></img>
                        </div>
                        <table class="table table-user-information">
                            <tbody>
                                <tr>
                                    <td>Username</td>
                                    <td id="viewProfile_username"></td>
                                </tr>
                                <tr>
                                    <td>Name</td>
                                    <td id="viewProfile_name"></td>
                                </tr>
                                <tr>
                                    <td>Gender</td>
                                    <td id="viewProfile_gender"></td>
                                </tr>
                                <tr>
                                    <td>Date of Birth</td>
                                    <td id="viewProfile_date_of_birth"></td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td id="viewProfile_address"></td>
                                </tr>
                                <tr>
                                    <td>Mobile Number</td>
                                    <td id="viewProfile_mobile"></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td id="viewProfile_email"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer" id="viewModalfooter">
                        <button type="button" class="btn btn-default hide" data-dismiss=
                        "modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- view profile modal--END -->
        <!-- edit profile modal--START -->
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
            aria-hidden="true" id="editProfileModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-horizontal" id="teachers_edit_form" role="form"
                        method="post" enctype="multipart/form-data">
                        <div class="modal-header hide" id="editModalHeader">
                            <button type="button" class="close" data-dismiss="modal" aria-label=
                            "Close"><span aria-hidden="true">&#215;</span></button>
                            <h4 class="modal-title" id="editModalLabel">Edit Profile</h4>
                        </div>
                        <div id="editModalLoadingImg">
                            <img src="<?php echo 'plugins/'.get_plugin_name().'/images/loading.gif'; ?>">
                        </div>
                        <div class="modal-body" id="editModalBody">
                            <div class="row">
                                <!-- left column -->
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <img src="" id="editProfile_img" class="avatar img-circle" alt="avatar"
                                        width="100px" height="100px">
                                    </div>
                                    </div><!-- edit form column -->
                                    <div class="col-md-12 personal-info">
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label" for="editProfile_name">Name:</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" id="editProfile_name" type="text"
                                                name="full_name" placeholder="Enter name" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label" for="editProfile_gender">Gender:</label>
                                            <div class="col-lg-4">
                                                <select name="gender" class="form-control" id="editProfile_gender" required>
                                                    <optgroup label="Select Gender">
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label" for="editProfile_date_of_birth">
                                                Date of Birth:
                                            </label>
                                            <div class="col-lg-8" id="datepick">
                                                <input class="form-control" id="editProfile_date_of_birth" type="text"
                                                name="date_of_birth" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label" for="editProfile_address">Address:</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" id="editProfile_address" type="text"
                                                name="address" placeholder="Enter address" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label" for="editProfile_mobile ">Mobile:</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" id="editProfile_mobile" type="text"
                                                name="mobile" placeholder="Enter mobile number">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label" for="editProfile_email">Email:</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" id="editProfile_email" type="text"
                                                name="email" placeholder="Enter email address">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="photo">Photo:</label>
                                            <div class="col-md-8">
                                                <input type="file" name="edit-teacher-image" class="file-pos"
                                                id="photo" accept="image/*">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" id="editModalfooter">
                                <input id="editProfile_username" type="hidden" name="username">
                                <button type="button" class="btn btn-default hide" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary hide" name="editTeacherData">
                                Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- edit profile modal--END -->
            <!-- change password modal--START -->
            <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="passwordProfileModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label=
                            "Close"><span aria-hidden="true">&#215;</span></button>
                            <h4 class="modal-title">Change Password</h4>
                        </div>
                        <form class="form-horizontal" id="changePasswordForm" role="form" method="post">
                            <div class="modal-body" id="passwordModalBody">
                                <div class="row">
                                    <div class="col-md-12 personal-info">
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label" for="newPassword_field">New Password:</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" id="newPassword_field" type="password"
                                                name="newPassword" placeholder="*********" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label" for="reTypePassword_field">Re-type New Password:</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" id="reTypePassword_field" type="password"
                                                name="reTypePassword" placeholder="*********" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input id="password_username" type="hidden" name="username">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" name="editPassword">
                                Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- change password modal--END -->
            <!--add new teacher --START-->
            <div class="tab-pane" id="addNewTeacher">
                <div class="personal-info" id="principalsEditForm">
                    <form class="form-horizontal" id="principal_edit_form" role="form" method="post"
                        enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-lg-3 control-label" for="username">Username:</label>
                            <div class="col-lg-8">
                                <input class="form-control" id="username" type="text" name="username"
                                placeholder="Enter username" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label" for="password">password:</label>
                            <div class="col-lg-8">
                                <input class="form-control" id="password" type="password" name="password"
                                placeholder="Enter password" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label" for="full_name">Name:</label>
                            <div class="col-lg-8">
                                <input class="form-control" id="full_name" type="text" name="full_name"
                                placeholder="Enter name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label" for="gender">Gender:</label>
                            <div class="col-lg-4">
                                <select name="gender" class="form-control" id="gender">
                                    <optgroup label="Select Gender">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label" for="address">Address:</label>
                            <div class="col-lg-8">
                                <input class="form-control" id="address" type="text" name="address"
                                placeholder="Enter address" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label" for="date_of_birth">Date of Birth:</label>
                            <div class="col-lg-8" id="datepick">
                                <input class="form-control" id="date_of_birth" type="text" name="date_of_birth"
                                placeholder="Select Date of birth" required>
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="mobile">Mobile:</label>
                            <div class="col-md-8">
                                <input class="form-control" id="mobile" type="text" name="mobile"
                                placeholder="Enter mobile number">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="email">Email:</label>
                            <div class="col-md-8">
                                <input class="form-control" id="email" type="text" name="email"
                                placeholder="Enter email address">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="photo">Photo:</label>
                            <div class="col-md-8">
                                <input type="file" name="add-teacher-image" class="file-pos"
                                id="photo" accept="image/*">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-full form-control"
                        name="addNewTeacher">Add new teacher</button>
                    </form>
                </div>
                </div>    <!--add new data --END-->
                <div class="tab-pane download-csv" id="CSV">
                    <div class="download-csv">
                        <h4>Download a blank CSV file and add teacher's data</h4>
                        <div align="center">
                            <a href="<?php echo get_plugin_dir().'/blank_csv/blank_teacher_field.csv' ?>">
                                <button type="button" class="btn btn-success">Download Blank CSV</button>
                            </a>
                        </div>
                    </div>
                    <div class="upload-csv">
                        <form role="form" enctype="multipart/form-data" method="post">
                            <h4>Choose a CSV file to upload</h4>
                            <div align="center"><input type="file" name="teacher-csv"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                <div align="center"><input type="submit" name="addTeacherCSV"
                                class="btn btn-outline btn-success" value="Upload CSV"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>