<?php
define('FORMULARIO_DE_CADASTRO', 250);
define('PERSISTIR_ALUNO', 251);
define('EXCLUIR_ALUNO', 252);
define('ADICIONAR_COMBO', 253);

$html .= $o->msgTitle("Matrícula testes");

switch ($gPage) {
	case FORMULARIO_DE_CADASTRO:
		if ($_REQUEST['gId']) {
			$html .= $o->msgSubTitle('Editar dados do aluno');
			$sql = "SELECT geral_pessoas.id, geral_pessoas.apelido, geral_pessoas.cpf_cnpj, geral_pessoas.renach_pago, geral_pessoas.nome, notas.numero FROM geral_pessoas LEFT JOIN notas ON geral_pessoas.id=notas.id WHERE geral_pessoas.apelido = '".$_REQUEST['nome']."';";

			$rs = dbQuery($sql);
		} else {
			$html .= $o->msgSubTitle('Cadastrar novo aluno');
		}

		$js ='
		function checarNota(numero) {
			if (numero) {
				bootbox.confirm({
					message: "Notas encontradas.",
					buttons: {
						confirm: {
							label: "Ok",
							className: "btn-success"
						},
						cancel: {
							label: "Cancelar",
							className: "btn-danger"
						}
					},
					callback: function (result) {
						if(result) {
							window.location = "'.$o->page.'&gPage='.FORMULARIO_DE_CADASTRO.'&gId='.$rs[0]['id'].'&cpf='.$rs[0]['cpf_cnpj'].'&nome='.$rs[0]['apelido'].'&nota='.$_REQUEST['nota'].'";
						}
					}
				});
			} 
			else {
				bootbox.confirm({
					message: "Nenhuma nota encontradas. Deseja tentar novamente?",
					buttons: {
						confirm: {
							label: "Sim",
							className: "btn-success"
						},
						cancel: {
							label: "Não",
							className: "btn-danger"
						}
					},
					callback: function (result) {
						if(!result) {
							window.location = "'.$o->page.'&gPage=teste";
						}
					}
				});
			}
		}';

		$o->addJavascript($js);

		$sp['combo_add_notas'] = "SELECT numero FROM notas WHERE cliente = '".$rs[0]['apelido']."';";
		echo "<pre>";var_dump($sp);exit;

		$frm = new gForm('{columns: 2}');
		$frm->add("{type: text; fieldLabel:	Nome; value: ".$rs[0]['apelido']."; name: nomeAluno}");
		$frm->add("{type: cpf; fieldLabel: CPF; value: ".$rs[0]['cpf_cnpj'].";name: cpf;}");
		$frm->add("{type: text; fieldLabel: RENACH; value: ".$rs[0]['renach_pago']."; name: renach}");
		$frm->add("{type: text; fieldLabel: RG; value: ".$rs[0]['nome']."; name: rg}");
		$frm->add("{type: text; fieldLabel: Nota Fiscal; value: ".$_REQUEST['nota']."; name: numero}"); 
		$frm->add("{type: combo; allowBlank: true; fieldLabel: Notas fiscais associadas a este aluno; items: ".$sp['combo_add_notas']."; name: numero3}");
		$frm->add("{name: gPage; type: hidden; value: ".PERSISTIR_ALUNO."}");
		$frm->add("{name: numeroNota; type: hidden; value: }");
		$html .= $frm->render($o);
		$html .= $o->button("{icon: search; size: normal; caption: Buscar notas fiscais do aluno; onClick: checarNota(".$_REQUEST['nota']."); id: editar;}");		
		break;

	case PERSISTIR_ALUNO:
		if (!$gId) {
			$sql = "INSERT INTO geral_pessoas (apelido, cpf_cnpj, renach_pago, nome) VALUES ('".$_REQUEST['nomeAluno']."', ".$_REQUEST['cpf'].", ".$_REQUEST['renach'].", ".$_REQUEST['rg'].");";
			$sql .= "INSERT INTO notas (cliente, numero) VALUES ('".$_REQUEST['nomeAluno']."', ".$_REQUEST['numero'].");";
		} else {
			$sql = "UPDATE geral_pessoas SET apelido = '".$_REQUEST['nomeAluno']."', cpf_cnpj = ".$_REQUEST['cpf'].", renach_pago = ".$_REQUEST['renach'].", nome = ".$_REQUEST['rg']." WHERE id =".$_REQUEST['gId'].";";

			$sql .= "UPDATE notas SET cliente = '".$_REQUEST['nomeAluno']."', numero = ".$_REQUEST['numero']." WHERE id = ". $_REQUEST['gId']."";
		}
		dbQuery($sql);
		 
		header('Location:index.php?g=teste');

		$o->addJavascript($js);

		break;

	case EXCLUIR_ALUNO:
		$sql = "DELETE FROM geral_pessoas WHERE id =". $_REQUEST['gId'].";";
		$sql .= "DELETE FROM notas cliente WHERE id =".$_REQUEST['gId'].";";
		dbQuery($sql);
		header('Location:index.php?g=teste');
		break;
	
	//case ADICIONAR_COMBO:
//
	//	//jogar aqui um select que une geral_pessoas e notas e verifica os cpfs e numeros de notas associadas àquele cpf (acho que cabe um goup by ai). Retorna opções no combo/select caso exista nota associada ao cpf. Caso não, retorna "não existe nota associada a este cpf".
//
	//	$sql = "SELECT geral_pessoas.id, geral_pessoas.apelido, geral_pessoas.cpf_cnpj, geral_pessoas.renach_pago, geral_pessoas.nome, notas.numero FROM geral_pessoas LEFT JOIN notas ON geral_pessoas.id=notas.id WHERE geral_pessoas.cpf_cnpj = ".$_REQUEST['cpf'].";";
	//	$rs = dbQuery($sql);
	//	redirect($o->page);
	//	
	//	break;
	default:

		$html .= $o->button("{icon: plus; caption: Novo; hint: Cadastrar um novo aluno; style: info; size: normal; href: index.php?g=teste&gPage='".FORMULARIO_DE_CADASTRO."'}");

		$html .= $o->tableBegin('big', true, true);
		$mtz = array();
		$mtz[] = "<-Opções";
		$mtz[] = "<-ID";
		$mtz[] = "<-Nome";
		$mtz[] = "<-RENACH";
		$mtz[] = "<-CPF";
		$mtz[] = "<-RG";
		$mtz[] = "<-Nota";
		$html .= $o->tableRow($mtz, 'header');

		$sql = "SELECT geral_pessoas.id, geral_pessoas.apelido, geral_pessoas.cpf_cnpj, geral_pessoas.renach_pago, geral_pessoas.nome, notas.numero FROM geral_pessoas LEFT JOIN notas ON geral_pessoas.id=notas.id;";
		$rs = dbQuery($sql);

		foreach ($rs as $row) {
			$btnEditar = $o->button("{icon: pencil; size: normal; id: excluir; href: index.php?g=teste&gPage='".FORMULARIO_DE_CADASTRO."'&gId='".$row['id']."'&cpf=".$row['cpf_cnpj']."'&nome='".$row['apelido']."'&nota='".$row['numero']."'}");
			
			$btnExcluir = $o->button("{icon: trash; size: normal; onClick: deletarAluno(".$row['id']."); id: editar;}");

			$mtz =	array();
			$mtz[] = $btnEditar.$btnExcluir;
			$mtz[] = '<-'.$row['id'];
			$mtz[] = '<-'.$row['apelido'];
			$mtz[] = '<-'.$row['cpf_cnpj'];
			$mtz[] = '<-'.$row['renach_pago'];
			$mtz[] = '<-'.$row['nome'];
			$mtz[] = '<-'.$row['numero'];
			$html .= $o->tableRow($mtz, 'detail');
		}

		$js = '
			function deletarAluno(id) {
				bootbox.confirm({
					message: "Gostaria de excluir o aluno "+id+"?",
					buttons: {
						confirm: {
							label: "Sim",
							className: "btn-success"
						},
						cancel: {
							label: "Não",
							className: "btn-danger"
						}
					},
					callback: function (result) {
						if(result) {
							window.location = "'.$o->page.'&gPage='.EXCLUIR_ALUNO.'&gId='.$row['id'].'";
						}
					}
				});
			}
		';
		$o->addJavascript($js);		
		
		$html .= $o->tableEnd();
		break;
}
?>