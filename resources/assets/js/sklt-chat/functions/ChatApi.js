// require jquery

function ChatApi(login_token) {
  this.login_token = login_token;
}

ChatApi.prototype.searchContacts = function(keyword, callback) {
  $.get("/api/chat-application/search-contacts?keyword=" + keyword + "&login_token=" + this.login_token, callback);
};

ChatApi.prototype.getPendingRequest = function(callback) {
  $.get("/api/chat-application/pending-requests?login_token=" + this.login_token, callback);
};

ChatApi.prototype.addContact = function(contact_id, callback) {
  var params = {
    contact_id: contact_id,
    login_token: this.login_token
  };
  $.post("/api/chat-application/add-contact/" + contact_id, params, callback);
};

module.exports = ChatApi;
