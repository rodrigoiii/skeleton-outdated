$(document).ready(function() {
    $.ajaxSetup({
        complete: function (jqXHR)
        {
            var new_token = jqXHR.getResponseHeader('X-CSRF-TOKEN');
            $('meta[name="_token"]').attr('content', new_token);
        }
    });
});

/**
 * The framework use slim csrf(cross site request forge) by default.
 * Use this function to use POST request.
 *
 * @param  json data
 * @return json data with csrf token
 */
function postWithToken(data)
{
    var csrf = {};

    if ($('meta[name="_token"]').length > 0)
    {
        var token = JSON.parse($('meta[name="_token"]').attr('content'));
        for (var key in token)
        {
            csrf[key] = token[key];
        }
    }

    return $.extend(csrf, data);
}
