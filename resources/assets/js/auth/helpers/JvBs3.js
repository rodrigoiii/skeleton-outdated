// Jquery Validation Bootstrap 3
var JvBs3 = {
    icon_ok: "glyphicon-ok",
    icon_error: "glyphicon-remove",

    // highlight_only_fields: [],
    unhighlight_only_fields: [],

    // error
    // highlight_fields: function(element, errorClass, successClass) {
    //     var field_name = $(element).attr('name');
    //     var form_group = $(element).closest('.form-group');
    //     var feedback = form_group.find('.form-control-feedback');

    //     if (JvBs3.highlight_only_fields.indexOf(field_name) !== -1) {

    //         // remove has-success class in form-group class if exists
    //         if (form_group.hasClass('has-success')) {
    //             form_group.removeClass('has-success');
    //         }

    //         // append has-error class in form-group class
    //         form_group.addClass('has-error');

    //         // add element span with form-control-feedback class after the input element if not exists
    //         if (feedback.length === 0) {
    //             $(element).after('<span class="glyphicon '+JvBs3.icon_error+' form-control-feedback"></span>');
    //         } else if (feedback.hasClass(JvBs3.icon_ok)) { // if form-control-feedback and icon for icon_ok are exist
    //             feedback.removeClass(JvBs3.icon_ok).addClass(JvBs3.icon_error);
    //         }
    //     }
    // },
    highlight_all: function(element, errorClass, successClass) {
        var form_group = $(element).closest('.form-group');
        var feedback = form_group.find('.form-control-feedback');

        // remove has-success class in form-group class if exists
        if (form_group.hasClass('has-success')) {
            form_group.removeClass('has-success');
        }

        // append has-error class in form-group class
        form_group.addClass('has-error');

        // add element span with form-control-feedback class after the input element if not exists
        if (feedback.length === 0) {
            $(element).after('<span class="glyphicon '+JvBs3.icon_error+' form-control-feedback"></span>');
        } else if (feedback.hasClass(JvBs3.icon_ok)) { // if form-control-feedback and icon for icon_ok are exist
            feedback.removeClass(JvBs3.icon_ok).addClass(JvBs3.icon_error);
        }
    },

    // ok
    unhighlight_fields: function(element, errorClass, successClass) {
        var field_name = $(element).attr('name');
        var form_group = $(element).closest('.form-group');
        var feedback = form_group.find('.form-control-feedback');

        if (JvBs3.unhighlight_only_fields.indexOf(field_name) !== -1) {
            // remove has-error class in form-group class if exists
            if (form_group.hasClass('has-error')) {
                form_group.removeClass('has-error');
            }

            // append has-success class in form-group class
            form_group.addClass('has-success');

            // add element span with form-control-feedback class after the input element if not exists
            if (feedback.length === 0) {
                $(element).after('<span class="glyphicon '+JvBs3.icon_ok+' form-control-feedback"></span>');
            } else if (feedback.hasClass(JvBs3.icon_error)) { // if form-control-feedback and icon for icon_error are exist
                feedback.removeClass(JvBs3.icon_error).addClass(JvBs3.icon_ok);
            }
        }
    },
    unhighlight_all: function(element, errorClass, successClass) {
        var form_group = $(element).closest('.form-group');
        var feedback = form_group.find('.form-control-feedback');

        // remove has-error class in form-group class if exists
        if (form_group.hasClass('has-error')) {
            form_group.removeClass('has-error');
        }

        // append has-success class in form-group class
        form_group.addClass('has-success');

        // add element span with form-control-feedback class after the input element if not exists
        if (feedback.length === 0) {
            $(element).after('<span class="glyphicon '+JvBs3.icon_ok+' form-control-feedback"></span>');
        } else if (feedback.hasClass(JvBs3.icon_error)) { // if form-control-feedback and icon for icon_error are exist
            feedback.removeClass(JvBs3.icon_error).addClass(JvBs3.icon_ok);
        }
    }
};

module.exports = JvBs3;
