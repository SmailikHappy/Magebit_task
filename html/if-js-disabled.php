<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>

</head>


<body id="app">

    <?php
    define('APPLICATION_BASE', __DIR__ . DIRECTORY_SEPARATOR);
    include APPLICATION_BASE . '../src/db.php';
    include APPLICATION_BASE . '../src/emailvalidcheck.php';
    ?>

    <div class="grouper">
    
        <header> <!--header with logos and navigators-->
            <div class="logos">
                <img src="details/pineapple.svg" alt="logotype" id="logo1" >
                <img src="details/pineapple_text.svg" alt="pineapple." id="logo2">
            </div>
            <nav>
                <a href=#>About</a>
                <a href=#>How it works</a>
                <a href=#>Contact</a>
            </nav>
        </header>

        <section class="hero">

            <?php 
            if ($isValidated) {
                echo ('<img src="details/cup.svg" alt="cup" id="cup_img">');
            }
            ?>
            
            <div class="main_text">
                <h1><?php echo ("$headline")?></h1>
                <p><?php echo ("$paragraph")?></p>
            </div>

            <?php
            if (!$isValidated) {
                ?>
                <!--input / form-->
                <form class="main_input" method="GET" action="if-js-disabled.php">
                    <div class="email_container">
                        <div class="decorative_line"></div> <!--just for blue left "border" of input-->
                        <input id="email" type="text" placeholder="Type your email address here..." name="emailInputPHP" value="<?php echo("$email") ?>">
                        <button class="submit_button">
                            <i class="icon arrow_icon"></i>
                        </button>
                    </div>

                    <div class="error_msg_div">
                        <p class="error_msg"> <?php echo("$error_msg")?> </p>
                    </div>

                    <label class="check_container">
                        <input type="checkbox" name="checkboxPHP" <?php echo($isChecked ? 'checked' : '') ?> >
                        <span class="checkmark"><i class="icon check_icon"></i></span>
                        <p>I agree to <a href=# class="tos_link">terms of service</a></p>
                    </label>
                </form> 

                <?php
            }
            ?>

            <div class="horiz_decor_line"></div> <!--just decor-->

            <footer>  <!--social sites' links-->
                <button class="fb_but">
                    <i class="icon fb_icon"></i>
                </button>
                <button class="inst_but">
                    <i class="icon inst_icon"></i>
                </button>
                <button class="twit_but">
                    <i class="icon twit_icon"></i>
                </button>
                <button class="yt_but">
                    <i class="icon yt_icon"></i>
                </button>
            </footer>
        </section>
    </div>

    <div class="backgr_photo"></div> <!--just for desktop's background photo-->

    <?php mysqli_close($connection); // close connection?>
</body>
</html>