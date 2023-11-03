    const token = sessionStorage.getItem('token');
    const urlBase= "http://localhost:5500/"
    if (!token) {
        redirecioneLogin();
    }

  async function validaToken() {
   
    try {
        const response = await fetch(urlBase+'backend/Router/token', {
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
            if (telasPermitidas.includes(nomeTela)) {
                item.style.display = 'block'; 
            } else {
                item.style.display = 'none'; 
            }
        });

        if (!telasPermitidas.includes(nomePaginaAtual)) {
            // alert('Você não tem permissão para acessar esta página!');
            if (telasPermitidas.length > 0) {  
                window.location.href = telasPermitidas[0] + '.html';  
            } else {
                window.location.href = 'login.html';  
            }
        }


        if (!response.ok || !jsonResponse.status) {
            redirecioneLogin(jsonResponse.message);
        }
        document.body.style.display = 'block';

    } catch (error) {
        console.error("Erro ao validar token:", error);
        redirecioneLogin(error);
    }
    }

    validaToken();

    setInterval(validaToken, 60000);


function redirecioneLogin() {
    // document.getElementById("mensagem").innerText="Token inválido ou expirado!"
    // document.getElementById('id02').style.display='block'
    window.location.href = "login.html";
}
