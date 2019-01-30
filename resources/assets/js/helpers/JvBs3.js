// Jquery Validation Bootstrap 3
var JvBs3 = {
    icon_ok: "",
    icon_error: "",

    ok: function(element, errorClass, successClass) {

    },

    error: function(element, errorClass, successClass) {
        var feedback = $(element).closest('.form-group').find('.form-control-feedback');
        var form_group = $(element).closest('.form-group');

        if (form_group.hasClass('has-success')) {
            form_group.removeClass('has-success');
        }

        form_group.addClass('has-error');

        if (feedback.length === 0) {
            $(element).after('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
        } else if (feedback.hasClass('glyphicon-ok')) {
            feedback.removeClass('glyphicon-ok').addClass('glyphicon-remove');
        }
    },
};
