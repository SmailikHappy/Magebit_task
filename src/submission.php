<?php
    $email_name = mb_substr($email, 0, stripos($email, '@'));
    $email_domain = mb_substr($email, stripos($email, '@') + 1, NULL);

    $result = mysqli_query($connection, "SELECT * FROM `domains` WHERE `domain_name` = '$email_domain'");

    if (mysqli_fetch_assoc($result) == false) {
        mysqli_query($connection, "INSERT INTO `domains` (`domain_name`) VALUES ('$email_domain')");
    }   

    $result = mysqli_query($connection, "SELECT `domain_id` FROM `domains` WHERE `domain_name` = '$email_domain'");
    $domain_id = mysqli_fetch_assoc($result)['domain_id'];

    mysqli_query($connection, "INSERT INTO `emails` (`user_name`, `email_domain_id`, `email_domain`) VALUES ('$email_name', $domain_id, '$email_domain')");
?>
