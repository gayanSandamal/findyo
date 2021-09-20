/*eslint-disable*/
export function firebaseMessaging (firebase) {
  const messaging = firebase.messaging()
  messaging.usePublicVapidKey('BOb75htLMBGc8Gx8ZF-td7z81k-7EZdrwpMZKg7uRIHjHwOj1jh9ad5Ev0vtXConektgjX0fqD9H3HQgCDYHPiQ');
  messaging.requestPermission()
  .then(() => {
    console.log('have permission')
    return messaging.getToken()
  })
  .then(token => {
    console.log(token)
    if (token) {
      sendTokenToServer(token)
      updateUIForPushEnabled(token)
    } else {
      // Show permission request.
      console.log('No Instance ID token available. Request permission to generate one.')
      // Show permission UI.
      updateUIForPushPermissionRequired()
      setTokenSentToServer(false)
    }
  })
  .catch(error => {
    console.log(error)
  })

  messaging.onMessage(payload => {
    alert()
    console.log('onMessage', payload)
  })
}
function sendTokenToServer(currentToken) {
  if (!isTokenSentToServer()) {
    console.log('Sending token to server...');
    // TODO(developer): Send the current token to your server.
    setTokenSentToServer(true);
  } else {
    console.log('Token already sent to server so won\'t send it again ' + 'unless it changes');
  }
}
function setTokenSentToServer(sent) {
  window.localStorage.setItem('sentToServer', sent ? '1' : '0');
}
function updateUIForPushEnabled(currentToken) {
  console.log('done')
}
function updateUIForPushPermissionRequired() {
  showHideDiv(tokenDivId, false);
  showHideDiv(permissionDivId, true);
}
function isTokenSentToServer() {
  return window.localStorage.getItem('sentToServer') === '1';
}