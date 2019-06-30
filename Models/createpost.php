<?php
    session_start();
    require_once "../config/connect.php";
    include "../Class/Db.class.php";
    include "../Class/Form.class.php";
    include "../Class/User.class.php";
    if(isset($_POST['hidden_data']) && !empty($_POST['hidden_data']) && !empty($_POST['img']))
    {
        $upload_dir = "upload/";
        $img = $_POST['hidden_data'];
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $name = mktime();
        $file = $upload_dir . $name . ".png";
        file_put_contents($file, $data);
        if($_POST['img'])
        {
            switch($_POST['img'])
            {
                case "hacker":
                    $obj = "hacker";
                    break;
                case "mail":
                    $obj = "mail";
                    break;
                case "code":
                    $obj = "code";
                    break;
                case "1337":
                    $obj = "1337";
                    break;
                case "basma":
                    $obj = "basma";
                    break;
            }
            $dest = imagecreatefrompng($file);
            $src = imagecreatefrompng("../Assets/obj/$obj.png");
            $srcTransparency = 100;
            list($srcWidth, $srcHeight) = getimagesize("../Assets/obj/$obj.png");
        
            $src_xPosition =  0;
            $src_yPosition = 30;
            $src_cropXposition = 100;
            $src_cropYposition = 0;
            imagecolortransparent($src,imagecolorat($src,0,0));
            imagecopymerge($dest,$src,$src_xPosition,$src_yPosition,$src_cropXposition,$src_cropYposition,$srcWidth,$srcHeight,$srcTransparency);
            imagejpeg($dest,$file,100);
            $arr = [
                "user_id" => $_SESSION["user_id"],
                "username"=> $_SESSION["username"],
                "image" => $name.'.png',
                "likes" => 0
            ];
            $t = new Database;
            if($t->insert_to_db("posts",$arr) == 1)
            {
                echo "ues";
            }
        }
       
    }
    
?>