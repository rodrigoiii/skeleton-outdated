// var Chat = {
//   init: function() {
//     Chat.scrollDown();

//     $("#profile-img").on('click touchstart', Chat.showStatusOptions);
//     $(".expand-button").on('click touchstart', Chat.expandButton);
//     $('#contacts').on("click", ".contact", Chat.selectContact);
//     $('#input-message').on('keyup input', Chat.typing);
//     $('button.submit').on('click touchstart', Chat.send);
//     $('.messages').scroll(Chat.loadMoreMessages);

//     ChatEvents.init();
//   },

//   showStatusOptions: function() {
//     $("#status-options").toggleClass("active");
//   },

//   expandButton: function() {
//     $("#profile").toggleClass("expanded");
//     $("#contacts").toggleClass("expanded");
//   },

//   selectContact: function() {
//     var img = $(this).find('.image').attr('src');
//     var name = $(this).find('.name').text();
//     var selected_contact = $('.contact-profile :input[name="selected-user-id"]').val();

//     $('#contacts .contact').removeClass('active');
//     $(this).addClass('active');
//     var sender_id = $('#contacts .contact.active').data('id');

//     if (selected_contact != sender_id) {
//       $('.contact-profile img').attr('src', img);
//       $('.contact-profile p').text(name);
//       $('.contact-profile :input[name="selected-user-id"]').val(sender_id);

//       var data = {
//         event: ChatEvents.ON_FETCH_MESSAGES,
//         sender_id: sender_id
//       };

//       ChatEvents.send(data);
//     } else {
//       console.log(selected_contact, sender_id);
//     }
//   },

//   typing: function(e) {
//     var receiver_id = $('#contacts .contact.active').data('id');
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
//   },

//   send: function() {
//     var receiver_id = $('#contacts .contact.active').data('id');
//     var message = $('#input-message').val().trim();

//     if (!_.isEmpty(message)) {
//       var data = {
//         event: ChatEvents.ON_SEND_MESSAGE,
//         receiver_id: receiver_id,
//         message: message
//       };

//       $('button.submit').prop("disable", true);
//       ChatEvents.send(data);
//     }

//     $('#input-message').val("");
//   },

//   loadMoreMessages: function() {
//     if ($(this).scrollTop() === 0 && ChatEvents.load_more_increment !== -1) {
//       var sender_id = $('#contacts .contact.active').data('id');
//       var data = {
//         event: ChatEvents.ON_LOAD_MORE_MESSAGES,
//         sender_id: sender_id,
//         load_more_increment: ++ChatEvents.load_more_increment
//       };

//       ChatEvents.send(data);
//     }
//   },

//   scrollDown: function() {
//     var bottom = $('.messages').prop('scrollHeight');
//     $('.messages').animate({ scrollTop: bottom });
//   }
// };

// $(document).ready(Chat.init);

/**
 * Require global object
 * - sklt_chat
 *   - host
 *   - port
 *   - login_token
 *
 * Require file
 * - sklt-chat/events.js
 */

if (typeof(jQuery) === "undefined") {
  window.jQuery = require("jquery");
  window.$ = window.jQuery;
}

require("bootstrap/js/transition");
require("bootstrap/js/modal");
require("bootstrap/js/button");

var _ = require("underscore");
var WebSocketChat = require("./WebSocketChat");
var ChatApi = require("./ChatApi");

var SkltChat = {
  webSocketChat: null,
  chatApi: null,

  init: function() {
    SkltChat.webSocketChat = new WebSocketChat(ChatEvents, sklt_chat.host, sklt_chat.port, sklt_chat.login_token);
    SkltChat.chatApi = new ChatApi(sklt_chat.login_token);

    $('#add-contact-btn').click(SkltChat.onSearchContact);
    $('body').on("keyup", '.add-contact-modal :input[name="search_contact"]', _.throttle(SkltChat.onSearchingContact, 800));
    $('body').on('click', ".add-contact-modal .add-contact", SkltChat.onAddContact);

    $(window).on('keydown', SkltChat.onHitEnter);
    $('.submit').click(SkltChat.onNewMessage);
    SkltChat.scrollMessage();

    $(window).on('beforeunload', SkltChat.onUnloadWindow);

    $('#contacts').on('click', ".contact", SkltChat.onChangeActiveContact);
  },

  getActiveContact: function() {
    return parseInt($('.contact-profile').data('id'));
  },

  onUnloadWindow: function() {
    SkltChat.webSocketChat.emitMessage({
      event: WebSocketChat.ON_DISCONNECT
    });
  },

  onSearchContact: function() {
    var tmpl = _.template($('#add-contact-tmpl').html());
    console.log(tmpl);

    bootbox.dialog({
      title: "Add Contact",
      className: "add-contact-modal",
      message: tmpl()
    });
  },

  onSearchingContact: function() {
    var keyword = $(this).val();

    SkltChat.chatApi.searchContacts(keyword, function(response) {
      if (response.success) {
        var tmpl = _.template($('#result-contacts-tmpl').html());

        $('.add-contact-modal table tbody').html(tmpl({
          result_contacts: response.data
        }));
      } else {
        console.log(response.message);
      }
    });
  },

  onAddContact: function() {
    var contact_id = $(this).data('id');
    var tr_el = $(this).closest('tr');

    $(this).prop('disabled', true);
    $(this).button('loading');

    SkltChat.chatApi.addContact(contact_id, function(response) {
      if (response.success) {
        var tmpl = _.template($('#contact-item-tmpl').html());
        var is_contacts_empty = $('#contacts ul .contact.empty').length === 1;

        var template = tmpl({
          picture: $('.contact-picture', tr_el).attr('src'),
          fullname: $('.contact-fullname', tr_el).text()
        });

        if (is_contacts_empty) {
          $('#contacts ul').html(template);
        } else {
          $('#contacts ul').prepend(template);
        }

        bootbox.hideAll();
      } else {
        console.log(response.message);
      }
    });
  },

  onChangeActiveContact: function() {
    var user_id = $(this).data('id');
    $('.contact-profile').data('id', user_id);

    $('#contacts .contact').removeClass("active");
    $(this).addClass("active");
  },

  onHitEnter: function(e) {
    var ENTER_KEYCODE = 13;

    // is focused in input message
    var is_focused = $('.message-input :input[name="message"]').is(':focus');

    if (e.which == ENTER_KEYCODE && is_focused) {
      SkltChat.onNewMessage();
      return false;
    }
  },

  onNewMessage: function() {
    // $(this).prop('disabled', true);
    // $(this).button('loading');

    var msg = {
      event: WebSocketChat.ON_SEND_MESSAGE,
      receiver_id: SkltChat.getActiveContact(),
      message: "Hello"
    };

    console.log(msg);

    // var message = $(".message-input input").val();
    // if ($.trim(message) == '') {
    //   return false;
    // }
    // $('<li class="sent"><img src="http://emilcarlsson.se/assets/mikeross.png" alt="" /><p>' + message + '</p></li>').appendTo($('.messages ul'));
    // $('.message-input input').val(null);
    // $('.contact.active .preview').html('<span>You: </span>' + message);
    // SkltChat.scrollMessage();
  },

  scrollMessage: function() {
    $(".messages").animate({ scrollTop: $(document).height() }, "fast");
  }
};

window.SkltChat = SkltChat;
window.WebSocketChat = WebSocketChat;

var ChatEvents = {
  typing_delay: "",
  typing_delay_time: 3000, // 3 seconds
  load_more_increment: 0,
  is_initial_typing: true,

  // check if token is valid
  isTokenValid: function(token) {
    return sklt_chat.login_token === token;
  },

  /**
   * Event Listener
   */
  onConnectionEstablish: function(data) {
    // if (data.result) {
    //   var contact_status = $('.contact[data-id="' + data.user_id + '"]');
    //   var tmpl = $('.contact[data-id="' + data.user_id + '"]').wrap("<div></div>").parent().html();

    //   // remove div inside of ul
    //   $('#contacts ul > div').remove();

    //   // move new online to the last user's online
    //   $('.contact[data-id="' + data.user_id + '"]').remove();
    //   if ($('#contacts ul li .contact-status.online').length > 0) {
    //     $('#contacts ul li .contact-status.online').last().closest('li').after(tmpl);
    //   } else {
    //     $('#contacts ul').html(tmpl);
    //   }

    //   $('.contact[data-id="' + data.user_id + '"] .contact-status').addClass("online");
    // }
  },

  onDisconnect: function(data) {
    // if (data.result) {
    //   var contact_status = $('.contact[data-id="' + data.user_id + '"] .contact-status');

    //   if (contact_status.hasClass('online')) {
    //     $('.contact[data-id="' + data.user_id + '"] .contact-status').removeClass("online");
    //   }
    // }
  },

  onSendMessage: function(data) {
    if (ChatEvents.isTokenValid(data.token)) {
      var msg = {
        event: WebSocketChat.ON_RECEIVE_MESSAGE,
        message: data.message
      };

      SkltChat.webSocketChat.emitMessage(msg);
    }
    // var sent_tmpl = _.template($('#message-sent-tmpl').html());
    // var replied_tmpl = _.template($('#message-replied-tmpl').html());
    // var message;

    // if ($('.messages .no-conversation').length > 0) {
    //   $('.messages .no-conversation').remove();
    // }

    // if (typeof(data.sender) !== "undefined") {
    //   var sender = data.sender;
    //   message = sender.message;

    //   $('.messages ul li.typing').remove();
    //   $('.contact[data-id="' + message.receiver.id + '"] .preview').html('<span>You: </span>' + message.message);
    //   $('.messages ul').append(sent_tmpl({ 'message': message.message, 'picture': message.sender.picture }));
    //   $('button.submit').prop("disable", false);

    //   Chat.scrollDown();
    // }

    // // if receiver online
    // if (typeof(data.receiver) !== "undefined") {
    //   var receiver = data.receiver;
    //   message = receiver.message;

    //   $('.messages ul li.typing').remove();
    //   $('.contact[data-id="' + message.sender.id + '"] .badge').html(receiver.number_unread);
    //   $('.contact[data-id="' + message.sender.id + '"] .preview').html(message.message);

    //   if ($('#contacts .contact.active').data('id') == data.sender_id) {
    //     $('.messages ul').append(replied_tmpl({ 'message': message.message, 'picture': message.sender.picture }));
    //     Chat.scrollDown();
    //   }
    // }
  },

  onReceiveMessage: function(data) {
    if (ChatEvents.isTokenValid(data.token)) {

    }
  },

  onTyping: function(data) {
    // var typing_tmpl = _.template($('#typing-tmpl').html());

    // if ($('#contacts .contact.active').data('id') == data.sender_id && $('.messages ul li.typing').length === 0) {
    //   $('.messages ul').append(typing_tmpl());
    //   Chat.scrollDown();
    // }
  },

  onStopTyping: function() {
    // $('.messages ul li.typing').remove();
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
    //   ChatEvents.load_more_increment = 0;
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
    //   ChatEvents.load_more_increment = -1;
    // }
  }
};

$(document).ready(SkltChat.init);
