document.addEventListener("DOMContentLoaded", async function() {
    const token = sessionStorage.getItem('token');
    const nomePaginaAtual = window.location.pathname.split('/').pop().replace('.html', '');

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
        const telasPermitidas = jsonResponse.telas.map(tela => tela.nome);
        const nomePaginaAtual = window.location.pathname.split('/').pop().replace('.html', '');
        
        const itensMenu = document.querySelectorAll('.w3-bar-item');

        itensMenu.forEach(item => {
            const nomeTela = item.dataset.tela; 
            console.log(nomeTela)
            if (telasPermitidas.includes(nomeTela)) {
                item.style.display = 'block'; 
            } else {
                item.style.display = 'none'; 
            }
        });

        if (!telasPermitidas.includes(nomePaginaAtual)) {
            alert('Você não tem permissão para acessar esta página!');
            window.location.href = telasPermitidas+'.html';
        }


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
