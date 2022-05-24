<?php
define('FORMULARIO_DE_CADASTRO', 250);
define('PERSISTIR_ALUNO', 251);
define('EXCLUIR_ALUNO', 252);

$html .= $o->msgTitle("Matrícula testes");

switch ($gPage) {
	case FORMULARIO_DE_CADASTRO:
		if ($_REQUEST['gId']) {

			$html .= $o->msgSubTitle('Editar dados do aluno');

			$sql = "SELECT * FROM geral_pessoas WHERE id=".$_REQUEST['gId'];
			$rs = dbQuery($sql);
		} else {
			$html .= $o->msgSubTitle('Cadastrar novo aluno');
		}

		$frm = new gForm('{columns: 2}');
		$frm->add("{type: text; fieldLabel:	Nome; value: ".$rs[0]['apelido']."; name: nomeAluno}");
		$frm->add("{type: cpf; fieldLabel: CPF; value:".$rs[0]['cpf_cnpj'].";name: cpf;}");
		$frm->add("{type:text; fieldLabel: RENACH; value:".$rs[0]['renach_pago']."; name: renach}");
		$frm->add("{type:text; fieldLabel: RG; value:".$rs[0]['nome']."; name: rg}");
		$frm->add("{name: gPage; type: hidden; value: ".PERSISTIR_ALUNO."}");
		$html .= $frm->render($o);
		break;
	case PERSISTIR_ALUNO:
		if (!$gId) {
			$sql = "INSERT INTO geral_pessoas (apelido, cpf_cnpj, renach_pago, nome) VALUES ('".$_REQUEST['nomeAluno']."', '".$_REQUEST['cpf']."', '".$_REQUEST['renach']."', '".$_REQUEST['rg']."')";
		} else {
			$sql = "UPDATE geral_pessoas SET apelido = '".$_REQUEST['nomeAluno']."', cpf_cnpj = '".$_REQUEST['cpf']."', renach_pago = '".$_REQUEST['renach']."', nome = '".$_REQUEST['rg']."' WHERE id ='".$_REQUEST['gId']."'";
		}

		dbQuery($sql);

		header('Location:index.php?g=teste');
		break;
	case EXCLUIR_ALUNO:
		$sql = "DELETE FROM geral_pessoas WHERE id ='". $_REQUEST['gId']."'; ";
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
		$html .= $o->tableRow($mtz, 'header');

		$sql = "SELECT id, apelido, cpf_cnpj, renach_pago, nome FROM geral_pessoas;";
		$rs = dbQuery($sql);

		foreach ($rs as $row) {
			$btnEditar = $o->button("{icon: pencil; size: normal; id: excluir; href: index.php?g=teste&gPage='".FORMULARIO_DE_CADASTRO."'&gId='".$row['id']."'}");
			
			$btnExcluir = $o->button("{icon: trash; size: normal; onClick: deletarAluno(".$row['id']."); id: editar;}");

			$mtz =	array();
			$mtz[] = $btnEditar.$btnExcluir;
			$mtz[] = '<-'.$row['id'];
			$mtz[] = '<-'.$row['apelido'];
			$mtz[] = '<-'.$row['cpf_cnpj'];
			$mtz[] = '<-'.$row['renach_pago'];
			$mtz[] = '<-'.$row['nome'];
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