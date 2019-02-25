// var ChatEvents = {
//   ON_CONNECTION_ESTABLISH: "onConnectionEstablish",
//   ON_DISCONNECT: "onDisconnect",
//   ON_SEND_MESSAGE: "onSendMessage",
//   ON_TYPING: "onTyping",
//   ON_STOP_TYPING: "onStopTyping",
//   ON_READ_MESSAGE: "onReadMessage",
//   ON_FETCH_MESSAGES: "onFetchMessages",
//   ON_LOAD_MORE_MESSAGES: "onLoadMoreMessages",

//   typing_delay: "",
//   typing_delay_time: 3000, // 3 seconds
//   load_more_increment: 0,
//   is_initial_typing: true,

//   init: function() {
//     ChatEvents.conn = new WebSocket("ws://" + chat.hostname + ":" + chat.port + "?auth_id=" + chat.auth_id);
//     ChatEvents.conn.onopen = ChatEvents.onOpen;
//     ChatEvents.conn.onmessage = ChatEvents.onMessage;
//   },

//   /**
//    * Event Handler
//    */
//   send: function(data, errorCallback) {
//     if (ChatEvents.conn.readyState === 1) { // open state
//       ChatEvents.conn.send(JSON.stringify(data));
//     } else {
//       if (typeof(errorCallback) !== "undefined") {
//         errorCallback();
//       } else {
//         console.log("The server is disconnect.");
//       }
//     }
//   },

//   /**
//    * Event Listener
//    */
//   onOpen: function(e) {
//     console.log("Connection established!");
//     ChatEvents.send({ event: ChatEvents.ON_CONNECTION_ESTABLISH });
//   },

//   onMessage: function(e) {
//     var parse_data = JSON.parse(e.data);

//     var event = parse_data.event;
//     delete parse_data.event;

//     console.log(event, parse_data);

//     ChatEvents[event](parse_data);
//   },

//   onConnectionEstablish: function(data) {
//     if (data.result) {
//       var contact_status = $('.contact[data-id="' + data.user_id + '"]');
//       var tmpl = $('.contact[data-id="' + data.user_id + '"]').wrap("<div></div>").parent().html();

//       // remove div inside of ul
//       $('#contacts ul > div').remove();

//       // move new online to the last user's online
//       $('.contact[data-id="' + data.user_id + '"]').remove();
//       if ($('#contacts ul li .contact-status.online').length > 0) {
//         $('#contacts ul li .contact-status.online').last().closest('li').after(tmpl);
//       } else {
//         $('#contacts ul').html(tmpl);
//       }

//       $('.contact[data-id="' + data.user_id + '"] .contact-status').addClass("online");
//     }
//   },

//   onDisconnect: function(data) {
//     if (data.result) {
//       var contact_status = $('.contact[data-id="' + data.user_id + '"] .contact-status');

//       if (contact_status.hasClass('online')) {
//         $('.contact[data-id="' + data.user_id + '"] .contact-status').removeClass("online");
//       }
//     }
//   },

//   onSendMessage: function(data) {
//     var sent_tmpl = _.template($('#message-sent-tmpl').html());
//     var replied_tmpl = _.template($('#message-replied-tmpl').html());
//     var message;

//     if ($('.messages .no-conversation').length > 0) {
//       $('.messages .no-conversation').remove();
//     }

//     if (typeof(data.sender) !== "undefined") {
//       var sender = data.sender;
//       message = sender.message;

//       $('.messages ul li.typing').remove();
//       $('.contact[data-id="' + message.receiver.id + '"] .preview').html('<span>You: </span>' + message.message);
//       $('.messages ul').append(sent_tmpl({ 'message': message.message, 'picture': message.sender.picture }));
//       $('button.submit').prop("disable", false);

//       Chat.scrollDown();
//     }

//     // if receiver online
//     if (typeof(data.receiver) !== "undefined") {
//       var receiver = data.receiver;
//       message = receiver.message;

//       $('.messages ul li.typing').remove();
//       $('.contact[data-id="' + message.sender.id + '"] .badge').html(receiver.number_unread);
//       $('.contact[data-id="' + message.sender.id + '"] .preview').html(message.message);

//       if ($('#contacts .contact.active').data('id') == data.sender_id) {
//         $('.messages ul').append(replied_tmpl({ 'message': message.message, 'picture': message.sender.picture }));
//         Chat.scrollDown();
//       }
//     }
//   },

//   onTyping: function(data) {
//     var typing_tmpl = _.template($('#typing-tmpl').html());

//     if ($('#contacts .contact.active').data('id') == data.sender_id && $('.messages ul li.typing').length === 0) {
//       $('.messages ul').append(typing_tmpl());
//       Chat.scrollDown();
//     }
//   },

//   onStopTyping: function() {
//     $('.messages ul li.typing').remove();
//   },

//   onReadMessage: function(data) {
//     var sender_id = data.sender_id;
//     $('.contact[data-id="' + sender_id + '"] .badge').text("0");
//   },

//   onFetchMessages: function(data) {
//     var conversation = data.conversation;
//     console.log(conversation);

//     $('.messages ul').html("");

//     if (Object.keys(conversation).length > 0) {
//       for (var i in conversation) {
//         var message = conversation[i],
//           tmpl_func = message.sender.id == chat.auth_id ?
//           _.template($('#message-sent-tmpl').html()) :
//           _.template($('#message-replied-tmpl').html());

//         $('.messages ul').prepend(tmpl_func({ 'message': message.message, 'picture': message.sender.picture }));
//       }

//       Chat.scrollDown();
//       ChatEvents.load_more_increment = 0;
//     } else {
//       $('.messages ul').html('<li class="no-conversation">No conversation.</li>');
//     }
//   },

//   onLoadMoreMessages: function(data) {
//     var conversation = data.conversation;

//     if (Object.keys(conversation).length > 0) {
//       for (var i in conversation) {
//         var message = conversation[i],
//           tmpl_func = message.sender.id == chat.auth_id ?
//           _.template($('#message-sent-tmpl').html()) :
//           _.template($('#message-replied-tmpl').html());

//         $('.messages ul').prepend(tmpl_func({ 'message': message.message, 'picture': message.sender.picture }));
//       }
//     } else {
//       ChatEvents.load_more_increment = -1;
//     }
//   }
// };

function WebSocketChat(EventObject, host, port, login_token) {
  var webSocket = new WebSocket("ws://" + host + ":" + port + "?login_token=" + login_token);

  webSocket.onopen = function(e) {
    console.log("Connection established!");
    this.send(JSON.stringify({ event: WebSocketChat.ON_CONNECTION_ESTABLISH }));
  };

  webSocket.onmessage = function(e) {
    var parse_data = JSON.parse(e.data);
    var event = parse_data.event;
    delete parse_data.event;

    console.log(event, parse_data);
    EventObject[event](parse_data);
  };

  this.webSocket = webSocket;
}

WebSocketChat.ON_CONNECTION_ESTABLISH = "onConnectionEstablish";
WebSocketChat.ON_DISCONNECT = "onDisconnect";
WebSocketChat.ON_SEND_MESSAGE = "onSendMessage";
WebSocketChat.ON_TYPING = "onTyping";
WebSocketChat.ON_STOP_TYPING = "onStopTyping";
WebSocketChat.ON_READ_MESSAGE = "onReadMessage";
WebSocketChat.ON_FETCH_MESSAGES = "onFetchMessages";
WebSocketChat.ON_LOAD_MORE_MESSAGES = "onLoadMoreMessages";

WebSocketChat.prototype = {
  emitMessage: function(data, errorCallback) {
    if (this.webSocket.readyState === 1) { // open state
      this.webSocket.send(JSON.stringify(data));
    } else {
      if (typeof(errorCallback) !== "undefined") {
        errorCallback();
      } else {
        console.log("The server is disconnect.");
      }
    }
  }
};

module.exports = WebSocketChat;
