var Csrf = {
    /**
     * Ajax setup that changing token every sending request.
     *
     * @return void
     */
    init: function() {
        $.ajaxSetup({
            complete: function (jqXHR)
            {
                var new_token = jqXHR.getResponseHeader('X-CSRF-TOKEN');
                $('meta[name="_token"]').attr('content', new_token);
            }
        });
    },

    /**
     * Helper function to use post request via ajax.
     *
     * @param  object data
     * @return object data with csrf token
     */
    postWithToken: function(data) {
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
};

module.exports = Csrf;
