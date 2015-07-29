<?php debug_backtrace() || die ("Direct access not permitted."); ?>
<?php
  global $schoolManagement;
?>

<div class="row state-overview">
  <div class="col-lg-3 col-sm-6">
    <section class="panel">
      <div class="symbol teachers_panel">
        <i class="fa fa-group"></i>
      </div>
      <div class="value">
        <h1 class="count"><?php echo $schoolManagement->teachers_data()->count(); ?></h1>
        <p>Total Teachers</p>
      </div>
    </section>
  </div>
  <div class="col-lg-3 col-sm-6">
    <section class="panel">
      <div class="symbol students_panel">
        <i class="fa fa-user"></i>
      </div>
      <div class="value">
        <h1 class="count"><?php echo $schoolManagement->students_data()->count(); ?></h1>
        <p>Total Students</p>
      </div>
    </section>
  </div>
  <div class="col-lg-3 col-sm-6">
    <section class="panel">
      <div class="symbol terques">
        <i class="fa fa-group"></i>
      </div>
      <div class="value">
        <h1 class="count"><?php echo $schoolManagement->parents_data()->count(); ?></h1>
        <p>Total Parents</p>
      </div>
    </section>
  </div>
  <div class="col-lg-3 col-sm-6">
    <section class="panel">
      <div class="symbol notices_panel">
        <i class="fa fa-tags"></i>
      </div>
      <div class="value">
        <h1 class="count"><?php echo $schoolManagement->notices_data()->count(); ?></h1>
        <p>Total Notices</p>
      </div>
    </section>
  </div>
</div>