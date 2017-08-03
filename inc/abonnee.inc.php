<?php 

//-------------------INSERT INTO TABLE abonne--------------------//
if (!empty($_POST) && isset($_POST['prenom'])) {

	$_POST['prenom']=htmlspecialchars(addslashes($_POST['prenom']));//protection contre injection sql

	if (!preg_match('/^[a-zA-Z-_.]+$/', $_POST['prenom'])) {//on définie les charactères accéptés par le champ

		$message='<div class="error">Le nom ne peut contenir que des lettres et des charactères spéciaux comme: -_.</div>';

	}

	if (empty($message)) {//si pas d'erreurs...

		if ($_GET['action']==='ajout_abonne') {//et action 'ajout d'un abonee...

			$update=$pdo->prepare("INSERT INTO abonne(prenom) VALUES('$_POST[prenom]')");//alors on fait une requete d'insertion
			$update->execute();
			$message='<div class="success">L\'abonne a bien été ajouté à la base de donné</div>';

		} elseif($_GET['action']==='modification_abonne') {//sinon si action 'modification des infos dun abonnee'...

			$update=$pdo->prepare("UPDATE abonne SET prenom='$_POST[prenom]' WHERE id_abonne=$_GET[id_abonne]");//alors -> une requete d'update
			$update->execute();
			$message='<div class="success">Le prenom a bien été mis à jour dans la base de donné</div>';

		}	
	}
}

//-------------------DELETE FROM TABLE abonne--------------------//
if (isset($_GET['action']) && $_GET['action']==='suppression_abonne') {//si action 'suppression'...

	$delete=$pdo->prepare("DELETE FROM abonne WHERE id_abonne=$_GET[id_abonne]");//alors on fait une requete de delete
	$delete->execute();

	$_GET['table']='abonne';
	$message.="<div class=\"success\">L\"abonne $_GET[id_abonne] a été supprimé</div>";

}

//-------------------DISPLAY TABLE abonne--------------------//
if (isset($_GET['table']) && $_GET['table'] === 'abonne') {//affichage de tous les abonnees dans un tableau

$abonne=$pdo->prepare("SELECT * FROM abonne");
$abonne->execute();

$content.=$message;
$content.= "<table class=\"table table-hover\">";
$content.= "<caption>Abonne</caption>";
$content.= "<a class=\"table-heading\" href=\"?action=ajout_abonne\">Ajouter an abonne</a>";
$content.= '<thead>';
$content.= "<tr>";

for ($i=0; $i<$abonne->columnCount(); $i++) { //columnCount() nous donne le nombre de colonnes dans une table

	$column=$abonne->getColumnMeta($i);//getColumnMeta() renvoie toutes les infos sur les colonnes d'une table
	$content.= "<th>$column[name]</th>";

}

$content.= "<th>modification</th>";
$content.= "<th>suppression</th>";
$content.= "</tr>";
$content.= '</thead>';
$content.= '<tbody>';

while ($array_abonne=$abonne->fetch(PDO::FETCH_ASSOC)) {

	$content.= "<tr>";

	foreach ($array_abonne as $key => $value) {

	$content.= "<td>$value</td>";

	}

$content.= '<td><a href="?action=modification_abonne&id_abonne='.$array_abonne['id_abonne'].'"><span class="glyphicon glyphicon-pencil"></span></a></td>';
$content.= '<td><a href="?action=suppression_abonne&id_abonne='.$array_abonne['id_abonne'].'"><span class="glyphicon glyphicon-remove"></span></a></td>';
$content.= "</tr>";

}

$content.= '</tbody>';
$content.= "</table>";

}

//-------------------INSERT A SUBSCRIBER INTO DB --------------------//
if(isset($_GET['action']) && $_GET['action']==='ajout_abonne') {//affichage d'un formulaire pour ansertion dans la BD

$content.= '<h3 classe="header">Ajouter un abonne</h3>';
$content.= '<form action="" method="post">';
$content.= '<div class="form-group">';
$content.= '<label for="prenom">Prenom</label>';
$content.= '<input type="text" name="prenom" id="prenom" class="form-control" placeholder="prenom">';
$content.= '</div>';
$content.= '<button type="submit" class="btn btn-default">Ajouter</button>';
$content.= '</form>';

if(isset($message)){

	$content.= $message;

	}

}

//-------------------MODIFY THE TABLE abonne--------------------//
if (isset($_GET['action']) && $_GET['action'] === 'modification_abonne') {//affichage d'un formulaire pour modification de données d'un membre dans la BD


$abonne=$pdo->prepare("SELECT * FROM abonne WHERE id_abonne=$_GET[id_abonne]");
$abonne->execute();
$data=$abonne->fetch(PDO::FETCH_ASSOC);

$content.= '<h3 classe="header">Modifier les données sur un abonne</h3>';
$content.= '<form action="" method="post">';
$content.= '<div class="form-group">';
$content.= '<input type="hidden" name="id_abonne" id="id_abonne" class="form-control" value="'.$_GET['id_abonne'].'">';
$content.= '</div>';
$content.= '<div class="form-group">';
$content.= '<label for="prenom">Prenom</label>';
$content.= '<input type="text" name="prenom" id="prenom" class="form-control" value="'.$data['prenom'].'">';
$content.= '</div>';
$content.= '<button type="submit" class="btn btn-default">Ajouter</button>';
$content.= '</form>';

if(isset($message)){

	$content.= $message;

	}
}

?>