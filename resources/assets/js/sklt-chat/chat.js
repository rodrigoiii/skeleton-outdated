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
// };

// $(document).ready(Chat.init);

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
    $('body').on('click', ".add-contact-modal .add-contact", Chat.onAddContact);
    $('#contacts').on('click', ".contact", Chat.onChangeActiveContact);

    // Chat.scrollMessage();
  },

  scrollMessage: function() {
    var bottom = $('.messages').prop('scrollHeight');
    $('.messages').animate({ scrollTop: bottom });
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

    Chat.chatApi.searchContacts(keyword, function(response) {
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

    Chat.chatApi.addContact(contact_id, function(response) {
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
    var user_picture = $(this).data('picture');
    var user_fullname = $(this).data('fullname');

    $('.contact-profile').data('id', user_id);

    $('#contacts .contact').removeClass("active");
    $(this).addClass("active");

    var tmpl = '<img src="'+user_picture+'" alt="" />';
      tmpl += '<p>'+user_fullname+'</p>';

    $('.contact-profile .image-fullname').html(tmpl);
  }
};

$(document).ready(Chat.init);
