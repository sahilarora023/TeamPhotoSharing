<?php
    require_once "file_db.php";

    // initiate db connection
    FileDB::init();
    $records = FileDb::get_all_pictures();
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Rapid start">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">

        <title>Team Photo Sharing - CMPE 207</title>

        <link href='http://fonts.googleapis.com/css?family=Scada:400,400italic,700,700italic&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/demo.css">
        <link rel="stylesheet" href="css/jquery.galereya.css">
        <!--[if lt IE 9]>
        <link rel="stylesheet" href="css/jquery.galereya.ie.css">
        <![endif]-->
    </head>
    <body>
        <div id="gal1">
        <?php 

            while ($row = mysql_fetch_array($records, MYSQL_ASSOC)) {
                echo '
                    <img src="'.$row["file_path"].'"
                        alt="'.$row["title"].'"
                        title="'.$row["title"].' By '.$row["from"].'"
                        data-desc="'.$row["desc"].'"
                        data-category="'.$row["category"].'"
                        data-fullsrc="'.$row["file_path"].'"
                    />
                ';
            }
        ?>
        </div>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/jquery-1.9.1.min.js"><\/script>')</script>
        <script src="js/jquery.galereya.min.js"></script>
        <script>
            $(function() {
                $('#gal1').galereya();
            });
        </script>
        <!-- Google Analytics -->
        <script>
            var _gaq=[['_setAccount','UA-40540506-1'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
                g.src='//www.google-analytics.com/ga.js';
                s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>
