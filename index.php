<?php
require_once "phpqrcode/qrlib.php";
if($_POST){

/*
 * QR Encoding Functions
 */

function __getLength($value) {
    return strlen($value);
}

function __toHex($value) {
    return pack("H*", sprintf("%02X", $value));
}

function __toString($__tag, $__value, $__length) {
    $value = (string) $__value;
    return __toHex($__tag) . __toHex($__length) . $value;
}

function __getTLV($dataToEncode) {
    $__TLVS = '';
    for ($i = 0; $i < count($dataToEncode); $i++) {
        $__tag = $dataToEncode[$i][0];
        $__value = $dataToEncode[$i][1];
        $__length = __getLength($__value);
        $__TLVS .= __toString($__tag, $__value, $__length);
    }

    return $__TLVS;
}
/*
 * QR Encoding Functions
 *
 * QR Code
*/
$dataToEncode = [
    [1, $_POST['SellerName']],
    [2, $_POST['VATNumber']],
    [3, $_POST['invoiceDatetime']],
    [4, $_POST['AmtwithVAT']],
    [5, $_POST['VATamt']]
];

$__TLV = __getTLV($dataToEncode);
$__QR = base64_encode($__TLV);

/*
 * QR Code
 */

$location ="images/".rand().".png";

QRcode::png($__QR,$location);
$image =imagecreatefrompng($location);
$jpg='images/'.$_POST['invoiceNumber'].".jpg";
imagejpeg($image,$jpg,70);
imagedestroy($image);
unlink($location);

}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> QR </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body>
<div class="container">
    <div class="py-5"><h1 class="h1">QR E-INVOICE KSA</h1></div>

<form  method="post">
    
  <div class="row">
    <div class="col">
      <input type="text" name="SellerName"class="form-control" value="<?php if(isset($_POST['SellerName'])){echo $_POST['SellerName'];}?>"placeholder="Seller Name" require>
    </div>
    <div class="col">
      <input type="number" name="VATNumber"class="form-control"value="<?php if(isset($_POST['VATNumber'])){echo $_POST['VATNumber'];}?>" placeholder="VAT Number" require>
    </div>
    <div class="col">
      <input type="date" name="invoiceDatetime" class="form-control" value="<?php if(isset($_POST['invoiceDatetime'])){echo $_POST['invoiceDatetime'];}?>" placeholder="invoice Datetime" require>
    </div>
  </div>
  <div>
  <div class="row py-4">
    <div class="col">
      <input type="number" min="0"  name="AmtwithVAT"class="form-control" value="<?php if(isset($_POST['AmtwithVAT'])){echo $_POST['AmtwithVAT'];}?>" placeholder="Amtwith VAT" require>
    </div>
    <div class="col">
      <input type="number" min="0"  name="VATamt"class="form-control" value="<?php if(isset($_POST['VATamt'])){echo $_POST['VATamt'];}?>" placeholder="VAT amt" require>
    </div>
    <div class="col">
      <input type="number" min="0"  name="invoiceNumber"class="form-control" value="<?php if(isset($_POST['invoiceNumber'])){echo $_POST['invoiceNumber'];}?>" placeholder="Invoice Number" require>
    </div>
  </div>
  <div class="d-flex justify-content-end py-4 ">

  <button type="submit" class="btn btn-success">submit <i class="bi bi-qr-code"></i> </button> </div>

  <div class="d-flex justify-content-center border border-dark">
    <?php if(isset($jpg)) { ?>
    <img src="<?php echo $jpg ; ?>" class="col-4" > 
    <?php } ?>
</div>
</form>  
</div>

</body>
</html>
