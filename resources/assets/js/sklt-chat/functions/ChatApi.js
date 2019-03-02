// require jquery

function ChatApi(login_token) {
  this.login_token = login_token;
}

ChatApi.prototype.searchContacts = function(keyword, callback) {
  $.get("/api/chat-application/search-contacts?keyword=" + keyword + "&login_token=" + this.login_token, callback);
};

ChatApi.prototype.getContactRequest = function(callback) {
  $.get("/api/chat-application/contact-requests?login_token=" + this.login_token, callback);
};

ChatApi.prototype.addContact = function(contact_id, callback) {
  var params = {
    contact_id: contact_id,
    login_token: this.login_token
  };
  $.post("/api/chat-application/add-contact", params, callback);
};

ChatApi.prototype.removeRequest = function(contact_id, callback) {
  var params = {
    login_token: this.login_token,
    _METHOD: "DELETE"
  };
  $.post("/api/chat-application/remove-request/" + contact_id, params, callback);
};

ChatApi.prototype.removeNotification = function(contact_id, callback) {
  var params = {
    contact_id: contact_id,
    login_token: this.login_token,
    _METHOD: "DELETE"
  };
  $.post("/api/chat-application/remove-notification/" + contact_id, params, callback);
};

module.exports = ChatApi;
