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
var Emitter = require("./functions/Emitter");
var ChatApi = require("./functions/ChatApi");

var Chat = {
  chatApi: null,

  is_user_typing: false,

  init: function() {
    Chat.emitter = new Emitter(EventHandler, {
      host: sklt_chat.host,
      port: sklt_chat.port,
      login_token: sklt_chat.login_token,
    });

    Chat.chatApi = new ChatApi(sklt_chat.login_token);

    $('#contacts').on('click', '.contact:not(".active")', Chat.onSelectContact);
    $('.message-input :input[name="message"]').on('keyup', Chat.onTyping);
    $('.message-input :input[name="message"]').on('keyup', _.debounce(Chat.onStopTyping, 1500));

    _.delay(function() {
      if (!Helper.isContactEmpty()) {
        $('#contacts .contact:first').click(); // select first contact
      }
    }, 100);
  },

  onSelectContact: function() {
    var data = $(this).data();
    Helper.changeActiveContact(data.id, data.picture, data.fullname);

    var chatting_to_id = Helper.getActiveContactId();
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

        Helper.scrollMessage(function() {
          $('.messages').removeClass("invisible");
        });
      }
    });
  },

  onTyping: function(e) {
    var ENTER_KEYCODE = 13;

    if (e.which == ENTER_KEYCODE) {
      console.log("send message");
      return false;
    }

    if (!Chat.is_user_typing) {
      Chat.is_user_typing = true;

      var chatting_to_id = Helper.getActiveContactId();
      Chat.emitter.typing(chatting_to_id);
    }
  },

  onStopTyping: function() {
    Chat.is_user_typing = false;

    var chatting_to_id = Helper.getActiveContactId();
    Chat.emitter.stopTyping(chatting_to_id);
  }
};

var EventHandler = {
  onConnectionEstablish: function(data) {
    if (Helper.isTokenValid(data.token) && data.success) {
      var contact_el = $('#contacts .contact[data-id="'+data.auth_user_id+'"]');

      if (!$('.contact-status', contact_el).hasClass("online")) {
        $('.contact-status', contact_el).addClass("online");
      }
    }
  },

  onDisconnect: function(data) {
    if (Helper.isTokenValid(data.token) && data.success) {
      var contact_el = $('#contacts .contact[data-id="'+data.auth_user_id+'"]');

      if ($('.contact-status', contact_el).hasClass("online")) {
        $('.contact-status', contact_el).removeClass("online");
      }
    }
  },

  onTyping: function(data) {
    if (Helper.isTokenValid(data.token)) {
      var user = data.chatting_from;

      if (Helper.getActiveContactId() == user.id) {
        var tmpl = _.template($('#typing-tmpl').html());

        $('.messages ul').append(tmpl({
          picture: user.picture
        }));
      }

      $('#contacts .contact[data-id="'+user.id+'"] .meta .preview').text("...");
    }
  },

  onStopTyping: function(data) {
    if (Helper.isTokenValid(data.token)) {
      $('.messages ul li.typing').remove();

      var last_message = $('.messages li:last-child p').text();
      $('#contacts .contact[data-id="'+data.chatting_from_id+'"] .meta .preview').text(last_message);
    }
  },
};

var Helper = {
  // check if token is valid
  isTokenValid: function(token) {
    if (token !== null) {
      return sklt_chat.login_token === token;
    }

    return false;
  },

  getActiveContactId: function() {
    return parseInt($('.contact-profile').data('id'));
  },

  isContactEmpty: function() {
    return $('#contacts ul li.no-contacts').length === 1;
  },

  changeActiveContact: function(user_id, user_picture, user_fullname) {
    $('.contact-profile').data('id', user_id);

    $('#contacts .contact').removeClass("active");
    $(this).addClass("active");

    var tmpl = '<img src="'+user_picture+'" alt="" />';
      tmpl += '<p>'+user_fullname+'</p>';

    $('.contact-profile .image-fullname').html(tmpl);
  },

  scrollMessage: function(callback) {
    var bottom = $('.messages').prop('scrollHeight');
    $('.messages').animate({ scrollTop: bottom }, "fast", callback);
  }
};

window.Helper = Helper;

$(document).ready(Chat.init);
