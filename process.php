<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
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
	'Lab6'   // baza de date
	);

// verificam daca am reusit
if (mysqli_connect_errno()) {
	die ('Conexiunea a esuat...');
}

// formulam o interogare si o executam  
if (!($rez = $mysql->query ("SELECT password from accounts where username = '$username'"))) {
	echo 'Cont inexistent';
} else {
    $inreg = $rez->fetch_assoc();
    if ($inreg['password'] == $password) {
        echo 'Autentificare reusita';
    } else {
        echo 'Autentificare esuata';
    }
}


// inchidem conexiunea cu serverul MySQL
$mysql->close();
}
?>
