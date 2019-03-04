function Emitter(Chat, EventHandler, config) {
  var webSocket = new WebSocket("ws://" + config.host + ":" + config.port + "?login_token=" + config.login_token);

  webSocket.onopen = function(e) {
    console.log("Connection established!");
    this.send(JSON.stringify({ event: Emitter.ON_CONNECTION_ESTABLISH }));
  };

  webSocket.onclose = function(e) {
    console.log("Connection closed!");

    Chat.emitter = null;
    webSocket = null;

    var reconnect_countdown = 5000;

    var reconnectTime = setInterval(function () {
      if (reconnect_countdown !== 0) {
        console.log("Reconnecting... " + (reconnect_countdown/1000));
        reconnect_countdown -= 1000;
      } else {
        Chat.emitter = new Emitter(Chat, EventHandler, {
          host: sklt_chat.host,
          port: sklt_chat.port,
          login_token: sklt_chat.login_token,
        });

        clearInterval(reconnectTime);
      }
    }, 1000);
  };

  webSocket.onmessage = function(e) {
    var parse_data = JSON.parse(e.data);
    var event = parse_data.event;
    delete parse_data.event;

    console.log(event, parse_data);
    EventHandler[event](parse_data);
  };

  this.webSocket = webSocket;
}

Emitter.ON_CONNECTION_ESTABLISH = "onConnectionEstablish";
Emitter.ON_SEND_MESSAGE = "onSendMessage";
Emitter.ON_RECEIVE_MESSAGE = "onReceiveMessage";
Emitter.ON_TYPING = "onTyping";
Emitter.ON_STOP_TYPING = "onStopTyping";
Emitter.ON_READ_MESSAGE = "onReadMessage";
Emitter.ON_FETCH_MESSAGE = "onFetchMessage";
Emitter.ON_LOAD_MORE_MESSAGES = "onLoadMoreMessages";
Emitter.ON_REQUEST_CONTACT = "onRequestContact";
Emitter.ON_ACCEPT_CONTACT = "onAcceptContact";

Emitter.prototype = {
  typing: function(chatting_to_id) {
    var msg = {
      event: Emitter.ON_TYPING,
      chatting_to_id: chatting_to_id
    };

    this.emitMessage(msg);
  },

  stopTyping: function(chatting_to_id) {
    var msg = {
      event: Emitter.ON_STOP_TYPING,
      chatting_to_id: chatting_to_id
    };

    this.emitMessage(msg);
  },

  sendMessage: function(chatting_to_id, message_id) {
    var msg = {
      event: Emitter.ON_SEND_MESSAGE,
      chatting_to_id: chatting_to_id,
      message_id: message_id
    };

    this.stopTyping(chatting_to_id);
    this.emitMessage(msg);
  }
};

Emitter.prototype.emitMessage = function(msg, errorCallback) {
  var OPEN_STATE = 1;

  if (this.webSocket.readyState === OPEN_STATE) {
    this.webSocket.send(JSON.stringify(msg));
  } else {
    if (typeof(errorCallback) !== "undefined") {
      errorCallback();
    } else {
      console.log("The server is disconnect.");
    }
  }
};

module.exports = Emitter;
