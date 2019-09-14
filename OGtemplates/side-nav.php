<ul class="nav nav-list bs-docs-sidenav">
  <li class="active">
      <a href="/index.php">
          <i class="icon-home glyphicon glyphicon-home"></i>
          <i class="icon-chevron-right glyphicon glyphicon-chevron-right"></i> 
          <?php echo ASLang::get('home'); ?>
      </a>
  </li>
  <li>
      <a href="/users/update-user.php?user_id_fk=<?php echo $visitor_id; ?>">
          <i class="icon-user glyphicon glyphicon-user"></i>
          <i class="icon-chevron-right glyphicon glyphicon-chevron-right"></i> 
          My Profile
      </a>
  </li>
  <li>
      <a href="/profile.php">
          <i class="icon-user glyphicon glyphicon-user"></i>
          <i class="icon-chevron-right glyphicon glyphicon-chevron-right"></i> 
          Change Password
      </a>
  </li>
  <li>
      <a href="/my-work.php">
          <i class="icon-user glyphicon glyphicon-road"></i>
          <i class="icon-chevron-right glyphicon glyphicon-chevron-right"></i> 
          My Work
      </a>
  </li>
  <li>
      <a href="/river-log.php">
          <i class="icon-user glyphicon glyphicon-th-list"></i>
          <i class="icon-chevron-right glyphicon glyphicon-chevron-right"></i> 
          My River Log
      </a>
  </li>
  <?php if($visitor->isAdmin()): ?>
<!--   <li>
      <a href="/trips/trips.php">
          <i class="icon-fire glyphicon glyphicon-usd"></i>
          <i class="icon-chevron-right glyphicon glyphicon-chevron-right"></i> 
          <?php //echo 'Payroll'; ?>
      </a>
  </li> -->
<!--   <li>
      <a href="/users.php">
          <i class="icon-fire glyphicon glyphicon-user"></i>
          <i class="icon-chevron-right glyphicon glyphicon-chevron-right"></i> 
          <?php //echo ASLang::get('users'); ?>
      </a>
  </li> -->
  <li>
      <a href="/user_roles.php">
          <i class="icon-fire glyphicon glyphicon-user"></i>
          <i class="icon-chevron-right glyphicon glyphicon-chevron-right"></i> 
          <?php echo ASLang::get('user_roles'); ?>
      </a>
  </li>
  <?php endif; ?>
</ul>