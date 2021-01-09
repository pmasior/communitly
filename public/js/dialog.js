const statementButton = document.querySelector('.js-add-content');
const dialogDiv = document.querySelector('.js-dialog');
const dialogBackgroundDiv = document.querySelector('.js-dialog-background');


statementButton.addEventListener('click', showDialog);
dialogBackgroundDiv.addEventListener('click', hideDialog);

function showDialog() {
    dialogBackgroundDiv.classList.add('js-show-dialog-background');
    dialogDiv.classList.add('js-show-dialog');
}

function hideDialog(event) {
    if (event.target == dialogBackgroundDiv) {
        dialogBackgroundDiv.classList.remove('js-show-dialog-background');
        dialogDiv.classList.remove('js-show-dialog');
    }
}