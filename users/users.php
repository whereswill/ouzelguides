<?php 
include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

if(!$visitor->isAdmin()) {
  header("Location: index.php");
}

//variable to identify this page title
$title = "All Users";

include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>
    
        <!-- Left nav
        ================================================== -->
        <div class="row">
          <div class="col-sm-1"> <!--start sidebar column-->
          </div> <!--end sidebar column-->
          <div class="col-sm-10"> <!--start content column--> 

              <button class="btn btn-warning" id="add-user" data-toggle="modal" data-target="#modal-add-user"><i class="icon-user icon-white glyphicon glyphicon-user"></i> Add User</button>

              <?php $users = $db->select("SELECT * FROM `as_users` ORDER BY `register_date` DESC"); ?>
              <table cellpadding="0" cellspacing="0" border="0" class="table table-striped users-table" id="sorted_table_1" width="100%">
                  <thead>
                  <th>Username</th>
                  <th>Email</th>
                  <th>Register Date</th>
                  <th>Confirmed</th>
                  <th>Active</th>
                  <th>Action</th>
                  </thead>

                  <?php $tempUser = new User();
                  foreach ($users as $user):
                      $tempUser->set_user_id($user['user_id']);
                      $userRole = $tempUser->getRole(); ?>
                      <tr class="user-row">
                          <td><?php echo htmlentities($user['username']); ?></td>
                          <td><?php echo htmlentities($user['email']); ?></td>
                          <td><?php echo $user['register_date']; ?></td>
                          <td class="text-center">
                              <?php echo $user['confirmed'] == "Y"
                                  ? "<p class='text-success'>Yes</p>"
                                  : "<p class='text-error'>No</p>"
                              ?>
                          </td>
                          <td class="text-center"><?php echo $tempUser->isActiveUser() ?'<span class="glyphicon glyphicon-ok text-success"></span>' : ''; ?></td>
                          <td>
                              <div class="btn-group">
                                  <a  class="btn <?php echo $user['banned'] == 'Y' ? 'btn-danger' : 'btn-info'; ?> btn-user"
                                      href="/users/update-user.php?user_id_fk=<?php echo $user['user_id']; ?>">

                                      <i class="icon-user icon-white glyphicon glyphicon-edit"></i>
                                      <span class="user-role"><?php echo ucfirst($userRole); ?></span>
                                  </a>
                                  <a class="btn <?php echo $user['banned'] == 'Y' ? 'btn-danger' : 'btn-info'; ?> dropdown-toggle" data-toggle="dropdown" href="#">
                                      <span class="caret"></span>
                                  </a>
                                  <ul class="dropdown-menu">

                                      <?php if ($tempUser->isActiveUser()): ?>
                                          <li>
                                              <a href="javascript:void(0);"
                                                 onclick="deactivateUser(this,<?php echo $user['user_id'];  ?>);">
                                                  <i class="icon-ban-circle glyphicon glyphicon-thumbs-up"></i>
                                                  <span>Deactivate</span>
                                              </a>
                                          </li>
                                      <?php else: ?>
                                          <li>
                                              <a href="javascript:void(0);"
                                                 onclick="activateUser(this,<?php echo $user['user_id'];  ?>);">
                                                  <i class="icon-ban-circle glyphicon glyphicon-thumbs-up"></i>
                                                  <span>Activate</span>
                                              </a>
                                          </li>
                                      <?php endif; ?>

                                      <?php if ( $user['banned'] == 'Y' ): ?>
                                          <li>
                                              <a href="javascript:void(0);"
                                                 onclick="unbanUser(this,<?php echo $user['user_id'];  ?>);">
                                                  <i class="icon-ban-circle glyphicon glyphicon-ban-circle"></i>
                                                  <span>unBan</span>
                                              </a>
                                          </li>
                                      <?php else: ?>
                                          <li>
                                              <a href="javascript:void(0);"
                                                 onclick="banUser(this,<?php echo $user['user_id'];  ?>);">
                                                  <i class="icon-ban-circle glyphicon glyphicon-ban-circle"></i>
                                                  <span>Ban</span>
                                              </a>
                                          </li>
                                      <?php endif; ?>

                                      <li class="divider"></li>

                                      <li>
                                          <a href="javascript:void(0);"
                                             onclick="roleChanger(this,<?php echo $user['user_id'];  ?>);">
                                              <i class="i"></i> Change Role</a>
                                      </li>
                                  </ul>
                              </div>
                          </td>
                      </tr>
                  <?php endforeach; ?>
              </table>
          </div>
        </div>
    
    <?php include $_SERVER['DOCUMENT_ROOT'].'/templates/footer.php'; ?>


           <div class="modal fade" id="modal-change-role">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title" id="modal-username">
                    <?php echo ASLang::get('pick_user_role'); ?>
                  </h4>
                </div>
                <div class="modal-body" id="details-body">
                    <?php $roles = $db->select("SELECT * FROM `as_user_roles`"); ?>
                    <?php if(count($roles) > 0): ?>
                      <p>Select Role:</p>
                      <select id="select-user-role" class="form-control" style="width: 100%;">
                      <?php foreach($roles as $role): ?>
                          <option value="<?php echo $role['role_id']; ?>">
                            <?php echo htmlentities(ucfirst($role['role'])); ?>
                          </option>
                      <?php endforeach; ?>
                      </select>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                  <a href="javascript:void(0);" class="btn btn-default" data-dismiss="modal" aria-hidden="true">
                    Cancel
                  </a>
                  <a href="javascript:void(0);" class="btn btn-primary" id="change-role-button" data-dismiss="modal" aria-hidden="true">
                     OK
                  </a>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->



          <div class="modal" id="modal-add-user" style="display: none;">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <h4 class="modal-title" id="modal-username">
                    Add User
                  </h4>
                </div>
                <div class="modal-body" id="details-body">
                    <form class="form-horizontal" id="add-user-form">

                      <!-- Set visitor and user IDs -->
                      <input type="hidden" id="adduser-visitorId" value="<?php echo $visitor_id; ?>" />
                      <input type="hidden" id="adduser-userId" />

                      <div class="control-group form-group">
                        <label class="control-label col-lg-3" for="adduser-email">
                          Email
                        </label>
                        <div class="controls col-lg-9">
                          <input id="adduser-email" name="adduser-email" type="text" class="input-xlarge form-control" >
                        </div>
                      </div>

                      <div class="control-group form-group">
                        <label class="control-label col-lg-3" for="adduser-username">
                          Username
                        </label>
                        <div class="controls col-lg-9">
                          <input id="adduser-username" name="adduser-username" type="text" class="input-xlarge form-control" >
                        </div>
                      </div>

                      <div class="control-group form-group">
                        <label class="control-label col-lg-3" for="adduser-password">
                          Password
                        </label>
                        <div class="controls col-lg-9">
                          <input id="adduser-password" name="adduser-password" type="password" class="input-xlarge form-control" >
                        </div>
                      </div>

                      <div class="control-group form-group">
                        <label class="control-label col-lg-3" for="adduser-confirm_password">
                          Repeat Password
                        </label>
                        <div class="controls col-lg-9">
                          <input id="adduser-confirm_password" name="adduser-confirm_password" type="password" class="input-xlarge form-control" >
                        </div>
                      </div>
                      <hr>
                      <div class="control-group form-group">
                        <label class="control-label col-lg-3" for="adduser-first_name">
                          First Name
                        </label>
                        <div class="controls col-lg-9">
                          <input id="adduser-first_name" name="adduser-first_name" type="text" class="input-xlarge form-control" >
                        </div>
                      </div>
                      <div class="control-group form-group">
                        <label class="control-label col-lg-3" for="adduser-last_name">
                          Last Name
                        </label>
                        <div class="controls col-lg-9">
                          <input id="adduser-last_name" name="adduser-last_name" type="text" class="input-xlarge form-control" >
                        </div>
                      </div>
                  </form>
                </div>
                <div align="center" class="ajax-loading"><img src="/assets/img/ajax_loader.gif" /></div>
                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-default" data-dismiss="modal" aria-hidden="true">
                      Cancel
                    </a>
                    <a href="javascript:void(0);" id="btn-add-user" class="btn btn-primary">
                      <span>Add</span>
                    </a>
                </div>
              </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
          </div><!-- /.modal -->
        
      <script type="text/javascript" src="/assets/js/sha512.js"></script>
      <script src="/OGLibrary/js/ogengine.js" type="text/javascript" charset="utf-8"></script>
      <script src="/OGLibrary/js/user-data.js" type="text/javascript" charset="utf-8"></script>

      <script type="text/javascript">
          $(document).ready(function() {

            //click add button to add user
            $('#btn-add-user').click(function(event) {
               ogengine.removeErrorMessages();
               addUser();
            });

            //click cancel button clear Modal
            $('[data-dismiss=modal]').on('click', function (e) {
              ogengine.clearModal($(this));

            })

            //$('#users-list').dataTable();
          } );
      </script>

  </body>
</html>
