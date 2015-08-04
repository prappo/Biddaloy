<?php
global $schoolManagement, $pdo;
$principal_info = $schoolManagement->principal_data()->get();

$image = (file_exists('img/user/'.$principal_info['image']))
    ? 'img/user/'.$principal_info['image'] : 'img/user/default_image.png';

$config = [
    'table'       => 'scl_principal',
    'type'        => 'principal',
];

if(isset($_POST['editPrincipalsData'])) {
$configg = [
    'action'      => 'edit',
    'type'        => 'principal',
    'file'        => $_FILES['edit-principal-image'],
    'username'    => $_POST['username'],
    'extension'   => pathinfo($_FILES['edit-principal-image']['name'])['extension']
];

$config = array_merge($configg, $config);

$schoolManagement
    ->setPrincipalData($_POST)
    ->sendProfileData($config)
    ->setUserDataField($_POST, $config)
    ->updateUser();

if($schoolManagement->status) {
    echo '<script>profileUpdated()</script>';
    echo "<meta http-equiv='refresh' content='1;url='>";
} else {
    echo '<script>error()</script>';
}
//edit ptincipal data --END
} elseif(isset($_POST['addPrincipalsData'])) {
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
            'type'        => 'principal',
            'file'        => $_FILES['add-principal-image'],
            'username'    => $_POST['username'],
            'extension'   => pathinfo($_FILES['add-principal-image']['name'])['extension']
        ];

        $config = array_merge($configg, $config);

        $schoolManagement
            ->setPrincipalData($_POST)
            ->sendProfileData($config)
            ->setUserDataField($_POST, $config)
            ->createUser();

        if($schoolManagement->status) {
            echo '<script>profileaAdded()</script>';
            echo "<meta http-equiv='refresh' content='1;url='>";
        } else {
            echo '<script>error()</script>';
        }
    }
} elseif(isset($_POST['changePrincipalsPass'])) {
    $schoolManagement->matchPassword($_POST);

    if($schoolManagement->status) {
        $configg = [
            'username' => $_POST['username'],
        ];

        $config = array_merge($configg, $config);
        
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
}
?>
<div>
  <h3 style="margin:10px 10px 20px 15px; color:#818da1; font-weight:200;">
    <i class="fa fa-arrow-circle-right"></i> Manage principal's data</h3>
</div>
<div class="col-md-12">
  <div class="panel">
    <div class="panel-body">
    
      <!-- add new data --START -->
      <?php
      if($schoolManagement->principal_data()->count() == 0): ?>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li class="active"><a href="#principalsData" role="tab" data-toggle="tab">Profile</a></li>
          <li><a href="#addPrincipalData" role="tab" data-toggle="tab">Add new profile</a></li>
        </ul>

        <!-- tab panes -->
        <div class="tab-content">
          <div class="tab-pane active" id="principalsData">
            <div class="alert alert-info alert-block">
              <h4>No Data Found!</h4>
              <p>&nbsp;Add new data from Add new tab</p>
            </div>
          </div>
          <div class="tab-pane" id="addPrincipalData">
            <div class="personal-info" id="principalsEditForm">
              <form class="form-horizontal" id="principal_edit_form" role="form" method="post" 
                enctype="multipart/form-data">
                <div class="form-group">
                  <label class="col-lg-3 control-label" for="username">Username:</label>
                  <div class="col-lg-8">
                    <input class="form-control" id="username" type="text" name="username"
                    placeholder="Enter username"
                    value="<?php echo (isset($_POST['username'])) ? $_POST['username'] : NULL; ?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-lg-3 control-label" for="password">Password:</label>
                  <div class="col-lg-8">
                    <input class="form-control" id="password" type="password" name="password"
                    placeholder="Enter password" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-lg-3 control-label" for="full_name">Name:</label>
                  <div class="col-lg-8">
                    <input class="form-control" id="full_name" type="text" name="full_name" 
                    placeholder="Enter name"
                    value="<?php echo (isset($_POST['full_name'])) ? $_POST['full_name'] : NULL; ?>" required>
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
                    placeholder="Enter address"
                    value="<?php echo (isset($_POST['address'])) ? $_POST['address'] : NULL; ?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-lg-3 control-label" for="date_of_birth">Date of Birth:</label>
                  <div class="col-lg-8" id="datepick">
                    <input class="form-control" id="date_of_birth" type="text" name="date_of_birth" 
                    placeholder="Select Date of birth"
                    value="<?php echo (isset($_POST['date_of_birth'])) ? $_POST['date_of_birth'] : NULL; ?>"
                    required>
                    <i class="fa fa-calendar"></i>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-3 control-label" for="mobile">Mobile:</label>
                  <div class="col-md-8">
                    <input class="form-control" id="mobile" type="text" name="mobile" 
                    placeholder="Enter mobile number" 
                    value="<?php echo (isset($_POST['mobile'])) ? $_POST['mobile'] : NULL; ?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-3 control-label" for="email">Email:</label>
                  <div class="col-md-8">
                    <input class="form-control" id="email" type="text" name="email" 
                    placeholder="Enter email address"
                    value="<?php echo (isset($_POST['email'])) ? $_POST['email'] : NULL; ?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-3 control-label" for="photo">Photo:</label>
                  <div class="col-md-8">
                    <input type="file" name="add-principal-image" class="file-pos" 
                    id="photo" accept="image/*">
                  </div>
                </div>
                <button type="submit" class="btn btn-full form-control" 
                name="addPrincipalsData">Add new profile</button>
              </form>
            </div>
          </div>
        </div>
      <!-- add new data --END -->
      <?php else: ?>
      <!-- nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#principalsData" role="tab" data-toggle="tab">Profile</a></li>
        <li><a href="#editPrincipalsData" role="tab" data-toggle="tab">Edit profile</a></li>
        <li><a href="#changePrincipalsPass" role="tab" data-toggle="tab">Change password</a></li>
      </ul>

      <!-- tab panes -->
      <div class="tab-content">
        <div class="tab-pane active" id="principalsData">
          <section>
            <div class="row">
              <aside class="profile-info">
                <section class="panel">
                  <div class="panel-body bio-graph-info">
                    <?php if(!empty($principal_info)) : ?>
                      <div class="row">
                        <div class="user-heading">
                          <img src="<?php echo $image; ?>" alt="avatar" class="principalHeadingImage">
                        </div>
                        <div class="bio-row">
                          <p><span>Username </span>: <?php echo $principal_info['username']; ?></p>
                        </div>
                        <div class="bio-row">
                          <p><span>Email </span>: <?php echo $principal_info['email']; ?></p>
                        </div>
                        <div class="bio-row">
                          <p><span>Name </span>: <?php echo $principal_info['full_name']; ?></p>
                        </div>
                        <div class="bio-row">
                          <p><span>Gender </span>: <?php echo $principal_info['gender']; ?></p>
                        </div>
                        <div class="bio-row">
                          <p><span>Address </span>: <?php echo $principal_info['address']; ?></p>
                        </div>
                        <div class="bio-row">
                          <p><span>Date of Birth </span>: <?php echo $principal_info['date_of_birth']; ?></p>
                        </div>
                        <div class="bio-row">
                          <p><span>Mobile </span>: <?php echo $principal_info['mobile']; ?></p>
                        </div>
                      </div>
                    <?php endif; ?>
                  </div>
                </section>
              </aside>
            </div>
          </section>
        </div>
        <!-- edit data --START -->
        <div class="tab-pane" id="editPrincipalsData">
          <div class="personal-info" id="principalsEditForm">
            <?php if(!empty($principal_info)) : ?>
            <form class="form-horizontal" id="principal_edit_form" role="form" method="post" 
              enctype="multipart/form-data">
              <div class="form-group">
                <label class="col-lg-3 control-label" for="full_name">Name:</label>
                <div class="col-lg-8">
                  <input class="form-control" id="full_name" type="text" name="full_name"
                  value="<?php echo $principal_info['full_name']; ?>" placeholder="Enter name" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label" for="gender">Gender:</label>
                <div class="col-lg-4">
                  <select name="gender" class="form-control" id="gender">
                    <optgroup label="Select Gender">
                      <?php
                        if(strtolower($principal_info['gender']) == 'male') {
                          echo'<option value="Male">Male</option>
                        <option value="Female">Female</option>';
                        } else {
                          echo'<option value="Female">Female</option>
                              <option value="Male">Male</option>';
                        }
                      ?>
                    </optgroup>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label" for="address">Address:</label>
                <div class="col-lg-8">
                  <input class="form-control" id="address" type="text" name="address" 
                  value="<?php echo $principal_info['address']; ?>" placeholder="Enter address" required>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label" for="date_of_birth">Date of Birth:</label>
                <div class="col-lg-8" id="datepick">
                  <input class="form-control" id="date_of_birth" type="text" name="date_of_birth"
                  value="<?php echo $principal_info['date_of_birth']; ?>"
                  placeholder="Select Date of birth" required>
                  <i class="fa fa-calendar"></i>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label" for="mobile">Mobile:</label>
                <div class="col-md-8">
                  <input class="form-control" id="mobile" type="text" name="mobile"
                  value="<?php echo $principal_info['mobile']; ?>" placeholder="Enter mobile number">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label" for="email">Email:</label>
                <div class="col-md-8">
                  <input class="form-control" id="email" type="text" name="email"
                  value="<?php echo $principal_info['email']; ?>" placeholder="Enter email address">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label" for="photo">Photo:</label>
                <div class="col-md-8">
                  <input type="file" name="edit-principal-image" class="file-pos"
                  id="photo" accept="image/*">
                </div>
              </div>
              <input type="hidden" name="username" value="<?php echo $principal_info['username']; ?>">
              <button type="submit" class="btn btn-full form-control"
                name="editPrincipalsData">Save Changes</button>
            </form>
            <?php endif; ?>
          </div>
        </div> <!-- edit data --END -->
        <div class="tab-pane" id="changePrincipalsPass">
          <section>
            <div class="panel panel-primary">
              <div class="panel-body">
                <form class="form-horizontal" role="form" method="post">
                  <div class="form-group">
                    <label class="col-lg-2 control-label" for="newPassword">New Password</label>
                    <div class="col-lg-6">
                      <input type="password" class="form-control" id="newPassword" name="newPassword"
                        placeholder="*********" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-2 control-label" for="reTypePassword_field">Re-type New Password</label>
                    <div class="col-lg-6">
                      <input type="password" class="form-control" id="reTypePassword_field" name="reTypePassword"
                        placeholder="*********" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-6">
                      <input type="hidden" name="username" value="<?php echo $principal_info['username']; ?>">
                      <button type="submit" class="btn btn-full pull-right"
                        name="changePrincipalsPass">Change Password</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
endif;