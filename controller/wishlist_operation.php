<?php
require_once '../page_fragment/define.php';
include ('../page_fragment/dbConnect.php');
include ('../page_fragment/dbGeneral.php');
include ('../page_fragment/njGeneral.php');
include ('../page_fragment/njFile.php');
$dbConObj = new dbConnect();
$dbComObj = new dbGeneral();
$njGenObj = new njGeneral();
$conn = $dbConObj->dbConnect();
$njFileObj = new njFile();

$mode = "";
$requestData = array();
if(isset($_POST['mode'])) {
    $mode = ($_POST['mode']);
    unset($_POST['mode']);
    $requestData = $_POST;
} elseif(isset($_GET['mode'])) {
    $mode = ($_GET['mode']);
    unset($_GET['mode']);
    $requestData = $_GET;
}
if($_SESSION){
   if(isset($_SESSION['user_id']))
        $login_id=$_SESSION['user_id'];
    if(isset($_SESSION['DH_seller_id'])) {
     $login_id=$_SESSION['DH_seller_id'];
      $loginSeller=$_SESSION['DH_seller_type_name'];
    }
}
$table = "wishlist";

if ($mode == "addWishlist") {
    
   // print_r($_POST);
     extract($_POST);
         $condition = " `user_id` = '".$login_id."' and product_id= '".$product_id."'";
        $result = $dbComObj->viewData($conn,$table,"*",$condition);
        $num = $dbComObj->num_rows($result);
        if ($num == 0) 
        {
            $data = array();
            $data['user_id'] = $login_id;
            $data['product_id'] = $product_id;
            $data['ip_address'] = $_SERVER['REMOTE_ADDR'];
            $data['added_on'] = date("Y-m-d H:i:s");
 
            $dbComObj->addData($conn,$table,$data);
        
          //  echo "Added to Wishlist successfully.";
              echo '1';
        } else {
                echo '0';
           // echo "Products is already in your wishlist.";
        }
   
} 

if($mode == 'removeWishlist')
{
    print_r($_POST);
    $res= $dbComObj->deleteData($conn,$table,"`id` = '".$_POST['id']."'");
    if($res){   echo '1';
        // echo "Remove wishlist product successfully.";
        }
   
    else  {  echo '1';
        // "Not Removed  please try again later";
    //}
}
}
if($mode == 'removeWishlistUSR')
{
 //   print_r($_POST);
    $rConditon="`product_id` = '".$_POST['id']."' and `user_id` = '".$login_id."'";
    $res= $dbComObj->deleteData($conn,$table,$rConditon);
    if($res){   echo '1';
        // echo "Remove wishlist product successfully.";
        }
   
    else  {  echo '1';
        // "Not Removed  please try again later";
    //}
}
}


