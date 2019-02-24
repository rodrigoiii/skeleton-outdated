// require jquery

function ChatApi(login_token) {
  this.login_token = login_token;
}

ChatApi.prototype.searchContact = function(keyword, callback) {
  $.get('/api/chat-application/search-contacts?keyword=' + keyword + "&login_token=" + this.login_token, callback);
};

module.exports = ChatApi;
