
const form = document.querySelector('form');
const loginInput = form.querySelector('input[name="login"]');
const passwordInput = form.querySelector('input[name="pass"]');
const confirmPasswordInput = form.querySelector('input[name="pass222222"]'); // TODO: dodać pole w html i ustawić tu name=""

function isEmail(email) {
    return /\S+@\S+.\S+/.test(email);
    // return false;
}

function arePasswordsEqual(password, confirm_password) {
    return password === confirm_password;
}

function markValidation(input_element, condition) {
    !condition ? input_element.classList.add('js-input-invalid') : input_element.classList.remove('js-input-invalid');
}


function validateEmail() {
    setTimeout(function () {
        console.log("debug email event");
        const condition = isEmail(loginInput.value);
        console.log(condition);
        markValidation(loginInput, condition);
    },
        1000);
}

function validatePassword() {
    // TODO: sprawdzanie skomplikowania hasła
    setTimeout(function () {
        const condition = arePasswordsEqual(passwordInput.value, confirmPasswordInput.value)
        markValidation(confirmedPasswordInput, condition);
    },
        1000);    
}

loginInput.addEventListener('keyup', validateEmail);
passwordInput.addEventListener('keyup', validatePassword);