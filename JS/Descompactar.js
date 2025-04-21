const user = JSON.parse(localStorage.getItem("userData"));

if (user) {
    document.getElementById("nome").textContent = user.username;
    document.getElementById("email").textContent = user.email;
    document.getElementById("foto").src = "../images/Users/" + (user.imagem || "default.jpg");
}