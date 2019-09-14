<?php
include $_SERVER['DOCUMENT_ROOT'].'/OGtemplates/page-init.php';
include 'OGtemplates/header.php';
include 'OGtemplates/top-nav.php';
//include 'templates/header.php';

//variable to identify this page title
$title = "Home";
?>
        
            <div class="row">
              <div class="col-sm-3 bs-docs-sidebar">
                
                <?php
                ///Left nav box
                include 'OGtemplates/side-nav.php';
                ?>

              </div>
              <div class="col-sm-9">
                    
                    <div class="comments">
                            <h3 id="comments-title">
                              Guide Update Information 
                              <small>last 7 updates</small>
                            </h3>
                            <div class="comments-comments">
                                <?php $OGComment = new Note(); ?>
                                <?php $comments = $OGComment->getComments(); ?>
                                <?php foreach($comments as $comment): ?>
                                 <blockquote>
                                    <p><?php echo htmlentities( stripslashes($comment['comment']) ); ?></p>
                                    <small>
                                        <?php echo htmlentities($comment['posted_by_name']);  ?> 
                                        <em> at <?php echo format_datetime($comment['post_time']); ?></em></small>
                                </blockquote>
                                <?php endforeach; ?>
                            </div>
                    </div>
                
                    <?php if($visitor->getRole() != 'user'): ?>
                    <div class="leave-comment">
                        <div class="control-group form-group">
                            <h5>Leave update</h5>
                            <div class="controls">
                                <textarea class="form-control" id="comment-text"></textarea>
                            </div>
                        </div>
                        <div class="control-group form-group">
                             <div class="controls">
                                <button class="btn btn-success" id="comment">
                                  Publish
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                        <p>You can't Post</p>
                    <?php endif; ?>
                    
        
              </div>
            </div>
        
    <?php include 'templates/footer.php'; ?>

    <script src="/ASLibrary/js/asengine.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="/ASLibrary/js/index.js" charset="utf-8"></script>

    
  </body>
</html>
