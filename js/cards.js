function openTab(tabName) {
    var i;
    var x = document.getElementsByClassName("tab");
    for (i = 0; i < x.length; i++) {
      x[i].style.display = "none";  
    }
    document.getElementById(tabName).style.display = "block";  
  }

  var header = document.getElementById("myDIV");
  var btns = header.getElementsByClassName("w3-bar-item");
  for (var i = 0; i < btns.length; i++) {
    btns[i].addEventListener("click", function() {
    var current = document.getElementsByClassName("active");
    current[0].className = current[0].className.replace(" active", "");
    this.className += " active";
    });
  }




  document.addEventListener("DOMContentLoaded", async function() {
  const cards = await fetch('js/json/cards.json', {
    method: 'GET'
  });

  const data = await cards.json();
  const cardsContainer = document.querySelector("#cards");
  data.forEach(projeto => {
    const card = document.createElement("div");
    card.classList.add("w3-container");
    card.classList.add("w3-card-4");
    card.classList.add("w3-center");
    card.style.width="50%"
    card.style.marginTop="10px"
    card.style.marginLeft="20%"
    card.innerHTML = `
      <h3>${projeto.titulo}</h3>
      <div class="imagens-story">
        ${projeto.imagens.map(imagem => `<img src="${imagem}" style="width:70%" alt="Imagem do projeto ${projeto.titulo}">`).join("")}
      </div>
      <p>${projeto.descricao}</p>
      <a href="${projeto.link}" target="_blank" class="w3-button w3-dark-grey">Ver projeto</a>
    `;
    cardsContainer.appendChild(card);
  });




  });