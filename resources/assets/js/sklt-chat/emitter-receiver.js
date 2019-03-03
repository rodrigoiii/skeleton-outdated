/**
 * Require global object
 * - sklt_chat
 *   - host
 *   - port
 *   - login_token
 */
var _ = require("underscore");
var WebSocketChat = require("./functions/WebSocketChat");
var ChatApi = require("./functions/ChatApi");

var Emitter = {
  webSocketChat: null,
  chatApi: null,

  is_user_typing: false,
  load_more_counter: 0,

  TYPE_ACCEPTED: "accepted",
  TYPE_REQUESTED: "requested",

  init: function() {
    Emitter.webSocketChat = new WebSocketChat(Receiver, sklt_chat.host, sklt_chat.port, sklt_chat.login_token);
    Emitter.chatApi = new ChatApi(sklt_chat.login_token);

    $('.submit').click(Emitter.onSendMessage);
    $('.message-input :input[name="message"]').on('keyup', Emitter.onTyping);
    $('.message-input :input[name="message"]').on('keyup', _.debounce(Emitter.onStopTyping, 1500));
    $('.message-input :input[name="message"]').on('keydown', Emitter.onHitEnter);
    $('#contacts').on('click', '.contact:not(".active")', Emitter.onReadMessage);
    $('.messages').scroll(Emitter.onLoadMoreMessages);

    $('body').on('click', ".add-contact-modal .add-contact, .show-contact-requests .accept-request", Emitter.onAddContact);
    $('body').on('click', ".show-contact-requests .remove-request-btn", Emitter.onRemoveRequestContact);

    _.delay(function() {
      if (!Helper.isContactEmpty()) {
        // activate first contact
        $('#contacts .contact:first').click();
      }
    }, 100);
  },

  onTyping: function(e) {
    var ENTER_KEYCODE = 13;

    if (e.which == ENTER_KEYCODE) {
      Emitter.onSendMessage();
      return false;
    }

    if (!Emitter.is_user_typing) {
      Emitter.is_user_typing = true;

      var has_unread_message = $('#contacts .contact.active .wrap .name .unread-number').text() !== "";

      if (has_unread_message) {
        console.log("fdsa");
        Emitter.webSocketChat.emitMessage({
          event: WebSocketChat.ON_READ_MESSAGE,
          chatting_to_id: Helper.getActiveContactId()
        });
      }

      Emitter.webSocketChat.emitMessage({
        event: WebSocketChat.ON_TYPING,
        chatting_to_id: Helper.getActiveContactId()
      });
    }
  },

  onStopTyping: function() {
    Emitter.is_user_typing = false;

    Emitter.webSocketChat.emitMessage({
      event: WebSocketChat.ON_STOP_TYPING,
      chatting_to_id: Helper.getActiveContactId()
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
        chatting_to_id: Helper.getActiveContactId(),
        message: message
      };

      Emitter.onStopTyping();
      Emitter.webSocketChat.emitMessage(msg);
    } else {
      $('.message-input :input[name="message"]').val("");
    }
  },

  onReadMessage: function() {
    var user_id = $(this).data('id');

    $('.messages').addClass("invisible");
    Emitter.load_more_counter = 0;

    Emitter.webSocketChat.emitMessage({
      event: WebSocketChat.ON_READ_MESSAGE,
      chatting_to_id: user_id
    });

    Emitter.webSocketChat.emitMessage({
      event: WebSocketChat.ON_FETCH_MESSAGE,
      chatting_to_id: user_id
    });
  },

  onHitEnter: function(e) {
    var ENTER_KEYCODE = 13;

    if (e.which == ENTER_KEYCODE) {
      Emitter.onSendMessage();
      return false;
    }
  },

  onLoadMoreMessages: function() {
    if ($(this).scrollTop() === 0) {
      // if messages not show all yet
      if ($('ul li:first-child.no-more', $(this)).length === 0) {
        // if still loading
        if ($('ul li:first-child.load-more', $(this)).length === 0) {
          Emitter.load_more_counter++;

          $('ul', $(this)).prepend('<li class="load-more text-center">Loading...</li>');

          Emitter.webSocketChat.emitMessage({
            event: WebSocketChat.ON_LOAD_MORE_MESSAGES,
            load_more_counter: Emitter.load_more_counter,
            chatting_to_id: Helper.getActiveContactId()
          });
        }
      }
    }
  },

  onAddContact: function() {
    var _this = this;

    var user_id = $(this).data('user-id');
    var tr_el = $(this).closest('tr');

    $(this).prop('disabled', true);
    $(this).button('loading');

    Emitter.chatApi.addContactRequest(user_id, function(response) {
      console.log(response);
      if (response.success) {
        switch(response.type) {
          case Emitter.TYPE_ACCEPTED:
            console.log("accepted");

            $(_this).prop('disabled', false);
            $(_this).button('reset');
            bootbox.hideAll();

            Helper.addContactItem({
              'user': _.extend(response.user, {id: user_id})
            });

            // activate new contact
            $('#contacts .contact[data-id="'+user_id+'"]').click();

            // Emitter.onAcceptContact(contact_id);
            break;

          case Emitter.TYPE_REQUESTED:
            console.log("requested");

            $(_this).prop('disabled', false);
            $(_this).button('reset');
            bootbox.hideAll();

            Emitter.onRequestContact(user_id);

            break;
        }

        // var tmpl = _.template($('#contact-item-tmpl').html());
        // var is_contacts_empty = $('#contacts ul .contact.empty').length === 1;

        // var template = tmpl({
        //   picture: $('.contact-picture', tr_el).attr('src'),
        //   fullname: $('.contact-fullname', tr_el).text()
        // });

        // if (is_contacts_empty) {
        //   $('#contacts ul').html(template);
        // } else {
        //   $('#contacts ul').prepend(template);
        // }

        // bootbox.hideAll();
      } else {
        console.log(response.message);
      }
    });
  },

  onRequestContact: function(contact_id) {
    var msg = {
      event: WebSocketChat.ON_REQUEST_CONTACT,
      contact_id: contact_id
    };

    Emitter.webSocketChat.emitMessage(msg);
  },

  onAcceptContact: function(contact_id) {
    var msg = {
      event: WebSocketChat.ON_ACCEPT_CONTACT,
      contact_id: contact_id
    };

    Emitter.webSocketChat.emitMessage(msg);
  },

  onRemoveRequestContact: function() {
    var id = $(this).data('id');

    bootbox.confirm("Delete contact request?", function(is_yes) {
      if (is_yes) {
        Emitter.chatApi.removeRequest(id, function(response) {
          console.log(response);

          if (response.success) {
            // rnResponse - remove notification response
            Emitter.chatApi.removeNotification(response.notification_id, function(rnResponse) {
              console.log(rnResponse);
            });
          }
        });
      }
    });
  },
};

window.Emitter = Emitter;

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
        chatting_to_id: Helper.getActiveContactId(),
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

      var contact_el = $('#contacts .contact[data-id="'+data.chatting_to_id+'"]');
      $('.meta .preview', contact_el).text(message.message);

      Emitter.webSocketChat.emitMessage(msg);
    }
  },

  onReceiveMessage: function(data) {
    if (Helper.isTokenValid(data.token)) {
      var message = data.message;
      var unread_number = data.unread_number;

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
        $('.meta .name .unread-number', contact_el).text("(" + unread_number + ")");
        $('.meta .preview', contact_el).text(message.message);

        Helper.scrollMessage();
      }
    }
  },

  onTyping: function(data) {
    if (Helper.isTokenValid(data.token)) {
      var active_contact_id = $('#contacts .contact.active').data('id');

      if (active_contact_id == data.chatting_from_id) {
        var tmpl = _.template($('#typing-tmpl').html());
        $('.messages ul').append(tmpl());

        Helper.scrollMessage();
      }

      $('#contacts .contact[data-id="'+data.chatting_from_id+'"] .meta .preview').text("...");
    }
  },

  onStopTyping: function(data) {
    if (Helper.isTokenValid(data.token)) {
      $('.messages ul li.typing').remove();

      var last_message = $('.messages li:last-child p').text();
      $('#contacts .contact[data-id="'+data.chatting_from_id+'"] .meta .preview').text(last_message);
    }
  },

  onReadMessage: function(data) {
    if (Helper.isTokenValid(data.token)) {
      var contact_el = $('#contacts .contact[data-id="'+data.chatting_to_id+'"]');
      $('.meta .name .unread-number', contact_el).text("");
    }
  },

  onFetchMessage: function(data) {
    if (Helper.isTokenValid(data.token)) {
      if (data.conversation.length > 0) {
        if ($('.messages').hasClass("no-message")) {
          $('.messages').removeClass("no-message");
        }

        var tmpl = _.template($('#messages-item-tmpl').html());
        $('.messages').html('<ul>'+tmpl({conversation: data.conversation})+'</ul>');
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
  },

  onLoadMoreMessages: function(data) {
    if (Helper.isTokenValid(data.token)) {
      $('.messages ul li:first-child.load-more').remove();

      var tmpl = _.template($('#messages-item-tmpl').html());
      $('.messages ul').prepend(tmpl({conversation: data.conversation}));

      if (data.conversation.length === 0) {
        $('.messages ul').prepend('<li class="no-more text-center">No more message.</li>');
      }
    }
  },

  onRequestContact: function(data) {
    if (Helper.isTokenValid(data.token)) {
      Helper.updateNotificationNumber(data.notification_num);
    }
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

  scrollMessage: function(callback) {
    var bottom = $('.messages').prop('scrollHeight');
    $('.messages').animate({ scrollTop: bottom }, "fast", callback);
  },

  getActiveContactId: function() {
    return parseInt($('.contact-profile').data('id'));
  },

  updateNotificationNumber: function(number) {
    var notification_el = $('#notification-btn');

    if ($('.badge', notification_el).length > 0) {
      $('.badge', notification_el).data('count', number);
      $('.badge', notification_el).text(number);
    } else {
      notification_el.html('<span class="badge" data-count="'+number+'">'+number+'</span>');
    }
  },

  addContactItem: function(data) {
    var tmpl = _.template($('#contact-item-tmpl').html());
    var template = tmpl(data);

    if (Helper.isContactEmpty()) {
      $('#contacts ul').html(template);
    } else {
      $('#contacts ul').prepend(template);
    }
  },

  isContactEmpty: function() {
    return $('#contacts ul li:not(".contact")').length === 1;
  }
};

$(document).ready(Emitter.init);
