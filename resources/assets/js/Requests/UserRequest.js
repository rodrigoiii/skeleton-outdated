var UserRequest = {
    /**
     * jQuery validation rules.
     *
     * @return object
     */
    rules: function() {
        return {
            first_name: "required",
            last_name: "required",
            email: {
                required: true,
                email: true
            }
        };
    },

    /**
     * jQuery validation messages.
     *
     * @return object
     */
    messages: function() {
        return {
            first_name: "First Name must not be empty",
            last_name: "Last Name must not be empty",
            email: "Email must be valid email"
        };
    },

    /**
     * jQuery validation options.
     *
     * @return object
     */
    options: function() {
        return {
            debug: true
        };
    },

    /**
     * Return object to be as on validate function of jQuery validation.
     *
     * @return object
     */
    toJSON: function() {
        return $.extend(this.options(), {
            rules: this.rules(),
            messages: this.messages()
        });
    }
};

module.exports = UserRequest;
