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

  load_more_page: 0,

  init: function() {
    Chat.chatApi = new ChatApi(sklt_chat.login_token);

    $('#contacts').on('click', '.contact:not(".active")', Chat.onReadMessage);

    $('#add-contact-btn').click(Chat.onSearchContact);
    $('body').on("keyup", '.add-contact-modal :input[name="search_contact"]', _.throttle(Chat.onSearchingContact, 800));
    // $('body').on('click', ".add-contact-modal .add-contact", Chat.onAddContact);
    $('#contacts').on('click', ".contact", Chat.onChangeActiveContact);

    $('#notification-btn').click(Chat.onShowContactRequest);

    // Chat.scrollMessage();
  },

  resetLoadMorePage: function() {
    Chat.load_more_page = 0;
  },

  scrollMessage: function(callback) {
    var bottom = $('.messages').prop('scrollHeight');
    $('.messages').animate({ scrollTop: bottom }, "fast", callback);
  },

  onReadMessage: function() {
    var chatting_to_id = $(this).data('id');

    $('.messages').addClass("invisible");

    // Chat.webSocketChat.emitMessage({
    //   event: WebSocketChat.ON_READ_MESSAGE,
    //   chatting_to_id: user_id
    // });

    // Chat.webSocketChat.emitMessage({
    //   event: WebSocketChat.ON_FETCH_MESSAGE,
    //   chatting_to_id: user_id
    // });

    Chat.resetLoadMorePage();

    var contact_el = $('#contacts .contact[data-id="'+chatting_to_id+'"]');
    var unread_number_el = $('.meta .name .unread-number', contact_el);

    if (parseInt(unread_number_el.data('unread-number')) > 0) {
      Chat.chatApi.readMessages(chatting_to_id, function(response) {
        if (response.success) {
          unread_number_el.text("");
        }
      });
    }

    Chat.chatApi.fetchMessages(chatting_to_id, function(response) {
      if (response.success) {
        if (response.conversation.length > 0) {
          if ($('.messages').hasClass("no-message")) {
            $('.messages').removeClass("no-message");
          }

          var tmpl = _.template($('#messages-item-tmpl').html());
          $('.messages').html('<ul>'+tmpl({conversation: response.conversation})+'</ul>');
        } else {
          if (!$('.messages').hasClass("no-message")) {
            $('.messages').addClass("no-message");
          }

          $('.messages').html('<p>No conversation yet</p>');
        }

        Chat.scrollMessage(function() {
          $('.messages').removeClass("invisible");
        });
      }
    });
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
