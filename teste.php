<?php
define('FORMULARIO_DE_CADASTRO', 250);
define('PERSISTIR_ALUNO', 251);
define('EXCLUIR_ALUNO', 252);

/*
Aqui estou testando o framework gFW, baseado em PHP 5.0 vanilla e com conexão com DB MySql (Workbench).
*/

// cria a página de matrícula (default) e cria o formulário (case)
switch ($gPage){
	case FORMULARIO_DE_CADASTRO:
		$html .= $o->msgTitle("Teste");
		
		$frm = new gForm('{columns: 2}');
		$frm->add("{type: text; fieldLabel:	Nome; value: ; name: nomeAluno}");
		$frm->add("{type: cpf; fieldLabel: CPF; name: cpf;}");
		$frm->add("{type:text; fieldLabel: RENACH; value: ; name: renach}");
		$frm->add("{type:text; fieldLabel: RG; value: ; name: rg}");
		$frm->add("{name: gPage; type: hidden; value: ".PERSISTIR_ALUNO."}");
		$html .= $frm->render($o);


	
		//$js = '
		//	$("#nomeAluno").blur(function() {
		//		alert( "Handler for .blur() called." );
		//  	});
		//';
		//$o->addJavascript($js);
		
		break;

		//

	case PERSISTIR_ALUNO:
		echo '<pre>';
		var_dump($_REQUEST);

		$sql = "INSERT INTO geral_pessoas (apelido, cpf_cnpj, renach_pago, nome) 
		VALUES ('".$_REQUEST['nomeAluno']."', '".$_REQUEST['cpf']."', '".$_REQUEST['renach']."', '".$_REQUEST['rg']."')";

		$rodarQuery = dbQuery($sql);

		header('Location:index.php?g=teste');
		
		break;

	case EXCLUIR_ALUNO:
		$html .= $o->msgTitle("Aluno Excluído com sucesso.");

		$sql = "DELETE FROM geral_pessoas WHERE id ='". $_REQUEST['gId']."'; ";
		dbQuery($sql);	
	
		break;

	default:
		$html.= $o->msgTitle("Matrícula testes");

		$html .= $o->button("{icon: plus; caption: Novo; hint: Cadastrar um novo aluno; style: info; size: normal; href: index.php?g=teste&gPage='".FORMULARIO_DE_CADASTRO."'}");

			//$js = 'document.getElementById("nome").addEventListener("click", function(){
			//	alert("clicado")
			//})';
			//$o->addJavascript($js);

		$html .= $o->tableBegin('big', true, true);
		$mtz = array();
		$mtz[] = "<-Opções";
		$mtz[] = "<-ID";
		$mtz[] = "<-Nome";
		$mtz[] = "<-RENACH";
		$mtz[] = "<-CPF";
		$mtz[] = "<-RG";
		$html.= $o->tableRow($mtz, 'header');

		$sql = "SELECT id, apelido, cpf_cnpj, renach_pago, nome FROM geral_pessoas;";
		$rs = dbQuery($sql);
		// $mtz = array();
		// echo count($mtz);
		// exit;

		

		foreach ($rs as $row) {
			$btnExcluir = $o->button("{icon: trash; size: normal; id: excluir; href: index.php?g=teste&gPage='".EXCLUIR_ALUNO."'&gId='".$row['id']."'}");
			$mtz =	array();
			$mtz[] = $btnEditar.$btnExcluir;
			$mtz[]=	'<-'.$row['id'];
			$mtz[]=	'<-'.$row['apelido'];
			$mtz[]=	'<-'.$row['cpf_cnpj'];
			$mtz[]=	'<-'.$row['renach_pago'];
			$mtz[]=	'<-'.$row['nome'];
			$html .= $o->tableRow($mtz, 'detail');
		}	

		$js = '
			$("#excluir").click(function() {
				confirm("Deseja realmente excluir o aluno?" );
		  	});
		';
		$o->addJavascript($js);
		
		$html .= $o->tableEnd();



		break;

		
}


// Persistir os dados dos alunos no banco


//validar dados dos alunos


//Adicionar aluno na tabela

//Editar aluno

//Excluir aluno





//$html .= $o->msgSubTitle("Com atividades recentes ou pendentes");
//$html .= $o->tableBegin('big', true, true);
//$mtz = '';
//$mtz[] = "<-Opções";
//if ($gParam[49]['ativo']) {
//	$mtz[] = "<-CFC";
//}
//$mtz[] = "<-Matrícula";
//$mtz[] = "<-Serviço / Categoria";
//$mtz[] = "<-Nome";
//$mtz[] = "<-Situação";
//$mtz[] = "<-RENACH";
//$mtz[] = "<-CPF";
//$mtz[] = "<-Celular";
//$mtz[] = "<-Telefone";
//$mtz[] = "<>Data cadastro";
//if ($_SESSION['db'] == 'webcfc_pr_estacaoa') {
//	$mtz[] = "<-Cadastrado por";
//}
//$html .= $o->tableRow($mtz, 'header');
//$cnt = 0;
//foreach ($alunos as $id => $aluno) {
//	$categoria = !empty($aluno['categoria']) ? ' / '.$aluno['categoria'] : '';
//	if ($cnt < $gParam[4]['valor']) {
//		$mtz = '';
//		$mtz[] = '<-' . $o->button("{icon: folder-open; caption: Abrir; hint: Abrir a ficha do aluno; size: small; href: " . $o->page . "&gPage=20&gId=" . $aluno['id'] . "}");
//		if ($gParam[49]['ativo']) {
//			$mtz[] = '<-' . $aluno['cfc'];
//		}
//		$mtz[] = '<-' . $aluno['apelido'];
//		$mtz[] = '<-' . $aluno['servico'].$categoria;
//		$mtz[] = '<-' . $aluno['nome'];
//		$mtz[] = '<-' . $aluno['situacao'];
//		$renach = $aluno['renach'];
//		if (isUF('BA')) {
//			if ($aluno['renach'] == '' && $aluno['renach_anterior'] <> '') {
//				$renach = $aluno['renach_anterior'];
//			}
//		}
//		$mtz[] = '<-' . $renach;
//		$mtz[] = '<-' . $aluno['cpf'].(strlen($aluno['cpf'])<>11 ? " (?)" : "");
//		$mtz[] = '<-' . $aluno['celular'];
//		$mtz[] = '<-' . $aluno['telefone'];
//		$mtz[] = '<>' . gDate($aluno['data_cadastro']);
//		if ($_SESSION['db'] == 'webcfc_pr_estacaoa') {
//			$mtz[] = '<-' . $aluno['cadastrado_por'];
//		}
//		$html .= $o->tableRow($mtz, 'detail');
//	}
//	$cnt++;
//}
//$html .= $o->tableEnd();



// TREINAMENTOS ALEATORIOS DE FAMILIARIZAÇÃO.

//$html .= $o->msgSubTitle("Com atividades recentes ou pendentes");

//$html .= $o->tableBegin('big', true, true);
//$mtz = '';
//$mtz[] = "<-Opções";
//$mtz[] = "<-CFC";
//$mtz[] = "<-Matrícula";
//$mtz[] = "<-Serviço / Categoria";
//$mtz[] = "<-Nome";
//$mtz[] = "<-Situação";
//$mtz[] = "<-RENACH";
//$mtz[] = "<-CPF";
//$mtz[] = "<-Celular";
//$mtz[] = "<-Telefone";
//$mtz[] = "<>Data cadastro";
//$html.= $o->tableRow($mtz, 'header');
//$o->tableEnd();



//href: index.php?g=matricula&gPage=24&gId=0

//$sql = "SELECT * FROM geral_pessoas /*AND CAST(AES_DECRYPT(UNHEX('nome'),'{$AESKEY}') AS CHAR(150))*/";

//$sql = "SELECT
//		id,apelido,data_cadastro, situacao,
//		CAST(AES_DECRYPT(UNHEX(nome),'" . $AESKEY . "') AS CHAR(150)) nome,
//		CAST(AES_DECRYPT(UNHEX(email),'" . $AESKEY . "') AS CHAR(150)) email,
//		CAST(AES_DECRYPT(UNHEX(celular),'" . $AESKEY . "') AS CHAR(150)) celular,
//		CAST(AES_DECRYPT(UNHEX(telefone),'" . $AESKEY . "') AS CHAR(150)) telefone
//		FROM geral_pessoas
//		WHERE tipo='F' AND cliente=1 AND situacao<>'' AND situacao<>'Inativo' ";
//$rodarQuery = dbQuery($sql);
//$sai = '';
//foreach ($rodarQuery as $row) {
//	$sai .= $row['id'] . '#' . $row['apelido'] . '^';
//}
//$html .= $o->msgSubTitle($sai);


//$sql = "INSERT INTO geral_pessoas";
//$PDO  = new PDO('mysql:host=localost;dbname=geral_pessoas', 'root', '');
//$PDO->query($sql);

//$html.= $o->tableBegin('big', true);
//
//$mtz[]='<-Opções';
//$mtz[]= '<-Matrícula';
//$mtz[]= '<-Serviço/Categoria';
//$mtz[]='<-Nome';
//$mtz[]='<-Situação';
//$mtz[]='<-RENACH';
//$mtz[]='<-CPF';
//$mtz[]='<-Celular';
//$mtz[]='<-Telefone';
//$mtz[]='<-Data cadastro';
//$html.= $o->tableRow($mtz, 'header');
//
//$o->tableEnd();


//$frm = new gForm('(title: Form de teste; columns: 2)');
//
//$frm->add("{type: label; value: Teste de campo label; }");
//$frm->add("{type: date; fieldLabel: Data; name: data; value: ; }");
//$frm->add("{type: date; fieldLabel: Data; name: data; value: ; }");
//$frm->add("{type: text; fieldLabel: Campo Texto; name: texto; value: ; }");
//$frm->add("{type: text; allowBlank: false; fieldLabel: Texto obrigatorio; name: textoObrig; value: ; }");
//$frm->add("{type: upperText; fieldLabel: Texto maiúsculas; name: textoMai; value: ; }");
//$frm->add("{type: lowerText; fieldLabel: Texto minúsculas; name: textoMin; value: ; }");
//$frm->add("{type: email; fieldLabel: Email; name: email; value: ; }");
//$frm->add("{type: url; fieldLabel: URL; name: url; value: ; }");
//$frm->add("{type: number; fieldLabel: Numero; name: num; value: ; }");
//$frm->add("{type: integer; fieldLabel: Inteiro; name: num; value: ; }");
//$frm->add("{type: positive; fieldLabel: Positivo; name: num; value: ; }");
//$frm->add("{type: positiveInteger; fieldLabel: Inteiro positivo; name: num; value: ; }");
//$frm->add("{type: datetime; fieldLabel: Data e hora; name: datahora; value: ".(date("Y-m-d H:i:s"))."; }");
//$frm->add("{type: time; fieldLabel: Hora; name: hora; value: ; }");
//$frm->add("{type: time; fieldLabel: Hora personalizada; name: hora2; minValue: 08:00; maxValue: 11:00;
//increment: 30}");
//$frm->add("{type: checkbox; fieldLabel: Dois estados; name: checkb; }");
//$frm->add("{type: cpf; fieldLabel: CPF; name: cpf; }");
//$frm->add("{type: plate; fieldLabel: Placa de veículo; name: placa; }");
//$frm->add("{type: container; fieldLabel: Número de container; name: container; }");
//
//$mtz="";
//$mtz[]="Elemento 1";
//$mtz[]="Elemento 2";
//$mtz[]="Elemento 3";
//$frm->add("{type: combo; fieldLabel: Listagem; name: combo1; value:1 }",$mtz);
//$mtz="";
//$mtz["el1"]="Outro Elemento 1";
//$mtz["el2"]="Outro Elemento 2";
//$mtz["el3"]="Outro Elemento 3";
//
//$mtz="";
//$mtz[]="Elemento 1";
//$mtz[]="Elemento 2";
//$mtz[]="Elemento 3";
//$frm->add("{type: combo; fieldLabel: Listagem; name: combo1; value:1 }",$mtz);
//$mtz="";
//$mtz["el1"]="Outro Elemento 1";
//$mtz["el2"]="Outro Elemento 2";
//$mtz["el3"]="Outro Elemento 3";
//$frm->add("{type: combo; fieldLabel: Listagem associativo; name: combo2 }",$mtz);
//$frm->add("{type: combo; fieldLabel: Listagem do banco; name: combo3; value: 9; items: $sql");
//$frm->add("{type: combo; fieldLabel: Listagem do banco2; name: combo35; value: 9; items: $sql }");
//$frm->add("{type: combo; fieldLabel: Listagem em JSON; name: combo4; value: 2; items: [{1,Um},{2,Dois},{3,Três}]
//}");

//$html.=$frm->render($o);



?>