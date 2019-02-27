/**
 * Require global object
 * - sklt_chat
 *   - host
 *   - port
 *   - login_token
 */
var _ = require("underscore");
var WebSocketChat = require("./functions/WebSocketChat");

var Emitter = {
  webSocketChat: null,

  is_user_typing: false,

  init: function() {
    Emitter.webSocketChat = new WebSocketChat(Receiver, sklt_chat.host, sklt_chat.port, sklt_chat.login_token);

    $('.submit').click(Emitter.onSendMessage);
    $('.message-input :input[name="message"]').on('keyup', Emitter.onTyping);
    $('.message-input :input[name="message"]').on('keyup', _.debounce(Emitter.onStopTyping, 1500));
    $('.message-input :input[name="message"]').on('keydown', Emitter.onHitEnter);
  },

  onTyping: function(e) {
    var ENTER_KEYCODE = 13;

    if (e.which == ENTER_KEYCODE) {
      Emitter.onSendMessage();
      return false;
    }

    if (!Emitter.is_user_typing) {
      Emitter.is_user_typing = true;

      Emitter.webSocketChat.emitMessage({
        event: WebSocketChat.ON_TYPING,
        receiver_id: Helper.getActiveContactId()
      });
    }

    // var receiver_id = $('#contacts .contact.active').data('id');
    //     var key_code = e.which;
    //     var data = { receiver_id: receiver_id };

    //     // initial typing
    //     if (ChatEvents.is_initial_typing) {
    //       ChatEvents.is_initial_typing = false;

    //       ChatEvents.send($.extend({ event: ChatEvents.ON_TYPING }, data));
    //     }

    //     // when stop typing
    //     clearTimeout(ChatEvents.typing_delay);
    //     ChatEvents.typing_delay = _.delay(function() {
    //       ChatEvents.is_initial_typing = true;

    //       ChatEvents.send($.extend({ event: ChatEvents.ON_STOP_TYPING }, data));
    //     }, ChatEvents.typing_delay_time);

    //     if (key_code === 13) { // hit enter
    //       Chat.send();
    //     }
  },

  onStopTyping: function() {
    Emitter.is_user_typing = false;

    Emitter.webSocketChat.emitMessage({
      event: WebSocketChat.ON_STOP_TYPING,
      receiver_id: Helper.getActiveContactId()
    });
  },

  onSendMessage: function() {
    var message = $('.message-input :input[name="message"]').val().trim();

    if (message.length > 0) {
      $('.submit').prop('disabled', true);
      $('.submit').button("loading");
      $('.message-input :input[name="message"]').val("")
      .attr('placeholder', "Sending...")
      .prop('disabled', true);

      var msg = {
        event: WebSocketChat.ON_SEND_MESSAGE,
        receiver_id: Helper.getActiveContactId(),
        message: message
      };

      Emitter.onStopTyping();
      Emitter.webSocketChat.emitMessage(msg);
    } else {
      $('.message-input :input[name="message"]').val("");
    }
  },

  onHitEnter: function(e) {
    var ENTER_KEYCODE = 13;

    if (e.which == ENTER_KEYCODE) {
      Emitter.onSendMessage();
      return false;
    }
  },

  onUnloadWindow: function() {
    Emitter.webSocketChat.emitMessage({
      event: WebSocketChat.ON_DISCONNECT
    });
  },
};

var Receiver = {
  typing_delay: "",
  typing_delay_time: 3000, // 3 seconds
  load_more_increment: 0,
  is_initial_typing: true,

  /**
   * Event Listener
   */
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

  onSendMessage: function(data) {
    if (Helper.isTokenValid(data.token)) {
      $('.submit').prop('disabled', false);
      $('.submit').button("reset");
      $('.message-input :input[name="message"]')
      .attr('placeholder', "Write your message...")
      .prop('disabled', false)
      .focus();

      var message = data.message;

      var msg = {
        event: WebSocketChat.ON_RECEIVE_MESSAGE,
        message: message
      };

      if ($('.messages ul').length === 0) {
        $('.messages').html('<ul></ul>');
      }

      if ($('.messages').hasClass('no-message')) {
        $('.messages').removeClass('no-message');
      }

      var tmpl = _.template($('#message-item-tmpl').html());
      $('.messages ul').append(tmpl({
        is_sender: true,
        picture: message.sender.picture,
        message: message.message
      }));
      Helper.scrollMessage();

      var contact_el = $('#contacts .contact[data-id="'+message.receiver.id+'"]');
      $('.meta .preview', contact_el).text(message.message);

      Emitter.webSocketChat.emitMessage(msg);
    }
  },

  onReceiveMessage: function(data) {
    if (Helper.isTokenValid(data.token)) {
      var message = data.message;

      var active_contact_id = $('#contacts .contact.active').data('id');

      if (active_contact_id == message.sender.id) {
        if ($('.messages ul').length === 0) {
          $('.messages').html('<ul></ul>');
        }

        if ($('.messages').hasClass('no-message')) {
          $('.messages').removeClass('no-message');
        }

        var tmpl = _.template($('#message-item-tmpl').html());
        $('.messages ul').append(tmpl({
          is_sender: false,
          picture: message.sender.picture,
          message: message.message
        }));

        var contact_el = $('#contacts .contact[data-id="'+message.sender.id+'"]');
        $('.meta .preview', contact_el).text(message.message);

        Helper.scrollMessage();
      }
    }
  },

  onTyping: function(data) {
    if (Helper.isTokenValid(data.token)) {
      var active_contact_id = $('#contacts .contact.active').data('id');

      if (active_contact_id == data.sender_id) {
        var tmpl = _.template($('#typing-tmpl').html());
        $('.messages ul').append(tmpl());

        Helper.scrollMessage();
      }

      $('#contacts .contact[data-id="'+data.sender_id+'"] .meta .preview').text("...");
    }

    // if ($('#contacts .contact.active').data('id') == data.sender_id && $('.messages ul li.typing').length === 0) {
    //   $('.messages ul').append(typing_tmpl());
    //   Chat.scrollDown();
    // }
  },

  onStopTyping: function(data) {
    $('.messages ul li.typing').remove();

    var last_message = $('.messages li:last-child p').text();
    $('#contacts .contact[data-id="'+data.sender_id+'"] .meta .preview').text(last_message);
  },

  onReadMessage: function(data) {
    // var sender_id = data.sender_id;
    // $('.contact[data-id="' + sender_id + '"] .badge').text("0");
  },

  onFetchMessages: function(data) {
    // var conversation = data.conversation;
    // console.log(conversation);

    // $('.messages ul').html("");

    // if (Object.keys(conversation).length > 0) {
    //   for (var i in conversation) {
    //     var message = conversation[i],
    //       tmpl_func = message.sender.id == chat.auth_id ?
    //       _.template($('#message-sent-tmpl').html()) :
    //       _.template($('#message-replied-tmpl').html());

    //     $('.messages ul').prepend(tmpl_func({ 'message': message.message, 'picture': message.sender.picture }));
    //   }

    //   Chat.scrollDown();
    //   Receiver.load_more_increment = 0;
    // } else {
    //   $('.messages ul').html('<li class="no-conversation">No conversation.</li>');
    // }
  },

  onLoadMoreMessages: function(data) {
    // var conversation = data.conversation;

    // if (Object.keys(conversation).length > 0) {
    //   for (var i in conversation) {
    //     var message = conversation[i],
    //       tmpl_func = message.sender.id == chat.auth_id ?
    //       _.template($('#message-sent-tmpl').html()) :
    //       _.template($('#message-replied-tmpl').html());

    //     $('.messages ul').prepend(tmpl_func({ 'message': message.message, 'picture': message.sender.picture }));
    //   }
    // } else {
    //   Receiver.load_more_increment = -1;
    // }
  }
};

var Helper = {
  // check if token is valid
  isTokenValid: function(token) {
    if (token !== null) {
      return sklt_chat.login_token === token;
    }

    return false;
  },

  scrollMessage: function() {
    var bottom = $('.messages').prop('scrollHeight');
    $('.messages').animate({ scrollTop: bottom });
  },

  getActiveContactId: function() {
    return parseInt($('.contact-profile').data('id'));
  }
};

$(document).ready(Emitter.init);
