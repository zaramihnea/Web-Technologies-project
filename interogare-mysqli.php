<?php
/* Un program PHP5 ilustrand maniera de interogare 
	a serverului MySQL via extensia mysqli
     
 	Autor: Sabin-Corneliu Buraga - https://profs.info.uaic.ro/~busaco/
	2007, 2008, 2011, 2015, 2017
   
     Ultima actualizare: 14 martie 2017
*/    

// instantiem obiectul MySQL, via constructorul care va realiza   
// conectarea la serverul MySQL
$mysql = new mysqli (
	'localhost', // locatia serverului (aici, masina locala)
	'root',       // numele de cont
	'',    // parola (atentie, in clar!)
	'students'   // baza de date
	);

// verificam daca am reusit
if (mysqli_connect_errno()) {
	die ('Conexiunea a esuat...');
}

// formulam o interogare si o executam  
if (!($rez = $mysql->query ('select name, year from students'))) {
	die ('A survenit o eroare la interogare');
}

// generam o lista numerotata cu informatii despre studenti
echo ('<ol>');
// preluam inregistrarile gasite   
while ($inreg = $rez->fetch_assoc()) {
	echo ('<li>Studentul ' . $inreg['name'] . 
		' este in anul ' . $inreg['year'] . '</li>');
}
echo ('</ol>');

// inchidem conexiunea cu serverul MySQL
$mysql->close();
?>
