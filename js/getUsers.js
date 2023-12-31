document.addEventListener("DOMContentLoaded", async function() {
    

  async function deleteUsuario(email) {
    const response = await fetch(`backend/Router/UsuarioRouter.php`, {
        method: 'DELETE',
        headers: {
            'Authorization':  token,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email: email })
    });
    return await response.json();
}
  async function buscaUsers() {
    try {
        const response = await fetch(`backend/Router/UsuarioRouter.php`, {
            method: 'GET',
            
        });

        const jsonResponse = await response.json();
        const lista = document.querySelector("#users");
        lista.classList.add("row");
        jsonResponse.usuarios.forEach(usuario => {
            const container = document.createElement("div");
            container.classList.add("card");
            container.innerHTML = `
              <div class="container">
              <h3>Dados no banco criptografados</h3>
              <p>Id: ${usuario.id}</p>
              <h3>Nome: ${usuario.nome}</h3>
              <p>Email: ${usuario.email}</p>
              <p>Criação: ${usuario.criado}</p>
              </div>
              
            `;
            const removeBtn = document.createElement('button');
                removeBtn.textContent = "Remover";
                removeBtn.classList.add("w3-button")
                removeBtn.classList.add("w3-round")
                removeBtn.classList.add("w3-border")
                removeBtn.addEventListener('click', async function() {
                    const result = await deleteUsuario(usuario.email);
                    if (result.status) {
                        alert('Usuario removido com sucesso!');
                        li.remove(); 
                    } else {
                        alert('Erro ao remover Usuario.');
                    }
                });
            container.appendChild(removeBtn);
            lista.appendChild(container);
          });
    } catch (error) {
        console.error(error);
        
    }
}
buscaUsers()
});


