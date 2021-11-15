<?php
session_start();

if (!isset($_GET['day'])) {
    $_SESSION['pagehide'] = "display: none";
    $sgetday = "0";

    $offset = 6 * 60 * 60; //converting 6 hours to seconds.
    $dateFormat = "l";
    $timeNdate = gmdate($dateFormat, time() + $offset);
    if ($timeNdate == "Sunday") {
        $_GET['day'] = "sun";
    }
    if ($timeNdate == "Monday") {
        $_GET['day'] = "mon";
    }
    if ($timeNdate == "Tuesday") {
        $_GET['day'] = "tue";
    }
    if ($timeNdate == "Wednesday") {
        $_GET['day'] = "wed";
    }
    if ($timeNdate == "Thursday") {
        $_GET['day'] = "thu";
    }
    if ($timeNdate == "Friday") {
        $_GET['day'] = "fri";
    }
    if ($timeNdate == "Saturday") {
        $_GET['day'] = "sat";
    }

    $getday = $_GET['day'];
    $_SESSION['getday'] = $getday;
    $sgetday = $_SESSION['getday'];
    $_SESSION['pagehide'] = "display: block";
} else {


    $getday = $_GET['day'];
    $_SESSION['getday'] = $getday;
    $sgetday = $_SESSION['getday'];
    $_SESSION['pagehide'] = "display: block";
}

if ($getday == "sun") {
    $activesun = "liactive";
}
if ($getday == "mon") {
    $activemon = "liactive";
}
if ($getday == "tue") {
    $activetue = "liactive";
}
if ($getday == "wed") {
    $activewed = "liactive";
}
if ($getday == "thu") {
    $activethu = "liactive";
}
if ($getday == "fri") {
    $activefri = "liactive";
}
if ($getday == "sat") {
    $activesat = "liactive";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BURADiO Programme Output</title>
    <link rel="stylesheet" href="../mediaasset/style.css" />
    <style>
        html {
            overflow: auto;
        }

        html,
        body,
        iframe {
            margin: 0px;
            padding: 0px;
            height: 100%;
            border: none;
            background-color: #f1f1f1;
        }

        /* nav menu */
        ul {
            margin: 0;
            padding: 0;
            background-color: #f1f1f1;
            color: #000;
            overflow: hidden;
            font-family: Arial, Helvetica, sans-serif;
        }

        ul li {
            float: left;
            list-style-type: none;
        }

        ul li a {
            padding: 10px 10px;
            display: block;
            text-decoration: none;
            color: #000;
            text-transform: uppercase;
            font-size: 16px;
        }

        li a:hover {
            background-color: rgb(212, 212, 212);
            border-radius: 3px;
        }

        .liactive {
            background-color: #ffffff;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            font-weight: 600;
        }

        #navitem {
            margin: 0 auto;
            max-width: 800px;
            margin-top: 0px;
        }

        iframe {
            display: block;
            width: 100%;
            border: none;
            overflow-y: auto;
            overflow-x: auto;
        }
    </style>
</head>

<body>

    <div id="navitem">
        <ul>
            <li><a href="program.php">Programme</a></li>
            <li><a href="member.php">Member</a></li>
            <li><a class="liactive" href="output.php">Output</a></li>
        </ul>
    </div>

    <div style="height: 50px;background-color:#ffffff;"></div>
    <div style="background-color: #ffffff;">
        <div class="navmenu hide">
            <ul>
                <li><a class="<?php echo $activesun ?>" href="index.php?day=sun">Sunday</a></li>
                <li><a class="<?php echo $activemon ?>" href="index.php?day=mon">Monday</a></li>
                <li><a class="<?php echo $activetue ?>" href="index.php?day=tue">Tuesday</a></li>
                <li><a class="<?php echo $activewed ?>" href="index.php?day=wed">Wednesday</a></li>
                <li><a class="<?php echo $activethu ?>" href="index.php?day=thu">Thursday</a></li>
                <li><a class="<?php echo $activefri ?>" href="index.php?day=fri">Friday</a></li>
                <li><a class="<?php echo $activesat ?>" href="index.php?day=sat">Saturday</a></li>
            </ul>
        </div>
        <div class="navmenu hide2">
            <ul>
                <li><a class="<?php echo $activesun ?>" href="index.php?day=sun">Sun</a></li>
                <li><a class="<?php echo $activemon ?>" href="index.php?day=mon">Mon</a></li>
                <li><a class="<?php echo $activetue ?>" href="index.php?day=tue">Tue</a></li>
                <li><a class="<?php echo $activewed ?>" href="index.php?day=wed">Wed</a></li>
                <li><a class="<?php echo $activethu ?>" href="index.php?day=thu">Thu</a></li>
                <li><a class="<?php echo $activefri ?>" href="index.php?day=fri">Fri</a></li>
                <li><a class="<?php echo $activesat ?>" href="index.php?day=sat">Sat</a></li>
            </ul>
        </div>
        <br />

        <table class="table" style="<?php echo $_SESSION['pagehide']; ?>">
            <tbody>

                <?php
                require('../mediaasset/db.php');
                $sql = "SELECT * FROM program WHERE pday='$sgetday' ORDER BY pserial ASC";

                $result = mysqli_query($db, $sql);
                $count = mysqli_num_rows($result);

                while ($rows = mysqli_fetch_array($result)) {
                    $rj1 = $rows['prj1'];
                    $rj2 = $rows['prj2'];
                    $tel = $rows['ptelecast'];
                    $gra = $rows['pgraphic'];

                    $sqlrj1 = "SELECT * FROM member WHERE radioid = '$rj1'";
                    $resultrj1 = mysqli_query($db, $sqlrj1);
                    $rowrj1 = mysqli_fetch_array($resultrj1, MYSQLI_ASSOC);

                    $sqlrj2 = "SELECT * FROM member WHERE radioid = '$rj2'";
                    $resultrj2 = mysqli_query($db, $sqlrj2);
                    $rowrj2 = mysqli_fetch_array($resultrj2, MYSQLI_ASSOC);

                    $sqltel = "SELECT * FROM member WHERE radioid = '$tel'";
                    $resulttel = mysqli_query($db, $sqltel);
                    $rowtel = mysqli_fetch_array($resulttel, MYSQLI_ASSOC);

                    $sqlgra = "SELECT * FROM member WHERE radioid = '$gra'";
                    $resultgra = mysqli_query($db, $sqlgra);
                    $rowgra = mysqli_fetch_array($resultgra, MYSQLI_ASSOC);
                ?>
                    <tr>
                        <td style="padding-top: 0px;padding-bottom: 0px;padding-left: 0px;"><img src="../mediaasset/covers/<?php echo $rows['pcover'] ?>" width="300" />
                        </td>
                        <td width="200">
                            <a style="font-size: 24px;font-family:bangla;"><strong> <?php echo $rows['pname'] ?></strong></a> <br /><br /> <?php echo $rows['ptime'] ?> <br />
                        </td>
                        <td width="200">
                            <p><strong>RJ:</strong>
                                <a href="<?php echo $rowrj1['profile'] ?>" target="_blank" class="prolink"> <?php echo $rowrj1['name'] ?></a>
                                <p><a href="<?php echo $rowrj2['profile'] ?>" target="_blank" class="prolink"> <?php echo $rowrj2['name'] ?></a></p>
                            </p>
                            <p><strong>Telecast:</strong> <a href="<?php echo $rowtel['profile'] ?>" target="_blank" class="prolink"> <?php echo $rowtel['name'] ?></a></p>
                            <p><strong>Graphics:</strong> <a href="<?php echo $rowgra['profile'] ?>" target="_blank" class="prolink"> <?php echo $rowgra['name'] ?></a></p>
                        </td>
                    </tr>
                <?php
                }
                ?>

            </tbody>
        </table>


        <br /><br />

    </div>

</body>

</html>