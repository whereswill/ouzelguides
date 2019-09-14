<?php

	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

	//SET PERMISSIONS FOR PAGE
	if(!$visitor->isAdmin()) {
    header("Location: /index.php");
    exit();
   }

	//variable to identify this page title
	$title = "Guide Details";
	
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';

	if (isset($_REQUEST['filter']) && $_REQUEST['filter'] == 'all') {
		$details = $db->select("SELECT * FROM `guide_details` ORDER BY `user_id_fk` DESC");
	} else {
		$details = $db->select("SELECT * FROM `guide_details` WHERE `active_bool` = 'Y' ORDER BY `user_id_fk` DESC");
	}
	$guide = new Guide();
	
?>
			
			<div class="row">
				<div class="col-sm-1"> <!--start sidebar column-->
				</div> <!--end sidebar column-->
				<div class="col-sm-10"> <!--start content column-->		
					<div>
						<?php if (isset($_REQUEST['filter']) && $_REQUEST['filter'] == 'all') {?>
							<p><a href="/guideDetails/guides.php" class="btn btn-warning"><span class="glyphicon glyphicon-plus btn-style"> Show Active Guides</span></a></p>
						<?php } else { ?>
							<p><a href="/guideDetails/guides.php?filter=all" class="btn btn-warning"><span class="glyphicon glyphicon-plus btn-style"> Show All Guides</span></a></p>
						<?php } ?>
					</div>
					<div>
						<table class="table table-striped table-bordered" id="sorted_table">
							<thead>
								<tr>
									<th>Guide Name</th>
									<th>Pay Rate</th>
									<th>Hire Date</th>
									<th>Bonus</th>
									<th>Certs</th>
									<th>Active?</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									foreach ($details as $row) {
										
										$guide->set_guide_id($row['user_id_fk']);
										
								  	echo '<tr>' . "\n";
										echo '<td>'. $guide->getUserName() . '</td>' . "\n";
								   	echo '<td>'. $guide->getCurrentPayrate() . '</td>' . "\n";
								   	echo '<td>'. format_date($row['hire_date']) . '</td>' . "\n";
										echo '<td width=100 class="text-center">';
									 	echo ($row['bonus_eligible'] == 'Y' ? '<span class="glyphicon glyphicon-ok text-success"></span>' : '');
										echo '</td>' . "\n";
										echo '<td width=100 class="text-center">';
									 	echo ($guide->areCertsCurrent() ? '<span class="glyphicon glyphicon-ok text-success"></span>' : '');
										echo '</td>' . "\n";
										echo '<td width=100 class="text-center">';
									 	echo ($row['active_bool'] == 'Y' ? '<span class="glyphicon glyphicon-ok text-success"></span>' : '');
										echo '</td>' . "\n";
										?>

					   				<td>
                        <div class="btn-group">
                            <a  class="btn btn-info btn-user"
                                href="/users/update-user.php?user_id_fk=<?php echo $row['user_id_fk']; ?>">

                                <i class="icon-user icon-white glyphicon glyphicon-edit"></i>
                            </a>
                            <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#">
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">

                                <?php if ($guide->isActiveGuide()): ?>
                                    <li>
                                        <a href="javascript:void(0);"
                                           onclick="deactivateGuide(this,<?php echo $row['user_id_fk'];  ?>);">
                                            <i class="icon-ban-circle glyphicon glyphicon-thumbs-up"></i>
                                            <span>Deactivate</span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li>
                                        <a href="javascript:void(0);"
                                           onclick="activateGuide(this,<?php echo $row['user_id_fk'];  ?>);">
                                            <i class="icon-ban-circle glyphicon glyphicon-thumbs-up"></i>
                                            <span>Activate</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </td>
                  </tr>
               <?php } ?>
							</tbody>
						</table>
					</div> <!-- .row -->
				</div> <!--end content column-->
			</div> <!-- .row -->
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/footer.php';
?>

  <script src="/OGLibrary/js/guide-data.js" type="text/javascript" charset="utf-8"></script>

	</body>
</html>