function deleteProd() {
    const prodId = document.getElementById("getProdId").value;
    fetch('/backend/usuario/' + prodId, {
        method: 'DELETE'
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 401) {
                throw new Error('Não autorizado');
            } else {
                throw new Error('Sem rede ou não conseguiu localizar o recurso');
            }
        }
        return response.json();
    })
    .then(data => {
        if(!data.status){
            alert("Não pode Deletar: ");
        }else{
            alert("Usuário deletado: " + JSON.stringify(data));
            document.getElementById("inpuNome").value = ''; 
        } 
        
    })
    .catch(error => alert('Erro na requisição: ' + error));
}