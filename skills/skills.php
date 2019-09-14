<?php 
include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';

if(!$visitor->isAdmin()) {
  header("Location: index.php");
}

//variable to identify this page title
$title = "Guide Skills";

include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/header.php';
include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/top-nav.php';
?>
    
        <!-- Left nav
        ================================================== -->
        <div class="row">
          <div class="col-sm-1"> <!--start sidebar column-->
          </div> <!--end sidebar column-->
          <div class="col-sm-10" id="container"> <!--start content column--> 

              <div>
                <h3 class="pageTitle">Guide Skills</h3>
              </div>

              <a class="btn btn-warning btn-high" href="javascript:void(0);" 
                  onclick="showModal('add','none')" > 
                  <i class="icon-user icon-white glyphicon glyphicon-wrench"></i>
                  Add Skill
              </a>

              <?php $skills = $db->select("SELECT * FROM `skills`"); ?>
              <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="skills-list" width="100%">

                  <thead>
                  <th width=200>Skill</th>
                  <th>Description</th>
                  <th width=65>Action</th>
                  </thead>

                  <?php foreach ($skills as $skill) { ?>
                      <tr class="user-row">
                          <td><?php echo htmlentities($skill['skill_name']); ?></td>
                          <td><?php echo htmlentities($skill['description']); ?></td>
                          <td>
                              <div class="btn-group btn-group-xs">
                                  <a class="btn btn-info" href="javascript:void(0);" onclick="showModal('edit',<?php echo $skill['skill_id'];  ?>);"><span class="glyphicon glyphicon-pencil"></span></a>
                                  <a class="btn btn-danger" href="javascript:void(0);" onclick="deleteSkill(this,<?php echo $skill['skill_id'];  ?>);"><span class="glyphicon glyphicon-remove"></span></a>
                              </div>
                          </td>
                      </tr>
                  <?php } ?>

              </table>
          </div>
        </div>
    
    <?php include $_SERVER['DOCUMENT_ROOT'].'/templates/footer.php'; ?>

      <!--ADD SKILL MODAL-->

      <div class="modal" id="modal-skill" style="display: none;">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="modal-skill-title">
                Add Skill
              </h4>
            </div>
            <div class="modal-body" id="details-body">
                <form class="form-horizontal" id="skill-form">

                  <!-- Set visitor and user IDs -->
                  <input type="hidden" id="skill-visitorId" value="<?php echo $visitor_id; ?>" />
                  <input type="hidden" id="skill-id" />

                  <div class="control-group form-group">
                    <label class="control-label col-lg-3" for="skill-name">
                      Name
                    </label>
                    <div class="controls col-lg-9">
                      <input id="skill-name" name="skill-name" type="text" class="input-xlarge form-control" autofocus="autofocus">
                    </div>
                  </div>

                  <div class="control-group form-group">
                    <label class="control-label col-lg-3" for="skill-description">
                      Description
                    </label>
                    <div class="controls col-lg-9">
                      <textarea id="skill-description" name="skill-description" type="text" class="input-xlarge form-control" ></textarea>
                    </div>
                  </div>

              </form>
            </div>
            <div align="center" class="ajax-loading"><img src="/assets/img/ajax_loader.gif" /></div>
            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-default" data-dismiss="modal" aria-hidden="true">
                  Cancel
                </a>
                <a href="javascript:void(0);" id="btn-skill" class="btn btn-primary">
                  Add
                </a>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->
        
      <script type="text/javascript" src="/assets/js/sha512.js"></script>
      <script src="/OGLibrary/js/ogengine.js" type="text/javascript" charset="utf-8"></script>
      <script src="/OGLibrary/js/skill-data.js" type="text/javascript" charset="utf-8"></script>

      <script type="text/javascript">
          $(document).ready(function() {

            //click cancel button clear Modal
            $('[data-dismiss=modal]').on('click', function (e) {
              ogengine.clearModal($(this));
            })

          } );
      </script>

  </body>
</html>
