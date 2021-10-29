<?php 
$isValidated = false;
$email = $_GET['emailInputPHP'];
$isChecked = $_GET['checkboxPHP'];
$headline = 'Subscribe to newsletter';
$paragraph = 'Subscribe to our newsletter and get 10% discount on pineapple glasses.';
$valid_error = 'false'; // for js enabled
$succeeded = 'false'; //for js enabled

if ($email !== NULL) {
    if ($email === ''){
        $error_msg = 'Email address is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = 'Please provide a valid e-mail address';
        $valid_error = 'true'; // for js enabled
    } elseif (substr($email, -3) === '.co') {
        $error_msg = 'We are not accepting subscriptions from Colombia emails';
    } elseif (!$isChecked) {
        $error_msg = 'You must accept the terms and conditions';
    } else {
        include('submission.php');
        $succeeded = 'true'; // for js enabled
        $headline = 'Thanks for subscribing!';
        $paragraph = 'You have successfully subscribed to our email listing. Check your email for the discount code.';
        $isValidated = true;
    }
}
$email = filter_var($email, FILTER_SANITIZE_EMAIL);
?>