function funcao_filtro(nome_input_filtro, nome_tabela, num_coluna)
{
	// Declara variáveis
	var input, filter, table, tr, td, i, txtValue;
	input = document.getElementById(nome_input_filtro);
	filter = input.value.toUpperCase();
	table = document.getElementById(nome_tabela);
	tr = table.getElementsByTagName("tr");

	// Faz um loop por todas as linhas da tabela e oculta as que não corresponderem à consulta de pesquisa
	for (i = 1; i < tr.length; i++)
	{
		td = tr[i].getElementsByTagName("td")[num_coluna];
		if (td)
		{
			txtValue = td.textContent || td.innerText;
			if (txtValue.toUpperCase().indexOf(filter) > -1)
			{
				tr[i].style.display = "";
			} else {
			tr[i].style.display = "none";
			}
		}
	}
}