function updateUserProfile() {

    var container       = $('#user_profile'),     
        user_id         = $('#form-userId'),
        username        = $('#userProfile-username'),
        email           = $('#userProfile-email'),
        btn             = $('#btn-update-profile');

    //validate username
    if($.trim(username.val()) == "") {
        ogengine.displayErrorMessage(username, "Please provide a username");
        return;
    }

    //validate email address
    if($.trim(email.val()) != "") {
        proMail = ogengine.validateEmail(email.val())
        if(proMail == false) {
            ogengine.displayErrorMessage(email, "Please provide a valid email address");
            return;
        }
    } else {
        ogengine.displayErrorMessage(email, "Please provide a valid email address");
        return;
    }

    //create data that will be sent to server
    var userData = {
            username:   username.val(),
            email:      email.val()
        };
    //set button to posting state
    ogengine.loadingButton(btn, "Posting");
    
     $.ajax({
        url: "/OGEngine/OGAjax.php",
        type: "POST",
        data: {
            action : "updateProfile",
            user_id: user_id.val(),
            userDetails: userData
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

function updateUserDetails() {

    var container       = $('#user_details'),     
        user_id         = $('#form-userId'),
        active          = $('#userDetail_active'),
        first_name      = $('#userDetail_first_name'),
        middle_name     = $('#userDetail_middle_name'),
        last_name       = $('#userDetail_last_name'),
        nickname        = $('#userDetail_nickname'),
        birthdate       = $('#userDetail_birthdate'),
        t_size          = $('#userDetail_t_size'),
        star_sign       = $('#userDetail_star_sign'),
        btn             = $('#btn-update-user');

    if(active.prop("checked") == true){
        active_user = "Y";
    }
    else {
        active_user = "N";
    }

    //validate bonus start date is before today
    if(new Date(birthdate.val()) >= new Date()) {
        ogengine.displayErrorMessage(birthdate, "Please select a start date before today");
        return;
    }

    //create data that will be sent to server
    var userData = {
            active:         active_user,
            first_name:     first_name.val(),
            middle_name:    middle_name.val(),
            last_name:      last_name.val(),
            nickname:       nickname.val(),
            birthdate:      birthdate.val(),
            t_size:         t_size.val(),
            star_sign:      star_sign.val()
        };
    //set button to posting state
    ogengine.loadingButton(btn, "Posting");
    
     $.ajax({
        url: "/OGEngine/OGAjax.php",
        type: "POST",
        data: {
            action : "updateUser",
            user_id: user_id.val(),
            userDetails: userData
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

function addPhone() {
    
    var container       = $('#userPhone'),
        user_id         = $('#form-userId'),
        visitor_id      = $('#form-visitorId'),
        phone_type      = $('#select-phone-type'),
        phone_number    = $('#phone_number'),
        best_order      = $('#phone-order'),
        first_cell      = $('#phone-list>tbody>tr>td:first'),
        btn             = $('#btn-add-phone');

    var first = first_cell.html();
    console.log(first);
         
    //validate type selection
    if($.trim(phone_type.val()) == "") {
        ogengine.displayErrorMessage(phone_type, "Please select a phone type");
        return;
    }

    //validate that it is a valid phone and that it is filled out
    phonenum = $.trim(phone_number.val());
    if(phonenum == "") {
        ogengine.displayErrorMessage(phone_number, "Please provide a valid phone number");
        return;
    } else {
        
        phonenum = ogengine.validatePhone(phonenum);
        if(phonenum === "false") {
            ogengine.displayErrorMessage(phone_number, "Please provide a valid phone number");
            return;
        } else {
            phone_number.val(phonenum);
        }
    }

    //validate order
    if($.trim(best_order.val()) == "") {
        ogengine.displayErrorMessage(best_order, "Please select the order of priority to call");
        return;
    }

    //create data that will be sent to server
    var phoneData = {
            phone_type:    phone_type.val(),
            phone_number:  phonenum,
            best_order:    best_order.val(),
            updated_by:    visitor_id.val()
        };

    //set button to posting state
    ogengine.loadingButton(btn, "Posting");
    
     $.ajax({
        url: "/OGEngine/OGAjax.php",
        type: "POST",
        data: {
            action :        "addPhone",
            user_id_fk:     user_id.val(),
            phoneDetails:   phoneData
        },
        success: function (result) {
            //return button to normal state
            ogengine.removeLoadingButton(btn);
            try {
                //try to parse result to JSON
                var res = JSON.parse(result);

                //Display Bootstrap alert with success
                if (res.status === "success") {

                    //generate new line in certs table and display it
                    var html  = "<tr>";
                        html += "<td>"+res.phone_type+"</td>";
                        html += "<td>"+res.phone_number+"</td>";
                        html += "<td>"+res.best_order+"</td>";
                        html += '<td class="remove"><a href="javascript:void(0);" onclick="deletePhone(this,'+res.phone_id+');"><i class="icon-trash glyphicon glyphicon-trash"></i></a></td>';
                        html += "</tr>";
                    $('#modal-add-phone').modal('toggle');
                    if (first == "No Phone Data" || first == undefined) {
                        $('#phone-list>tbody>tr:last').replaceWith(html);
                    } else {
                        $('#phone-list>tbody>tr:last').after(html);
                    }
                    ogengine.clearModal(btn);
                } else {
                    ogengine.displayFailureAlert(container, res.msg);
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

/**
 * Deletes an emergency contact
 * @param {object} element Clicked DOM element.
 * @param {int} ec_id Id of cert that will be deleted.
 */
function deletePhone(btn, phone_id) {
    //get whole e-contact row that will be deleted
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
                action:     "deletePhone",
                phone_id:   phone_id
            },
            success: function (result) {
                var count = $('#phone-list tr').length;
                if (count > 2) {
                    row.remove();
                } else {
                    var html  = "<tr><td>No Phone Data</td><td></td><td></td><td></td></tr>";
                    $('#phone-list>tbody>tr:last').replaceWith(html);
                }
            }
        });
    }
}

function addAddress() {
    
    var container   = $('#userAddress'),
        user_id     = $('#form-userId'),
        visitor_id  = $('#form-visitorId'),
        address_type= $('#select-address-type'),
        care_of     = $('#address-co'),
        street_one  = $('#address-street1'),
        street_two  = $('#address-street2'),
        city        = $('#address-city'),
        state       = $('#address-state'),
        postal_code = $('#address-zip'),
        first_cell  = $('#address-list>tbody>tr>td:first'),
        btn         = $('#btn-add-address');

    var first = first_cell.html();
         
    //validate type selection
    if($.trim(address_type.val()) == "") {
        ogengine.displayErrorMessage(address_type, "Please select an address type");
        return;
    }

    //validate street address
    if($.trim(street_one.val()) == "") {
        ogengine.displayErrorMessage(street_one, "Please give us your street address");
        return;
    }

    //validate city
    if($.trim(city.val()) == "") {
        ogengine.displayErrorMessage(city, "Please include a city");
        return;
    }

    //validate state
    if($.trim(state.val()) == "") {
        ogengine.displayErrorMessage(state, "Please include a state");
        return;
    }

    //validate zip
    if($.trim(postal_code.val()) == "") {
        ogengine.displayErrorMessage(postal_code, "Please include a zip code");
        return;
    }

    //create data that will be sent to server
    var addressData = {
            address_type:   address_type.val(),
            care_of:        care_of.val(),
            street_one:     street_one.val(),
            street_two:     street_two.val(),
            city:           city.val(),
            state:          state.val(),
            postal_code:    postal_code.val(),
            updated_by:     visitor_id.val()
        };

    //set button to posting state
    ogengine.loadingButton(btn, "Posting");
    
     $.ajax({
        url: "/OGEngine/OGAjax.php",
        type: "POST",
        data: {
            action :        "addAddress",
            user_id:        user_id.val(),
            addressDetails: addressData
        },
        success: function (result) {
            //return button to normal state
            ogengine.removeLoadingButton(btn);

            try {
                //try to parse result to JSON
                var res = JSON.parse(result);

                //Display Bootstrap alert with success
                if (res.status === "success") {

                    //generate new line in certs table and display it
                    var html  = "<tr>";
                        html += "<td>"+res.address_type+"</td>";
                        html += "<td>";
                        if (care_of.val()) {html += 'c/o: '+res.care_of+'<br />';}
                        html += res.street_one;
                        if (street_two.val()) {html += '<br />'+res.street_two;}
                        html += "</td>";
                        html += "<td>"+res.city+"</td>";
                        html += "<td>"+res.state+"</td>";
                        html += "<td>"+res.postal_code+"</td>";
                        html += '<td class="remove"><a href="javascript:void(0);" onclick="deleteAddress(this,'+res.address_id+');"><i class="icon-trash glyphicon glyphicon-trash"></i></a></td>';
                        html += "</tr>";
                    $('#modal-add-address').modal('toggle');
                    if (first == "No Address Data") {
                        $('#address-list>tbody>tr:last').replaceWith(html);
                    } else {
                        $('#address-list>tbody>tr:last').after(html);
                    }
                    ogengine.clearModal(btn);
                } else {
                    ogengine.displayFailureAlert(container, res.msg);
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

/**
 * Deletes an address
 * @param {object} element Clicked DOM element.
 * @param {int} address_id of address that will be deleted.
 */
function deleteAddress(btn, address_id) {
    //get whole address row that will be deleted
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
                action:     "deleteAddress",
                address_id: address_id
            },
            success: function (result) {
                var count = $('#address-list tr').length;
                if (count > 2) {
                    row.remove();
                } else {
                    var html  = "<tr><td>No Address Data</td><td></td><td></td><td></td><td></td><td></td></tr>";
                    $('#address-list>tbody>tr:last').replaceWith(html);
                }
            }
        });
    }
}

function addEC() {
    
    var container   = $('#userEC'),
        user_id     = $('#form-userId'),
        visitor_id  = $('#form-visitorId'),
        ec_relation = $('#select-ec-relation'),
        ec_name     = $('#ec-name'),
        ec_phone1   = $('#ec-phone1'),
        ec_phone2   = $('#ec-phone2'),
        ec_email    = $('#ec-email'),
        first_cell  = $('#ec-list>tbody>tr>td:first'),
        btn         = $('#btn-add-ec');

    var first = first_cell.html();
         
    //validate type selection
    if($.trim(ec_relation.val()) == "") {
        ogengine.displayErrorMessage(ec_relation, "Please select a relationship");
        return;
    }

    //validate EC name
    if($.trim(ec_name.val()) == "") {
        ogengine.displayErrorMessage(ec_name, "Please give us your contact's name");
        return;
    }

    //validate that it is a valid phone and that it is filled out
    phonenum1 = $.trim(ec_phone1.val());
    if(phonenum1 == "") {
        ogengine.displayErrorMessage(ec_phone1, "Please provide a valid phone number");
        return;
    } else {
        
        phonenum1 = ogengine.validatePhone(phonenum1);
        if(phonenum1 === "false") {
            ogengine.displayErrorMessage(ec_phone1, "Please provide a valid phone number");
            return;
        } else {
            ec_phone1.val(phonenum1);
        }
    }

    //validate secondary phone number
    phonenum2 = $.trim(ec_phone2.val());
    if($.trim(ec_phone2.val()) != "") {
        phonenum2 = ogengine.validatePhone(phonenum2);
        if(phonenum2 === "false") {
            ogengine.displayErrorMessage(ec_phone2, "Please provide a valid phone number");
            return;
        } else {
            ec_phone2.val(phonenum1);
        }
    }

    //validate email address
    if($.trim(ec_email.val()) != "") {
        ecMail = ogengine.validateEmail(ec_email.val())
        if(ecMail == false) {
            ogengine.displayErrorMessage(ec_email, "Please provide a valid email address");
            return;
        }
    }

    //create data that will be sent to server
    var ecData = {
            ec_relation:    ec_relation.val(),
            ec_name:        ec_name.val(),
            ec_phone:       phonenum1,
            ec_phone2:      phonenum2,
            ec_email:       ec_email.val(),
            updated_by:     visitor_id.val()
        };

    //set button to posting state
    ogengine.loadingButton(btn, "Posting");
    
     $.ajax({
        url: "/OGEngine/OGAjax.php",
        type: "POST",
        data: {
            action :    "addEC",
            user_id:    user_id.val(),
            ecDetails:  ecData
        },
        success: function (result) {
            //return button to normal state
            ogengine.removeLoadingButton(btn);
            try {
                //try to parse result to JSON
                var res = JSON.parse(result);

                //Display Bootstrap alert with success
                if (res.status === "success") {

                    //generate new line in certs table and display it
                    var html  = "<tr>";
                        html += "<td>"+res.ec_relation+"</td>";
                        html += "<td>"+res.ec_name+"</td>";
                        html += "<td>"+res.ec_phone+"</td>";
                        html += "<td>"+res.ec_phone2+"</td>";
                        html += "<td>"+res.ec_email+"</td>";
                        html += '<td class="remove"><a href="javascript:void(0);" onclick="deleteEC(this,'+res.ec_id+');"><i class="icon-trash glyphicon glyphicon-trash"></i></a></td>';
                        html += "</tr>";
                    $('#modal-add-ec').modal('toggle');
                    if (first == "No Emergency Contacts") {
                        $('#ec-list>tbody>tr:last').replaceWith(html);
                    } else {
                        $('#ec-list>tbody>tr:last').after(html);
                    }
                    ogengine.clearModal(btn);
                } else {
                    ogengine.displayFailureAlert(container, res.msg);
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

/**
 * Deletes an emergency contact
 * @param {object} element Clicked DOM element.
 * @param {int} ec_id Id of cert that will be deleted.
 */
function deleteEC(btn, userEC_id) {
    //get whole e-contact row that will be deleted
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
                action: "deleteEC",
                ec_id: userEC_id
            },
            success: function (result) {
                var count = $('#ec-list tr').length;
                if (count > 2) {
                    row.remove();
                } else {
                    var html  = "<tr><td>No Emergency Contacts</td><td></td><td></td><td></td><td></td><td></td></tr>";
                    $('#ec-list>tbody>tr:last').replaceWith(html);
                }
            }
        });
    }
}

function updateUserMed() {
    
    var container       = $('#userMed'),
        user_id         = $('#form-userId'),
        medical         = $('#userMed-medical'),
        allergy         = $('#userMed-allergy'),
        dietary         = $('#userMed-dietary'),
        btn             = $('#btn-update-medical');

    //create data that will be sent to server
    var userData = {
            medical: medical.val(),
            allergy: allergy.val(),
            dietary: dietary.val()
        };

    //set button to posting state
    ogengine.loadingButton(btn, "Posting");
    
     $.ajax({
        url: "/OGEngine/OGAjax.php",
        type: "POST",
        data: {
            action : "updateMed",
            user_id: user_id.val(),
            userMed: userData
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

function addUserNote() {
    
    var container       = $('#notes-list'),
        user_id         = $('#form-userId'),
        note            = $('#note-text'),
        public_check    = $('#public-box'),
        first_cell      = $('#notes-list>blockquote>p:first'),
        btn             = $('#btn-add-note'),
        visitor_id      = $('#form-visitorId');

    var first = first_cell.html();

    console.log(visitor_id.val());

    if(public_check.prop("checked") == true){
        pub = "Y";
    }
    else {
        pub = "N";
    }
         
    //validate type selection
    if($.trim(note.val()) == "") {
        ogengine.displayErrorMessage(note, "Please leave a Note");
        return;
    }

    //set button to posting state
    ogengine.loadingButton(btn, "Posting");
    
     $.ajax({
        url: "/OGEngine/OGAjax.php",
        type: "POST",
        data: {
            action :        "addUserNote",
            visitor_id:     visitor_id.val(),
            user_id:        user_id.val(),
            note:           note.val(),
            public_check:   pub,
        },
        success: function (result) {
            //return button to normal state
            ogengine.removeLoadingButton(btn);
            try {
                //try to parse result to JSON
                var res = JSON.parse(result);

                //Display Bootstrap alert with success
                if (res.status === "success") {

                    //generate new line in certs table and display it
                    var html  = "<blockquote>";
                        html += "<p>"+res.user_note+"</p>";
                        html += "<small>";
                        html += res.visitor+"<em> at "+res.created_on;
                        if (res.is_public == 'Y') {
                            html += " (public)  ";
                        } else {
                            html += " (private)  ";
                        }
                        html += '<a href="javascript:void(0);" onclick="deleteNote(this,' + res.usernotes_id +');"><i class="icon-trash glyphicon glyphicon-trash"></i></a>';
                        html += "</small>";
                        html += "</blockquote>";
                    $('#modal-add-note').modal('toggle');
                    if (first == "No User Notes") {
                        $('#notes-list>blockquote:first').replaceWith(html);
                    } else {
                        $('#notes-list>blockquote:first').before(html);
                    }
                    ogengine.clearModal(btn);
                } else {
                    ogengine.displayFailureAlert(container, res.msg);
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

/**
 * Deletes a user note
 * @param {object} element Clicked DOM element.
 * @param {int} note_id Id of note that will be deleted.
 */
function deleteNote(btn, note_id) {
    //get whole note row that will be deleted
    var row = btn.closest('blockquote');

    //ask admin to confirm that he want to delete this user
    var c = confirm("Are you sure? This cannot be undone!");
    if(c) {
        //confimed
        //send data to server
        $.ajax({
            type: "POST",
            url: "/OGEngine/OGAjax.php",
            data: {
                action: "deleteNote",
                note_id: note_id
            },
            success: function (result) {
                var count = $('#notes-list blockquote').length;
                console.log(count);
                if (count > 1) {
                    row.remove();
                } else {
                    var html  = "<blockquote><p>No User Notes</p></blockquote>";
                    $('#notes-list>blockquote:last').replaceWith(html);
                }
            }
        });
    }
}

function addUser() {

    var email               = $('#adduser-email'),
        btn                 = $('#btn-add-user'),
        username            = $('#adduser-username'),
        password            = $('#adduser-password'),
        last_name           = $('#adduser-last_name'),
        first_name          = $('#adduser-first_name'),
        confirm_password    = $('#adduser-confirm_password');

    //validate type selection
    if($.trim(username.val()) == "") {
        ogengine.displayErrorMessage(username, "Please provide a username");
        return;
    }

    //validate email address
    if($.trim(email.val()) != "") {
        ecMail = ogengine.validateEmail(email.val())
        if(ecMail == false) {
            ogengine.displayErrorMessage(email, "Please provide a valid email address");
            return;
        }
    }

    //validate passwords    
    if ( $.trim( password.val() ) == "" ) {
        valid = false;
        ogengine.displayErrorMessage( password, "Please provide a valid password");
        return;
    }
    else if ( password.val().length < 6 ) {
        valid = false;
        ogengine.displayErrorMessage( password, "Password must be 6 or more characters long");
        return;
    }

    if ( $.trim( confirm_password.val() ) == "" ) {
        valid = false;
        ogengine.displayErrorMessage( confirm_password, "Please confirm your password");
        return;
    }

    if ( password.val() != confirm_password.val() ) {
        valid = false;
        ogengine.displayErrorMessage( password );
        ogengine.displayErrorMessage( confirm_password,"Passwords don't match");
        return;
    }

    //create data object that will be sent to server
    var forServer = { 
        action : "addUser",
        userId : 0,
        userData: {
            email      : email.val(),
            username   : username.val(),
            password   : CryptoJS.SHA512(password.val()).toString(),
            last_name  : last_name.val(),
            first_name : first_name.val(),
            confirm_password: CryptoJS.SHA512(confirm_password.val()).toString()
        },
        fieldId: {
            email      : "adduser-email",
            username   : "adduser-username",
            password   : "adduser-password",
            last_name  : "adduser-last_name",
            first_name : "adduser-first_name",
            confirm_password: "adduser-confirm_password",
        }
    };

    //put button to loading state
    ogengine.loadingButton(btn, "Working");

    $.ajax({
        type: "POST",
        url: "/ASEngine/ASAjax.php",
        data: forServer,
        success: function ( result ) {
            //return button to normal state
            ogengine.removeLoadingButton(btn);
            
            //parse result to JSON
            var res = JSON.parse(result);

            if(res.status === "error") {
                //error
                
                //display all errors
                for(var i=0; i<res.errors.length; i++) {
                    var error = res.errors[i];
                    ogengine.displayErrorMessage($("#"+error.id), error.msg);
                }
            }
            else {
               location.reload();
            }
        }
    });
};

function changeUserPassword() {
    
    var container           = $('#user_profile'),
        user_id             = $('#form-userId'),
        password            = $('#new-pass'),
        confirm_password    = $('#pass-repeat'),
        btn                 = $('#btn-change-pass');

    //validate passwords    
    if ( $.trim( password.val() ) == "" ) {
        valid = false;
        ogengine.displayErrorMessage( password, "Please provide a valid password");
        return;
    }
    else if ( password.val().length < 6 ) {
        valid = false;
        ogengine.displayErrorMessage( password, "Password must be 6 or more characters long");
        return;
    }

    if ( $.trim( confirm_password.val() ) == "" ) {
        valid = false;
        ogengine.displayErrorMessage( confirm_password, "Please confirm your password");
        return;
    }

    if ( password.val() != confirm_password.val() ) {
        valid = false;
        ogengine.displayErrorMessage( password );
        ogengine.displayErrorMessage( confirm_password,"Passwords don't match");
        return;
    }

    //create data that will be sent to server
    password = CryptoJS.SHA512(password.val()).toString();

    //set button to posting state
    ogengine.loadingButton(btn, "Posting");
    
     $.ajax({
        url: "/OGEngine/OGAjax.php",
        type: "POST",
        data: {
            action : "changePassword",
            user_id: user_id.val(),
            userPass: password
        },
        success: function (result) {
            //return button to normal state
            ogengine.removeLoadingButton(btn);
            try {
                //try to parse result to JSON
                var res = JSON.parse(result);

                $('#modal-change-pass').modal('toggle');
                ogengine.clearModal(btn);

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

function banUser(el, userid) {
    $.ajax({
        type: "POST",
        url: "/OGEngine/OGAjax.php",
        data: {
            userId : userid,
            action : "banUser"
        },
        success: function ( result ) {
            var buttons = $(el).parents('.btn-group').find(".btn-info");
            buttons.each(function () {
                $(this).removeClass("btn-primary").addClass("btn-danger");
            });
            $(el).find('span').text('unBan');
            $(el).attr('onclick', 'unbanUser(this,'+userid+');');
        }
    });
};

function unbanUser(el, userid) {
    $.ajax({
        type: "POST",
        url: "/OGEngine/OGAjax.php",
        data: {
            userId : userid,
            action : "unbanUser"
        },
        success: function ( result ) {
            var buttons = $(el).parents('.btn-group').find(".btn-danger");
            buttons.each(function () {
                $(this).removeClass("btn-danger").addClass("btn-info");
            });
            $(el).find('span').text('Ban');
            $(el).attr('onclick', 'banUser(this,'+userid+');');
        }
    });
};

function deactivateUser(el, userid) {
    $.ajax({
        type: "POST",
        url: "/OGEngine/OGAjax.php",
        data: {
            userId : userid,
            action : "deactivateUser"
        },
        success: function ( result ) {
            var cell = $(el).closest('td').prev('td');
            cell.find('span').remove();
            $(el).find('span').text('Activate');
            $(el).attr('onclick', 'activateUser(this,'+userid+');');
        }
    });
};

function activateUser(el, userid) {
    $.ajax({
        type: "POST",
        url: "/OGEngine/OGAjax.php",
        data: {
            userId : userid,
            action : "activateUser"
        },
        success: function ( result ) {
            var cell = $(el).closest('td').prev('td');
            cell.html('<span class="glyphicon glyphicon-ok text-success"></span>');
            $(el).find('span').text('Deactivate');
            $(el).attr('onclick', 'deactivateUser(this,'+userid+');');
        }
    });
};


/**
 * Changes user's role.
 * @param {Object} Clicked DOM element.
 * @param {int} userId User ID.
 */
function changeRole(element, role, userId) {
  //send data to server
    $.ajax({
        type: "POST",
        url: "/OGEngine/OGAjax.php",
        data: {
            action: "changeRole",
            userId: userId,
            role  : role
        },
        success: function (newRole) {
      //change button text
            element.text(newRole);
        }
    });
};


function roleChanger(element, userId) {
    $("#modal-change-role").modal({
        keyboard: false,
        backdrop: "static",
        show: true
    });

   //find elements needed for changing text
    var userRoleSpan = $(element).parents(".btn-group").find(".user-role");

    $("#change-role-button").unbind().bind('click', function () {
        var newRole = $("#select-user-role").val();
        changeRole(userRoleSpan, newRole, userId);
    });
};
