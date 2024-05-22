<?php

//database connection
function connectToDatabase() {
    $mysql = new mysqli(
        'localhost', // locatia serverului (aici, masina locala)
        'root',      // numele de cont
        '',          // parola (atentie, in clar!)
        'culinary_users'   // baza de date
    );
    if (mysqli_connect_errno()) {
        die ('Conexiunea a esuat...');
    }
    return $mysql;
}

//for login and signup


?>