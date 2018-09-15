<?php
require_once '../page_fragment/define.php';
include ('../page_fragment/dbConnect.php');
include ('../page_fragment/dbGeneral.php');
include ('../page_fragment/njGeneral.php');
include ('../page_fragment/njFile.php');
include ('../page_fragment/njEncription.php');
$dbConObj = new dbConnect();
$dbComObj = new dbGeneral();
$njGenObj = new njGeneral();
$njFileObj = new njFile();
$njEncryptionObj = new njEncryption();
$conn = $dbConObj->dbConnect();

$mode = "";
$requestData = array();
if(isset($_POST['mode'])) {
    $mode = base64_decode($_POST['mode']);
    unset($_POST['mode']);
    $requestData = $_POST;
} elseif(isset($_GET['mode'])) {
    $mode = base64_decode($_GET['mode']);
    unset($_GET['mode']);
    $requestData = $_GET;
}

$time = time() + (86400 * 30);
switch($mode)
{
/* Manage Add to cart */
    case 'setCart':
        $cartItem = array();
        $jsonCartItem = "";
        $product_data_arr= array_merge($_POST['productData'],$_POST['product']);
        if (isset($_COOKIE['cartItems']) && !empty($_COOKIE['cartItems'])) {
            $preCartItems = $_COOKIE['cartItems'];
            $preCartItems1 = stripslashes($preCartItems);
            $savedPreCartItems = json_decode($preCartItems1, true);
            $cartItem = $savedPreCartItems;
            if (!isset($savedPreCartItems[$_POST['productId']]) || empty($savedPreCartItems[$_POST['productId']])) {
                $cartItem[$_POST['productId']] = array("productData" => $product_data_arr);
                $jsonCartItem = json_encode($cartItem);
                setcookie('cartItems', $jsonCartItem,$time, '/');
                echo 'Added Successfully 3';
            } else {
                $array1 = $cartItem[$_POST['productId']];
                $array = $array1['productData'];
                $array['qty'] += $_POST['productData']['qty'];
                $cartItem[$_POST['productId']] = array("productData" => $array);
                $jsonCartItem = json_encode($cartItem);
                setcookie('cartItems', $jsonCartItem,$time,'/');
                echo 'Added Successfully 2';
            }
            //print_r($_COOKIE['cartItems']);
        } else {
            $cartItem[$_POST['productId']] = array("productData" => $product_data_arr);
            $jsonCartItem = json_encode($cartItem);
            setcookie('cartItems', $jsonCartItem,$time, '/');
            //print_r($_COOKIE['cartItems']);
            echo 'Added Successfully 1';
        }
       
        //echo 'Nj';
    break;    
    
    case 'setCartSingle':
        
        $cartItem = array();
        $jsonCartItem = "";
        if (isset($_COOKIE['cartItems']) && !empty($_COOKIE['cartItems'])) {
            $preCartItems = $_COOKIE['cartItems'];
            $preCartItems1 = stripslashes($preCartItems);
           // print_r(array_merge($a1,$a2));
            $savedPreCartItems = json_decode($preCartItems1, true);
            $cartItem = $savedPreCartItems;
            $product_data_arr= array_merge($_POST['productData'],$_POST['product']);
            if (!isset($savedPreCartItems[$_POST['productId']]) || empty($savedPreCartItems[$_POST['productId']])) {
                $cartItem[$_POST['productId']] = array("productData" =>$product_data_arr);
                $array1 = $cartItem[$_POST['productId']];
                $array = $array1['productData'];
                $array['qty'] = $_POST['quantity_wanted'];
                $cartItem[$_POST['productId']] = array("productData" => $array);
                $jsonCartItem = json_encode($cartItem);
                setcookie('cartItems', $jsonCartItem,$time, '/');
                echo 'Added Successfully 3';
            } else {
                $array1 = $cartItem[$_POST['productId']];
                $array = $array1['productData'];
                $array['qty'] += $_POST['quantity_wanted'];
                $cartItem[$_POST['productId']] = array("productData" => $array);
                $jsonCartItem = json_encode($cartItem);
                setcookie('cartItems', $jsonCartItem,$time,'/');
                echo 'Added Successfully 2';
            }
            //print_r($_COOKIE['cartItems']);
        } else {
           // $cartItem[$_POST['productId']] = array("productData" => $_POST['productData']);
            if(!isset($_REQUEST['quantity_wanted']))
            {
                $cartItem[$_POST['productId']] = array("productData" => $product_data_arr);
            }
            else
            {
                $array = $product_data_arr;
                $array['qty'] = $_REQUEST['quantity_wanted'];
                $cartItem[$_POST['productId']] = array("productData" => $array);
            }
            $jsonCartItem = json_encode($cartItem);
            setcookie('cartItems', $jsonCartItem,$time, '/');
            //print_r($_COOKIE['cartItems']);
            echo 'Added Successfully 1';
        }
       
        //echo 'Nj';
    break;    
    
    /* Manage Add to cart */
    case 'setWishCart':
        $cartItem = array();
        $jsonCartItem = "";
        if (isset($_COOKIE['cartItems']) && !empty($_COOKIE['cartItems'])) {
            $preCartItems = $_COOKIE['cartItems'];
            $preCartItems1 = stripslashes($preCartItems);
            $savedPreCartItems = json_decode($preCartItems1, true);
            $cartItem = $savedPreCartItems;
            if (!isset($savedPreCartItems[$_POST['productId']]) || empty($savedPreCartItems[$_POST['productId']])) {
                $cartItem[$_POST['productId']] = array("productData" => $_POST['productData']);
                $jsonCartItem = json_encode($cartItem);
                setcookie('cartItems', $jsonCartItem,$time, '/');
                mysql_query("DELETE FROM user_wishlist WHERE menu_id ='".$_POST['productId']."' AND user_id = '".$_SESSION['guest_id']."'");
                echo 'Added Successfully 3';
                
            } else {
                $array1 = $cartItem[$_POST['productId']];
                $array = $array1['productData'];
                $array['qty'] += $_POST['productData']['qty'];
                $cartItem[$_POST['productId']] = array("productData" => $array);
                $jsonCartItem = json_encode($cartItem);
                setcookie('cartItems', $jsonCartItem,$time, '/');
                mysql_query("DELETE FROM user_wishlist WHERE menu_id ='".$_POST['productId']."' AND user_id = '".$_SESSION['guest_id']."'");
                echo 'Added Successfully 2';
            }
            //print_r($_COOKIE['cartItems']);
        } else {
            $cartItem[$_POST['productId']] = array("productData" => $_POST['productData']);
            $jsonCartItem = json_encode($cartItem);
            setcookie('cartItems', $jsonCartItem,$time, '/');
            mysql_query("DELETE FROM user_wishlist WHERE menu_id ='".$_POST['productId']."' AND user_id = '".$_SESSION['guest_id']."'");
            //print_r($_COOKIE['cartItems']);
            echo 'Added Successfully 1';
        }
       
        //echo 'Nj';
    break;
    
    case 'removeCartItem':
        $cartItem = array();
        $jsonCartItem = "";
        if (isset($_COOKIE['cartItems']) && !empty($_COOKIE['cartItems'])) {
            $preCartItems = $_COOKIE['cartItems'];
            $preCartItems1 = stripslashes($preCartItems);
            $savedPreCartItems = json_decode($preCartItems1, true);
            $cartItem = $savedPreCartItems;
            if (!isset($savedPreCartItems[$_POST['productId']]) || empty($savedPreCartItems[$_POST['productId']])) {
                echo 'Removed Successfully';
            }else{
                unset($cartItem[$_POST['productId']]);
                if(sizeof($cartItem)>0){
                    $jsonCartItem = json_encode($cartItem);
                    setcookie('cartItems', $jsonCartItem,$time, '/');
                } else{
                    unset($_COOKIE['cartItems']);
                    setcookie("cartItems", "", time() - 3600,'/');
                }
                echo 'Removed Successfully';
            }
            //print_r($_COOKIE['cartItems']);
        } else {
            echo 'Not Removed Successfully';
        }
    break;   
    
    case 'emptyCart':
    if (isset($_COOKIE['cartItems'])) {
        unset($_COOKIE['cartItems']);
        setcookie("cartItems", "", time() - 3600,'/');
    }
    echo 'Removed Successfully';
    break;  
    
    
    case 'editCart':
        $cartItem = array();
        $jsonCartItem = "";
        if (isset($_COOKIE['cartItems']) && !empty($_COOKIE['cartItems'])) {
            $preCartItems = $_COOKIE['cartItems'];
            $preCartItems1 = stripslashes($preCartItems);
            $savedPreCartItems = json_decode($preCartItems1, true);
            $cartItem = $savedPreCartItems;
            if (!isset($savedPreCartItems[$_POST['productId']]) || empty($savedPreCartItems[$_POST['productId']])) {
                echo 'Updated Successfully';
            } else {
                $array1 = $cartItem[$_POST['productId']];
                $array = $array1['productData'];
                $array['outletId'] = $_POST['productData']['outletId'];
                $array['outletArea'] = $_POST['productData']['outletArea'];
                $array['qty'] = $_POST['productData']['qty'];
                
                $cartItem[$_POST['productId']] = array("productData" => $array);
                $jsonCartItem = json_encode($cartItem);
                setcookie('cartItems', $jsonCartItem,$time, '/');
                echo 'Updated Successfully';
            }
            //print_r($_COOKIE['cartItems']);
        } else {
            echo 'Updated Successfully';
        }
    break;
    
    
    case 'updateCart':
        $cartItem = array();
        $jsonCartItem = "";
        echo 'Nj';
        if (isset($_COOKIE['cartItems']) && !empty($_COOKIE['cartItems'])) {
            $preCartItems = $_COOKIE['cartItems'];
            $preCartItems1 = stripslashes($preCartItems);
            $savedPreCartItems = json_decode($preCartItems1, true);
            $cartItem = $savedPreCartItems;
            if (!isset($savedPreCartItems[$_POST['productId']]) || empty($savedPreCartItems[$_POST['productId']])) {
                echo 'Updated Successfully';
            } else {
                $array1 = $cartItem[$_POST['productId']];
                $array = $array1['productData'];
                
                $array[$_POST['itemKey']] = $_POST['keyValue'];
                
                $cartItem[$_POST['productId']] = array("productData" => $array);
                $jsonCartItem = json_encode($cartItem);
                setcookie('cartItems', $jsonCartItem,$time, '/');
                echo 'Updated Successfully';
            }
           // print_r($_COOKIE['cartItems']);
        } else {
            echo 'Updated Successfully';
        }
    break;  
    
    /* Manage Add to cart */
    /* Code for Create Order Here*/
    
    
    case 'createOrder':
        
        $user_id = $_SESSION['MarketPlaceId'];
        if (isset($_COOKIE['cartItems']) && !empty($_COOKIE['cartItems'])) 
        {
            $cartItems1 = $_COOKIE['cartItems'];
            $cartItems = json_decode($cartItems1);
            //print_r($cartItems);
            
            
            $dates = date("Y-m-d-H-i-s");
            if (isset($_FILES['fileUpload']['name']) && !empty($_FILES['fileUpload']['name'])) {
                $image = $_FILES['fileUpload'];
                $name = preg_replace('/[^a-zA-Z0-9_]/', '-',"prescription");
                $filename = $name . "-" . $dates;
                $pathToSave = "../images/upload_prescription/";
                $thumbPathToSave = "../images/upload_prescription/thumb/";
                $main_logo = $njFileObj->uploadImage($image, $filename, $pathToSave);
                $image_source = "../images/upload_prescription/" . $main_logo;
                $thumb_logo = $njFileObj->resizeImage($image_source, $filename, $thumbPathToSave);
                $data['image'] = $main_logo;
            }
            
            
            
            $data['user_id'] = $user_id;
            $data['actual_price'] = '0';
            $data['offer_perc'] = '0';
            $data['amount'] = '0';
            $data['quantity'] = '0';
            $data['location'] = $_POST['location'];
            $data['address'] = mysqli_real_escape_string ($conn, $_POST['address']);
            $data['payment_mode'] = 'Cash';
            $data['status'] = '0';
            $data['added_on'] = date('Y-m-d H:i:s');
            $data['added_by'] = $user_id;
        
            $dbComObj->addData($conn,"`order`", $data);
            $order_id = $dbComObj->insert_id($conn);
            
            if($_POST['location'] == '1')
            {
                $shipped = $_SESSION['MarketPlaceName'].'<br>'.$_POST['address'];
            }
            else
            {
                $shipped = 'At Shop';
            }
            
            $msgFormatNj = '';
            $total = '0';
            $totalQty = '0';
            $invoicData = '';
            foreach ($cartItems as $key => $cartItem1) {
                $cartItem = $cartItem1->productData;
                if(!isset($cartItem->discount))
                {
                    $actualll = $cartItem->price;
                    $actualllQ = $cartItem->price * $cartItem->qty;
                }
                else
                {
                    $offer = $cartItem->price * $cartItem->discount / 100;
                    $actualll = $cartItem->price - $offer;
                    $actualllQ = $actualll * $cartItem->qty;
                }
                
                $nj['user_id'] = $user_id;
                $nj['order_id'] = $order_id;
                               
                $nj['product_id'] = $cartItem->productId;
                $nj['company_id'] = $cartItem->companyId;
                $nj['sku_product'] = $cartItem->sku_product;
                $nj['price'] = $actualllQ;
                $nj['qty'] = $cartItem->qty;
                $nj['offer_price'] = $cartItem->discount;
                $nj['status'] = "0";
                $nj['done_by'] = "0";
                
                $nj['added_on'] = date('Y-m-d H:i:s');
                $nj['added_by'] = $user_id;
                $total = $total + $actualllQ * $cartItem->qty;
                $totalQty = $totalQty + $cartItem->qty;
                $dbComObj->addData($conn,"`order_cart`", $nj);
                
                $invoicData .= '<tr><td>'.$cartItem->productName.' ('.$cartItem->sku_product.')</td><td class="text-center"><i class="fa fa-rupee" aria-hidden="true"></i> '.$actualllQ.'</td><td class="text-center">'.$cartItem->qty.'</td><td class="text-right"><i class="fa fa-rupee" aria-hidden="true"></i> '.$actualll * $cartItem->qty.'</td>
            </tr>';
            }
            
            $updat['actual_price'] = $total;
            $updat['amount'] = $total;
            $updat['quantity'] = $totalQty;
            $dbComObj->editData($conn,"`order`", $updat,"`id`='".$order_id."'");
            
            
            $returnData = '<div class="row">
                <div class="col-xs-12"><div class="invoice-title">
                        <h2>Invoice</h2><h3 class="pull-right">Order # DH100'.$order_id.'</h3>
                    </div><hr>
                    <div class="row"><div class="col-xs-6"><address>
                    <strong>Billed To:</strong><br>'.$_SESSION['MarketPlaceName'].'<br></address></div>
                    <strong>Billed To:</strong><br>'.$_SESSION['MarketPlaceName'].'<br></address></div>
                        <div class="col-xs-6 text-right">
                        <address><strong>Shipped To:</strong><br>
                                '.$shipped.'
                                </address>
                        </div></div><div class="row"><div class="col-xs-6"><address>
                        <strong>Payment Method:</strong><br>
                        Cash<br></address>
                        </div><div class="col-xs-6 text-right"><address>
                        <strong>Order Date:</strong><br>
                        '.date("F j, Y").'<br><br>
                        </address>
                        </div></div></div></div>
            <div class="row"><div class="col-md-12">
            <div class="panel panel-default"><div class="panel-heading">
            <h3 class="panel-title"><strong>Order summary</strong></h3></div>
            <div class="panel-body"><div class="table-responsive"><table class="table table-condensed">
            <thead><tr>
            <td><strong>Item</strong></td><td class="text-center"><strong>Price</strong></td><td class="text-center"><strong>Quantity</strong></td>
            <td class="text-right"><strong>Totals</strong></td></tr></thead><tbody>
            '.$invoicData.'</tbody></table></div></div></div></div></div>';
            
            removeCartItem_Nj();
            unset($_COOKIE['cartItems']);
            setcookie("cartItems", "", time() - 100,'/');
            
           echo $returnData;
        }
    break;
}


function removeCartItem_Nj()
{
    if (isset($_COOKIE['cartItems'])) {
        unset($_COOKIE['cartItems']);
        setcookie("cartItems", "", time() - 3600,'/');
    }
}

?>  