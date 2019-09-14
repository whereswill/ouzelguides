
      <!-- Navbar
      ================================================== -->
      <div class="navbar navbar-default navbar-fixed-top">
        <div class="navbar-inner">
          <div class="container">
            <ul class="nav navbar-nav"> 
              <a class="brand navbar-brand" href="./index.php"><?php echo WEBSITE_NAME;  ?></a>
            </ul>
            <div class="pull-right">
<!--               <div class="header-flags-wrapper">
                <a href="?lang=en">
                  <img src="assets/img/en.png" alt="English" title="English" class="<?php //echo ASLang::getLanguage() != 'en' ? 'fade' : ''; ?>" />
                </a>
                <a href="?lang=rs">
                  <img src="assets/img/rs.png" alt="Serbian" title="Serbian (cyrillic)" class="<?php //echo ASLang::getLanguage() != 'rs' ? 'fade' : ''; ?>" />
                </a>
              </div> -->
              <ul class="nav pull-right">
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
                      <a href="logout.php" id="logout">
                        <i class="icon-off glyphicon glyphicon-off"></i> 
                        <?php echo ASLang::get('logout'); ?>
                      </a>
                    </li>
                  </ul>
                </li>
              </ul>
            </div> <!-- pull-right --> 
          </div><!-- end container --> 
        </div><!-- end navbar-inner --> 
      </div><!-- end navbar --> 


      <div class="container">