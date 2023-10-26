document.addEventListener("DOMContentLoaded", async function() {
    const token = sessionStorage.getItem('token');

    if (!token) {
        redirecioneLogin();
        return;
    }

  async function validaToken() {
    try {
        const response = await fetch('backend/Router/loginRouter.php', {
            method: 'GET',
            headers: {
                'Authorization':  token
            }
        });

        const jsonResponse = await response.json();

        if (!response.ok || !jsonResponse.status) {
            redirecioneLogin(jsonResponse.message);
        }
    } catch (error) {
        console.error("Erro ao validar token:", error);
        redirecioneLogin(error);
    }
}

validaToken();

setInterval(validaToken, 60000);
});

function redirecioneLogin() {
    document.getElementById("mensagem").innerText="Token inválido ou expirado!"
    document.getElementById('id02').style.display='block'
    window.location.href = "login.html";
}
