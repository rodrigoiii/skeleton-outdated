// require jquery

function ChatApi(login_token) {
  this.login_token = login_token;
}

ChatApi.prototype = {
  contactRequests: function(callback) {
    var query_string = "?login_token=" + this.login_token;
    $.get("/api/chat-application/contact-requests" + query_string, callback);
  },

  readMessages: function(chatting_to_id, callback) {
    var params = {
      login_token: this.login_token,
      _METHOD: "PUT"
    };
    $.post("/api/chat-application/read-messages/" + chatting_to_id, params, callback);
  },

  conversation: function(chatting_to_id, callback) {
    var query_string = "?login_token=" + this.login_token;
    $.get("/api/chat-application/conversation/" + chatting_to_id + query_string, callback);
  },

  sendMessage: function(chatting_to_id, message, callback) {
    var params = {
      login_token: this.login_token,
      message: message
    };
    $.post("/api/chat-application/send-message/" + chatting_to_id, params, callback);
  },

  loadMoreMessages: function(chatting_to_id, load_more_counter, callback) {
    var query_string = "?login_token=" + login_token +
                       "&load_more_counter=" + load_more_counter;
    $.get("/api/chat-application/load-more-messages/" + chatting_to_id + query_string, callback);
  },

  searchContacts: function(keyword, callback) {
    var query_string = "?login_token=" + this.login_token +
                       "&keyword=" + keyword;
    $.get("/api/chat-application/search-contacts" + query_string, callback);
  },

  sendContactRequest: function(to_id, callback) {
    var params = {
      to_id: to_id,
      login_token: this.login_token
    };
    $.post("/api/chat-application/send-contact-request", params, callback);
  },

  acceptContactRequest: function(from_id, callback) {
    var params = {
      from_id: from_id,
      login_token: this.login_token
    };
    $.post("/api/chat-application/accept-contact-request", params, callback);
  },

  getUnreadNumber: function(callback) {
    var query_string = "?login_token=" + this.login_token;
    $.get("/api/chat-application/get-unread-number" + query_string, callback);
  }
};


// ChatApi.prototype.removeRequest = function(contact_id, callback) {
//   var params = {
//     login_token: this.login_token,
//     _METHOD: "DELETE"
//   };
//   $.post("/api/chat-application/remove-request/" + contact_id, params, callback);
// };

// ChatApi.prototype.readNotification = function(callback) {
//   var params = {
//     login_token: this.login_token,
//     _METHOD: "PUT"
//   };

//   $.post("/api/chat-application/read-notification", params, callback);
// };

// ChatApi.prototype.removeNotification = function(contact_id, callback) {
//   var params = {
//     contact_id: contact_id,
//     login_token: this.login_token,
//     _METHOD: "DELETE"
//   };
//   $.post("/api/chat-application/remove-notification/" + contact_id, params, callback);
// };

module.exports = ChatApi;
