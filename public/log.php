<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
        <link rel="stylesheet" href="/vendor/nova/app.css">
        <style media="screen">
            table{margin: auto; }
        </style>
    </head>
    <body>
        <table class="table mt-3">
            <thead>
                <th>数据</th>
                <th>结果</th>
            </thead>
            <tbody>
                <?php include("./storage/import/".$_GET['p'].".html");?>
            </tbody>
        </table>
        
    </body>
</html>
