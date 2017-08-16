<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>開始</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<body>
    <div class="head">第一步</div>
    <div class="content">
        <form action="asset/prog/web_setting.php" method="post">
            <select name="name">
                <?php
                    require_once('asset/prog/db_conf.php');
                    
                    foreach($db->query('select Conf_Name,Conf_Id from tb_config ') as $row){
                        print('<option value="'.$row['Conf_Name'].'">'.$row['Conf_Name'].'</option>');
                    }
                ?>
            </select>
            <input name="ac">
            <br>
            <br>
            <input name="pwd">
            <br>
            <br>
            <button>管理網站</button>
        </form>
        <hr>
        <br>
        <br>
        <hr>
        <br>
        <form action="asset/prog/step1.php">
            <button>新建網站</button>
        </form>
    </div>
    <div class="footer"></div>
</body>

</html>
