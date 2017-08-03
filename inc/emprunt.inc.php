<?php 

//-------------------INSERT INTO TABLE emprunt--------------------//
if (!empty($_POST) && isset($_POST['abonne_emprunt'])) {

	foreach ($_POST as $key => $value) {

		$value=htmlspecialchars(addslashes($value));//protection contre injection sql

	}

	if (empty($_POST['date_sortie'])) {//vérification de champ date_sortie 

			$message.="<div class=\"error\">Le champs 'Date Sortie' ne peut pas etre vide</div>";

	} else {

		$date_sortie=date('Y-m-d', strtotime($_POST['date_sortie']));//strtotime -> transforme la 'date_sortie' en nombre de secondes écoulés depuis 1970; date -> transforme ce nombre de secondes en objet type 'date' (seul format reconnu par mysql) de format 'Y-m-d'.
	}
	
//-------------------IF ALL THE FIELDS ARE SUCCESSFULLY CHECKED--------------------//

	if (empty($message)) {
//-------------------INSERT INTO TABLE 'emprunt' -------------------//
		if ($_GET['action']==='ajout_emprunt') {

			if (empty($_POST['date_rendu'])) {//si la date rendu n'est pas renseignée, alors nous n'ajoutons pas le champs 'date_rendu' dans la requete(car sinon la nouvelle version de mysql donne une erreur)
				$update=$pdo->prepare("INSERT INTO emprunt(id_livre,id_abonne,date_sortie) VALUES('$_POST[livre_emprunt]','$_POST[abonne_emprunt]','$date_sortie')");
				$update->execute();
				$message.='<div class="success">L\'emprunt a bien été ajouté à la base de donné</div>';

			} else {//sinon si la date rendu est renseignée, alors ajoutons le champs 'date_rendu' dans la requete

				$date_rendu=date('Y-m-d', strtotime($_POST['date_rendu']));
				$update=$pdo->prepare("INSERT INTO emprunt(id_livre,id_abonne,date_sortie,date_rendu) VALUES('$_POST[livre_emprunt]','$_POST[abonne_emprunt]','$date_sortie','$date_rendu')");
				$update->execute();

				// var_dump($update);
				$message.='<div class="success">L\'emprunt a bien été ajouté à la base de donné</div>';

			}
		} 
//-------------------MODIFY TABLE 'emprunt' IN DATABASE--------------------//		

		if($_GET['action']==='modification_emprunt') {//si action 'midification'...

			$update=$pdo->prepare("UPDATE emprunt SET abonne_emprunt='$_POST[abonne_emprunt]', livre_emprunt='$_POST[livre_emprunt]', WHERE id_livre=$_GET[id_livre]");//alors on fait une requete d'update
			$update->execute();

			$message.='<div class="success">Le livre a bien été mise à jour dans la base de donné</div>';
		}	
	}
}

//-------------------DISPLAY TABLE emprunt--------------------//
if (isset($_GET['table']) && $_GET['table'] === 'emprunt') {//affichage de tous les emprunt sous forme d'un tableau

$emprunt=$pdo->prepare("SELECT * FROM emprunt");
$emprunt->execute();

$content.= '<a href="?action=ajout_emprunt">Ajouter un emprunt</a>';
$content.= "<h3>Emprunt</h3>";
$content.= "<table class=\"table table-hover\">";
$content.= "<tr>";

for ($i=0; $i<$emprunt->columnCount(); $i++) {//columnCount() nous donne le nombre de colonnes dans une table

	$column=$emprunt->getColumnMeta($i);//getColumnMeta() renvoie toutes les infos sur les colonnes d'une table
	$content.= "<th>$column[name]</th>";

}

$content.= "<th>modification</th>";
$content.= "<th>suppression</th>";
$content.="</tr>";

while ($array_emprunt=$emprunt->fetch(PDO::FETCH_ASSOC)) {

	$content.= "<tr>";

	foreach ($array_emprunt as $key => $value) {

		$content.= "<td>$value</td>";

	}

$content.= '<td><a href="?action=modification_emprunt&id_emprunt='.$array_emprunt['id_emprunt'].'"><span class="glyphicon glyphicon-pencil"></span></a></td>';
$content.= '<td><a href="?action=suppression_emprunt&id_emprunt='.$array_emprunt['id_emprunt'].'"><span class="glyphicon glyphicon-remove"></span></a></td>';
$content.= "</tr>";

}

$content.= "</table>";

}

//-------------------DISPLAY FORM - INSERT INTO TABLE 'emprunt'--------------------//
if(isset($_GET['action']) && $_GET['action']==='ajout_emprunt') {//si action 'acjouter un emprunt'...

$abonne=$pdo->prepare("SELECT * FROM abonne");//alors nous recupérons tous les abonnees...
$abonne->execute();

$livre=$pdo->prepare("SELECT * FROM livre");//et les livres pour pouvoir les afficher dans le formulaire par la suite
$livre->execute();

$content.= '<h3 classe="header">Ajouter un emprunt</h3>';
$content.= '<form action="" method="post">';
$content.= '<div class="form-group">';
$content.= '<label for="abonne_emprunt">Abonne</label>';
$content.= '<select name="abonne_emprunt" id="abonne_emprunt" class="form-control">';

while ($prenom=$abonne->fetch(PDO::FETCH_ASSOC)) {

	$content.="<option value=\"$prenom[id_abonne]\">$prenom[prenom]</option>";

}

$content.= '</select>';
$content.= '</div>';
$content.= '<div class="form-group">';
$content.= '<label for="livre_emprunt">Livre</label>';
$content.= '<select name="livre_emprunt" id="livre_emprunt" class="form-control">';

while ($nom_livre=$livre->fetch(PDO::FETCH_ASSOC)) {

	$content.='<option value="'.$nom_livre['id_livre'].'">'.$nom_livre['auteur'].' | '.$nom_livre['titre'].'</option>';

}

$content.= '</select>';
$content.= '</div>';
$content.= '<div class="form-group">';
$content.= '<label for="date_sortie">Date Sortie</label>';
$content.= '<input type="date" name="date_sortie" id="date_sortie" class="form-control">';
$content.= '</div>';
$content.= '<div class="form-group">';
$content.= '<label for="date_rendu">Date Rendu</label>';
$content.= '<input type="date" name="date_rendu" id="date_rendu" class="form-control"">';
$content.= '</div>';
$content.= '<button type="submit" class="btn btn-default">Ajouter</button>';
$content.= '</form>';

if(isset($message)){

	$content.= $message;

	}
}

//-------------------DISPLAY FORM - MODIFY VALUES IN DB--------------------//
if(isset($_GET['action']) && $_GET['action']==='modification_emprunt') {//si action 'modification d'un emprunt

$abonne=$pdo->prepare("SELECT * FROM abonne");
$abonne->execute();

$livre=$pdo->prepare("SELECT * FROM livre");
$livre->execute();

$emprunt=$pdo->prepare("SELECT * FROM emprunt WHERE id_emprunt=$_GET[id_emprunt]");//on recpére l'emprunt selon son id qui nous interesse
$emprunt->execute();
$query_result=$emprunt->fetch(PDO::FETCH_ASSOC);

$content.= '<h2 classe="header">Modifier les données d\'un abonne</h3>';
$content.= '<form action="" method="post">';
$content.= '<input type="hidden" name="id_emprunt" value="'.$_GET['id_emprunt'].'">';
$content.= '<div class="form-group">';
$content.= '<label for="abonne_emprunt">Abonne</label>';
$content.= '<select name="abonne_emprunt" id="abonne_emprunt" class="form-control">';

while ($prenom=$abonne->fetch(PDO::FETCH_ASSOC)) {

	if ($query_result['id_abonne']===$prenom['id_abonne']) {//si id_abonne dans la table 'emprunt' et la table 'abonnee' sont les memes  

		$content.="<option value=\"$prenom[id_abonne]\" selected>$prenom[prenom]</option>";//alors l'option correspondante est attribuée la propriété 'selected'

	} else {

	$content.="<option value=\"$prenom[id_abonne]\">$prenom[prenom]</option>";// sinon pas de 'selected'

	}
}

$content.= '</select>';
$content.= '</div>';
$content.= '<div class="form-group">';
$content.= '<label for="livre_emprunt">Livre</label>';
$content.= '<select name="livre_emprunt" id="livre_emprunt" class="form-control">';

	while ($nom_livre=$livre->fetch(PDO::FETCH_ASSOC)) {

		if ($query_result['id_livre']===$nom_livre['id_livre']) {//si id_livre dans la table 'emprunt' et la table 'livre' sont les memes  

			$content.='<option value="'.$nom_livre['id_livre'].'" selected>'.$nom_livre['auteur'].' | '.$nom_livre['titre'].'</option>';//alors l'option correspondante est attribuée la propriété 'selected'

		} else {

		$content.='<option value="'.$nom_livre['id_livre'].'">'.$nom_livre['auteur'].' | '.$nom_livre['titre'].'</option>';// sinon pas de 'selected'

		}
	}

$content.= '</select>';
$content.= '</div>';
$content.= '<div class="form-group">';
$content.= '<label for="date_sortie">Date Sortie</label>';
$content.= '<input type="date" name="date_sortie" id="date_sortie" class="form-control" value="'.date('d-m-Y', strtotime($query_result['date_sortie'])).'">';
$content.= '</div>';
$content.= '<div class="form-group">';
$content.= '<label for="date_rendu">Date Rendu</label>';
$content.= '<input type="date" name="date_rendu" id="date_rendu" class="form-control" value="'.date('d-m-Y', strtotime($query_result['date_rendu'])).'">';;
$content.= '</div>';
$content.= '<button type="submit" class="btn btn-default">Ajouter</button>';
$content.= '</form>';

if(isset($message)){

	$content.= $message;

	}
}


?>