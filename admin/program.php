<?php
session_start();
?>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

<?php

if (isset($_GET['program'])) {
    $prmid = $_GET['program'];
}

header('Cache-control: no-cache, must-revalidate, max-age=0');

require('../mediaasset/db.php');

$_SESSION['prmshow'] = "display: none";

if (isset($_POST['covercng'])) {

    $check = getimagesize($_FILES["pic"]["tmp_name"]);
    if ($check !== false) {

        $file = uniqid() . time() . rand(1000, 100000);
        $file_type = pathinfo($_FILES['pic']['name'], PATHINFO_EXTENSION);
        $save_file = $file . "." . $file_type;
        $file_loc = $_FILES['pic']['tmp_name'];
        $save = "../mediaasset/covers/" . $save_file;

        // make file name in lower case
        $lowerchr = strtolower($file);
        // make file name in lower case

        $new_file_name = $lowerchr . "." . $file_type;

        $final_file = str_replace(' ', '-', $new_file_name);


        // Compress image
        function compressIMG($source, $destination, $quality)
        {

            $info = getimagesize($source);

            if ($info['mime'] == 'image/jpeg')
                $image = imagecreatefromjpeg($source);

            elseif ($info['mime'] == 'image/png')
                $image = imagecreatefrompng($source);

            elseif ($info['mime'] == 'image/gif')
                $image = imagecreatefromgif($source);

            imagejpeg($image, $destination, $quality);

            return $destination;
        }


        if (compressIMG($file_loc, $save, 60)) {
            $sqlcvr = "SELECT * FROM program WHERE prmid = '$prmid'";
            $resultcvr = mysqli_query($db, $sqlcvr);
            $countcvr = mysqli_num_rows($resultcvr);
            $rowcvr = mysqli_fetch_array($resultcvr);

            if ($countcvr = 1) {

                if ($rowcvr['pcover'] != "default.jpg") {
                    unlink("../mediaasset/covers/" . $rowcvr['pcover']);
                }

                $inputcvr = "UPDATE program SET pcover='$final_file' WHERE prmid='$prmid'";
                mysqli_query($db, $inputcvr);

                echo "<script type='text/javascript'> document.location = 'program.php?program=$prmid'; </script>";
                exit;
            } else {
            }
        }
    } else {
    }
}

if (isset($_POST['upprm'])) {

    $sqlprm = "SELECT * FROM program WHERE prmid = '$prmid'";
    $resultprm = mysqli_query($db, $sqlprm) or die(mysqli_error($db));
    $countprm = mysqli_num_rows($resultprm);
    $rowprm = mysqli_fetch_array($resultprm);

    if ($countprm = 1) {
        // Insert Data
        $inputprm = "UPDATE program SET pname='$_POST[pname]', ptime='$_POST[ptime]', pday='$_POST[pday]', prj1='$_POST[prj1]', prj2='$_POST[prj2]', pgraphic='$_POST[pgraphic]', ptelecast='$_POST[ptelecast]', pserial='$_POST[pserial]' WHERE prmid='$prmid'";

        if (mysqli_query($db, $inputprm)) {
            echo "<script type='text/javascript'> document.location = 'program.php?program=$prmid'; </script>";
            exit;
        } else {
        }
    }
}

if (isset($_POST['delprm'])) {
    $sqldel = "SELECT * FROM program WHERE prmid = '$prmid'";
    $resultdel = mysqli_query($db, $sqldel) or die(mysqli_error($db));
    $rowdel = mysqli_fetch_array($resultdel);

    if ($rowdel['pcover'] != "default.jpg") {
        unlink("../mediaasset/covers/" . $rowdel['pcover']);
    }
    $delprmqury = "DELETE FROM program WHERE prmid = '$prmid'";
    mysqli_query($db, $delprmqury);
} else {
}

if (isset($_POST['addprm'])) {

    $memid = rand(100, 10000);
    $sqlmemid = "SELECT radioid FROM member WHERE radioid = '$memid'";
    $resultmemid = mysqli_query($db, $sqlmemid);
    $rowmem = mysqli_fetch_array($resultmemid, MYSQLI_ASSOC);

    if ($memid == $rowmem['radioid']) {
        $again = rand(1000, 1000000);
        $memid = $again;

        if ($memid == $rowmem['radioid']) {
            header("member.php");
        }
    }

    $addmemqury = "INSERT INTO program (prmid, pname, ptime, pday, pcover, prj1, prj2, pgraphic, ptelecast, pserial) VALUES ('$memid', '', '', '', 'default.jpg', '', '', '', '','')";
    if (mysqli_query($db, $addmemqury)) {
        echo "<script type='text/javascript'> document.location = 'program.php?program=$memid'; </script>";
    }
} else {
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BURADiO Progrmme</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="bodycontainer">
        <!-- body container to centralize elements-->

        <div id="navitem">
            <ul>
                <li><a class="liactive" href="program.php">Programme</a></li>
                <li><a href="member.php">Member</a></li>
                <li><a href="index.php">Output</a></li>
            </ul>
        </div>
        <div id="containbody">
            <div id="quizbody">

                <form action="" method="post">
                    <button type="submit" name="addprm" class="quizbutton" style="float: right;padding: 5px 17px;">Add a Programme</button>
                </form>

                <br /><br /><br />
                <div style="text-align: center;">
                    <form action="" method="get">
                        <select name="program" style="width: 350px;" onchange="javascript:this.form.submit()">
                            <option value="0" hidden>Programme List</option>
                            <?php
                            $sqlprmslt = "SELECT * FROM program ORDER BY id ASC";
                            $resultprmslt = mysqli_query($db, $sqlprmslt);
                            while ($rowprmslt = mysqli_fetch_array($resultprmslt, MYSQLI_ASSOC)) {
                            ?>
                                <option value="<?php echo $rowprmslt['prmid'] ?>"><?php echo $rowprmslt['pname'] ?></option>
                            <?php
                            }
                            ?>
                        </select>

                    </form>

                </div>
                <br />

                <?php
                if (isset($_GET['program'])) {
                    $_SESSION['prmshow'] = "display: block";

                    $sqlprmdtl = "SELECT * FROM program WHERE prmid = '$_GET[program]'";
                    $resultprmdtl = mysqli_query($db, $sqlprmdtl);
                    while ($rowprmdtl = mysqli_fetch_array($resultprmdtl, MYSQLI_ASSOC)) {

                ?>
                        <div class="editcontainer" style="<?php echo $_SESSION['prmshow']; ?>">

                            <div style="border: 1px solid #ddd;overflow: auto;width: 100%;border-radius: 5px;padding: 2px;">
                                <div style="max-width: 450px;margin-top: -16px;">
                                    <div style="max-width: 200px;width: 100%;float:left">
                                        <img style="border-radius: 5px;" src="../mediaasset/covers/<?php echo $rowprmdtl['pcover'] ?>" width="230" />
                                    </div>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="fileup fileup2">
                                            <div style="max-width: 200px;width: 100%;float:right;margin-left: 30px;">
                                                <label>Select New Cover:</label>
                                                <input style="padding: 3px;" type="file" name="pic" id="pic" required />
                                                <br /><br />
                                                <button style="float: left;width: 300px;margin-bottom: 10px;" type="submit" name="covercng">UPDATE COVER</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <form action="" method="post" enctype="form-data">
                                <div style="max-width: 300px;">
                                    <label>Programme Name:</label>
                                    <input type="text" name="pname" autocomplete="off" value="<?php echo $rowprmdtl['pname'] ?>">
                                </div>
                                <div style="max-width: 420px;margin-top: 5px;">
                                    <div style="max-width: 200px;width: 100%;float:left">
                                        <label>Time:</label>
                                        <input type="text" name="ptime" autocomplete="off" value="<?php echo $rowprmdtl['ptime'] ?>">
                                    </div>
                                    <div class="sltleft">
                                        <div style="max-width: 200px;width: 100%;float:right;margin-top: -3px;">
                                            <?php
                                            if ($rowprmdtl['pday'] == "sun") {
                                                $fullday = "Sunday";
                                            }
                                            if ($rowprmdtl['pday'] == "mon") {
                                                $fullday = "Monday";
                                            }
                                            if ($rowprmdtl['pday'] == "tue") {
                                                $fullday = "Tuesday";
                                            }
                                            if ($rowprmdtl['pday'] == "wed") {
                                                $fullday = "Wednesday";
                                            }
                                            if ($rowprmdtl['pday'] == "thu") {
                                                $fullday = "Thursday";
                                            }
                                            if ($rowprmdtl['pday'] == "fri") {
                                                $fullday = "Friday";
                                            }
                                            if ($rowprmdtl['pday'] == "sat") {
                                                $fullday = "Saturday";
                                            }
                                            if ($rowprmdtl['pday'] == "") {
                                                $fullday = "";
                                            }
                                            ?>
                                            <label>Day:</label>
                                            <select name="pday">
                                                <option value="<?php echo $rowprmdtl['pday'] ?>" hidden selected><a><?php echo $fullday ?></a></option>
                                                <option value="sun"><b>Sunday</b></option>
                                                <option value="mon">Monday</option>
                                                <option value="tue">Tuesday</option>
                                                <option value="wed">Wednesday</option>
                                                <option value="thu">Thursday</option>
                                                <option value="fri">Friday</option>
                                                <option value="sat">Saturday</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div style="max-width: 200px;width: 100%;float:left;margin-top: 0px;">
                                    <label>Schedule Serial:</label>
                                    <select name="pserial">
                                        <option value="<?php echo $rowprmdtl['pserial'] ?>" hidden selected><a><?php echo $rowprmdtl['pserial'] ?></a></option>
                                        <option value="1"><b>1</b></option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                                <div style="max-width: 420px;margin-top: 5px;clear:both;">
                                    <?php
                                    $sqlprmrj1nam = "SELECT * FROM member WHERE radioid = '$rowprmdtl[prj1]'";
                                    $resultprmrj1nam = mysqli_query($db, $sqlprmrj1nam);
                                    $rowprmrj1nam = mysqli_fetch_array($resultprmrj1nam, MYSQLI_ASSOC);
                                    ?>
                                    <div style="max-width: 200px;width: 100%;float:left">
                                        <label>RJ 1:</label>
                                        <select name="prj1">
                                            <option value="<?php echo $rowprmrj1nam['radioid'] ?>" hidden selected><?php echo $rowprmrj1nam['name'] ?></option>
                                            <?php
                                            $sqlprmrj1 = "SELECT * FROM member WHERE position = 'RJ' ORDER BY id ASC";
                                            $resultprmrj1 = mysqli_query($db, $sqlprmrj1);
                                            while ($rowprmrj1 = mysqli_fetch_array($resultprmrj1, MYSQLI_ASSOC)) {
                                            ?>
                                                <option value="<?php echo $rowprmrj1['radioid'] ?>"><?php echo $rowprmrj1['name'] ?></option>
                                            <?php
                                            }
                                            ?>
                                            <option value="">*NO RJ*</option>
                                        </select>
                                    </div>
                                    <div class="sltleft">
                                        <?php
                                        $sqlprmrj2nam = "SELECT * FROM member WHERE radioid = '$rowprmdtl[prj2]'";
                                        $resultprmrj2nam = mysqli_query($db, $sqlprmrj2nam);
                                        $rowprmrj2nam = mysqli_fetch_array($resultprmrj2nam, MYSQLI_ASSOC);
                                        ?>
                                        <div style="max-width: 200px;width: 100%;float:right;margin-top: 0px;">
                                            <label>RJ 2:</label>
                                            <select name="prj2">
                                                <option value="<?php echo $rowprmrj2nam['radioid'] ?>" hidden selected><?php echo $rowprmrj2nam['name'] ?></option>
                                                <?php
                                                $sqlprmrj2 = "SELECT * FROM member WHERE position = 'RJ' ORDER BY id ASC";
                                                $resultprmrj2 = mysqli_query($db, $sqlprmrj2);
                                                while ($rowprmrj2 = mysqli_fetch_array($resultprmrj2, MYSQLI_ASSOC)) {
                                                ?>
                                                    <option value="<?php echo $rowprmrj2['radioid'] ?>"><?php echo $rowprmrj2['name'] ?></option>
                                                <?php
                                                }
                                                ?>
                                                <option value="">*NO RJ*</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div style="max-width: 250px;width:100%;margin-top: 5px;">
                                    <?php
                                    $sqlprmtelnam = "SELECT * FROM member WHERE radioid = '$rowprmdtl[ptelecast]'";
                                    $resultprmtelnam = mysqli_query($db, $sqlprmtelnam);
                                    $rowprmtelnam = mysqli_fetch_array($resultprmtelnam, MYSQLI_ASSOC);
                                    ?>
                                    <label>Telecast:</label>
                                    <select name="ptelecast">
                                        <option value="<?php echo $rowprmtelnam['radioid'] ?>" hidden selected><?php echo $rowprmtelnam['name'] ?></option>
                                        <?php
                                        $sqlprmtel = "SELECT * FROM member WHERE position = 'Telecast' ORDER BY id ASC";
                                        $resultprmtel = mysqli_query($db, $sqlprmtel);
                                        while ($rowprmtel = mysqli_fetch_array($resultprmtel, MYSQLI_ASSOC)) {
                                        ?>
                                            <option value="<?php echo $rowprmtel['radioid'] ?>"><?php echo $rowprmtel['name'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div style="max-width: 250px;width:100%;margin-top: 5px;">
                                    <?php
                                    $sqlprmgrpnam = "SELECT * FROM member WHERE radioid = '$rowprmdtl[pgraphic]'";
                                    $resultprmgrpnam = mysqli_query($db, $sqlprmgrpnam);
                                    $rowprmgrpnam = mysqli_fetch_array($resultprmgrpnam, MYSQLI_ASSOC);
                                    ?>
                                    <label>Graphic:</label>
                                    <select name="pgraphic">
                                        <option value="<?php echo $rowprmgrpnam['radioid'] ?>" hidden selected><?php echo $rowprmgrpnam['name'] ?></option>
                                        <?php
                                        $sqlprmgra = "SELECT * FROM member WHERE position = 'Graphics' ORDER BY id ASC";
                                        $resultprmgra = mysqli_query($db, $sqlprmgra);
                                        while ($rowprmgra = mysqli_fetch_array($resultprmgra, MYSQLI_ASSOC)) {
                                        ?>
                                            <option value="<?php echo $rowprmgra['radioid'] ?>"><?php echo $rowprmgra['name'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div style="float: left; margin-top: 50px;">
                                    <button style="margin-top: 10px;" value="" type="submit" name="upprm">UPDATE</button>
                                    <button style="margin-top: 5px;background-color: red;border: 1px solid red;margin-left: 30px" value="" type="submit" name="delprm">DELETE</button>
                                </div>
                            </form>
                        </div>

            </div>
        </div>
<?php
                    }
                }
?>

    </div>

</body>

</html>