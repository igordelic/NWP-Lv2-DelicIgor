<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="treci.css">
    <title>Treci</title>
</head>
<body>
<?php
    $xml = simplexml_load_file("LV2.xml");

    function valueOrEmpty($value) {
        if (isset($value)) {
            return $value;
        } else {
            return "";
        }
    }

    $content = "
    <div class='container'>
        <div class='main-body'>
            <div class='row gutters-sm'>";
    
    foreach ($xml->record as $person) {
        $id = valueOrEmpty($person->id);
        $firstName = valueOrEmpty($person->ime);
        $lastName = valueOrEmpty($person->prezime);
        $email = valueOrEmpty($person->email);
        $sex = valueOrEmpty($person->spol);
        $image = valueOrEmpty($person->slika);
        $bio = valueOrEmpty($person->zivotopis);

        $content .=
                "<div class='col-md-4 mb-3'>
                     <div class='card'>
                        <div class='card-body'>
                            <div class='d-flex flex-column align-items-center text-center'>
                                <img src=$image alt='Profile image' class='rounded-circle' width='150'>
                                <div class='mt-3'>
                                    <h4>$firstName $lastName</h4>
                                    <p class='text-secondary mb-1'>$sex</p>
                                    <p class='text-muted font-size-sm'>$email</p>
                                    <p class='text-muted font-size-sm'>$bio</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>";
    }

    $content .=
            "</div>
        </div>
    </div>";

    echo $content;
?>
</body>
</html>