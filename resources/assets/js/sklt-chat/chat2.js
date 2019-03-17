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
  emitter: null, // interface
  chatApi: null,

  is_user_typing: false,
  load_more_counter: 0,

  contact_requests: [],

  onConnected: function() { // interface
    console.log("Connection established!");
    $('#frame').show(function() {
      if ($('body > .disconnected').length === 1) {
        $('body > .disconnected').remove();
      }
    });
  },

  onDisconnected: function() { // interface
    console.log("Connection closed!");
    $('#frame').hide(function() {
      if ($('body > .disconnected').length === 0) {
        $('body').append('<p class="disconnected">Disconnected!</p>');
      }
    });
  },

  init: function() {
    Chat.emitter = new Emitter(Chat, EventHandler, {
      host: sklt_chat.host,
      port: sklt_chat.port,
      login_token: sklt_chat.login_token,
    });

    Chat.chatApi = new ChatApi(sklt_chat.login_token);

    // set contact requests before declare the event handlers
    Chat.chatApi.contactRequests(function(response) {
      console.log(response);
      if (response.success) {
        Chat.contact_requests = response.contact_requests;
      }

      $('#contacts').on('click', '.contact:not(".active")', Chat.onSelectContact);
      $('.message-input :input[name="message"]').on('keyup', Chat.onTyping);
      $('.message-input :input[name="message"]').on('keyup', _.debounce(Chat.onStopTyping, 1500));
      $('.submit').click(Chat.onSendMessage);
      $('.message-input :input[name="message"]').on('keydown', Emitter.onHitEnter);
      $('.messages').scroll(Chat.onLoadMoreMessages);

      $('#add-contact-btn').click(Chat.onSearchContact);
      $('body').on("keyup", '.add-contact-modal :input[name="search_contact"]', _.throttle(Chat.onSearchingContact, 800));
      $('body').on('click', ".add-contact-modal .send-contact-request", Chat.onSendContactRequest);
      $('body').on('click', ".add-contact-modal .accept-contact-request", Chat.onAcceptContactRequest);

      _.delay(function() {
        if (!Helper.isContactEmpty()) {
          $('#contacts .contact:first').click(); // select first contact
        }
      }, 100);
    });
  },

  onSelectContact: function() {
    var data = $(this).data();
    Helper.changeActiveContact(data.id, data.picture, data.fullname);

    var chatting_to_id = Helper.getActiveContactId();

    if (Helper.hasUnreadMessage(chatting_to_id)) {
      Chat.chatApi.readMessages(chatting_to_id, function(response) {
        if (response.success) {
          Helper.setUnreadNumber(chatting_to_id, 0);
        }
      });
    }

    // hide the messages block while fetching the conversation
    $('.messages').addClass("invisible");

    Chat.chatApi.conversation(chatting_to_id, function(response) {
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

        Helper.scrollMessage(null, function() {
          $('.messages').removeClass("invisible");
        });
      }
    });
  },

  onTyping: function(e) {
    var ENTER_KEYCODE = 13;

    if (e.which == ENTER_KEYCODE) {
      $('.submit').click();
      return false;
    }

    if (!Chat.is_user_typing) {
      Chat.is_user_typing = true;

      var chatting_to_id = Helper.getActiveContactId();

      if (Helper.hasUnreadMessage(chatting_to_id)) {
        Chat.chatApi.readMessages(chatting_to_id, function(response) {
          if (response.success) {
            Helper.setUnreadNumber(chatting_to_id, 0);
          }
        });
      }

      Chat.emitter.typing(chatting_to_id);
    }
  },

  onStopTyping: function() {
    Chat.is_user_typing = false;

    var chatting_to_id = Helper.getActiveContactId();
    Chat.emitter.stopTyping(chatting_to_id);
  },

  onSendMessage: function() {
    var message = $('.message-input :input[name="message"]').val().trim();

    if (message.length > 0) {
      Helper.sendingMessageState("loading");

      var chatting_to_id = Helper.getActiveContactId();

      Chat.chatApi.sendMessage(chatting_to_id, message, function(response) {
        console.log(response);
        if (response.success) {
          Helper.sendingMessageState("reset");

          var sent_message = response.sent_message;

          if (Helper.isMessagesBlockEmpty()) {
            $('.messages').html('<ul></ul>');
          }

          if ($('.messages').hasClass('no-message')) {
            $('.messages').removeClass('no-message');
          }

          // render to messages block
          var tmpl = _.template($('#message-item-tmpl').html());
          var sender = Helper.getAuthInfo();
          $('.messages ul').append(tmpl({
            is_sender: true,
            picture: sender.picture,
            message: sent_message.message
          }));
          Helper.scrollMessage();

          // render to contact preview message block
          var contact_el = $('#contacts .contact[data-id="'+chatting_to_id+'"]');
          $('.meta .preview', contact_el).text(sent_message.message);

          Chat.emitter.sendMessage(chatting_to_id, sent_message.id);
        }
      });
    } else {
      $('.message-input :input[name="message"]').val("");
    }
  },

  onLoadMoreMessages: function() {
    if (Helper.isLoadMoreMessages()) {
      Chat.load_more_counter++;

      $('ul', $(this)).prepend('<li class="load-more text-center">Loading...</li>');

      Chat.chatApi.loadMoreMessages(Helper.getActiveContactId(), Chat.load_more_counter, function(response) {
        var conversation = response.conversation;

        $('.messages ul li:first-child.load-more').remove();

        var tmpl = _.template($('#messages-item-tmpl').html());
        $('.messages ul').prepend(tmpl({conversation: conversation}));

        if (conversation.length === 0) {
          $('.messages ul').prepend('<li class="no-more text-center">No more message.</li>');
        } else {
          Helper.scrollMessage(5);
        }
      });
    }
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
          result_users: response.users
        }));
      } else {
        console.log(response.message);
      }
    });
  },

  onSendContactRequest: function() {
    var _this = this;

    var to_id = $(this).data('user-id');
    var tr_el = $(this).closest('tr');

    $(this).prop('disabled', true);
    $(this).button('loading');

    Chat.chatApi.sendContactRequest(to_id, function(response) {
      if (response.success) {
        $(_this).fadeOut(function() {
          $(this).parent().html('<span class="label label-success">Successfully send request. Please wait of acceptance</span>');
          $(this).remove();

          Chat.chatApi.getUnreadNumber(function(unreadNumberResponse) {
            if (unreadNumberResponse.success) {
              Helper.updateNotificationNumber(unreadNumberResponse.unread_number);
              Chat.emitter.sendContactRequest(to_id);
            }
          });
        });
      }
    });
  },

  onAcceptContactRequest: function() {
    var _this = this;

    var from_id = $(this).data('user-id');
    var tr_el = $(this).closest('tr');

    $(this).prop('disabled', true);
    $(this).button('loading');

    Chat.chatApi.acceptContactRequest(from_id, function(response) {
      if (response.success) {
        Helper.addContactItem({
          'user': _.extend(response.user, {id: from_id})
        });

        // select new contact
        $('#contacts .contact[data-id="'+from_id+'"]').click();

        // bootbox.hideAll();
      }
    });
  }
};

window._ = _;
window.Chat = Chat;

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

      Helper.scrollMessage();
    }
  },

  onStopTyping: function(data) {
    if (Helper.isTokenValid(data.token)) {
      $('.messages ul li.typing').remove();

      var last_message = $('.messages li:last-child p').text();
      $('#contacts .contact[data-id="'+data.chatting_from_id+'"] .meta .preview').text(last_message);
    }
  },

  onSendMessage: function(data) {
    if (Helper.isTokenValid(data.token)) {
      var message = data.message;
      var sender = message.sender;
      var unread_number = data.unread_number;

      if (Helper.getActiveContactId() == sender.id) {
        if ($('.messages ul').length === 0) {
          $('.messages').html('<ul></ul>');
        }

        if ($('.messages').hasClass('no-message')) {
          $('.messages').removeClass('no-message');
        }

        var tmpl = _.template($('#message-item-tmpl').html());
        $('.messages ul').append(tmpl({
          is_sender: false,
          picture: sender.picture,
          message: message.message
        }));

        Helper.setUnreadNumber(sender.id, unread_number);

        var contact_el = $('#contacts .contact[data-id="'+sender.id+'"]');
        $('.meta .preview', contact_el).text(message.message);

        Helper.scrollMessage();
      }
    }
  },

  onSendContactRequest: function(data) {
    if (Helper.isTokenValid(data.token)) {
      Chat.chatApi.getUnreadNumber(function(unreadNumberResponse) {
        if (unreadNumberResponse.success) {
          Helper.updateNotificationNumber(unreadNumberResponse.unread_number);
        }
      });
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

  getActiveContactId: function() {
    return parseInt($('.contact-profile').data('id'));
  },

  isContactEmpty: function() {
    return $('#contacts ul li.no-contacts').length === 1;
  },

  changeActiveContact: function(user_id, user_picture, user_fullname) {
    $('.contact-profile').data('id', user_id);

    $('#contacts .contact').removeClass("active");
    $('#contacts .contact[data-id="'+user_id+'"]').addClass("active");

    var tmpl = '<img src="'+user_picture+'" alt="" />';
      tmpl += '<p>'+user_fullname+'</p>';

    $('.contact-profile .image-fullname').html(tmpl);
  },

  scrollMessage: function(height, callback) {
    var bottom = height || $('.messages').prop('scrollHeight');
    $('.messages').animate({ scrollTop: bottom }, "fast", callback);
  },

  sendingMessageState: function(state) {
    switch (state) {
      case "loading":
        $('.submit').prop('disabled', true);
        $('.submit').button("loading");
        $('.message-input :input[name="message"]').val("")
        .attr('placeholder', "Sending...")
        .prop('disabled', true);

        break;

      case "reset":
        $('.submit').prop('disabled', false);
        $('.submit').button("reset");
        $('.message-input :input[name="message"]')
        .attr('placeholder', "Write your message...")
        .prop('disabled', false)
        .focus();

        break;
    }
  },

  getAuthInfo: function() {
    var profile_el = $('#profile');

    return {
      'picture': profile_el.data('picture'),
      'full_name': profile_el.data('full-name')
    };
  },

  isMessagesBlockEmpty: function() {
    return $('.messages ul').length === 0;
  },

  setUnreadNumber: function(user_id, unread_number) {
    var contact_el = $('#contacts .contact[data-id="'+user_id+'"]');
    var unread_number_el = $('.meta .name .unread-number', contact_el);
    unread_number_el.data('unread-number', unread_number);

    if (unread_number !== 0) {
      unread_number_el.text("(" + unread_number + ")");
    } else {
      unread_number_el.text("");
    }
  },

  hasUnreadMessage: function(id) {
    var contact_el = $('#contacts .contact[data-id="'+id+'"]');
    var unread_number_el = $('.meta .name .unread-number', contact_el);
    return parseInt(unread_number_el.data('unread-number')) > 0;
  },

  isLoadMoreMessages: function() {
    var messages_el = $('.messages');

    if (messages_el.scrollTop() === 0) {
      // if messages not show all yet
      if ($('ul li:first-child.no-more', messages_el).length === 0) {
        // if still loading
        if ($('ul li:first-child.load-more', messages_el).length === 0) {
          return true;
        }
      }
    }

    return false;
  },

  addContactItem: function(data) {
    var tmpl = _.template($('#contact-item-tmpl').html());
    var template = tmpl(data);

    if (Helper.isContactEmpty()) {
      $('#contacts ul').html(template);

      // show field and button to able to send message
      $('.message-input').removeClass('hide');
    } else {
      $('#contacts ul').prepend(template);
    }
  },

  updateNotificationNumber: function(number) {
    var notification_el = $('#contact-request-notification');

    if ($('.badge', notification_el).length > 0) {
      $('.badge', notification_el).data('count', number);
      $('.badge', notification_el).text(number);
    } else {
      notification_el.html('<span class="badge" data-count="'+number+'">'+number+'</span>');
    }
  }
};

window.Helper = Helper;

$(document).ready(Chat.init);
