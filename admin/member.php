<?php
session_start();
?>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

<?php
header('Cache-control: no-cache, must-revalidate, max-age=0');

require('../mediaasset/db.php');

if (isset($_POST['upmem'])) {
    $getid = $_POST['upmem'];
    $sltqury = "UPDATE member SET name = '$_POST[namem]', profile = '$_POST[linkm]', position = '$_POST[position]'  WHERE id = '$getid'";
    mysqli_query($db, $sltqury);
} else {
}

if (isset($_POST['addmem'])) {

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

    $addmemqury = "INSERT INTO member (name, radioid, profile, position) VALUES ('', '$memid', '', '')";
    mysqli_query($db, $addmemqury);
} else {
}

if (isset($_POST['delmem'])) {
    $delmemqury = "DELETE FROM member WHERE id = '$_POST[delmem]'";
    mysqli_query($db, $delmemqury);
} else {
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BURADiO Members</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="bodycontainer">
        <!-- body container to centralize elements-->

        <div id="navitem">
            <ul>
                <li><a href="program.php">Programme</a></li>
                <li><a class="liactive" href="member.php">Member</a></li>
                <li><a href="index.php">Output</a></li>
            </ul>
        </div>
        <div id="containbody">
            <div id="quizbody">

                <form action="" method="post">
                    <button type="submit" name="addmem" class="quizbutton" style="float: right;padding: 5px 25px;">Add a Member</button>
                </form>

                <br /><br /><br />
                <table>
                    <tbody>

                        <?php
                        $sqlm = "SELECT * FROM member ORDER BY id DESC";
                        $resultm = mysqli_query($db, $sqlm);
                        $countm = mysqli_num_rows($resultm);
                        while ($rowm = mysqli_fetch_array($resultm, MYSQLI_ASSOC)) {
                        ?>
                            <form action="" method="post">
                                <tr>
                                    <td>
                                        <label>Name:</label>
                                        <input type="text" name="namem" autocomplete="off" value="<?php echo $rowm['name'] ?>">
                                    </td>
                                    <td>
                                        <label>Social Link:</label>
                                        <textarea type="text" name="linkm"><?php echo $rowm['profile'] ?></textarea>
                                    </td>
                                    <td>
                                        <label>Designation:</label>
                                        <select name="position">
                                            <option value="<?php echo $rowm['position'] ?>" hidden selected><?php echo $rowm['position'] ?></option>
                                            <option value="RJ"><b>RJ</b></option>
                                            <option value="Graphics">Graphics</option>
                                            <option value="Telecast">Telecast</option>
                                        </select>
                                    </td>
                                    <td style="width: 150px;">
                                        <button style="margin-top: 10px;" value="<?php echo $rowm['id'] ?>" type="submit" name="upmem">UPDATE</button>
                                        <button style="margin-top: 5px;background-color: red;border: 1px solid red;" value="<?php echo $rowm['id'] ?>" type="submit" name="delmem">DELETE</button>
                                    </td>
                                </tr>
                            </form>

                        <?php
                        }
                        ?>


                    </tbody>
                </table>

            </div>
        </div>

    </div>

</body>

</html>