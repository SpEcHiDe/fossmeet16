<?php

if(isset($_POST['fm16_pay_btn'])){
  require "config.php";

  $name = $_POST['billing_address_first_name'];
  $email = $_POST['customer_email'];
  $phoneno = $_POST['customer_phone'];
  $orgname = $_POST['Field_72942'];
  $food_prefs = $_POST['Field_52247'];
  $tshirts = $_POST['Field_65157'];
  $amount = $_POST['total_amount'];

  $api = new Instamojo($my_api_key, $my_auth_token);

try {
    $response = $api->paymentRequestCreate(array(
        "purpose" => "FOSSMeet 16 Registrations",
        "amount" => $amount,
        "send_email" => true,
        "email" => $email,
        "buyer_name" => $name,
        "phone" => $phoneno,
        "send_sms" => false
        ));
}
catch (Exception $e) {
    print('Error: ' . $e->getMessage());
    die();
}

  // after the transaction request

$id = $response['id'];
$purpose = $response['purpose'];
$status = $response['status'];
$s_url = $response['shorturl'];
$l_url = $response['longurl'];
$mat = $response['modified_at'];

  //  commit all values to database

	$mysqli = new mysqli($db_server, $db_user, $db_pass, $db_name);
	if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();
	$qry = "INSERT INTO instamojo_responses VALUES('$id','$phoneno','$email','$name',$amount,'$purpose','$status','$s_url','$l_url','$mat','$food_prefs','$tshirts','$orgname');";
  if ($mysqli->query($query) === TRUE) {
    header("Location: " . $response['longurl']);
  } else {
    echo "Error creating record: " . $mysqli->error;
  }

}
else{

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes" />

    <link rel="shortcut icon" type="image/png" href="../img/logo16.png" />

    <title>FOSSMeet '16</title>

    <link rel="stylesheet" href="../css/payment.css">

</head>
<body>

<h3 style="text-align:center;">Online Registrations</h3>

<center>
<form method="POST" id="payment_form" name="payment_form" parsley-validate novalidate>

  <ul class="form-fields">

    <li class=" columns small-6 clear ">
      <label for="id_billing_address_first_name">Name:</label>
      <input class="text-input parsley-validated" id="id_billing_address_first_name" maxlength="20" name="billing_address_first_name" parsley-maxlength="20" parsley-maxlength-message="Please keep the length below 20" parsley-required="true" parsley-required-message="Please fill in your name." placeholder="Enter your full name." type="text">
    </li>

    <li class=" columns small-6 clear ">
      <label for="id_customer_email">Email Address:</label>
      <input class="text-input parsley-validated" id="id_customer_email" maxlength="75" name="customer_email" parsley-required="true" parsley-required-message="Please fill in your email address." parsley-type="email" parsley-type-email-message="Please enter a valid email" placeholder="So we can send you the purchase details." type="email">
    </li>

    <li class=" columns small-6 clear  ">
      <label for="id_customer_phone">Phone Number:</label>
      <input class="text-input parsley-validated" id="id_customer_phone" maxlength="20" name="customer_phone" parsley-maxlength="20" parsley-maxlength-message="Please keep the length below 20" parsley-required="true" parsley-required-message="Please fill in your phone number." parsley-type="phone" parsley-type-phone-message="Please enter a valid phone number" placeholder="Your phone number" type="tel">
    </li>

    <li class=" columns small-6 clear  ">
      <label for="id_Field_72942">Institution Name:</label>
      <input id="id_Field_72942" maxlength="255" name="Field_72942" parsley-required="true" placeholder="" type="text" class="parsley-validated">
    </li>

    <li class=" columns small-6 clear  ">
      <label for="id_Field_52247">Food Preference:</label>
      <select name="Field_52247" id="id_Field_52247" parsley-required="true" class="parsley-validated">
        <option value="veg">Vegetarian</option>
        <option value="non_veg">Non Vegetarian</option>
      </select>
    </li>

    <li class=" columns small-6 clear  ">
      <label for="id_Field_65157">FOSSMeet '16 T-Shirts ***:</label>
      <select name="Field_65157" id="id_Field_65157" parsley-required="true" class="parsley-validated">
        <option value="Y">Yes</option>
        <option value="N">No</option>
      </select>
    </li>

    <li class=" columns small-6 clear  ">
      <label for="id_total_amount">Enter Amount:</label>
      <select name="total_amount" id="id_total_amount" parsley-type="number" class="parsley-validated">
        <option value="100">National Institute of Technology, Calicut STUDENTS</option>
        <option value="500">Other STUDENTS</option>
        <option value="1000">Young Professionals</option>
      </select>
      
    </li>

    <li class="columns small-6 clear"><input value="Pay Now" type="submit" class="btn--green btn--full" name="fm16_pay_btn"></li>

  </ul>

</form>
</center>

</body>
</html>


<?php
}
?>

