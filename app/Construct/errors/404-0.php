<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Page 404">
    <meta name="author" content="Vadim Islamov">
    <link rel="icon" href="favicon.ico">
    <title>Page 404</title>
    <!-- Bootstrap CDN CSS -->
    <link rel="stylesheet" href="<?= base_url('/library/bootstrap/css/bootstrap.min.css'); ?>">
        <!--href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"-->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        @import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700,900);
        body{font-family:Rubik,sans-serif;margin:0;padding:0;font-weight:300}
        #wrapper{width:100%}
        .error-box{height:100%;width:100%;position:fixed;left:0;top:20%}
        .error-box .footer{width:100%;left:0;right:0}
        .error-body h1{font-size:200px;font-weight:900;line-height:210px}
        .text-404{color:#4169e1} /*#f33155}*/
        .text-error{color:#4169e1} /*#161c24}*/
        .text-muted{color:#8d9ea7}
        .text-back{background-color:#3CB371;color:white}
        .text-back:hover{background-color:#3CB371;color:white}
        .m-b-40{margin-bottom:40px!important}
        .m-t-30{margin-top:30px!important}
        .m-b-30{margin-bottom:30px!important}
        @media only screen and (max-width: 520px){
            .error-body h1{
                font-size:100px;font-weight:700;line-height:110px;
            }
        }
    </style>
</head>
<body>
    <section id="wrapper" class="container-fluid">
        <div class="error-box text-center">
            <div class="error-body">
                <h1 class="text-404">404</h1>
                <h3 class="text-error">Page Not Found !</h3>
                <p class="text-muted m-t-30 m-b-30">СТРАНИЦА, КОТОРУЮ ВЫ ИЩЕТЕ, НЕ СУЩЕСТВУЕТ.</p>
                <a href="/" class="text-back btn btn-rounded m-b-40">Back to home</a> </div>
            <footer class=""><a href="" target="_blank"></a></footer>
        </div>
    </section>
</body>
</html>
