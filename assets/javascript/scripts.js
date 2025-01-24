function validatePassword(password) {
    return password.length >= 8;
}
document.getElementById("signupForm").addEventListener("submit", function (event) {
    event.preventDefault();

    const passwordInput = document.getElementById("password").value;
    const passMessage = document.getElementById("message");
    if (!validatePassword(passwordInput)) {
        passMessage.textContent = "Password must be at least 8 characters long.";
    } else {
        passMessage.textContent = "";
        document.getElementById("signupForm").submit();
    }
});
