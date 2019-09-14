	<div class="navbar navbar-default navbar-fixed-top" role="navigation">
	  <div class="container">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>	      
        <ul class="nav navbar-nav"> 
            <a class="brand navbar-brand" href="/index.php">OuzelGuides</a>
        </ul>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	    	<?php if($visitor->isAdmin()) { ?>
	      <ul class="nav navbar-nav">
			 		<li class="dropdown">
	         	<a href="#" class="dropdown-toggle" data-toggle="dropdown">People <span class="caret"></span></a>
          	<ul class="dropdown-menu" role="menu">
							<li <?php echo ($page == 'users.php'? 'class="active"':'') ?>><a href="/users/users.php">User List</a></li>
							<li <?php echo ($page == 'guides.php'? 'class="active"':'') ?>><a href="/guideDetails/guides.php">Active Guides</a></li>
        		</ul>
	        </li>
					<li class="dropdown">
	         	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Schedule <span class="caret"></span></a>
	          <ul class="dropdown-menu" role="menu">
							<li <?php echo ($page == 'trips.php'? 'class="active"':'') ?>><a href="/trips/trips.php">Trips</a></li>
							<li <?php echo ($page == 'timesheets.php'? 'class="active"':'') ?>><a href="/timesheets/timesheets.php">WH Work</a></li>
	        	</ul>
	        </li>
					<li class="dropdown">
	         	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Pay <span class="caret"></span></a>
	          <ul class="dropdown-menu" role="menu">
							<li <?php echo ($page == 'approve-work.php'? 'class="active"':'') ?>><a href="/approveWork/approve-work.php">Approve and Pay</a></li>
							<li <?php echo ($page == 'payPeriods.php'? 'class="active"':'') ?>><a href="/approveWork/payPeriods.php">Pay Periods</a></li>
	        	</ul>
	        </li>
					<li class="dropdown">
	         	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports <span class="caret"></span></a>
	          <ul class="dropdown-menu" role="menu">
							<li <?php echo ($page == 'all-trips.php'? 'class="active"':'') ?>><a href="/reports/all-trips.php">All Trips</a></li>
							<li <?php echo ($page == 'all-timesheets.php'? 'class="active"':'') ?>><a href="/reports/all-timesheets.php">All Timesheets</a></li>
	            <li class="divider"></li>
	            <li <?php echo ($page == 'river-days.php'? 'class="active"':'') ?>><a href="/reports/river-days.php">River Days</a></li>
	            <li <?php echo ($page == 'swamper-days.php'? 'class="active"':'') ?>><a href="/reports/swamper-days.php">Swamper Days</a></li>
	            <li <?php echo ($page == 'guide-breakdown.php'? 'class="active"':'') ?>><a href="/reports/guide-breakdown.php">Pay Breakdown by Guide</a></li>
	            <li <?php echo ($page == 'skills-breakdown.php'? 'class="active"':'') ?>><a href="/reports/skills-breakdown.php">Skills Breakdown by Guide</a></li>
	            <li class="divider"></li>
	            <li <?php echo ($page == 'drainage-ratio.php'? 'class="active"':'') ?>><a href="/reports/drainage-ratio.php">Ratio by Drainage</a></li>
	            <li <?php echo ($page == 'drainage-breakdown.php.php'? 'class="active"':'') ?>><a href="/reports/drainage-breakdown.php">Pay Breakdown by Drainage</a></li>
	        	</ul>
	        </li>
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Settings <span class="caret"></span></a>
	          <ul class="dropdown-menu" role="menu">
	            <li <?php echo ($page == 'riverTrips.php'? 'class="active"':'') ?>><a href="/riverTrips/riverTrips.php">Trip Names</a></li>
							<li <?php echo ($page == 'tripTypes.php'? 'class="active"':'') ?>><a href="/tripTypes/tripTypes.php">Trip Types</a></li>
							<li <?php echo ($page == 'roles.php'? 'class="active"':'') ?>><a href="/roles/roles.php">Roles</a></li>
	            <li class="divider"></li>
	            <li <?php echo ($page == 'payRates.php'? 'class="active"':'') ?>><a href="/payRates/payRates.php">Pay Rates</a></li>
							<li <?php echo ($page == 'bonusRates.php'? 'class="active"':'') ?>><a href="/bonusRates/bonusRates.php">Bonus Rates</a></li>
							<li <?php echo ($page == 'certRates.php'? 'class="active"':'') ?>><a href="/certRates/certRates.php">Certification Rates</a></li>
							<li <?php echo ($page == 'tlRates.php'? 'class="active"':'') ?>><a href="/tlRates/tlRates.php">TL Rates</a></li>
							<li <?php echo ($page == 'rigRates.php'? 'class="active"':'') ?>><a href="/rigRates/rigRates.php">Rig Rates</a></li>
							<li <?php echo ($page == 'skills.php'? 'class="active"':'') ?>><a href="/skills/skills.php">Guide Skills</a></li>
	          </ul>
	        </li>
	      </ul>
	      <?php } ?>
        <ul class="nav navbar-nav navbar-right">
        	 <?php if($visitor->isAdmin()) { ?>
	        <li <?php echo ($page == 'app_notes.php'? 'class="active"':'') ?>><a href="/app_notes.php">App Notes</a></li>
	        <?php } ?>
          <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <?php echo ASLang::get('welcome'); ?>, <?php echo htmlentities($visitorInfo['username']);  ?>
                  <b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                  <li>
                      <a href="/users/update-user.php?user_id_fk=<?php echo $visitor_id; ?>">
                          <i class="icon-user glyphicon glyphicon-user"></i> 
                          My Profile
                      </a>
                  </li>
                  <li>
                      <a href="/profile.php">
                          <i class="icon-user glyphicon glyphicon-user"></i> 
                          Change Password
                      </a>
                  </li>
                  <li class="divider"></li>
                  <li>
                      <a href="/logout.php" id="logout">
                          <i class="icon-off glyphicon glyphicon-off"></i> 
                          <?php echo ASLang::get('logout'); ?>
                      </a>
                  </li>
              </ul>
          </li>
      	</ul>
	    </div><!-- /.navbar-collapse -->
	  </div><!-- /.container-->
	</div>

	<div class="container">