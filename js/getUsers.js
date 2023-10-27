document.addEventListener("DOMContentLoaded", async function() {
    

  async function buscaUsers() {
    try {
        const response = await fetch('backend/Router/UsuarioRouter.php', {
            method: 'GET',
            
        });

        const jsonResponse = await response.json();
        const lista = document.querySelector("#users");
        lista.classList.add("row");
        jsonResponse.usuarios.forEach(usuario => {
            console.log(usuario)
            const container = document.createElement("div");
            container.classList.add("card");
            container.innerHTML = `
              <div class="container">
              <h3>Dados no banco com criptografado</h3>
              <p>Id: ${usuario.id}</p>
              <h3>Nome: ${usuario.nome}</h3>
              <p>Email: ${usuario.email}</p>
              <p>Criação: ${usuario.criado}</p>
              </div>
              
            `;
            lista.appendChild(container);
          });
    } catch (error) {
        console.error(error);
        
    }
}
buscaUsers()
});


