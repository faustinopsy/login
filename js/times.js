  document.addEventListener("DOMContentLoaded", async function() {

  const times = await fetch('js/json/times.json', {
    method: 'GET'
  });

const equipes = await times.json();
const timesContainer = document.querySelector("#time");
timesContainer.classList.add("row");
equipes.forEach(projeto => {
    const cardcoluna = document.createElement("div");
    cardcoluna.classList.add("column");
    const container = document.createElement("div");
    container.classList.add("card");
    container.innerHTML = `
      <div class="imagens-story">
        ${projeto.foto.map(imagem => `<img src="${imagem}" style="width:100%" alt="Imagem do projeto ${projeto.titulo}">`).join("")}
      </div>
      <div class="container">
      <h3>${projeto.nome}</h3>
      <p>${projeto.descricao}</p>
      </div>
      <a href="${projeto.link}" target="_blank" class="button">Contato</a>
    `;
    cardcoluna.appendChild(container);
    timesContainer.appendChild(cardcoluna);
  });



  
  });