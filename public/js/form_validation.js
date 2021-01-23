
const VALIDATE_TIMEOUT = 100;
const form = document.querySelector('form');
const emailInput = form.querySelector('input[name="email"]');
const passwordInput = form.querySelector('input[name="password"]');
const confirmPasswordInput = form.querySelector('input[name="confirmedPassword"]');
const firstNameInput = form.querySelector('input[name="firstName"]');
const lastNameInput = form.querySelector('input[name="lastName"]');
const statementHeaderInput = form.querySelector('input[name="statementHeader"]');
const statementURLInput = form.querySelector('input[name="statementURL"]');
const statementContentInput = form.querySelector('input[name="statementContent"]');
const linkNameInput = form.querySelector('input[name="linkName"]');
const linkURLInput = form.querySelector('input[name="linkURL"]');
let notEmptyInputs = [
    passwordInput,
    firstNameInput,
    lastNameInput,
    statementHeaderInput,
    statementURLInput,
    statementContentInput,
    linkNameInput,
    linkURLInput
];

if (emailInput) {
    emailInput.addEventListener('keyup', validateEmail);
}

if (confirmPasswordInput) {
    confirmPasswordInput.addEventListener('keyup', validateConfirmPassword);
}

notEmptyInputs.forEach(function(input) {
    if (input) {
        input.addEventListener('keyup', function() {
            validateNotEmpty(input);
        });
    }
});

function validateEmail() {
    setTimeout(function () {
            let condition1 = isEmail(emailInput.value);
            let condition2 = isInputHasNotEmptyValue(emailInput.value);
            markValidation(emailInput, condition1 && condition2);
        },
        VALIDATE_TIMEOUT);
}

function validateNotEmpty(input) {
    setTimeout(function () {
            let condition = isInputHasNotEmptyValue(input.value);
            markValidation(input, condition);
        },
        VALIDATE_TIMEOUT);
}

function validateConfirmPassword() {
    setTimeout(function () {
            let condition1 = isInputHasNotEmptyValue(passwordInput.value);
            let condition2 = arePasswordsEqual(passwordInput.value, confirmPasswordInput.value);
            markValidation(confirmPasswordInput, condition1 && condition2);
        },
        VALIDATE_TIMEOUT);
}

function isEmail(email) {
    return /\S+@\S+.\S+/.test(email);
}

function isInputHasNotEmptyValue(password) {
    return password.length > 0;
}

function arePasswordsEqual(password, confirm_password) {
    return password === confirm_password;
}

function markValidation(input_element, condition) {
    condition ? input_element.classList.remove('js-input-invalid') : input_element.classList.add('js-input-invalid');
}