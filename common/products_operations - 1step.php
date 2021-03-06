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
$conn = $dbConObj->dbConnect();
$njEncryptionObj = new njEncryption();

$njFileObj = new njFile();
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

$table = "products";

 if($_SESSION['DH_admin_id']) {
     $ss_user_id = $_SESSION['DH_admin_id'];
     $path_url= ADMIN_PATH;
     $redirect_url=ADMIN_URL;
}else { 
    $ss_user_id = $_SESSION['DH_seller_id'];
    $path_url= SELLER_PATH;
     $redirect_url=SELLER_URL;
}  

if ($mode == "addNewProduct") {
    
  //  echo '<pre>'; print_r($_POST);
  // die;

        $uniqid = uniqid();
        $condition = " `SKU` = '".$_POST['SKU']."'";
        $result = $dbComObj->viewData($conn,$table,"*",$condition);
        $num = $dbComObj->num_rows($result);
        if ($num == 0) 
        {

            $dates = date("Y-m-d-H-i-s");
            $data = array();

            $slug = $njGenObj->removeSpecialChar($_POST['product_name']);
            if( $_POST['SKU']){$SKU = $_POST['SKU'];}
            else { $SKU ='MPJ-'.$uniqid;}
            $data['product_name'] = $_POST['product_name'];
            $data['slug'] = $slug;
            $data['product_description'] = base64_encode($_POST['product_description']);
            $data['stone_description'] = base64_encode($_POST['stone_description']);
            $data['product_type'] = $_POST['product_type'];
            $data['SKU'] = $SKU;
            $data['Brand'] = ($_POST['Brand']);
            if(isset($_POST['category_id']))
            $data['category_id'] =implode(",",$_POST['category_id']);  
            $data['unit_weight'] = ($_POST['unit_weight']);
            $data['price'] = ($_POST['price']);
             /// n others fileds st///////
            $data['inv_qty'] = ($_POST['inv_qty']);
           //   

            //$data['min_sale_qty'] = ($_POST['min_sale_qty']);
          //  $data['max_sale_qty'] = ($_POST['max_sale_qty']);
            $data['inventory_min_qty'] = ($_POST['inventory_min_qty']);
            $data['is_in_stock'] = ($_POST['is_in_stock']);
             if(isset($_POST['related_check_list']))
            $data['related_check_list'] =implode(",",$_POST['related_check_list']);
          if(isset($_POST['attribute_arr']))
            $data['attribute_arr'] =implode(",",$_POST['attribute_arr']);
          
             if(isset($_POST['associated_check_list']))
            $data['associated_check_list'] =implode(",",$_POST['associated_check_list']);
            $data['meta_title'] = ($_POST['meta_title']);
            $data['meta_keyword'] = ($_POST['meta_keyword']);
            $data['meta_description'] = ($_POST['meta_description']);
             /// diamond and stone details
            $data['measurement_size'] = ($_POST['measurement_size']);
        //    $data['Material'] = ($_POST['Material']);
            //$data['discount'] = ($_POST['discount']);
            //$data['quantity'] = ($_POST['quantity']);
          //// $data['resizable'] = ($_POST['resizable']);
           // $data['is_lab_created'] = ($_POST['is_lab_created']);
            $data['product_metal'] = ($_POST['product_metal']);
            $data['stone'] = ($_POST['stone']);
            $data['no_of_stone'] = ($_POST['no_of_stone']);
            $data['stone_setting'] = ($_POST['stone_setting']);          
            $data['stone_color'] = ($_POST['stone_color']);
            $data['stone_clarity'] = ($_POST['stone_clarity']);
            $data['stone_shape'] = ($_POST['stone_shape']);
            $data['carat'] = ($_POST['carat']); 
             print_r($_FILES['image_certificate']);
           if (isset($_FILES['image_certificate']['name']) && !empty($_FILES['image_certificate']['name'])) {
              $image = $_FILES['image_certificate'];
              $name = preg_replace('/[^a-zA-Z0-9_]/', '-', $_POST['product_name']);
              $filename = $name . "-" . $dates;
              $pathToSave = "../../images/cert/";
              $thumbPathToSave = "../../images/cert/thumb/";
              $main_logo = $njFileObj->uploadImage($image, $filename, $pathToSave);
              $image_source = "../../images/cert/" . $main_logo;
              $thumb_logo = $njFileObj->resizeImage($image_source, $filename, $thumbPathToSave);
              $data['image_certificate'] = $main_logo;
            }

            $data['added_on'] = date("Y-m-d H:i:s");
            $data['added_user_type'] = $_SESSION['DH_acc_type_name'];; 
            $data['added_by'] = $ss_user_id;
            $data['status'] = '1';
           
             $dbComObj->addData($conn,$table,$data);
            $product_id = $dbComObj->insert_id($conn);
             /// image upload
             $image_data = array();
             $file=$_FILES['product_images'];
            $product_images = $file ['name'];
           foreach ($product_images as $key => $value) {     
               $tmppath = $file['tmp_name'][$key]; 
               if ($tmppath != '') {
                   $names = explode('.', $value);
                    $pname = preg_replace('/[^a-zA-Z0-9_]/', '-', $_POST['product_name']);
                   $value =  $pname.'-'.$dates .' _ '. $value;
                   $path = "../images/products/" . $value; 
                   move_uploaded_file($tmppath, $path);
                     $image_source = "../images/products/" . $value;
                      $thumbPathToSave = "../images/products/thumb/";
                     $thumb_logo = $njFileObj->resizeImage($image_source, $value, $thumbPathToSave);
                    $image_data['product_id'] = $product_id;
                     $image_data['thumb_image'] = $thumb_logo;
                     $image_data['image'] = $value;
                     $image_data['added_on'] = date("Y-m-d H:i:s");
                     $image_data['added_user_type'] = $_SESSION['DH_acc_type_name'];; 
                     $image_data['added_by'] = $ss_user_id;
                     $image_data['status'] = '1';
                     $product_imageAdd = $dbComObj->addData($conn, 'product_image', $image_data);
               }
           }
 
              if(isset($_POST['product_option_title']))  {
            $count_field= count($_POST['product_option_title']);
           
            for ($i = 0; $i < $count_field; $i++) {
                
            $option['title'] = $_POST['product_option_title'][$i];
            $option['input_type'] = 'drop_down';// $_POST['option_input_type'][$i];
            $option['is_require'] = $_POST['option_is_requre'][$i];
            $option['sort_order'] = $_POST['product_opt_sort_order'][$i];
            $option['product_id'] = $product_id;
            $option['added_on'] = date("Y-m-d H:i:s");
            $option['added_type'] = $_SESSION['DH_acc_type_name'];
            $option['added_by'] = $ss_user_id;
            $option['status'] = '1';
            //custom_options table
           // echo 'option----';print_r($option);
            $custom_optionsAdd = $dbComObj->addData($conn, 'custom_options', $option);
            $custom_options_ID = $dbComObj->insert_id($conn);

           // $input_opt=  $_POST['option_input_type'][$i];

              //  if($input_opt == 'field' )   {
                //    $option2['price'] = ($_POST['opt_price_field'][$i][0]);
                //    $option2['price_type'] = ($_POST['price_type_opt'][$i][0]);
                //    $option2['SKU'] = ($_POST['opt_sku'][$i][0]);
                //    $option2['opt_maxchar'] = ($_POST['opt_maxchar'][$i][0]); // nn
                //    $option2['option_id'] = $custom_options_ID; // nn
                //    $option2['input_type'] = $input_opt; // nn
                    
                //   //  echo 'option2----'; print_r($option2);
                //    $custom_option_valueAdd = $dbComObj->addData($conn, 'custom_option_value', $option2);
                // }
               // if($input_opt == 'drop_down')   {
                     $count_field_dl=count($_POST['opt_price_row'][$i]);
                //option type input filed dropdown - array multi  no need of opt_maxchar
                // and adeed new- option_title ,opt_sort_order_row
                      for ($J = 0; $J  < $count_field_dl; $J++) {
                            echo $i;  echo '------'. $J;
                           echo 'opt_price_row-----J. ---'. $_POST['opt_price_row'][$i][$J];
                $option3['price'] = ($_POST['opt_price_row'][$i][$J]);
                $option3['price_type'] = ($_POST['price_type_opt_row'][$i][$J]);
                $option3['opt_sort_order_row'] = ($_POST['opt_sort_order_row'][$i][$J]);
                $option3['SKU'] = ($_POST['opt_sku_row'][$i][$J]);     
                $option3['option_title'] = ($_POST['ddl_option_title'][$i][$J]);
                $option3['option_id'] = $custom_options_ID; // nn
                 $option3['input_type'] = 'drop_down';//$input_opt; // nn
                $custom_option_valueAdd = $dbComObj->addData($conn, 'custom_option_value', $option3);
              //  echo 'option3----'; print_r($option3);
                }   
              //}
            }
           }  
             if($_POST['attribute_arr']){
                  echo "Redirect : Select Assoctiative products. URL ".$path_url."products/new-product-step2-configure/?session_uid=".$njEncryptionObj->safe_b64encode($product_id)."";
                    // die;
             } else{
         
            // echo "Redirect : Products created successfully. URL ".$path_url."products/";
              echo "Redirect : Select Assoctiative products. URL ".$path_url."products/new-product-step2-configure/?session_uid=".$njEncryptionObj->safe_b64encode($product_id)."";
           }
        } else {
            echo "Error : product SKU already registered. Please try again with diffrent SKU.";
        }
    
} 

if ($mode == "manageProducts") {
    
    print_r($_POST);
         $condition = " `product_id` = '".$_POST['session_uid']."'";
        $result = $dbComObj->viewData($conn,$table,"*",$condition);
        $num = $dbComObj->num_rows($result);

        if ($num) {
            $slug = $njGenObj->removeSpecialChar($_POST['product_name']);
            $row = $dbComObj->fetch_assoc($result);
            $data = array();
            $data['product_name'] = $requestData['product_name'];
            $data['slug'] = $slug;
            $data['product_description'] = base64_encode($requestData['product_description']);
            $data['stone_description'] = base64_encode($_POST['stone_description']);
            $data['product_type'] = $requestData['product_type'];
            $data['Brand'] = ($requestData['Brand']);
            $category_id = ($requestData['category_id']);   //array
            if(($category_id))
            $data['category_id'] =implode(",",$requestData['category_id']);  
            $data['unit_weight'] = ($requestData['unit_weight']);
            $data['price'] = ($requestData['price']);
             /// n others fileds st///////
            $data['inv_qty'] = ($requestData['inv_qty']);
            //$data['min_sale_qty'] = ($requestData['min_sale_qty']);
           // $data['max_sale_qty'] = ($requestData['max_sale_qty']);
            $data['inventory_min_qty'] = ($requestData['inventory_min_qty']);
            $data['is_in_stock'] = ($requestData['is_in_stock']);
             if(isset($requestData['related_check_list']))
            $data['related_check_list'] =implode(",",$requestData['related_check_list']);
             if(isset($requestData['related_check_list']))
            $data['associated_check_list'] =implode(",",$requestData['associated_check_list']);
            $data['meta_title'] = ($requestData['meta_title']);
            $data['meta_keyword'] = ($requestData['meta_keyword']);
            $data['meta_description'] = ($requestData['meta_description']);
             /// diamond and stone details
            $data['measurement_size'] = ($requestData['measurement_size']);
           // $data['Material'] = ($_POST['Material']);
            //$data['discount'] = ($_POST['discount']);
            //$data['quantity'] = ($_POST['quantity']);
            //$data['resizable'] = ($_POST['resizable']);
           // $data['is_lab_created'] = ($_POST['is_lab_created']);
            $data['product_metal'] = ($requestData['product_metal']);
            $data['stone'] = ($requestData['stone']);
            $data['no_of_stone'] = ($requestData['no_of_stone']);
            $data['stone_setting'] = ($requestData['stone_setting']);          
            $data['stone_color'] = ($requestData['stone_color']);
            $data['stone_clarity'] = ($requestData['stone_clarity']);
            $data['stone_shape'] = ($requestData['stone_shape']);
            $data['carat'] = ($requestData['carat']); 

             if (isset($_FILES['image_certificate']['name']) && !empty($_FILES['image_certificate']['name'])) {
              $image = $_FILES['image_certificate'];
              $name = preg_replace('/[^a-zA-Z0-9_]/', '-', $requestData['product_name']);
              $filename = $name . "-" . $dates;
              $pathToSave = "../../images/cert/";
              $thumbPathToSave = "../../images/cert/thumb/";
              $main_logo = $njFileObj->uploadImage($image, $filename, $pathToSave);
              $image_source = "../../images/cert/" . $main_logo;
              $thumb_logo = $njFileObj->resizeImage($image_source, $filename, $thumbPathToSave);
              $data['image_certificate'] = $main_logo;
            }
            $data['updated_on'] = date("Y-m-d H:i:s");
            $data['update_user_type'] = $_SESSION['DH_acc_type_name'];
            $data['updated_by'] = $ss_user_id.'-'.$_SESSION['DH_acc_type_name']; 
            $dates = date("Y-m-d-H-i-s");
            if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                $image = $_FILES['image'];
                $name = preg_replace('/[^a-zA-Z0-9_]/', '-', $_POST['product_name']);
                $filename = $name . "-" . $dates;
                $pathToSave = "../../images/user/";
                $thumbPathToSave = "../../images/user/thumb/";
                $main_logo = $njFileObj->uploadImage($image, $filename, $pathToSave);
                $image_source = "../../images/user/" . $main_logo;
                $thumb_logo = $njFileObj->resizeImage($image_source, $filename, $thumbPathToSave);
                $data['image'] = $main_logo;
            }
           
            $dbComObj->editData($conn,$table,$data,$condition);

             /// image upload start
             $image_data = array();
             $file=$_FILES['product_images'];
            $product_images = $file ['name'];
           foreach ($product_images as $key => $value) {     
               $tmppath = $file['tmp_name'][$key]; 
               if ($tmppath != '') {
                   $names = explode('.', $value);
                    $pname = preg_replace('/[^a-zA-Z0-9_]/', '-', $_POST['product_name']);
                   $value =  $pname.'-'.$dates .' _ '. $value;
                   $path = "../images/products/" . $value; 
                   move_uploaded_file($tmppath, $path);
                     $image_source = "../images/products/" . $value;
                      $thumbPathToSave = "../images/products/thumb/";
                     $thumb_logo = $njFileObj->resizeImage($image_source, $value, $thumbPathToSave);
                    $image_data['product_id'] = $_POST['session_uid'];
                     $image_data['thumb_image'] = $thumb_logo;
                     $image_data['image'] = $value;
                     $image_data['added_on'] = date("Y-m-d H:i:s");
                     $image_data['added_user_type'] = $_SESSION['DH_acc_type_name'];; 
                     $image_data['added_by'] = $ss_user_id;
                     $image_data['status'] = '1';
                     $product_imageAdd = $dbComObj->addData($conn, 'product_image', $image_data);
               }
           }
                 
            // custom option start
            //  echo 'id--------'. $requestData['session_uid'];  //die;
               $product_id = $requestData['session_uid'];  
              if(isset($requestData['option_input_type']))  {
                  
            $count_field= count($requestData['option_input_type']);
             // print_r($requestData['option_input_type']);
            for ($i = 0; $i < $count_field; $i++) {    
            $option['title'] = $requestData['product_option_title'][$i];
            $option['input_type'] = $requestData['option_input_type'][$i];
            $option['is_require'] = $requestData['option_is_requre'][$i];
            $option['sort_order'] = $requestData['product_opt_sort_order'][$i];
            $option['product_id'] = $product_id;
            $option['added_on'] = date("Y-m-d H:i:s");
            $option['added_type'] = $_SESSION['DH_acc_type_name'];
            $option['added_by'] = $ss_user_id;
            $option['status'] = '1';
            //custom_options table
       //   echo '<pre>';  echo 'option----';print_r($option);
            
          //  $condition_opt = " `product_id` = '".$_POST['session_uid']."'";
           // $dbComObj->editData($conn,$table,$option,$condition_opt);
             $dbComObj->deleteData($conn,'custom_options',"`product_id` = '". $product_id."'");   // delete data
            $custom_optionsAdd = $dbComObj->addData($conn, 'custom_options', $option);
            $custom_options_ID = $dbComObj->insert_id($conn);

            $input_opt=  $requestData['option_input_type'][$i];
                     
                if($input_opt == 'field' )   {

                   $option2['price'] = ($requestData['opt_price_field'][$i][0]);
                   $option2['price_type'] = ($requestData['price_type_opt'][$i][0]);
                   $option2['SKU'] = ($requestData['opt_sku'][$i][0]);
                   $option2['opt_maxchar'] = ($requestData['opt_maxchar'][$i][0]); // nn
                   $option2['option_id'] = $custom_options_ID; // nn
                   $option2['input_type'] = $input_opt; // nn
                    
                 //  echo '<pre>'; echo 'option2----'; print_r($option2);
                   $dbComObj->deleteData($conn,'custom_option_value',"`option_id` = '".$custom_options_ID."'");   // delete data
                   $custom_option_valueAdd = $dbComObj->addData($conn, 'custom_option_value', $option2);
                }
                if($input_opt == 'drop_down')   {
                     $count_field_dl=count($requestData['opt_price_row'][$i]);
                //option type input filed dropdown - array multi  no need of opt_maxchar
                // and adeed new- option_title ,opt_sort_order_row
                      for ($J = 0; $J  < $count_field_dl; $J++) {
                           // echo $i;  echo '------'. $J;
                 //   echo     $_POST['opt_price_row'][$i][$J];
                $option3['price'] = ($_POST['opt_price_row'][$i][$J]);
                $option3['price_type'] = ($_POST['price_type_opt_row'][$i][$J]);
                $option3['opt_sort_order_row'] = ($_POST['opt_sort_order_row'][$i][$J]);
                $option3['SKU'] = ($_POST['opt_sku_row'][$i][$J]);     
                $option3['option_title'] = ($_POST['ddl_option_title'][$i][$J]);
                $option3['option_id'] = $custom_options_ID; // nn
                 $option3['input_type'] = $input_opt; // nn
                $dbComObj->deleteData($conn,'custom_option_value',"`option_id` = '".$custom_options_ID."'");   // delete data
                $custom_option_valueAdd = $dbComObj->addData($conn, 'custom_option_value', $option3);
              // echo '<pre>';  echo 'option3----'; print_r($option3);
                }   
              }
            }
           }  
                //sdie;
               echo "Redirect : Products updated successfully. URL ".$path_url."products/";
        } else {
            echo "Error : User not registered.";
        }
   
} 

if($mode == 'deleteProduct')
{
    //$dbComObj->deleteData($conn,$table,"`product_id` = '".$_POST['a']."'");
     $data="`delete` = 1";
     $condition ="`product_id` = '".$_POST['a']."'";
     $dbComObj->editData($conn,$table,$data,$condition);
    
    
}

if($mode == 'statusProduct')
{
    if($_POST['b'] == '1'){$a['status'] = '0';}else{$a['status'] = '1';}
    $dbComObj->editData($conn,$table,$a,"`product_id` = '".$_POST['a']."'");
}
if($mode == 'ImportCsv')
{

 $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
   if(!empty($_FILES['csv']['name']) && in_array($_FILES['csv']['type'],$csvMimes)){
       if(is_uploaded_file($_FILES['csv']['tmp_name'])){
           $csvFile = fopen($_FILES['csv']['tmp_name'], 'r');
           fgetcsv($csvFile);
               $already_exit_arr=array();
                $int_counter= 0;  $upt_counter= 0;
           while(($line = fgetcsv($csvFile)) !== FALSE){
			  
			   // print_r($line); die;
                //check whether member already exists in database with same email
                $condition = " `SKU` = '".$line[3]."'";
                $result = $dbComObj->viewData($conn,$table,"*",$condition);
                $num = $dbComObj->num_rows($result);
                $slug = $njGenObj->removeSpecialChar($line[0]);
                 $uniqid = uniqid(); 

                   if($line[3]){ $SKU = $line[4];}else {$SKU ='MPJ-'.$uniqid;}
                    $data['product_name'] = $line[0]; 
                    $data['slug'] = $slug;
                    $data['product_description'] = base64_encode($line[1]);
                    $data['product_type'] = $line[2];
                    $data['SKU'] = $SKU;
                    $data['Brand'] = ($line[4]);
                    $data['category_id'] = ($line[5]);
                    $data['unit_weight'] = ($line[6]);
                    $data['price'] = ($line[7]);
                     /// n others fileds st///////
                    $data['inv_qty'] = ($line[8]);
                    $data['min_sale_qty'] = ($line[9]);
                    $data['max_sale_qty'] = ($line[10]);
                    $data['inventory_min_qty'] =($line[11]);
                    $data['is_in_stock'] = ($line[12]);                 
                    $data['related_check_list'] =($line[13]);   
                    $data['associated_check_list'] =($line[14]);   
                    $data['meta_title'] = ($line[15]);   
                    $data['meta_keyword'] = ($line[16]);   
                    $data['meta_description'] = ($line[17]); 
                      /// diamond and stone details
                    $data['measurement_size'] = ($line[18]);
                    $data['product_metal'] = ($line[19]);  
                    $data['Material'] = ($line[20]); 
                     $data['stone'] = ($line[21]); 
                    $data['no_of_stone'] = ($line[22]); 
                    $data['stone_setting'] = ($line[23]);     
                    $data['stone_color'] = ($line[24]); 
                    $data['stone_clarity'] = ($line[25]); 
                    $data['stone_shape'] = ($line[26]); 
                    $data['carat'] = ($line[27]); 
                    $data['stone_description'] =  base64_encode(($line[28])); 
                    $data['added_on'] = date("Y-m-d H:i:s");
                    $data['added_user_type'] = $_SESSION['DH_acc_type_name'];
                    $data['added_by'] = $ss_user_id;
                     $data['method'] = 'csv';
                    $data['status'] = '1'; 
                       if($num > 0){
                       //update products data
                          
                           $already_exit_arr[]=$line[3];
                        //    $dbComObj->editData($conn,$table,$data,$condition);
                            $upt_counter++;
              
                    }else{

                        $dbComObj->addData($conn,$table,$data);
                        $product_id = $dbComObj->insert_id($conn);
                     //   echo "Redirect : Products created successfully. URL ".$path_url."products/";
                          $int_counter++;
                       }
               
              }
               if($already_exit_arr){
                 
             //  echo '$already_exit_arr-----'; print_r($already_exit_arr);
               $already_exit_product= implode(", ",$already_exit_arr);
               echo  'Error : Product SKU ('.$already_exit_product.')  is already exits. Please try again with diffrent product <br>'; 
            } else{
            $qstring = '?status=succ';
             echo  'Reload: Total(<b id="int_counter">'.$int_counter.'</b>) Products data has been inserted  successfully.';
            }


           fclose($csvFile); 
             echo 'Reload: Total(<b id="int_counter">'.$int_counter.'</b>) Products data has been inserted  successfully.';
          // echo "Redirect : Other User added successfully. URL ".BASE_URL."UsersList/boysList.php";
       }else{
           echo "Error : File Format not supported. Please check your file and upload again.";
       }
   }else{
      echo "Error : Invalid file Type. Please Upload Csv File.";
   }
}
