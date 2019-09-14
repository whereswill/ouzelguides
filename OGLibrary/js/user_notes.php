<script type="text/javascript">
    $(document).ready(function () {
        
        //comment button click
        $('#note').click(function () {
            //remove all error messages
            asengine.removeErrorMessages();

            if($('#public_box').is(':checked')) { 
                var is_public="Y";
            } else {
                var is_public="N";
            }
         
            var note    = $("#note-text");
            var btn        = $(this);
            var user_id_fk = <?php echo json_encode($user_id_fk); ?>;
            var visitor_id = <?php echo json_encode($visitor_id); ?>;

            //console.log(is_public);
                 
            //validate comment
            if($.trim(note.val()) == "") {
                asengine.displayErrorMessage(note, $_lang.field_required);
                return;
            }
            
            //set button to posting state
            asengine.loadingButton(btn, $_lang.posting);
            
             $.ajax({
                url: "/OGEngine/OGAjax.php",
                type: "POST",
                data: {
                    action : "postUserNote",
                    note: note.val(),
                    is_public: is_public,
                    user_id_fk: user_id_fk,
                    visitor_id: visitor_id
                },
                success: function (result) {
                    //return button to normal state
                    asengine.removeLoadingButton(btn);
                    document.getElementById("public_box").checked = false;
                    try {
                       //try to parse result to JSON
                       var res = JSON.parse(result);
                       
                       //generate comment html and display it
                       var html  = "<blockquote>";
                            html += "<p>"+res.user_note+"</p>";
                            html += "<small>"+res.visitor+" <em> "+ " at " +res.created_on;
                            if (res.is_public == "Y") {
                                html += " (public)";
                            } else {
                                html += " (private)";
                            }
                            html += "</em></small>";
                            html += "</blockquote>";
                        if( $(".notes-notes blockquote").length >= 7 )
                            $(".notes-notes blockquote").last().remove();
                        $(".notes-notes").prepend($(html));
                        note.val("");
                    }
                    catch(e){
                       //parsing error, display error message
                       asengine.displayErrorMessage(note, $_lang.error_writing_to_db);
                    }
                }
            });
        });

        //delete note button click
        $('.delete-note').click(function () {

            var q = $(this).attr('data-note-id');

            //console.log(q);

            $.ajax({
                url: "/OGEngine/OGAjax.php",
                type: "POST",
                data: {
                    action : "deleteUserNote",
                    usernotes_id: q
                },
                success: function (result) {

                    console.log(result);

                    try {
                        window.location.reload(true); 
                    }
                    catch(e){
                       //parsing error, display error message
                       asengine.displayErrorMessage(note, "error deleing note from Db");
                    }
                }

            });

        }); //end click
    	
    });
</script>