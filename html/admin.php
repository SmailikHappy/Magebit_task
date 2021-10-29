<?php
define('APPLICATION_BASE', __DIR__ . DIRECTORY_SEPARATOR);
include APPLICATION_BASE . '../src/db.php';

$domain_array_got = $_GET['domain'];
$gg = 'its not gg';

// DELETE from database
if ($_GET['action'] === 'delete' && $_GET['checkboxes']) {
    foreach ($_GET['checkboxes'] as $ID) {
        if ($_GET['checkboxes'][0] === $ID) {
            $IDsTODELETE = '`id` = ' . $ID;
        } else {
            $IDsTODELETE = $IDsTODELETE . ' OR `id` = ' . $ID;
        }
    }
    $result_query = mysqli_query($connection, "SELECT `email_domain_id` FROM `emails` WHERE $IDsTODELETE"); // gets all domains, which users will be removed
    mysqli_query($connection, "DELETE FROM `emails` WHERE $IDsTODELETE");

    while ($result = mysqli_fetch_assoc($result_query)['email_domain_id']) {
        $query = mysqli_query($connection, "SELECT `id` FROM `emails` WHERE `email_domain_id` = $result");
        if (!mysqli_fetch_assoc($query)) {
            mysqli_query($connection, "DELETE FROM `domains` WHERE `domain_id` = $result");
        }
    }
}

// read selected domains
if ($domain_array_got) {
    foreach ($domain_array_got as $domain_got){
        if ($domain_array_got[0] === $domain_got) {
            $DOMAINFILTER = 'WHERE `domain_name` = \'' . $domain_got . '\'';
        } else {
            $DOMAINFILTER = $DOMAINFILTER . ' or `domain_name` = \'' . $domain_got . '\'';
        }
    }

    $domain_id_list_query = mysqli_query($connection, "SELECT `domain_id` FROM `domains` $DOMAINFILTER");
    $domain_id = mysqli_fetch_assoc($domain_id_list_query)['domain_id'];
    $i = 0;

    // making WHERE filter for sql query
    while ($domain_id) {
        if ($i === 0) {
            $DOMAINIDFILTER = 'WHERE `email_domain_id` = ' . $domain_id;
        } else {
            $DOMAINIDFILTER = $DOMAINIDFILTER . ' or `email_domain_id` = ' . $domain_id;
        }
        $domain_id = mysqli_fetch_assoc($domain_id_list_query)['domain_id'];
        $i++;
    }
} else {
    $DOMAINIDFILTER = ''; // or not
}

// making ORDER BY function for sql query
if ($_GET['sort'] === 'sortbyname') {
    $ORDER = 'ORDER BY `user_name`';
} elseif ($_GET['sort'] === 'sortbydate') {
    $ORDER = 'ORDER BY `sub_date`';
}

// making LIMIT <number>, <number> for sql query
if ($_GET['page']) {
    $PAGE = 10*($_GET['page'] - 1) . ',' . 10*($_GET['page']);
} else {
    $PAGE = '0, 10';
}

// main sql query
$email_list_table = mysqli_query($connection, "SELECT `id`, `user_name`, `email_domain`, `sub_date` FROM `emails` $DOMAINIDFILTER $ORDER LIMIT $PAGE");

// CSV file generating and downloading
if ($_GET['action'] === 'export' && $_GET['checkboxes']) {

    echo("User ID, E-mail, Subscribtion date\n\n");

    // if ids macth, they are displayed

    while ($email_table_row = mysqli_fetch_assoc($email_list_table)) {

        if(in_array($email_table_row['id'], $_GET['checkboxes'])) {

            $r = $email_table_row['id'];
            echo("$r, ");

            $r = $email_table_row['user_name'];
            $r2 = $email_table_row['email_domain'];
            echo("$r@$r2, ");

            $r = $email_table_row['sub_date'];
            echo("$r,\n");
        }
    }
    header('Content-Description: File Transfer');
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="db.csv');
    header('Expires: 0');
    header('Cache-Control: no-cache, must-revalidate');
    flush(); // Flush system output buffer
    readfile('email-list.csv');
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body id="app">

    <!--check if array with ['checkboxes'] is empty-->
    <?php if(($_GET['action'] === 'export' || $_GET['action'] === 'delete') && !$_GET['checkboxes']) { ?>
        <script>
            alert("You have to choose at least one element!");
        </script>
    <?php } ?>
    <h3>Subscription control panel</h3>
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="GET">

        <table>
            <tr class = "headline">
                <td>Check</td>
                <td>User ID</td>
                <td>User e-mail</td>
                <td>User subscription date</td>
            </tr>

            <?php

            // reading and ilustarting result from main sql query

            $email_table_row = mysqli_fetch_assoc($email_list_table);

            if (!$email_table_row) {echo("<p style='color:red;'>This page is empty</p>");}

            while ($email_table_row) { ?>
                <tr>
                    <td><input type="checkbox" value="<?php $r = $email_table_row['id'];echo("$r")?>" name="checkboxes[]"></td>    <!--Checkbox-->
                    <td> <?php $r = $email_table_row['id'];          echo("$r") ?> </td>                                        <!--User ID-->
                    <td> <?php $r = $email_table_row['user_name'];
                            $r2 = $email_table_row['email_domain'];                                                             // User Email
                            echo("$r@$r2") ?>                                      </td>     
                    <td> <?php $r = $email_table_row['sub_date'];    echo("$r") ?> </td>                                        <!--Subscription date and time-->
                    <td></td>

                </tr>
            <?php 
                $email_table_row = mysqli_fetch_assoc($email_list_table);
            }
            ?>

        </table>

        <!--export and delete buttons-->
        <div class="export delete">
            <input type="submit" value="export" name="action">
            <input type="submit" value="delete" name="action" @click="deletePressed">
        </div>

        <!--generating domain list with checkboxes (already checked or not)-->
        <div class="domain_list">
            <br>
            <?php
            $result = mysqli_query($connection, "SELECT `domain_name` FROM `domains`");

            // getting array with selected domain ids
            if ($domain_array_got) {
                $domain_id_list_query = mysqli_query($connection, "SELECT `domain_name` FROM `domains` $DOMAINFILTER");
                $selected_domain_array = array();

                while ($selected_domain = mysqli_fetch_assoc($domain_id_list_query)) {
                    array_push($selected_domain_array, $selected_domain['domain_name']);
                }
            }

            // checkboxes are checked if ids match
            while ($domain_array = mysqli_fetch_assoc($result)) {
                $domain = $domain_array['domain_name'];
                
                if ($domain_array_got) {
                    if (in_array($domain, $selected_domain_array)) {
                        echo("<label><input type='checkbox' value='$domain' name='domain[]' checked>$domain   </label>");
                    } else {
                        echo("<label><input type='checkbox' value='$domain' name='domain[]'>$domain   </label>");
                    }
                } else {
                    echo("<label><input type='checkbox' value='$domain' name='domain[]'>$domain   </label>");
                }
            }
            ?>
        </div>
        <br>
        <!--sorting buttons-->
        <div class="basic_sort">
            <input type="radio" value="sortbydate" name='sort' <?php
                if ($_GET['sort']) {
                    if ($_GET['sort'] === 'sortbydate') {
                        echo("checked");
                    }
                } else {
                    echo("checked");
                }
            ?>>
            <label for="action1">Sort by date</label>
            <input type="radio" value="sortbyname" name='sort' <?php 
                if ($_GET['sort'] === 'sortbyname') {
                    echo("checked");
                }
            ?>>
            <label for="action2">Sort by name</label>
        </div>
        <br>
        <div>
            Page: <input type="number" name='page' min="1" value="<?php //set upping a value for page inpur
                if ($_GET['page']) {
                    $page = $_GET['page'];
                } else {
                    $page = 1;
                }
                echo("$page");
            ?>">
        </div>
        <!--sort button, just for page reload-->
        <div>
            <input type="submit" value="Sort/Reload">
        </div>
    </form>

    <!--Vue js linking-->
    <script src = "https://unpkg.com/vue@next"></script>
    <!--Vue js script-->
    <script>
    const { createApp, ref, computed } = Vue;
    const app = createApp({
        data() {},
        methods: {
            deletePressed(e) { 
                if (!confirm("Are you sure you want to delete??")){
                    e.preventDefault();
    }}}});
    app.mount('#app')
    </script>

    <?php mysqli_close($connection); //closing connection?>
</body>
</html>