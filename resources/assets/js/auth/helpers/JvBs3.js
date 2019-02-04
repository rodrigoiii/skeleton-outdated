// Jquery Validation Bootstrap 3
// var JvBs3 = {
//     icon_ok: "glyphicon-ok",
//     icon_error: "glyphicon-remove",

//     highlight_only_fields: [],
//     unhighlight_only_fields: [],

//     // error
//     highlight_fields: function(element, errorClass, successClass) {
//         var field_name = $(element).attr('name');
//         var form_group = $(element).closest('.form-group');
//         var feedback = form_group.find('.form-control-feedback');

//         if (JvBs3.highlight_only_fields.indexOf(field_name) !== -1) {

//             // remove has-success class in form-group class if exists
//             if (form_group.hasClass('has-success')) {
//                 form_group.removeClass('has-success');
//             }

//             // append has-error class in form-group class
//             form_group.addClass('has-error');

//             // add element span with form-control-feedback class after the input element if not exists
//             if (feedback.length === 0) {
//                 $(element).after('<span class="glyphicon '+JvBs3.icon_error+' form-control-feedback"></span>');
//             } else if (feedback.hasClass(JvBs3.icon_ok)) { // if form-control-feedback and icon for icon_ok are exist
//                 feedback.removeClass(JvBs3.icon_ok).addClass(JvBs3.icon_error);
//             }
//         }
//     },
//     highlight_all: function(element, errorClass, successClass) {
//         var form_group = $(element).closest('.form-group');
//         var feedback = form_group.find('.form-control-feedback');

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
//     },

//     // ok
//     unhighlight_fields: function(element, errorClass, successClass) {
//         var field_name = $(element).attr('name');
//         var form_group = $(element).closest('.form-group');
//         var feedback = form_group.find('.form-control-feedback');

//         if (JvBs3.unhighlight_only_fields.indexOf(field_name) !== -1) {
//             // remove has-error class in form-group class if exists
//             if (form_group.hasClass('has-error')) {
//                 form_group.removeClass('has-error');
//             }

//             // append has-success class in form-group class
//             form_group.addClass('has-success');

//             // add element span with form-control-feedback class after the input element if not exists
//             if (feedback.length === 0) {
//                 $(element).after('<span class="glyphicon '+JvBs3.icon_ok+' form-control-feedback"></span>');
//             } else if (feedback.hasClass(JvBs3.icon_error)) { // if form-control-feedback and icon for icon_error are exist
//                 feedback.removeClass(JvBs3.icon_error).addClass(JvBs3.icon_ok);
//             }
//         }
//     },
//     unhighlight_all: function(element, errorClass, successClass) {
//         var form_group = $(element).closest('.form-group');
//         var feedback = form_group.find('.form-control-feedback');

//         // remove has-error class in form-group class if exists
//         if (form_group.hasClass('has-error')) {
//             form_group.removeClass('has-error');
//         }

//         // append has-success class in form-group class
//         form_group.addClass('has-success');

//         // add element span with form-control-feedback class after the input element if not exists
//         if (feedback.length === 0) {
//             $(element).after('<span class="glyphicon '+JvBs3.icon_ok+' form-control-feedback"></span>');
//         } else if (feedback.hasClass(JvBs3.icon_error)) { // if form-control-feedback and icon for icon_error are exist
//             feedback.removeClass(JvBs3.icon_error).addClass(JvBs3.icon_ok);
//         }
//     }
// };

function JvBs3() {
    this.icon_ok = "glyphicon-ok";
    this.icon_error = "glyphicon-remove";

    this.fields_not_highlight = [];
    this.fields_not_unhighlight = [];
}

JvBs3.prototype.setFieldsNotHighlight = function(fields) {
    this.fields_not_highlight = fields;
};

JvBs3.prototype.setFieldsNotUnhighlight = function(fields) {
    this.fields_not_unhighlight = fields;
};

JvBs3.prototype.getSettings = function() {
    var icon_ok = this.icon_ok;
    var icon_error = this.icon_error;
    var fields_not_highlight = this.fields_not_highlight;
    var fields_not_unhighlight = this.fields_not_unhighlight;

    return {
        // errorPlacement: function(error, element) {
        //     var field_name = $(element).attr('name');

        //     if (fields_not_highlight.indexOf(field_name) !== -1 && fields_not_unhighlight.indexOf(field_name) !== -1) {
        //         return true;
        //     }

        //     return
        // },

        highlight: function(element, errorClass, successClass) {
            var field_name = $(element).attr('name');
            var form_group = $(element).closest('.form-group');
            var feedback = form_group.find('.form-control-feedback');

            console.log(fields_not_highlight);
            console.log(field_name);

            // if field_name is in fields_not_highlight
            if (fields_not_highlight.indexOf(field_name) !== -1) {
                if (feedback.length > 0) {
                    // remove icon
                    if (feedback.hasClass('glyphicon-remove')) {
                        feedback.removeClass('glyphicon-remove');
                    }
                }

                // remove has-error class
                if (form_group.hasClass('has-error')) {
                    form_group.removeClass('has-error');
                }

                // remove error message
                if ($('.help-block', form_group).length > 0) {
                    $('.help-block', form_group).remove();
                }
            } else {
                // remove has-success class in form-group class if exists
                if (form_group.hasClass('has-success')) {
                    form_group.removeClass('has-success');
                }

                // append has-error class in form-group class
                form_group.addClass('has-error');

                // add element span with form-control-feedback class after the input element if not exists
                if (feedback.length === 0) {
                    $(element).after('<span class="glyphicon '+icon_error+' form-control-feedback"></span>');
                } else if (feedback.hasClass(JvBs3.icon_ok)) { // if form-control-feedback and icon for icon_ok are exist
                    feedback.removeClass(JvBs3.icon_ok).addClass(icon_error);
                }
            }
        },

        unhighlight: function(element, errorClass, successClass) {

        }
    };
};

module.exports = JvBs3;
