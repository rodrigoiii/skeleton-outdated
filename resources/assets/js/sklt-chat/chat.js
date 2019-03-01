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
