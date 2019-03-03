var WebSocketChat = require("./WebSocketChat");

function Emitter(EventHandler, config) {
  this.webSocketChat = new WebSocketChat(EventHandler, config.host, config.port, config.login_token);
}

Emitter.prototype = {
  typing: function(chatting_to_id) {
    var msg = {
      event: WebSocketChat.ON_TYPING,
      chatting_to_id: chatting_to_id
    };

    this.webSocketChat.emitMessage(msg);
  },

  stopTyping: function(chatting_to_id) {
    var msg = {
      event: WebSocketChat.ON_STOP_TYPING,
      chatting_to_id: chatting_to_id
    };

    this.webSocketChat.emitMessage(msg);
  },

  sendMessage: function(chatting_to_id, message_id) {
    var msg = {
      event: WebSocketChat.ON_SEND_MESSAGE,
      chatting_to_id: chatting_to_id,
      message_id: message_id
    };

    this.stopTyping(chatting_to_id);
    this.webSocketChat.emitMessage(msg);
  }
};

module.exports = Emitter;
