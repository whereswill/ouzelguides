/**
 * Gets info and sets modal to display
 * @param {string} "edit" or "add" designates mode of modal
 * @param {int} skill_id of skill that will be updated.
 */
function showModal(mode, skill_id) {

    var modal       = $("#modal-skill"),
        name        = $('#skill-name'),
        description = $('#skill-description'),
        btn         = $('#btn-skill'),
        modalTitle  = modal.find(".modal-title"),
        modalBody   = modal.find(".modal-body"),
        ajaxLoading = modal.find(".ajax-loading");

        console.log("here");

    if(mode === "add") {
        btn.attr('onclick', 'addSkill();');
        btn.text('Add');
        modalTitle.text('Add Skill');
        modal.modal('show');

    } else {
        $("#skill-id").val(skill_id);
        btn.attr('onclick', 'updateSkill();');

        modal.modal('show');
        modalTitle.text('Loading');
        modalBody.hide();
        ajaxLoading.show();

        $.ajax({
            type: "POST",
            url: "/OGEngine/OGAjax.php",
            data: {
                action: "getSkill",
                id:     skill_id
            },
            success : function (result) {
                //parse result to JSON
                var res = JSON.parse(result);

                name.val( res.skill_name );
                description.val( res.description );

                btn.text('Update');

                ajaxLoading.hide();
                modalTitle.text('Update Skill');
                modalBody.show();
            }
        });
    }
}

/**
 * Adds a skill
 */
function addSkill() {
    
    var name 	      = $('#skill-name'),
    	description   = $('#skill-description'),
        container     = $('#container'),
        btn    		  = $('#btn-skill');
         
    //validate name
    if($.trim(name.val()) == "") {
        ogengine.displayErrorMessage(name, "Please enter a name for this skill");
        return;
    }

    //validate description
    if($.trim(description.val()) == "") {
        ogengine.displayErrorMessage(description, "Please describe the requirements for this skill");
        return;
    }

    //create data that will be sent to server
    var data = {
            name:           name.val(),
            description:    description.val()
        };
    
    //set button to posting state
    ogengine.loadingButton(btn, "Posting");
    
     $.ajax({
        url: "/OGEngine/OGAjax.php",
        type: "POST",
        data: {
            action : "addSkill",
            skillDetails: data
        },
        success: function (result) {
            //return button to normal state
            ogengine.removeLoadingButton(btn);
            try {
           		//try to parse result to JSON
                var res = JSON.parse(result);

                //Display Bootstrap alert with success
                if (res.status === "success") {

                    //var edit = 'edit';
                    //generate new line in certs table and display it
                    var html  = '<tr class="user-row">';
                        html += "<td>"+res.skill_name+"</td>";
                        html += "<td>"+res.skill_description+"</td>";
                        html += '<td><div class="btn-group btn-group-xs">';
                        html += '<a class="btn btn-info" href="javascript:void(0);" onclick="showModal(\'edit\','+res.skill_id+');"><span class="glyphicon glyphicon-pencil"></span></a>';
                        html += '<a class="btn btn-danger" href="javascript:void(0);" onclick="deleteSkill(this,'+res.skill_id+');"><span class="glyphicon glyphicon-remove"></span></a>';
                        html += '</div></td>';
                        html += "</tr>";
                    $('#modal-skill').modal('toggle');
                    $('#skills-list tr:last').after(html);
                    ogengine.displaySuccessAlert(container, res.msg);
                    ogengine.clearModal(btn);

                } else {
                    ogengine.displayFailureAlert(container, res.msg);
                }
            }
            catch(e){
                console.log("oops");
                //parsing error, display error message
                html = 'Ajax error: ' + e;
                ogengine.displayFailureAlert(container, html);
            }
        }
    });
}

/**
 * Updates a skill
 */
function updateSkill() {
    
    var id            = $("#skill-id"),
        name          = $('#skill-name'),
        description   = $('#skill-description'),
        container     = $('#container'),
        btn           = $('#btn-skill');
         
    //validate name
    if($.trim(name.val()) == "") {
        ogengine.displayErrorMessage(name, "Please enter a name for this skill");
        return;
    }

    //validate description
    if($.trim(description.val()) == "") {
        ogengine.displayErrorMessage(description, "Please describe the requirements for this skill");
        return;
    }

    //create data that will be sent to server
    var data = {
            id:             id.val(),
            name:           name.val(),
            description:    description.val()
        };
    
    //set button to posting state
    ogengine.loadingButton(btn, "Posting");
    
     $.ajax({
        url: "/OGEngine/OGAjax.php",
        type: "POST",
        data: {
            action : "updateSkill",
            skillDetails: data
        },
        success: function (result) {
            //return button to normal state
            ogengine.removeLoadingButton(btn);
            try {
                //try to parse result to JSON
                var res = JSON.parse(result);

                if(res.status === "error") {
                    ogengine.displayErrorMessage(container, res.msg);
                } else {
                    location.reload();
                }
            }
            catch(e){
                console.log("oops");
                //parsing error, display error message
                html = 'Ajax error: ' + e;
                $('#modal-skill').modal('toggle');
                ogengine.displayFailureAlert(container, res.msg);
            }
        }
    });
}

/**
 * Deletes an skill
 * @param {object} element Clicked DOM element.
 * @param {int} skill_id of skill that will be deleted.
 */
function deleteSkill(btn, skill_id) {
    //get whole e-contact row that will be deleted
    var row = btn.closest('tr');

    //ask admin to confirm that he want to delete this user
    var c = confirm("Are you sure? This action will remove the skill from all guides who currently have it. This cannot be undone!");
    if(c) {
        //confimed
        //send data to server
        $.ajax({
            type: "POST",
            url: "/OGEngine/OGAjax.php",
            data: {
                action:     "deleteSkill",
                skill_id:   skill_id
            },
            success: function (result) {
                try {
                    var count = $('#skills-list tr').length;
                    if (count > 2) {
                        row.remove();
                    } else {
                        var html  = "<tr><td>No Skill Data</td><td></td><td></td></tr>";
                        $('#skills-list>tbody>tr:last').replaceWith(html);
                    }
                }
                catch(e){
                    console.log("oops");
                    //Display Bootstrap alert with failure
                    html = 'Ajax error: ' + e;
                    ogengine.displayFailureAlert(container, html);
                }
            }
        });
    }
}