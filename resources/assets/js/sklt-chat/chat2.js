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
var WebSocketChat = require("./functions/WebSocketChat");
var Emitter = require("./functions/Emitter");

var Chat = {
  is_user_typing: false,

  init: function() {
    Chat.webSocketChat = new WebSocketChat(EventHandler, sklt_chat.host, sklt_chat.port, sklt_chat.login_token);

    $('.message-input :input[name="message"]').on('keyup', Chat.onTyping);
    $('.message-input :input[name="message"]').on('keyup', _.debounce(Chat.onStopTyping, 1500));
  },

  onTyping: function(e) {
    var ENTER_KEYCODE = 13;

    if (e.which == ENTER_KEYCODE) {
      console.log("send message");
      return false;
    }

    if (!Chat.is_user_typing) {
      Chat.is_user_typing = true;
      console.log("typing ...");
    }
  },

  onStopTyping: function() {
    Chat.is_user_typing = false;
    console.log("stop typing!");
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
};

var Helper = {
  // check if token is valid
  isTokenValid: function(token) {
    if (token !== null) {
      return sklt_chat.login_token === token;
    }

    return false;
  }
};

$(document).ready(Chat.init);
