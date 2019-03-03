var WebSocketChat = require("./WebSocketChat");

function Emitter(EventHandler, config) {
  this.webSocketChat = new WebSocketChat(EventHandler, config.host, config.port, config.login_token);
}

Emitter.prototype = {
  typing: function(chatting_to_id) {
    this.webSocketChat.emitMessage({
      event: WebSocketChat.ON_TYPING,
      chatting_to_id: chatting_to_id
    });
  },

  stopTyping: function(chatting_to_id) {
    this.webSocketChat.emitMessage({
      event: WebSocketChat.ON_STOP_TYPING,
      chatting_to_id: chatting_to_id
    });
  }
};

module.exports = Emitter;
