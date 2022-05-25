<?php
define('FORMULARIO_DE_CADASTRO', 250);
define('PERSISTIR_ALUNO', 251);
define('EXCLUIR_ALUNO', 252);

$html .= $o->msgTitle("Matrícula testes");

switch ($gPage) {
	case FORMULARIO_DE_CADASTRO:
		if ($_REQUEST['gId']) {
			$html .= $o->msgSubTitle('Editar dados do aluno');
			$sql = "SELECT geral_pessoas.id, geral_pessoas.apelido, geral_pessoas.cpf_cnpj, geral_pessoas.renach_pago, geral_pessoas.nome, notas.numero FROM geral_pessoas LEFT JOIN notas ON geral_pessoas.id=notas.id WHERE geral_pessoas.cpf_cnpj = '".$_REQUEST['cpf']."';";

			//jogar aqui um select que une geral_pessoas e notas e verifica os cpfs e numeros de notas associadas àquele cpf (acho que cabe um goup by ai). Retorna opções no combo/select caso exista nota associada ao cpf. Caso não, retorna "não existe nota associada a este cpf.".

			$rs = dbQuery($sql);

		} else {
			$html .= $o->msgSubTitle('Cadastrar novo aluno');
		}

		function checarNota($rs) {
			foreach ($rs as $row) {
				if ($row['numero']) {
					return $row['numero'];
				}
			}
		}

		$frm = new gForm('{columns: 2}');
		$frm->add("{type: text; fieldLabel:	Nome; value: ".$rs[0]['apelido']."; name: nomeAluno}");
		$frm->add("{type: cpf; fieldLabel: CPF; value: ".$rs[0]['cpf_cnpj'].";name: cpf;}");
		$frm->add("{type: text; fieldLabel: RENACH; value: ".$rs[0]['renach_pago']."; name: renach}");
		$frm->add("{type: text; fieldLabel: RG; value: ".$rs[0]['nome']."; name: rg}");
		$frm->add("{type: text; fieldLabel: Nota Fiscal; value: ".$rs[0]['numero']."; name: numero}");
		$frm->add("{type: combo; allowBlank: true; fieldLabel: Notas Fiscais associadas a este aluno; items: ".checarNota()."; name: numero2}");
		$frm->add("{name: gPage; type: hidden; value: ".PERSISTIR_ALUNO."}");
		$frm->add("{name: numeroNota; type: hidden; value: }");
		$html .= $frm->render($o);		
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
		break;

	case EXCLUIR_ALUNO:
		$sql = "DELETE FROM geral_pessoas WHERE id =". $_REQUEST['gId'].";";
		$sql .= "DELETE FROM notas cliente WHERE id =".$_REQUEST['gId'].";";
		dbQuery($sql);
		header('Location:index.php?g=teste');
		break;

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
			$btnEditar = $o->button("{icon: pencil; size: normal; id: excluir; href: index.php?g=teste&gPage='".FORMULARIO_DE_CADASTRO."'&gId='".$row['id']."'&cpf=".$row['cpf_cnpj']."'}");
			
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