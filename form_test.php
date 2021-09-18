<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>File upload test</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <script src='main.js'></script>
    <style>
        :root {
            --col: <?php require "sanitize.php";
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        echo isset($_POST["name"]) ? $_POST["color"] : "unset";
                    } else {
                        echo "unset";
                    } ?>
        }
    </style>
</head>

<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
        <label for="name">Name</label>
        <input type="text" name="name" id="name">
        <label for="date_of_birth">Date of birth</label>
        <input type="date" name="date_of_birth" id="date_of_birth">
        <label for="email">Email</label>
        <input type="email" name="email" id="email">
        <label for="ssn">Social Security Number</label>
        <input type="number" name="ssn" id="ssn">
        <label for="color">Favourite colour</label>
        <input type="color" name="color" id="color">
        <input type="submit" value="Login" name="submit">
    </form>
    <div>
        <?php

        require "main.php";
        //require "sanitize.php";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $person = new Person(sanitize($_POST["name"]), sanitize($_POST["date_of_birth"]), sanitize($_POST["email"]), sanitize($_POST["ssn"]));
            $f = fopen("userdata", "a");
            fwrite($f, $person->to_string() . "\n");
            fclose($f);
            echo $person->to_string();
        }
        ?>
    </div>
</body>

</html>