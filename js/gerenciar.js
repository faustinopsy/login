document.addEventListener("DOMContentLoaded", function() {
    const token = sessionStorage.getItem('token');
  async function getPerfis() {
      const response = await fetch('backend/Router/PerfilPermissaoRouter.php', {
          method: 'GET',
          headers: {
              'Authorization':  token,
              'Content-Type': 'application/json'
          }
      });
      return await response.json();
  }
  async function getListaPermissoes() {
    const response = await fetch('backend/Router/PerfilPermissaoRouter.php', {
        method: 'GET',
        headers: {
            'Authorization':  token,
            'Content-Type': 'application/json'
        }
    });
    return await response.json();
}
async function populateDropdown() {
    const permissoes = await getListaPermissoes();
    const selectElem = document.getElementById("permissaoSelect");

    permissoes.forEach(permissao => {
        const optionElem = document.createElement("option");
        optionElem.value = permissao.nome;
        optionElem.textContent = permissao.nome;
        selectElem.appendChild(optionElem);
    });
}

populateDropdown();

  async function addPermissao(perfilId, permissaoName) {
      const response = await fetch(`backend/Router/PerfilPermissaoRouter.php`, {
          method: 'POST',
          headers: {
               'Authorization':  token,
              'Content-Type': 'application/json'
          },
          body: JSON.stringify({perfilId: perfilId, nome: permissaoName })
      });
      return await response.json();
  }

  async function deletePermissao(perfilId, permissaoName) {
      const response = await fetch(`backend/Router/PerfilPermissaoRouter.php?perfilId=${perfilId}`, {
          method: 'DELETE',
          headers: {
              'Authorization':  token,
              'Content-Type': 'application/json'
          },
          body: JSON.stringify({ nome: permissaoName })
      });
      return await response.json();
  }

  async function getPermissoes(perfilId) {
    const response = await fetch(`backend/Router/PerfilPermissaoRouter.php?perfilId=${perfilId}`, {
        method: 'GET',
        headers: {
            'Authorization':  token,
            'Content-Type': 'application/json'
        }
    });
    return await response.json();
}
(async function populatePerfis() {
  const perfis = await getPerfis();
  const ul = document.getElementById('perfilList');
  const select = document.getElementById('perfilSelect');
  const permissoesList = document.getElementById('permissoesList');

  perfis.forEach(perfil => {
      const li = document.createElement('li');
      li.textContent = perfil.nome;
      ul.appendChild(li);

      const option = document.createElement('option');
      option.value = perfil.id;
      option.textContent = perfil.nome;
      select.appendChild(option);
  });

  select.addEventListener('change', async function() {
      while (permissoesList.firstChild) {
          permissoesList.removeChild(permissoesList.firstChild);
      }

      const perfilId = this.value;
      if (perfilId) {
          const permissoes = await getPermissoes(perfilId);
          if (permissoes && permissoes.length) {
            permissoes.forEach(permissao => {
                const li = document.createElement('li');
                
                const span = document.createElement('span');
                span.textContent = permissao.nome+ " ";
                li.appendChild(span);
            
                const removeBtn = document.createElement('button');
                removeBtn.textContent = "Remover";
                removeBtn.classList.add("w3-button")
                removeBtn.classList.add("w3-round")
                removeBtn.classList.add("w3-border")
                removeBtn.addEventListener('click', async function() {
                    const result = await deletePermissao(perfilId, permissao.nome);
                    if (result.status) {
                        alert('Permissão removida com sucesso!');
                        li.remove(); 
                    } else {
                        alert('Erro ao remover permissão.');
                    }
                });
                li.appendChild(removeBtn);
            
                permissoesList.appendChild(li);
            });
            
          } else {
              const li = document.createElement('li');
              li.textContent = "Sem permissões associadas";
              permissoesList.appendChild(li);
          }
      }
  });

})();

  document.getElementById('addPermissaoBtn').addEventListener('click', async function() {
      const perfilId = document.getElementById('perfilSelect').value;
      const permissaoName = document.getElementById('permissaoSelect').value;

      const result = await addPermissao(perfilId, permissaoName);
      if (result.status) {
          alert('Permissão adicionada com sucesso!');
      } else {
          alert('Erro ao adicionar permissão.');
      }
  });


//   document.getElementById('removePermissaoBtn').addEventListener('click', async function() {
//       const perfilId = document.getElementById('perfilSelect').value;
//       const permissaoName = document.getElementById('permissaoInput').value;

//       const result = await deletePermissao(perfilId, permissaoName);
//       if (result.status) {
//           alert('Permissão removida com sucesso!');
//       } else {
//           alert('Erro ao remover permissão.');
//       }
//   });
 
});
