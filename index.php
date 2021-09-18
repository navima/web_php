<!DOCTYPE html>
<html>

<head>
    <script src='main.js'></script>
    <?php

    require_once("sanitize.php");

    function encrypt($in): string
    {
        $out = $in;
        $len = strlen($in);
        $offsets = [5, -14, 31, -9, 3];
        $j = 0;
        for ($i = 0; $i < $len; $i++) {
            $c = $in[$i];
            $out[$i] = chr((ord($c) + $offsets[$j++ % 5]) % 255);
        }
        return $out;
    }

    function decrypt($in): string
    {
        $out = $in;
        $len = strlen($in);
        $offsets = [5, -14, 31, -9, 3];
        $j = 0;
        for ($i = 0; $i < $len; $i++) {
            $c = $in[$i];
            $out[$i] = chr((ord($c) - $offsets[$j++ % 5]) % 255);
        }
        return $out;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $in_username = sanitize($_POST["username"]);
        $password = sanitize($_POST["password"]);

        if (!filter_var($in_username, FILTER_VALIDATE_EMAIL)) {
            echo "<script> alert('Hibás felhasználónév!') </script>";
        } else {
            //do authentication

            $succ = false;

            $f = fopen("password.txt", "r");
            $found = false;
            while (!feof($f)) {
                $line = trim(fgets($f), "\n");

                $parts = explode("*", decrypt($line));
                if ($parts[0] === $in_username) {
                    $found = true;
                    if ($parts[1] === $password) {
                        $succ = true;
                    } else {
                        echo
                        "<script> 
                        alert('Hibás jelszó!'); 
                        sleep(3000).then(()=>{window.location.href='http://www.police.hu'})
                    </script>";
                    }
                    break;
                }
            }
            fclose($f);

            if (!$found) {
                echo "<script> alert('Nincs ilyen felhasználó!') </script>";
            }

            if ($succ) {
                //retrieve color

                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

                $servername = "localhost";
                $username = "root";
                $password = "";


                $conn = new mysqli(
                    $servername,
                    $username,
                    $password,
                    "adatok",
                    3306
                );

                if ($conn->connect_error) {
                    die("Connection failure: "
                        . $conn->connect_error);
                }

                $result;
                $prep = $conn->prepare("SELECT Titkos FROM tabla WHERE Username = ?");
                $prep->bind_param("s", $in_username);
                $prep->execute();
                $prep->bind_result($result);
                $prep->fetch();
                $res_raw = $result;


                // Closing connection
                $conn->close();

                $colors = array(
                    'piros' => 'red',
                    'zold'  => 'green',
                    'sarga' => 'yellow',
                    'kek'   => 'blue',
                    'fekete' => 'black',
                    'feher' => 'white'
                );
                $succ = true;
                $res = $colors[$res_raw];
            }
        }
    }
    ?>


    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Szines</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>

    <style>
        :root {
            <?php
            require_once("sanitize.php");
            if ($succ) {
                echo  "--col:" . $res;
            }
            ?>
        }
    </style>
</head>

<body>

    <div class="main">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
            <label for="username">Username</label>
            <input type="text" name="username" id="username">
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
            <input type="submit" value="Login" name="submit">
        </form>
        <div>
            <div>
                <?php echo isset($res_raw) ? "A kedvenc színed a " . $res_raw : "" ?>
            </div>
            <div id="resbox"></div>
            <div>
                Átjuthatsz a halál hídján?
            </div>
            <div class="picdiv" style="background-image: url(https://static.wikia.nocookie.net/montypython/images/c/c1/Bridge_of_Death_monty_python_and_the_holy_grail_591679_800_4411271399897.jpg);"></div>
            <div><?php echo isset($res_raw) && $res_raw === "kek" ? "Igen" : "Nem" ?></div>
        </div>
    </div>
</body>

</html>