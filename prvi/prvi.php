<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup baze podataka</title>
</head>
<body>
    <?php
    
    $columnName = function ($value) {
        return $value->name;
    };

    
    $dbName = "radovi";
    $dir = "backup/$dbName";

    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777, true)) {
            die("<p>Nije moguće stvoriti direktorij za backup.</p></body></html>");
        }
    }

    $dbc = mysqli_connect("localhost", "root", "", "radovi")
    or die("<p>Nije moguće uspostaviti vezu s bazom podataka radovi.</p></body></html>");


    $r = mysqli_query($dbc, "SHOW TABLES");
    if (mysqli_num_rows($r) > 0) {
        echo "<p>Backup za bazu podataka '$dbName'.</p>";

        while (list($table) = mysqli_fetch_array($r, MYSQLI_NUM)) {
            $q = "SELECT * FROM $table";

            $columns = array_map($columnName, $dbc->query($q)->fetch_fields());

            $r2 = mysqli_query($dbc, $q);

            if (mysqli_num_rows($r2) > 0) {
                
                $fileName = "{$table}_" . time();

                if ($fp = fopen("$dir/$fileName.txt", "w")) {
                    while ($row = mysqli_fetch_array($r2, MYSQLI_NUM)) {
                        $rowText = "INSERT INTO $table (";

                        foreach ($columns as $column) {
                            $rowText .= "$column, ";
                        }
                        $rowText = rtrim($rowText, ", ");
                        $rowText .= ") VALUES (";

                        foreach ($row as $value) {
                            $rowText .= "'$value', ";
                        }
                        $rowText = rtrim($rowText, ", ");
                        $rowText .= ");\n";

                        fwrite($fp, $rowText);
                    }
                    fclose($fp);

                    echo "<p>Tablica '$table' je spremljena u backup.</p>";

                    if ($fp = gzopen("$dir/$fileName.sql.gz", 'w9')) {
                        $content = file_get_contents("$dir/$fileName.txt");
                        gzwrite($fp, $content);
                        unlink("$dir/$fileName.txt");
                        gzclose($fp);

                        echo "<p>Backup za tablicu '$table' je sažet.</p>";
                    } else {
                        echo "<p>Greška prilikom sažimanja backupa za tablicu '$table'.</p>";
                    }
                } else {
                    echo "<p>Datoteka $dir/{$table}_{$time}.txt se ne može otvoriti.</p>";
                    break;
                }
            }
        }
    } else {
        echo "<p>Baza podataka $dbName ne sadrži tablice.</p>";
    }

    mysqli_close($dbc);
    ?>
</body>
</html>
