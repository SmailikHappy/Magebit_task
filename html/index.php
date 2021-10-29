<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href = "css/style.css">
    <title>Document</title>
    <style>
        [v-cloak] {
            display: none;
        }
    </style>
    <noscript>  <!--if js disabled, runs another url file-->
        <meta http-equiv="refresh"content="0; url=if-js-disabled.php">
    </noscript>
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

        <section class = "hero" id = <?php echo("$error_code") ?>> <!--main information with main input-->

            <img src="details/cup.svg" alt="cup" id="cup_img" v-if="cupIsVisible" v-cloak>

            <div class="main_text">
                <h1 v-cloak>{{ headline }}</h1>
                <p v-cloak>{{ paragraph }}</p>
            </div>

            <!--input / form-->
            <form class="main_input" v-if="formIsVisible" action="<?php $_SERVER['PHP_SELF'] ?>" method="GET" novalidate="true">
                <div class="email_container">
                    <div class="decorative_line"></div> <!--just for blue left "border" of input-->
                    <input id="email" type="email" placeholder="Type your email address here..." v-model="emailInput" name="emailInputPHP">
                    <button class="submit_button" @click="submitButtonPressed">
                        <i class="icon arrow_icon"></i>
                    </button>
                </div>

                <div class="error_msg_div">
                    <p v-cloak class="error_msg">{{ error_msg }}</p> 
                </div>

                <label class="check_container">
                    <input type="checkbox" v-model="tos_checked" name="checkboxPHP">
                    <span class="checkmark"><i class="icon check_icon"></i></span>
                    <p>I agree to <a href=# class="tos_link">terms of service</a></p>
                </label>
            </form>

            <div class = "horiz_decor_line"></div> <!--just decor-->

            <footer>    <!--social sites' links-->
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

    <div class = "backgr_photo"></div> <!--just for desktop's background photo-->

    <script src = "https://unpkg.com/vue@next"></script> <!--connect Vue js-->
    <script> // Vue js script

        const { createApp, ref, computed } = Vue;

        const app = createApp({
            data() {
                return { // variables
                    emailInput: '',
                    tos_checked: false,
                    error_msg: '',
                    cupIsVisible: false,
                    formIsVisible: true,
                    submitIsEnabled: false,
                    validError: <?php echo("$valid_error")?>, //took from php email validation
                    success: <?php echo("$succeeded")?>, //took from php email validation
                    headline: 'Subscribe to newsletter',
                    paragraph: 'Subscribe to our newsletter and get 10% discount on pineapple glasses.',
                }
            },
            methods: {
                validateEmail(email) { // vue js email validation check
                    const emailRules = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    return emailRules.test(email);
                },

                checkOnSuccess() { // check a variable from php
                    if (this.success) {
                        this.headline = 'Thanks for subscribing!';
                        this.paragraph = 'You have successfully subscribed to our email listing. Check your email for the discount code.';
                        this.cupIsVisible = true;
                        this.formIsVisible = false;
                    }
                },

                submitButtonPressed(e) { // when button pressed:
                    if (this.emailInput.length === 0){
                        this.error_msg = 'Email address is required'
                        console.log('No email')
                        e.preventDefault();
                    }
                    else if (!this.validateEmail(this.emailInput) || this.validError === true) {
                        this.error_msg = 'Please provide a valid e-mail address'
                        console.log('E-mail is invalid')
                        e.preventDefault();
                    }
                    else if (this.emailInput.slice(-3) === '.co'){
                        this.error_msg = 'We are not accepting subscriptions from Colombia emails'
                        console.log('Colombian subscriptions are not allowed')
                        e.preventDefault();
                    } 
                    else if (!this.tos_checked){
                        this.error_msg = 'You must accept the terms and conditions'
                        console.log('You must accept terms of service conditions')
                        e.preventDefault();
                    }
                }
            },

            beforeMount(){ // before the loading of the site script checks php variable
                this.checkOnSuccess()
            },
        });

        app.mount('#app')

    </script>

    <?php
    mysqli_close($connection); // close db connection
    ?>
</body>
</html>