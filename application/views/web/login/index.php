<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>uuJiang : Home</title>
    <link rel="icon" type="image/icon" href="<?php echo base_url('images/tabicon.ico')?>">

    <link rel="stylesheet" type="text/css" href="">
    <link href="<?php echo base_url('css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('css/bootstrap-theme.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('css/font-awesome.min.css')?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,700,700i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Crimson+Text:400,700,700i|Josefin+Sans:700" rel="stylesheet">
    <link href="<?php echo base_url('css/main.css')?>" rel="stylesheet">
    <link rel="icon" href="<?php echo base_url('images/logo.png')?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">

</head>

<body>
<div id="index">                                           <!-- Index starts here -->
    <div class="container main">
        <div class="row home">
            <div id = "index_left" class="col-md-6 left">
                <img class="img-responsive img-rabbit" src="<?php echo base_url('images/home.jpg')?>">
            </div>
            <div id = "index_right" class="col-md-6 text-center right">
                <div class="logo">
                    <img src="<?php echo base_url('images/logo.png')?>"><br><br>
                    <h4>I am uuJiang</h4><br><br>
                </div>

                <p><h4>Hi, I am uuJiang, designer of this feeds.</h4></p>
                <p><h4>I designed its architecture and most of its logic.</h4></p>
                <p><h4>I really love what I do.</h4></p><br><br>

                <div class="btn-group-vertical custom_btn animated slideinright">
                    <div id="about" class="btn btn-rabbit">Login</div>
                    <div id="work" class="btn btn-rabbit">Register</div>
                    <div id="contact" class="btn btn-rabbit">Retrieve</div>
                </div>
            </div>
        </div>


    </div>
</div>                                                      <!-- index ends here -->

<div id="about_scroll" class="pages ">                      <!-- Login strats here  -->
    <div class="container main">
        <div class="row">
            <div class="col-md-6 left" id="about_left">
                <img class="img-responsive img-rabbit" src="<?php echo base_url('images/about.jpg')?>">
            </div>

            <div class="col-md-6 right" id="about_right">
                <br><br><br>
                <a href="#index" class="btn btn-rabbit back"> <i class="fa fa-angle-left" aria-hidden="true"></i> Back to Index </a>
                <br>
                <div id="watermark">
                    <h2 class="page-title" text-center>Login</h2>
                    <div class="marker">L</div>
                </div>
                <br>
                <!-- form -->
                <form class="form_edit" name="login" action="">
                    <div class="form-group">
                        <input type="text" class="form-control" id="login_account" name="account" placeholder="Please enter your account .">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" id="login_password" name="password" placeholder="Please enter your password .">
                    </div>
                    <br>
                    <button type="button" class="btn btn-rabbit submit">Join</button>
                </form>
            </div>
        </div>
    </div>
</div>                                                                <!-- Login ends here -->

<div id="work_scroll" class="pages">                                  <!-- Register starts here -->
    <div class="container main">
        <div class="row">
            <div class="col-md-6" id="work_left">
                <div id="owl-demo" class="owl-carousel owl-theme">
                    <div class="item"><img class="img-responsive img-rabbit" src="<?php echo base_url('images/work.jpg')?>"></div>
                </div>
            </div>

            <div class="col-md-6" id="work_right">
                <br><br><br>
                <a href="#index" class="btn btn-rabbit back"> <i class="fa fa-angle-left" aria-hidden="true"></i> Back to Index </a>
                <br>
                <div id="watermark">
                    <h2 class="page-title" text-center>Register</h2>
                    <div class="marker">R</div>
                </div>
                <br>
                <!-- form -->
                <form class="form_edit" name="register" action="">
                    <div class="form-group">
                        <input type="text" class="form-control" id="register_account" placeholder="Please enter your account .">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" id="register_password" placeholder="Please enter your password .">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" id="register_repassword" placeholder="Please enter your password again.">
                    </div>
                    <br>
                    <button type="submit" class="btn btn-rabbit submit">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>                                                                 <!-- Register ends here  -->


<div id="contact_scroll" class="pages">                             <!-- Retrieve starts here -->
    <div class="container main">
        <div class="row">
            <div class="col-md-6 left" id="contact_left">
                <img class="img-responsive img-rabbit" src="<?php echo base_url('images/contact.jpg')?>">
            </div>

            <div class="col-md-6 right" id="contact_right">
                <br><br><br>
                <a href="#index" class="btn btn-rabbit back"> <i class="fa fa-angle-left" aria-hidden="true"></i> Back to Index </a>
                <br>
                <div id="watermark">
                    <h2 class="page-title" text-center>Retrieve</h2>
                    <div class="marker">R</div>
                </div>
                <br>
                <!-- form -->
                <form class="form_edit" name="retrieve" action="">
                    <div class="form-group">
                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Please enter your phone number . ">
                    </div>
                    <br><br>
                    <button type="submit" class="btn btn-rabbit submit">Join</button>
                </form>
            </div>
        </div>
    </div>

    <footer class="text-center">
        <div class="container bottom">
            <div class="row">
                <div class="col-sm-12">
                    <p>Made with <i class="fa fa-heartbeat" aria-hidden="true"></i> by <a href="#">Themewagon</a> More Templates <a href="http://www.cssmoban.com/" target="_blank" title="模板之家">模板之家</a> - Collect from <a href="http://www.cssmoban.com/" title="网页模板" target="_blank">网页模板</a></p>
                </div>
            </div>
        </div>
    </footer>

</div>                                                              <!-- Retrieve ends here -->

<script src="<?php echo base_url('js/jquery-3.1.0.min.js')?>"></script>
<script src="<?php echo base_url('js/bootstrap.min.js')?>">"></script>
<script src="<?php echo base_url('js/script.js')?>">"></script>
</body>
</html>