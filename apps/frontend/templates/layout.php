<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>sfSesame</title>
        <meta name="description" content="">
        <meta name="author" content="Rober Martín H">

        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <?php include_title() ?>
        <link rel="shortcut icon" href="/favicon.ico" />
        <?php echo stylesheet_tag('bootstrap/css/bootstrap.min.css'); ?>
        <?php echo stylesheet_tag('main.css'); ?>
    </head>

    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="brand" href="#">sfSesame</a>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="content">
<?php echo $sf_content ?>
            </div>

            <footer>
                <p>Footer info</p>
            </footer>
        </div>
    </body>
</html>
