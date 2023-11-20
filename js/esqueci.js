document.getElementById("recuperar").addEventListener("click", async function (e) {
    e.preventDefault();
    
    const email = document.getElementById("email").value;
    
    const response = await fetch('backend/Router/recuperarsenha', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email })
    });

    const data = await response.json();

    if (data.status) {
        alert("Você recebeu um email com uma senha temporaria");
        window.location.href = "login.html"; 
    } else {
        document.getElementById("mensagem").innerText="Falhou:\n " + data.message
        document.getElementById('id02').style.display='block'
    }

    
});
