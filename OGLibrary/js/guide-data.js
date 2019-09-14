function addGuideDetails() {

    var container       = $('#guide_details'),   
        user_id         = $('#form-userId'),
        active_bool     = $('#guideDetail_active_bool'),
        seniority       = $('#guideDetail_seniority'),
        hire_date       = $('#guideDetail_hire_date'),
        bonus_eligible  = $('#guideDetail_bonus_eligible'),
        bonus_start     = $('#guideDetail_bonus_start'),
        btn             = $('#btn-add-guide');

    if(active_bool.prop("checked") == true){
        active_guide = "Y";
    }
    else {
        active_guide = "N";
    }

    if(bonus_eligible.prop("checked") == true){
        bonus_guide = "Y";
    }
    else {
        bonus_guide = "N";
    }

    //validate hire date is before today
    if(!hire_date.val() || new Date(hire_date.val()) >= new Date()) {
        ogengine.displayErrorMessage(hire_date, "Please select a start date before today");
        return;
    }
    //validate bonus start date
    var bonus_start_date = bonus_start.val();
    if($.trim(bonus_start.val()) == "") {
        bonus_start_date = hire_date.val();
    }

    //create data that will be sent to server
    var userData = {
            user_id:        user_id.val(),
            active_bool:    active_guide,
            seniority:      seniority.val(),
            hire_date:      hire_date.val(),
            bonus_eligible: bonus_guide,
            bonus_start:    bonus_start_date,
        };
    
    //set button to posting state
    ogengine.loadingButton(btn, "Posting");
    
     $.ajax({
        url: "/OGEngine/OGAjax.php",
        type: "POST",
        data: {
            action :        "addGuide",
            guideDetails:   userData
        },
        success: function (result) {
            //return button to normal state
            ogengine.removeLoadingButton(btn);
            try {
                //try to parse result to JSON
                var res = JSON.parse(result);

                bonus_start.val(bonus_start_date);

                //Display Bootstrap alert with success
                if (res.status === "success") {
                    ogengine.displaySuccessAlert(container, res.msg);
                } else {
                    ogengine.displayFailureAlert(container, res.msg);
                }

                $('#btn-add-guide').replaceWith('<button class="col-sm-2 btn-group-sm btn btn-info" id="btn-update-guide">Update Guide</button>');
                $( "#btn-update-guide" ).bind( "click", function() {
                    ogengine.removeErrorMessages();
                    updateGuideDetails();
                });

            }
            catch(e){
                //Display Bootstrap alert with failure
                html = 'Ajax error: ' + e;
                ogengine.displayFailureAlert(container, html);
            }
        }
    });
}

function updateGuideDetails() {

    var container       = $('#guide_details'),     
        user_id         = $('#form-userId'),
        active_bool     = $('#guideDetail_active_bool'),
        seniority       = $('#guideDetail_seniority'),
        hire_date       = $('#guideDetail_hire_date'),
        bonus_eligible  = $('#guideDetail_bonus_eligible'),
        bonus_start     = $('#guideDetail_bonus_start'),
        btn             = $('#btn-update-guide');

    if(active_bool.prop("checked") == true){
        active_guide = "Y";
    }
    else {
        active_guide = "N";
    }

    if(bonus_eligible.prop("checked") == true){
        bonus_guide = "Y";
    }
    else {
        bonus_guide = "N";
    }

    //validate bonus start date is before today
    if(new Date(bonus_start.val()) >= new Date()) {
        ogengine.displayErrorMessage(bonus_start, "Please select a start date before today");
        return;
    }

    //create data that will be sent to server
    var userData = {
            active_bool:    active_guide,
            seniority:      seniority.val(),
            hire_date:      hire_date.val(),
            bonus_eligible: bonus_guide,
            bonus_start:    bonus_start.val()
        };
    
    //set button to posting state
    ogengine.loadingButton(btn, "Posting");
    
     $.ajax({
        url: "/OGEngine/OGAjax.php",
        type: "POST",
        data: {
            action : "updateGuide",
            user_id: user_id.val(),
            guideDetails: userData
        },
        success: function (result) {
            //return button to normal state
            ogengine.removeLoadingButton(btn);
            try {
                //try to parse result to JSON
                var res = JSON.parse(result);

                //Display Bootstrap alert with success
                if (res.status === "success") {
                    ogengine.displaySuccessAlert(container, res.msg);
                } else {
                    ogengine.displayFailureAlert(container, res.msg);
                }

            }
            catch(e){
                //Display Bootstrap alert with failure
                html = 'Ajax error: ' + e;
                ogengine.displayFailureAlert(container, html);
            }
        }
    });
}

function addCert() {
    
    var user_id 	= $('#form-userId'),
    	cert_id 	= $('#select-cert-name'),
    	exp_date 	= $('#addcert-expdate'),
        first_cell  = $('#cert-list>tbody>tr>td:first'),
        btn    		= $('#btn-add-cert');

    var first = first_cell.html();
         
    //validate cert selection
    if($.trim(cert_id.val()) == "") {
        ogengine.displayErrorMessage(cert_id, "Please select a certification");
        return;
    }

    //validate cert expiration date
    if($.trim(exp_date.val()) == "") {
        ogengine.displayErrorMessage(exp_date, "Please select an expiration date");
        return;
    }

    //validate cert expiration date is after today
    if(new Date(exp_date.val()) <= new Date()) {
        ogengine.displayErrorMessage(exp_date, "This expiration date is before today");
        return;
    }
    
    //set button to posting state
    ogengine.loadingButton(btn, "Posting");
    
     $.ajax({
        url: "/OGEngine/OGAjax.php",
        type: "POST",
        data: {
            action : "addCert",
            user_id: user_id.val(),
            cert_id: cert_id.val(),
            exp_date: exp_date.val(),
        },
        success: function (result) {
            //return button to normal state
            ogengine.removeLoadingButton(btn);
            try {
           		//try to parse result to JSON
                var res = JSON.parse(result);

               	//generate new line in table and display it
               	var html  = "<tr>";
                    html += "<td>"+res.cert+"</td>";
                    html += "<td>"+res.exp_date+"</td>";
                    html += '<td><span class="glyphicon glyphicon-ok text-success"></span></td>';
                    html += '<td class="remove"><a href="javascript:void(0);" onclick="deleteCert(this,'+res.guidecert_id+');"><i class="icon-trash glyphicon glyphicon-trash"></i></a></td>';
                    html += "</tr>";
                $('#modal-add-cert').modal('toggle');
                //$('#cert-list tr:last').after(html);
                if (first == "No Certifications") {
                    $('#cert-list>tbody>tr:last').replaceWith(html);
                } else {
                    $('#cert-list>tbody>tr:last').after(html);
                }
             	ogengine.clearModal(btn);
            }
            catch(e){
                console.log("oops");
                //parsing error, display error message
                ogengine.displayErrorMessage(exp_date, "Error writing to Db :(");
            }
        }
    });
}

/**
 * Deletes a cert
 * @param {object} element Clicked DOM element.
 * @param {int} guidecert_id Id of cert that will be deleted.
 */
function deleteCert(btn, guidecert_id) {
    //get whole user row that will be deleted
    var row = btn.closest('tr');

    //ask admin to confirm that he want to delete this user
    var c = confirm("Are you sure? This cannot be undone!");
    if(c) {
        //confimed
        //send data to server
        $.ajax({
            type: "POST",
            url: "/OGEngine/OGAjax.php",
            data: {
                action: "deleteCert",
                guidecert: guidecert_id
            },
            success: function (result) {
                var count = $('#cert-list tr').length;
                console.log(count);
                if (count > 2) {
                    row.remove();
                } else {
                    var html  = "<tr><td>No Certifications</td><td></td><td></td><td></td></tr>";
                    $('#cert-list>tbody>tr:last').replaceWith(html);
                }
            }
        });
    }
}

function addPayRate() {
    
    var user_id     = $('#form-userId'),
        visitor_id  = $('#form-visitorId'),
        payrate_id  = $('#select-payrate'),
        notes       = $('#addpayrate-notes'),
        first_cell  = $('#payrate-list>tbody>tr>td:first'),
        btn         = $('#btn-add-payrate');

    var first = first_cell.html();
         
    //validate pay rate selection
    if($.trim(payrate_id.val()) == "") {
        ogengine.displayErrorMessage(payrate_id, "Please select a pay rate");
        return;
    }
    
    //set button to posting state
    ogengine.loadingButton(btn, "Posting");
    
     $.ajax({
        url: "/OGEngine/OGAjax.php",
        type: "POST",
        data: {
            action      : "addPayRate",
            user_id     : user_id.val(),
            payrate_id  : payrate_id.val(),
            notes       : notes.val(),
            visitor_id  : visitor_id.val(),
        },
        success: function (result) {
            //return button to normal state
            ogengine.removeLoadingButton(btn);
            try {
                //try to parse result to JSON
                var res = JSON.parse(result);

                //generate new line in table and display it
                var html  = "<tr>";
                    html += "<td>"+res.rate+"</td>";
                    html += "<td>"+res.start_date+"</td>";
                    html += "<td>"+res.notes+"</td>";
                    html += '<td class="remove"><a href="javascript:void(0);" onclick="deletePayRate(this,'+res.guiderate_id+');"><i class="icon-trash glyphicon glyphicon-trash"></i></a></td>';
                    html += "</tr>";
                $('#modal-add-payrate').modal('toggle');
                if (first == "No Pay Rates") {
                    $('#payrate-list>tbody>tr:first').replaceWith(html);
                } else {
                    $('#payrate-list>tbody>tr:first').before(html);
                }
                ogengine.clearModal(btn);
            }
            catch(e){
                console.log("oops");
                //parsing error, display error message
                ogengine.displayErrorMessage(notes, "Error writing to Db :(");
            }
        }
    });
}

/**
 * Deletes a pay rate
 * @param {object} element Clicked DOM element.
 * @param {int} guidepayrate_id Id of pay rate that will be deleted.
 */
function deletePayRate(btn, guidepayrate_id) {
    //get whole user row that will be deleted
    var row = btn.closest('tr');

    //ask admin to confirm that he want to delete this user
    var c = confirm("Are you sure? This cannot be undone!");
    if(c) {
        //confimed
        //send data to server
        $.ajax({
            type: "POST",
            url: "/OGEngine/OGAjax.php",
            data: {
                action          : "deletePayRate",
                guidepayrate_id : guidepayrate_id
            },
            success: function (result) {
                var count = $('#payrate-list tr').length;
                console.log(count);
                if (count > 2) {
                    row.remove();
                } else {
                    var html  = "<tr><td>No Pay Rates</td><td></td><td></td><td></td></tr>";
                    $('#payrate-list>tbody>tr:last').replaceWith(html);
                }
            }
        });
    }
}

function addSkill(btn, skill_id) {
    //get whole row that will be modified
    var row = $(btn).closest('tr');
    
    var user_id     = $('#form-userId'),
        skill_name  = row.find('.guide_skill_name'),
        notes       = row.find('.guide_skill_notes');
    
    //set button to posting state
    ogengine.loadingIcon(btn);
    
     $.ajax({
        url: "/OGEngine/OGAjax.php",
        type: "POST",
        data: {
            action : "addGuideSkill",
            user_id:    user_id.val(),
            skill_id:   skill_id,
            notes:      notes.val()
        },
        success: function (result) {
            //return button to normal state
            ogengine.removeLoadingIcon(btn);
            try {
                //try to parse result to JSON
                var res = JSON.parse(result);

                //generate new line in table and display it
                var html  = "<tr>";
                    html += '<td class="guide_skill_name">'+skill_name[0].innerText+"</td>";
                    html += '<td style="text-align:center;"><span class="glyphicon glyphicon-ok text-success"></span></td>';
                    html += '<td>'+res.start_date+'</td>';
                    html += '<td>'+res.notes+'</td>';
                    html += '<td class="remove"><a href="javascript:void(0);" onclick="deleteSkill(this,'+res.guideskill_id+','+skill_id+');"><i class="icon-trash glyphicon glyphicon-trash"></i></a></td>';
                    html += "</tr>";
                    
                row.replaceWith(html);
            }
            catch(e){
                console.log("oops");
                //parsing error, display error message
                //ogengine.displayErrorMessage(exp_date, "Error writing to Db :(");
            }
        }
    });
}

function deleteSkill(btn, guideskill_id, skill_id) {
    //get whole row that will be modified
    var row = $(btn).closest('tr');
    
    var user_id     = $('#form-userId'),
        skill_name  = row.find('.guide_skill_name'),
        notes       = row.find('.guide_skill_notes');
    
    //set button to posting state
    ogengine.loadingIcon(btn);

    //ask admin to confirm that he want to delete this user
    var c = confirm("Are you sure? This cannot be undone!");

    if(c) {
         $.ajax({
            url: "/OGEngine/OGAjax.php",
            type: "POST",
            data: {
                action : "deleteGuideSkill",
                guideskill_id:  guideskill_id
            },
            success: function (result) {
                //return button to normal state
                ogengine.removeLoadingIcon(btn);
                try {
                    //try to parse result to JSON
                    //var res = JSON.parse(result);

                    //generate new line in table and display it
                    var html  = "<tr>";
                        html += '<td class="guide_skill_name">'+skill_name[0].innerText+"</td>";
                        html += '<td style="text-align:center;"><span class="glyphicon glyphicon-ban-circle text-muted"></span></td>';
                        html += '<td></td>';
                        html += '<td><textarea class="form-control guide_skill_notes" rows="1"></textarea></td>';
                        html += '<td class="remove"><a href="javascript:void(0);" onclick="addSkill(this,'+skill_id+');"><i class="icon-plus glyphicon glyphicon-plus"></i></a></td>';
                        html += "</tr>";
                        
                    row.replaceWith(html);

                }
                catch(e){
                    console.log("oops");
                    //parsing error, display error message
                    //ogengine.displayErrorMessage(exp_date, "Error writing to Db :(");
                }
            }
        });
    }
}

function addRiver(btn, rivertrip_id) {
    //get whole row that will be modified
    var row = $(btn).closest('tr');
    
    var user_id     = $('#form-userId'),
        longname    = row.find('.guide_river_name'),
        notes       = row.find('.guide_river_notes');
    
    //set button to posting state
    ogengine.loadingIcon(btn);
    
     $.ajax({
        url: "/OGEngine/OGAjax.php",
        type: "POST",
        data: {
            action : "addRiver",
            user_id:        user_id.val(),
            rivertrip_id:   rivertrip_id,
            notes:          notes.val()
        },
        success: function (result) {
            //return button to normal state
            ogengine.removeLoadingIcon(btn);
            try {
                //try to parse result to JSON
                var res = JSON.parse(result);

                //generate new line in table and display it
                var html  = "<tr>";
                    html += '<td class="guide_river_name">'+longname[0].innerText+"</td>";
                    html += '<td style="text-align:center;"><span class="glyphicon glyphicon-ok text-success"></span></td>';
                    html += '<td>'+res.start_date+'</td>';
                    html += '<td>'+res.notes+'</td>';
                    html += '<td class="remove"><a href="javascript:void(0);" onclick="deleteRiver(this,'+res.guideriver_id+','+rivertrip_id+');"><i class="icon-trash glyphicon glyphicon-trash"></i></a></td>';
                    html += "</tr>";
                    
                row.replaceWith(html);
            }
            catch(e){
                console.log("oops");
                //parsing error, display error message
                //ogengine.displayErrorMessage(exp_date, "Error writing to Db :(");
            }
        }
    });
}

function deleteRiver(btn, guideriver_id, rivertrip_id) {
    //get whole row that will be modified
    var row = $(btn).closest('tr');
    
    var user_id     = $('#form-userId'),
        longname    = row.find('.guide_river_name'),
        notes       = row.find('.guide_river_notes');
    
    //set button to posting state
    ogengine.loadingIcon(btn);

    //ask admin to confirm that he want to delete this user
    var c = confirm("Are you sure? This cannot be undone!");

    if(c) {
         $.ajax({
            url: "/OGEngine/OGAjax.php",
            type: "POST",
            data: {
                action : "deleteRiver",
                guideriver_id:  guideriver_id
            },
            success: function (result) {
                //return button to normal state
                ogengine.removeLoadingIcon(btn);
                try {
                    //try to parse result to JSON
                    //var res = JSON.parse(result);

                    //generate new line in table and display it
                    var html  = "<tr>";
                        html += '<td class="guide_river_name">'+longname[0].innerText+"</td>";
                        html += '<td style="text-align:center;"><span class="glyphicon glyphicon-ban-circle text-muted"></span></td>';
                        html += '<td></td>';
                        html += '<td><textarea class="form-control guide_river_notes" rows="1"></textarea></td>';
                        html += '<td class="remove"><a href="javascript:void(0);" onclick="addRiver(this,'+rivertrip_id+');"><i class="icon-plus glyphicon glyphicon-plus"></i></a></td>';
                        html += "</tr>";
                        
                    row.replaceWith(html);

                }
                catch(e){
                    console.log("oops");
                    //parsing error, display error message
                    //ogengine.displayErrorMessage(exp_date, "Error writing to Db :(");
                }
            }
        });
    }
}

function deactivateGuide(el, userid) {
    $.ajax({
        type: "POST",
        url: "/OGEngine/OGAjax.php",
        data: {
            userId : userid,
            action : "deactivateGuide"
        },
        success: function ( result ) {
            var cell = $(el).closest('td').prev('td');
            cell.find('span').remove();
            $(el).find('span').text('Activate');
            $(el).attr('onclick', 'activateGuide(this,'+userid+');');
        }
    });
};

function activateGuide(el, userid) {
    $.ajax({
        type: "POST",
        url: "/OGEngine/OGAjax.php",
        data: {
            userId : userid,
            action : "activateGuide"
        },
        success: function ( result ) {
            var cell = $(el).closest('td').prev('td');
            cell.html('<span class="glyphicon glyphicon-ok text-success"></span>');
            $(el).find('span').text('Deactivate');
            $(el).attr('onclick', 'deactivateGuide(this,'+userid+');');
        }
    });
};

