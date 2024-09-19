let alim_receita = []; 

document.getElementById('search').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    // Verifica se o campo de busca está vazio
    if (query.length < 3) {
        displaySuggestions([]); // Limpa as sugestões
        return;
    }
    if (query.length === 0) {
        document.getElementById('search').innerHTML = ''; // Limpa a lista se não houver texto
        return;
    }else if(query.length < 3)
    {
        return;
    }
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_suggestions.php?query=' + encodeURIComponent(query), true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            const suggestions = JSON.parse(xhr.responseText);
            displaySuggestions(suggestions);
        }
    };

    xhr.send();
})

// Função para exibir as sugestões na lista
 function displaySuggestions(filteredAlimentos) {
        const suggestionsContainer = document.getElementById('suggestions');
        suggestionsContainer.innerHTML = ''; // Limpa as sugestões anteriores

        filteredAlimentos.forEach(alimento => {
            const suggestionItem = document.createElement('div');
            suggestionItem.classList.add('suggestion-item');

            const leftText = document.createElement('span');
            leftText.textContent = alimento["nome"];
            leftText.classList.add('left-text');

            // Cria o elemento <span> para o texto do lado direito
            const rightText = document.createElement('span');
            rightText.textContent = alimento['nome_usuario']; 
            rightText.classList.add('right-text');

            // Adiciona os spans ao div suggestionItem
            suggestionItem.appendChild(leftText);
            suggestionItem.appendChild(rightText);
            

            // Evento para quando clicar na sugestão, adicionar o balão e preencher o input
            suggestionItem.addEventListener('click', function() {
                addSelectedItem(alimento);
                document.getElementById('search').value = '';
                suggestionsContainer.innerHTML = ''; // Limpa as sugestões após a seleção
            });

            suggestionsContainer.appendChild(suggestionItem);
        });

}

function addSelectedItem(alimento) {
    const selectedItemsContainer = document.getElementById('selected-items');
    const itemDiv = document.createElement('div');
    itemDiv.className = 'selected-item';
    
    // Cria o span para o nome
    const nameSpan = document.createElement('span');
    nameSpan.textContent = alimento["nome"];

    // Cria o campo de entrada
    const inputField = document.createElement('input');
    inputField.type = 'text';
    inputField.value = 0; // Define o valor inicial do campo de entrada
    inputField.className = 'item-input';

     // Cria o dropdown
     const dropdown = document.createElement('select');
     dropdown.className = 'item-dropdown';
     
     // Adiciona opções ao dropdown
     const unidmedidas = ["mililitros (ml)", "litros(l)", "xícaras", "colheres de sopa", "colheres de chá", "colheres de sobremesa", "gramas (g)", "quilos (kg)", "miligramas (mg)", "pitadas"]

     // Popula o dropdown com as opções
     unidmedidas.forEach(medida => {
         const option = document.createElement('option');
         option.value = medida;
         option.textContent = medida;
         dropdown.appendChild(option);
     });
    // Cria o botão de remoção
    const removeButton = document.createElement('button');
    removeButton.textContent = 'x';
    removeButton.addEventListener('click', function() {
        selectedItemsContainer.removeChild(itemDiv);
        alim_receita = alim_receita.filter(item => {
            const itemName = item.alimento["nome"]; // Remove espaços em branco
            const itemID = item.alimento["id"] // Converte para string se necessário
            const isMatch = itemName.toString() === alimento.nome.toString() && itemID.toString() === alimento.id.toString();
            return !isMatch; 
        });
        create_table(alim_receita);
    });
    
    
    // Adiciona o nome, o campo de entrada e o botão ao item
    itemDiv.appendChild(nameSpan);
    itemDiv.appendChild(inputField);
    itemDiv.appendChild(dropdown);
    itemDiv.appendChild(removeButton);
    selectedItemsContainer.appendChild(itemDiv);

    const itemObj = {
        alimento: alimento,
        quantidade: 0,
        unidade: unidmedidas[0]
    };
    alim_receita.push(itemObj);
    create_table(alim_receita);

    // Atualiza quantidade e unidade ao alterar os campos
    inputField.addEventListener('input', function() {
        const item = alim_receita.find(item => item.alimento.id === alimento.id);
        item.quantidade = parseFloat(this.value);
        create_table(alim_receita);
    });

    dropdown.addEventListener('change', function() {
        const item = alim_receita.find(item => item.alimento.id === alimento.id);
        item.unidade = this.value;
        create_table(alim_receita);
    });
}

function calcRatio100g(ing)
{
    let quant_min = ing.quantidade;
    let quant_max = quant_min;

    switch(ing.unidade)
    {
        case "mililitros (ml)":
            quant_min = quant_min*1;
            quant_max = quant_min;
            break;
        case "litros(l)":
            quant_min = quant_min*1000;
            quant_max = quant_min;
            break;
        case "xícaras":
            quant_min = quant_min*120
            quant_max = quant_max*240;
            break;
        case "gramas (g)":
            quant_min = quant_min*1;
            quant_max = quant_min;
            break;
        case "quilos (kg)":
            quant_min = quant_min*1000;
            quant_max = quant_min;
            break;
        case "miligramas (mg)":
            quant_min = quant_min*0.001;
            quant_max = quant_min;
            break;

        //A partir daqui, pode haver máximo e mínimo
        case "pitadas":
            quant_min = quant_min*0,3;
            quant_max = quant_max*1.5;
            break;
        case "colheres de sopa":
            quant_min = quant_min*10;
            quant_max = quant_max*15;
            break;
        case "colheres de chá": 
            quant_min = quant_min*2;
            quant_max = quant_max*7;
            break;
        case "colheres de sobremesa":
            quant_min = quant_min*5;
            quant_max = quant_max*14;
            break;
    }

    return [quant_min/100.0, quant_max/100.0];
}

function create_table(alim_receita)
{
    const nutrients = {
        energia: [0, 0],
        proteinas: [0, 0],
        colesterol: [0, 0],
        carboidrato: [0, 0],
        fibra: [0, 0],
        sodio: [0, 0],
        gordurassatudas: [0, 0],
        gordurastrans: [0, 0], 
        gordurastotais: [0, 0],
        acucares: [0,0]
    };
    

    for(let i=0; i<alim_receita.length; i++)
    {        
        const r100 = calcRatio100g(alim_receita[i]);

        //Levanta todos os itens compatíveis com a busca
        const alim = alim_receita[i].alimento;
        console.log(alim);
        console.log(r100)
        if (alim) {
            for(let i =0;i<2;i++)
            {
                nutrients.energia[i] += parseFloat(alim.energia)*r100[i] || 0;
                nutrients.proteinas[i] += parseFloat(alim.proteinas)*r100[i]  || 0;
                nutrients.colesterol[i] += parseFloat(alim["colesterol"]) *r100[i] || 0;
                nutrients.carboidrato[i] += parseFloat(alim["carboidrato"])*r100[i]  || 0;
                nutrients.fibra[i] += parseFloat(alim["fibras"])*r100[i]|| 0;
                nutrients.sodio[i] += parseFloat(alim["sodio"]) *r100[i] || 0;
                nutrients.gordurassatudas[i] += parseFloat(alim["Gorduras saturadas"])*r100[i] || 0 ;
                nutrients.gordurastrans[i] += parseFloat(alim["Gorduras trans"])*r100[i] || 0;
                nutrients.gordurastotais[i] += parseFloat(alim["Gorduras totais"])*r100[i] || 0;
                nutrients.acucares[i] += parseFloat(alim["acucares"])*r100[i] || 0;
            }
        } else {
            console.error(`Alimento com ID ${query} não encontrado.`);
        }

    }
    console.log(nutrients)

    createNutrientsTable(nutrients)
    
}

function createNutrientsTable(nutrients) {
    // Verifica se o objeto 'nutrients' está vazio ou todos os valores são zero
    if (!nutrients || Object.values(nutrients).every(value => value[0] === 0)) {
        // Remove a tabela existente se todos os valores forem zero
        const existingTable = document.getElementById('nutrients-table');
        if (existingTable) {
            existingTable.remove();
        }
        return; // Não cria a tabela
    }

    let table = document.getElementById('nutrients-table');
    
    // Se a tabela não existir, crie uma nova
    if (!table) {
        table = document.createElement('table');
        table.id = 'nutrients-table';
        table.border = "1";
        table.style.borderCollapse = "collapse";
        table.style.width = "100%";

        // Cria o cabeçalho da tabela
        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');

        const headerName = document.createElement('th');
        headerName.textContent = 'Nutriente';
        headerRow.appendChild(headerName);

        const unid = document.createElement('th');
        unid.textContent = 'Un. Medida';
        headerRow.appendChild(unid);

        const headerValue = document.createElement('th');
        headerValue.textContent = 'Valor (min)';
        headerRow.appendChild(headerValue);

        const headerValueMax = document.createElement('th');
        headerValueMax.textContent = 'Valor (max)';
        headerRow.appendChild(headerValueMax);

        thead.appendChild(headerRow);
        table.appendChild(thead);

        // Cria o corpo da tabela
        const tbody = document.createElement('tbody');
        tbody.id = 'nutrients-tbody';
        table.appendChild(tbody);

        // Insere a tabela no contêiner
        const container = document.getElementById('nutrients-table-container');
        container.appendChild(table);
    }

    // Atualiza o corpo da tabela
    const tbody = document.getElementById('nutrients-tbody');
    tbody.innerHTML = ''; // Limpa o conteúdo anterior
    const umedidas = ["kcal", "g", "g", "mg", "g", "g", "mg", "mg", "mg", "mg", "mg", "mg", "mg", "mg", "mg", "g", "g", "g"]
    let i = 0;
    
    // Adiciona uma linha para cada nutriente
    for (const [key, value] of Object.entries(nutrients)) {
        const row = document.createElement('tr');

        const cellName = document.createElement('td');
        // Capitaliza a primeira letra do nome do nutriente
        cellName.textContent = key.charAt(0).toUpperCase() + key.slice(1);
        row.appendChild(cellName);

        const cellUMedida = document.createElement('td');
        cellUMedida.textContent = umedidas[i];
        row.appendChild(cellUMedida);
        i++;

        const cellValue = document.createElement('td');
        // Formata o valor para duas casas decimais
        cellValue.textContent = parseFloat(value[0]).toFixed(2);
        row.appendChild(cellValue);

        const cellValueMax = document.createElement('td');
        // Formata o valor para duas casas decimais
        cellValueMax.textContent = parseFloat(value[1]).toFixed(2);
        row.appendChild(cellValueMax);

        tbody.appendChild(row);
    }
}


//Salvar receita
document.getElementById('postreceita').addEventListener('click', function() {
    // Supondo que você já tenha essas informações armazenadas em variáveis
    const recipeName = document.getElementById("receita-nome").value;
    const recipeDescription = document.getElementById("recipe-description").value;

    // Crie um objeto para enviar ao servidor
    const recipeData = {
        name: recipeName,
        description: recipeDescription,
        ingredients: alim_receita
    };
    console.log(JSON.stringify(recipeData));

    // Enviar os dados para o servidor usando AJAX (fetch API)
    fetch('salvar_receita.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(recipeData) // Converte o objeto em uma string JSON
    })
    .then(response => response.text()) // Recebe a resposta do servidor
    .then(data => {
        alert("Receita salva com sucesso!");
        window.location.href = "perfil.php";  // Redireciona para o perfil após o alerta    
    })
    .catch(error => {
        console.error('Erro:', error);
        alert("Houve um erro ao salvar a receita.");
    }); 
});
