<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>開始</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>


<body style="width:50%;margin:5% auto;">
    <h1 class="head">第一步</h1>
    <hr>
    <br>
    <div class="content" >
        <form action="asset/prog/web_setting.php" method="post">
            <label>
                選擇您的網站:
                 <select name="name" required>
                     <option value="-1">請選擇</option>
                    <?php
                        require_once('asset/prog/db_conf.php');

                        foreach($db->query('select Conf_Name,Conf_Id from tb_config ') as $row){
                            print('<option value="'.$row['Conf_Name'].'">'.$row['Conf_Name'].'</option>');
                        }
                    ?>
                </select>
            </label>
            <br>
            <br>
            <br>
            <label>帳號:<input name="ac" required></label>
            <br>
            <br>
            <br>
            <label>密碼:<input type="password" name="pwd" required></label>
            <br>
            <br>
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
