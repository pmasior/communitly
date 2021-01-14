const statementButton = document.querySelectorAll('.js-dialog-activator');
const dialogDiv = document.querySelectorAll('.js-dialog');
const dialogBackgroundDiv = document.querySelectorAll('.js-dialog-background');
const dialogCount = statementButton.length;

for (let i = 0; i < dialogCount; i++) {
    statementButton[i].addEventListener('click', showDialog);
    dialogBackgroundDiv[i].addEventListener('click', hideDialog);
}

function showDialog(event) {
    let i = 0;
    while (i < dialogCount) {
        if (event.currentTarget === statementButton[i]) {
            break;
        } else {
            i++;
        }
    }
    dialogBackgroundDiv[i].classList.add('js-show-dialog-background');
    dialogDiv[i].classList.add('js-show-dialog');
}

function hideDialog(event) {
    let i = 0;
    while (i < dialogCount) {
        if (event.target === dialogBackgroundDiv[i]) {
            break;
        } else {
            i++;
        }
    }
    dialogBackgroundDiv[i].classList.remove('js-show-dialog-background');
    dialogDiv[i].classList.remove('js-show-dialog');
}