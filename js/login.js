document.getElementById("login").addEventListener("click", async function (e) {
    e.preventDefault();
    const urlBase="https://faustinopsy.000webhostapp.com/"
    const email = document.getElementById("email").value;
    const password = document.getElementById("senha").value;
    const lembrar = document.getElementById("lembrar").checked;
    
    const response = await fetch(`${urlBase}backend/Router/LoginRouter.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email, senha: password,lembrar })
    });

    const data = await response.json();

    if (data.status) {
        sessionStorage.setItem('token', data.token);
        window.location.href = "index.html"; 
    } else {
        document.getElementById("mensagem").innerText="Login falhou:\n " + data.message
        document.getElementById('id02').style.display='block'
    }
});
