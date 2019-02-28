function WebSocketChat(Receiver, host, port, login_token) {
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
    Receiver[event](parse_data);
  };

  this.webSocket = webSocket;
}

WebSocketChat.ON_CONNECTION_ESTABLISH = "onConnectionEstablish";
WebSocketChat.ON_DISCONNECT = "onDisconnect";
WebSocketChat.ON_SEND_MESSAGE = "onSendMessage";
WebSocketChat.ON_RECEIVE_MESSAGE = "onReceiveMessage";
WebSocketChat.ON_TYPING = "onTyping";
WebSocketChat.ON_STOP_TYPING = "onStopTyping";
WebSocketChat.ON_READ_MESSAGE = "onReadMessage";
WebSocketChat.ON_FETCH_MESSAGES = "onFetchMessages";
WebSocketChat.ON_LOAD_MORE_MESSAGES = "onLoadMoreMessages";

WebSocketChat.prototype = {
  emitMessage: function(msg, errorCallback) {
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
  }
};

module.exports = WebSocketChat;
