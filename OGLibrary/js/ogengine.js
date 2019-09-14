
/** OGENGINE NAMESPACE
 ======================================== */
var ogengine = {};

/**
 * Put button to loading state.
 * @param {Object} button Button to be putted.
 * @param {string} loadingText Text that will be displayed while loading.
 */
ogengine.loadingButton = function(button, loadingText) {
    oldText = $("span", button).text();
    button.attr("rel",oldText);
    $("span", button).text(loadingText);
    button.addClass("disabled")
          .attr('disabled', "disabled");
};

/**
 * Returns button from loadin state to normal state.
 * @param {Object} button Button object.
 */
ogengine.removeLoadingButton = function (button) {
    var oldText = button.attr('rel');
    button.find('span').text(oldText);
    button.removeClass("disabled")
          .removeAttr("disabled")
          .removeAttr("rel");
};

/**
 * Put icon to loading state.
 * @param {Object} button Icon to be putted.
 */
ogengine.loadingIcon = function(button) {
    var icon = $(button).find('i');
    var link = $(button).closest('a');
    var oldClass = icon.attr("class");
    icon.attr("rel",oldClass);
    icon.removeClass(oldClass);
    icon.addClass("glyphicon glyphicon-refresh");
    link.addClass("disable_a_href");
};

/**
 * Returns icon from loadin state to normal state.
 * @param {Object} button Icon object.
 */
ogengine.removeLoadingIcon = function (button) {
    var icon = $(button).find('i');
    var link = $(button).closest('a');
    var oldClass = icon.attr('rel');
    var newClass = icon.attr("class");
    icon.removeClass(newClass);
    icon.addClass(oldClass);
    link.removeClass("disable_a_href")
          .removeAttr("rel");
};

/**
 * Append success message to provided parent element.
 * @param {Object} parentElement Parent element where message will be appended.
 * @param {String} message Message to be displayed.
 */
ogengine.displaySuccessMessage = function (parentElement, message) {
    $(".alert-success").remove();
    var div = ("<div class='alert alert-success'>"+message+"</div>");
    parentElement.append(div);
};


/**
 * Append error message to an input element. If message is omitted, it will be set to empty string.
 * @param {Object} element Input element on which error message will be appended.
 * @param {String} message Message to be displayed.
 */
ogengine.displayErrorMessage = function(element, message) {
    var controlGroup = element.parents(".control-group");
    controlGroup.addClass("error").addClass("has-error");
    if(typeof message !== "undefined") {
        var helpBlock = $("<span class='help-inline text-error'>"+message+"</span>");
        controlGroup.find(".form-control").after(helpBlock);
    }
};

/**
 * Prepend success alert to provided parent element that fades out.
 * @param {Object} parentElement Parent container element where message will be prepended.
 * @param {String} message Message to be displayed.
 */
ogengine.displaySuccessAlert = function (element, message) {
    //generate success alert
    var html  = '<div id="Alert" class="alert alert-success"><strong>Success!</strong> ';
        html += message;
        html += '</div>';

    element.prepend(html);

    window.setTimeout(function() {
        $('.alert').fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
        });
    }, 3000);
};

/**
 * Prepend failure alert to provided parent element that fades out.
 * @param {Object} parentElement Parent container element where message will be prepended.
 * @param {String} message Message to be displayed.
 */
ogengine.displayFailureAlert = function (element, message) {
    //generate success alert
    var html  = '<div id="Alert" class="alert alert-danger"><strong>Failure!</strong> ';
        html += message;
        html += '</div>';

    element.prepend(html);

    window.setTimeout(function() {
        $('.alert').fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
        });
    }, 3000);
};

/**
 * Removes all error messages from all input fields.
 */
ogengine.removeErrorMessages = function () {
    $(".control-group").removeClass("error").removeClass("has-error");
    $(".help-inline").remove();
};

/**
 * Clears all fields and removes all error messages from all input fields in a modal.
 */
ogengine.clearModal = function (t) {
    //var $t = $(this),
        var target = t.data("target") || t.parents('.modal') || [];
      
    $(target)
      .find("input,textarea,select")
         .val('')
         .end()
      .find("input[type=checkbox], input[type=radio]")
         .prop("checked", "")
         .end();

    ogengine.removeErrorMessages();
};


/**
 * Validate email format.
 * @param {String} email Email to be validated.
 * @returns {boolean} TRUE if email is valid, FALSE otherwise.
 */
ogengine.validateEmail = function (email) {
  var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
};

/**
 *  Validate and format phone numbers
*/
ogengine.validatePhone = function(phonenum) {
    var regexObj = /^(?:\+?1[-. ]?)?(?:\(?([0-9]{3})\)?[-. ]?)?([0-9]{3})[-. ]?([0-9]{4})$/;
    if (regexObj.test(phonenum)) {
        var parts = phonenum.match(regexObj);
        var phone = "";
        if (parts[1]) { phone += "(" + parts[1] + ") "; }
        phone += parts[2] + "-" + parts[3];
        return phone;
    }
    else {
        //invalid phone number
        return "false";
    }
}

/**
 * Get an parameter from URL.
 * @param {string} name Parameter name.
 * @returns {string} Value of parameter with given name.
 */
ogengine.urlParam = function(name){
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
};




