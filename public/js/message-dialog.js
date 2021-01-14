const messageDialogDiv = document.querySelector('.js-message-dialog');
const messageDialogBackgroundDiv = document.querySelector('.js-message-dialog-background');

messageDialogBackgroundDiv.addEventListener('click', hideDialog);

showMessageDialogIfMessagesExist();

function showMessageDialogIfMessagesExist() {
    if (messageDialogDiv.childElementCount) {
        messageDialogBackgroundDiv.classList.add('js-show-dialog-background');
        messageDialogDiv.classList.add('js-show-dialog');
    }
}

function hideDialog(event) {
    if (event.target === messageDialogBackgroundDiv) {
        messageDialogBackgroundDiv.classList.remove('js-show-dialog-background');
        messageDialogDiv.classList.remove('js-show-dialog');
    }
}