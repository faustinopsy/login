document.getElementById('registrationForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const urlBase="http://localhost:8089/"
    const nome = document.getElementById('nome').value;
    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;
    const resenha = document.getElementById('resenha').value;

    if(senha!=resenha){
        document.getElementById("mensagem").innerText="As senhas estão diferentes"
        document.getElementById('id02').style.display='block'
        return;
    }

    const usuario = {
        nome: nome,
        email: email,
        senha: senha
    };

    fetch('backend/Router/Usuarios/Registrar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(usuario)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            document.getElementById('message').textContent = 'Usuário registrado com sucesso!';
            document.getElementById('nome').value='';
            document.getElementById('email').value='';
            document.getElementById('senha').value='';
            document.getElementById('resenha').value='';
        } else {
            document.getElementById('message').textContent = 'Erro ao registrar o usuário.';
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        document.getElementById('message').textContent = 'Erro ao registrar o usuário.';
    });
});