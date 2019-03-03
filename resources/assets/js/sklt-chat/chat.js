/**
 * Require global object
 * - sklt_chat
 *   - login_token
 */

if (typeof(jQuery) === "undefined") {
  window.jQuery = require("jquery");
  window.$ = window.jQuery;
}

require("bootstrap/js/transition");
require("bootstrap/js/modal");
require("bootstrap/js/button");

var _ = require("underscore");
var ChatApi = require("./functions/ChatApi");

var Chat = {
  webSocketChat: null,
  chatApi: null,

  init: function() {
    Chat.chatApi = new ChatApi(sklt_chat.login_token);

    $('#add-contact-btn').click(Chat.onSearchContact);
    $('body').on("keyup", '.add-contact-modal :input[name="search_contact"]', _.throttle(Chat.onSearchingContact, 800));
    // $('body').on('click', ".add-contact-modal .add-contact", Chat.onAddContact);
    $('#contacts').on('click', ".contact", Chat.onChangeActiveContact);

    $('#notification-btn').click(Chat.onShowContactRequest);

    // Chat.scrollMessage();
  },

  scrollMessage: function() {
    var bottom = $('.messages').prop('scrollHeight');
    $('.messages').animate({ scrollTop: bottom });
  },

  onSearchContact: function() {
    var tmpl = _.template($('#add-contact-tmpl').html());

    var box = bootbox.dialog({
      title: "Add Contact",
      message: tmpl(),
      className: "add-contact-modal"
    });

    box.on("shown.bs.modal", function() {
      $('.add-contact-modal :input[name="search_contact"]').focus();
    });
  },

  onSearchingContact: function() {
    var keyword = $(this).val();

    Chat.chatApi.searchContacts(keyword, function(response) {
      if (response.success) {
        var tmpl = _.template($('#result-contacts-tmpl').html());

        $('.add-contact-modal table tbody').html(tmpl({
          result_users: response.data
        }));
      } else {
        console.log(response.message);
      }
    });
  },

  onChangeActiveContact: function() {
    var user_id = $(this).data('id');
    var user_picture = $(this).data('picture');
    var user_fullname = $(this).data('fullname');

    $('.contact-profile').data('id', user_id);

    $('#contacts .contact').removeClass("active");
    $(this).addClass("active");

    var tmpl = '<img src="'+user_picture+'" alt="" />';
      tmpl += '<p>'+user_fullname+'</p>';

    $('.contact-profile .image-fullname').html(tmpl);
  },

  onShowContactRequest: function() {
    var _this = this;

    Chat.chatApi.getContactRequest(function(response) {
      if (response.success) {
        var tmpl = _.template($('#contact-request-tmpl').html());

        bootbox.dialog({
          title: "Contact requests",
          message: tmpl({
            user_requests: response.user_requests,
            contact_requests: response.contact_requests
          }),
          className: "show-contact-requests"
        });

        var badge = $(_this).find('.badge');
        if (badge.length > 0) {
          if (parseInt(badge.data('count')) > 0) {
            // mark as read
            Chat.chatApi.readNotification(function(response) {
              if (response.success) {
                console.log(response.message);

                badge.remove();
              }
            });
          }
        }
      }
    });
  }
};

$(document).ready(Chat.init);
