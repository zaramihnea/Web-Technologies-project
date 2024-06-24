<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
function getID($username) {
    $mysql = connectToDatabase();
    $username = $mysql->real_escape_string($username);

    $query = "SELECT user_id FROM admins WHERE username = ?";
    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection($stmt, $mysql);
        return false;
    }
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        closeConnection($stmt, $mysql);
        return $row['admin_id'];
    } else {
        closeConnection($stmt, $mysql);
        return false;
    }
}

function checkAdminCredentials($username, $password) {
    $mysql = connectToDatabase();
    $username = $mysql->real_escape_string($username);
    $password = $mysql->real_escape_string($password);

    $query = "SELECT * FROM admins WHERE (username = ? AND password = ?)";
    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection($stmt, $mysql);
        return false;
    }
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        if(filter_var($username, FILTER_VALIDATE_EMAIL)){
            $username = getUsernameFromEmail($username);
            if (!$username) {
                closeConnection($stmt, $mysql);
                return false;
            }
        }
        $_SESSION['username'] = $username;
        $_SESSION['id'] = getID($username);
        closeConnection($stmt, $mysql);
        return true;
    } else {
        closeConnection($stmt, $mysql);
        return false;
    }
}

function getPreferences() {
    $mysql = connectToDatabase();
    $query = "SELECT name FROM Preferences";
    
    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection(null, $mysql);
        return false;
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $names = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $names[] = $row['name'];
        }
    }
    
    closeConnection($stmt, $mysql);
    return $names;
}

function getPreferenceUsage() {
    $mysql = connectToDatabase();
    $query = "SELECT p.name, COUNT(up.preference_id) AS count
              FROM preferences p
              LEFT JOIN user_preferences up ON p.id = up.preference_id
              GROUP BY p.name
              ORDER BY p.name";

    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection(null, $mysql);
        return false;
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $preferencesUsage = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $preferencesUsage[$row['name']] = $row['count'];
        }
    }

    closeConnection($stmt, $mysql);
    return $preferencesUsage;
}

function getUsers() {
    $mysql = connectToDatabase();
    $query = "SELECT user_id, username, email, fname, lname, age, gender FROM users";

    $stmt = $mysql->prepare($query);
    if (!$stmt) {
        closeConnection(null, $mysql);
        return false;
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $users[] = [
                'user_id' => $row['user_id'],
                'username' => $row['username'],
                'email' => $row['email'],
                'fname' => $row['fname'],
                'lname' => $row['lname'],
                'age' => $row['age'],
                'gender' => $row['gender']
            ];
        }
    }

    closeConnection($stmt, $mysql);
    return $users;
}

function deleteUser($userId) {
    $mysql = connectToDatabase();
    $userId = $mysql->real_escape_string($userId);

    $mysql->begin_transaction();
    try {
        // Delete from user_preferences
        $stmt = $mysql->prepare("DELETE FROM user_preferences WHERE user_id = ?");
        if (!$stmt) {
            throw new Exception($mysql->error);
        }
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();

        // Delete from Users
        $stmt = $mysql->prepare("DELETE FROM users WHERE user_id = ?");
        if (!$stmt) {
            throw new Exception($mysql->error);
        }
        $stmt->bind_param("i", $userId);  
        $stmt->execute();
        $stmt->close();

        $mysql->commit();
    } catch (mysqli_sql_exception $exception) {
        $mysql->rollback();
        throw $exception;
    }

    closeConnection(null, $mysql);
}

function exportTableToCSV($table) {
    $mysql = connectToDatabase();
    $query = "SELECT * FROM $table";

    $result = $mysql->query($query);

    if ($result->num_rows > 0) {
        $delimiter = ",";
        $filename = $table . "_" . date('Y-m-d') . ".csv";

        // Set headers to download file rather than displayed
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');

        // Create a file pointer connected to the output stream
        $f = fopen('php://output', 'w');

        // Set column headers
        $fields = $result->fetch_fields();
        $headers = [];
        foreach ($fields as $field) {
            $headers[] = $field->name;
        }
        fputcsv($f, $headers, $delimiter);

        // Output each row of the data, format line as CSV and write to file pointer
        while ($row = $result->fetch_assoc()) {
            $lineData = [];
            foreach ($headers as $header) {
                $lineData[] = $row[$header];
            }
            fputcsv($f, $lineData, $delimiter);
        }

        // Close file pointer
        fclose($f);
    } else {
        echo "No records found.";
    }

    $mysql->close();
    exit;
}

function exportDBToCSV() {
    exportTableToCSV('admins');
    exportTableToCSV('Category');
    exportTableToCSV('Incredient');
    exportTableToCSV('NutritionFact');
    exportTableToCSV('Preferences');
    exportTableToCSV('Product');
    exportTableToCSV('ProductCategories');
    exportTableToCSV('ProductIncredients');
    exportTableToCSV('ProductNutritionFacts');
    exportTableToCSV('ProductStores');
    exportTableToCSV('Store');
    exportTableToCSV('users');
    exportTableToCSV('user_preferences');
    exportTableToCSV('user_shopping_list');
}

function exportTableToPDF($table) {
    $mysql = connectToDatabase();
    $query = "SELECT * FROM $table";
    $result = $mysql->query($query);

    if ($result->num_rows > 0) {
        $pdfContent = "%PDF-1.4\n";
        $pdfContent .= "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
        $pdfContent .= "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 /MediaBox [0 0 612 792] >>\nendobj\n";
        $pdfContent .= "3 0 obj\n<< /Type /Page /Parent 2 0 R /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>\nendobj\n";
        $pdfContent .= "4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";

        $content = "BT /F1 12 Tf 70 700 Td (Table: $table) Tj T* ";
        $content .= "BT /F1 12 Tf 70 680 Td (";
        
        // Set column headers
        $fields = $result->fetch_fields();
        foreach ($fields as $field) {
            $content .= $field->name . " ";
        }
        $content .= ") Tj T* ";
        
        // Output each row of the data
        $y = 660;
        while ($row = $result->fetch_assoc()) {
            $content .= "BT /F1 12 Tf 70 $y Td (";
            foreach ($fields as $field) {
                $content .= $row[$field->name] . " ";
            }
            $content .= ") Tj T* ";
            $y -= 20;
        }
        
        $content = str_replace(array("\r", "\n"), '', $content);
        $content = $content . "ET";

        $pdfContent .= "5 0 obj\n<< /Length " . strlen($content) . " >>\nstream\n" . $content . "\nendstream\nendobj\n";
        $pdfContent .= "xref\n0 6\n0000000000 65535 f \n0000000010 00000 n \n0000000077 00000 n \n0000000178 00000 n \n0000000277 00000 n \n0000000376 00000 n \n";
        $pdfContent .= "trailer\n<< /Root 1 0 R /Size 6 >>\nstartxref\n" . (strlen($pdfContent) - 9) . "\n%%EOF";

        $filename = $table . "_" . date('Y-m-d') . ".pdf";

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        echo $pdfContent;
    } else {
        echo "No records found.";
    }

    $mysql->close();
    exit;
}

function exportStatsToCSV($names, $counter, $userCount) {
    $delimiter = ",";
    $filename = "stats_" . date('Y-m-d') . ".csv";

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');

    $f = fopen('php://output', 'w');

    $headers = array_merge(['No. of Users'], $names);
    fputcsv($f, $headers, $delimiter);

    $data = array_merge([$userCount], $counter);
    fputcsv($f, $data, $delimiter);

    fclose($f);
    exit;
}

function exportStatsToPDF($names, $counter, $userCount) {
    $content = "<h1>Stats</h1>";
    $content .= "<table border='1'>";
    $content .= "<tr><th>No. of Users</th>";
    foreach ($names as $header) {
        $content .= "<th>" . htmlspecialchars($header) . "</th>";
    }
    $content .= "</tr><tr>";
    $content .= "<td>" . htmlspecialchars($userCount) . "</td>";
    foreach ($counter as $info) {
        $content .= "<td>" . htmlspecialchars($info) . "</td>";
    }
    $content .= "</tr></table>";

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="stats_' . date('Y-m-d') . '.pdf"');

    echo "<html><body>" . $content . "</body></html>";
    exit;
}

function exportDatabaseToSQL() {
    $mysql = connectToDatabase();
    $tables = array();
    $result = $mysql->query("SHOW TABLES");

    while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
    }

    $sqlScript = "";
    foreach ($tables as $table) {
        $result = $mysql->query("SELECT * FROM $table");
        $numFields = $result->field_count;

        $row2 = $mysql->query("SHOW CREATE TABLE $table")->fetch_row();
        $sqlScript .= "\n\n" . $row2[1] . ";\n\n";

        for ($i = 0; $i < $numFields; $i++) {
            while ($row = $result->fetch_row()) {
                $sqlScript .= "INSERT INTO $table VALUES(";
                for ($j = 0; $j < $numFields; $j++) {
                    $row[$j] = $row[$j] ? addslashes($row[$j]) : "NULL";
                    $row[$j] = str_replace("\n", "\\n", $row[$j]);
                    $sqlScript .= '"' . $row[$j] . '"';
                    if ($j < ($numFields - 1)) {
                        $sqlScript .= ',';
                    }
                }
                $sqlScript .= ");\n";
            }
        }
        $sqlScript .= "\n\n\n";
    }

    $mysql->close();

    $backupFile = 'db-backup-' . time() . '.sql';
    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename=' . $backupFile);
    echo $sqlScript;
    exit;
}

function closeConnection($stmt, $mysql) {
    if ($stmt && $stmt instanceof mysqli_stmt) {
        $stmt->close();
    }
    if ($mysql && $mysql instanceof mysqli) {
        $mysql->close();
    }
}
?>
