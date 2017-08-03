<?php 

//-------------------INSERT INTO TABLE abonne--------------------//
if (!empty($_POST) && isset($_POST['auteur'])) {

	foreach ($_POST as $key => $value) {//protection contre injection sql

		$value=htmlspecialchars(addslashes($value));

	}

	//Vérification des champs de formulaire
	if (!preg_match('/^[a-zA-Z-_.]+$/', $_POST['auteur'])) {

		$message.='<div class="error">Le auteur ne peut contenir que des lettres et des charactères spéciaux comme: -_.</div>';

	}

	if (!preg_match('/^[a-zA-Z-_.]+$/', $_POST['titre'])) {

		$message.='<div class="error">Le titre ne peut contenir que des lettres et des charactères spéciaux comme: -_.</div>';

	}

	foreach ($_POST as $key => $value) {//nous vérifiyons qu'un champs ne peut pas etre vide

		if (empty($value)) {

			$message.="<div class=\"error\">Le champs '$key' ne peut pas etre vide</div>";

		}
	}

	if (empty($message)) {//si les données du formulaire sont validés on affectue une requete d'insertion ou d'update en fonction d'action

		if ($_GET['action']==='ajout_livre') {

			$update=$pdo->prepare("INSERT INTO livre(auteur,titre) VALUES('$_POST[auteur]','$_POST[titre]')");
			$update->execute();
			// var_dump($update);
			$message.='<div class="success">Le livre a bien été ajouté à la base de donné</div>';

		} elseif($_GET['action']==='modification_livre') {

			$update=$pdo->prepare("UPDATE livre SET auteur='$_POST[auteur]',titre='$_POST[titre]' WHERE id_livre=$_GET[id_livre]");
			$update->execute();

			$message.='<div class="success">Le livre a bien été ajouté mise à jour dans la base de donné</div>';

		}	
	}
}

//-------------------DELETE FROM TABLE abonne--------------------//
if (isset($_GET['action']) && $_GET['action']==='suppression_livre') {//si l'action 'suppression' -> on effectue une requete de suppression

	$delete=$pdo->prepare("DELETE FROM livre WHERE id_livre=$_GET[id_livre]");
	$delete->execute();

	$_GET['table']='livre';
	$message.="<div class=\"success\">Le livre $_GET[id_livre] a été supprimé</div>";

}

//-------------------DISPLAY TABLE livre--------------------//
if (isset($_GET['table']) && $_GET['table'] === 'livre') {//si action 'livre' -> on affecute une requete de select pour afficher tous les livres de la BD dans un tableau

$livre=$pdo->prepare("SELECT * FROM livre");
$livre->execute();

$content.= '<a href="?action=ajout_livre">Ajouter un livre</a>';
$content.=$message;
$content.= "<h3>Livre</h3>";
$content.= "<table class=\"table table-hover\">";
$content.= "<tr>";

for ($i=0; $i<$livre->columnCount(); $i++) {//columnCount() nous donne le nombre de colonnes dans une table

	$column=$livre->getColumnMeta($i);//getColumnMeta() renvoie toutes les infos sur les colonnes d'une table

	$content.= "<th>$column[name]</th>";

}

$content.= "<th>modification</th>";
$content.= "<th>suppression</th>";
$content.= "</tr>";

while ($array_livre=$livre->fetch(PDO::FETCH_ASSOC)) {

	$content.= "<tr>";

	foreach ($array_livre as $key => $value) {

		$content.= "<td>$value</td>";

	}

$content.= '<td><a href="?action=modification_livre&id_livre='.$array_livre['id_livre'].'"><span class="glyphicon glyphicon-pencil"></span></a></td>';
$content.= '<td><a href="?action=suppression_livre&id_livre='.$array_livre['id_livre'].'"><span class="glyphicon glyphicon-remove"></span></a></td>';
$content.= "</tr>";

}

$content.= "</table>";

}

//-------------------DISPLAY FORM THAT INSERTS IN DB A BOOK--------------------//
if(isset($_GET['action']) && $_GET['action']==='ajout_livre') {//affichage d'un formulaire d'insertion d'un nouveau livre

$content.= '<h3>Ajouter un livre</h3>';
$content.= '<form action="" method="post">';
$content.= '<div class="form-group">';
$content.= '<label for="auteur">Auteur</label>';
$content.= '<input type="text" name="auteur" id="auteur" class="form-control" placeholder="auteur">';
$content.= '</div>';
$content.= '<div class="form-group">';
$content.= '<label for="titre">Titre</label>';
$content.= '<input type="text" name="titre" id="titre" class="form-control" placeholder="titre">';
$content.= '</div>';
$content.= '<button type="submit" class="btn btn-default">Ajouter</button>';
$content.= '</form>';

if(isset($message)){

	$content.= $message;

	}
}

//-------------------DISPLAY FORM TO MODIFY THE TABLE abonne--------------------//
if (isset($_GET['action']) && $_GET['action'] === 'modification_livre') {//si action 'modification d'un livre'...

$livre=$pdo->prepare("SELECT * FROM livre WHERE id_livre=$_GET[id_livre]");//alors on fait une requete de select d'un livre de la BD selon id du livre qui nous intéresse
$livre->execute();
$data=$livre->fetch(PDO::FETCH_ASSOC);

//affichage des données du livre sous form d'un formulaire pour pouvoir les modifier
$content.= '<h3 classe="header">Modifier les données d\'un livre</h3>';
$content.= '<form action="" method="post">';
$content.= '<div class="form-group">';
$content.= '<input type="hidden" name="id_livre" id="id_livre" class="form-control" value="'.$_GET['id_livre'].'">';
$content.= '</div>';
$content.= '<div class="form-group">';
$content.= '<label for="auteur">Auteur</label>';
$content.= '<input type="text" name="auteur" id="auteur" class="form-control" value="'.$data['auteur'].'">';
$content.= '</div>';
$content.= '<div class="form-group">';
$content.= '<label for="titre">Titre</label>';
$content.= '<input type="text" name="titre" id="titre" class="form-control" value="'.$data['titre'].'">';
$content.= '</div>';
$content.= '<button type="submit" class="btn btn-default">Ajouter</button>';
$content.= '</form>';

if(isset($message)){

	$content.= $message;
	
	}
}

?>