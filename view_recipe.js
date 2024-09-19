document.addEventListener('DOMContentLoaded', function() {
    // Pega o ID da receita da URL
    const urlParams = new URLSearchParams(window.location.search);
    const recipeId = urlParams.get('id'); // Busca o parâmetro 'id' da URL

    if (recipeId) {
        // Faz a requisição para buscar os ingredientes da receita
        fetch(`get_ingredients.php?id_receita=${recipeId}`)
            .then(response => response.json()) // Converte a resposta para JSON
            .then(data => {
                if (data.success) {
                    const ingredientsList = document.getElementById('selected-items');
                    
                    // Limpa a lista antes de adicionar
                    ingredientsList.innerHTML = '';

                    // Adiciona cada ingrediente na lista
                    data.ingredients.forEach(ingredient => {
                        const listItem = document.createElement('li');
                        listItem.textContent = `${ingredient.nome}: ${ingredient.quantidade} ${ingredient.unidade_medida}`;
                        ingredientsList.appendChild(listItem);
                    });
                    console.log(data.ingredients);
                    create_table(data.ingredients);
                } else {
                    // Se não houver ingredientes, mostra uma mensagem
                    document.getElementById('ingredients-list').innerHTML = '<p>Não há ingredientes cadastrados para essa receita.</p>';
                }
            })
            .catch(error => console.error('Erro ao buscar ingredientes:', error));
    }
});

function calcRatio100g(ing)
{
    let quant_min = ing.quantidade;
    let quant_max = quant_min;

    switch(ing.unidade_medida)
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
        const alim = alim_receita[i];
        console.log(alim);

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


