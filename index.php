<?php

$contact_address = $_GET['c'];
$token_id = $_GET['t'];

$curl = curl_init();
// $url = 'https://nft.api.infura.io/networks/137/accounts/0x57A30cD54E47f2Fe3e10Cb5157A9C759379410d6/assets/nfts';
// $url = 'https://nft.api.infura.io/networks/' . $token_id . '/accounts/' . $contact_address . '/assets/nfts';
// $url = 'https://nft.api.infura.io/networks/137/nfts/0x78331a8c0089cf29185f2cc9aea97f7b6e9f8fb6/tokens/2';
$url = 'https://nft.api.infura.io/networks/137/nfts/' . $contact_address . '/tokens/' . $token_id;
// print_r($url);

$headers = array(
   'accept: application/json',
   'Authorization: Basic YmMzOWVlNTgwN2QxNDBmOThmMzk3NWFjYWZlMjMxYmQ6NGRiMjlkYTY1ZTYyNGQxOWEwYzNiMmY2YzdiOWQxMDE='
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$response = json_decode($response, true);
// echo "<pre>";
// print_r($response);
// die;
$response_arr = $response['metadata'];
if ($response_arr) {

   $foodData = $response_arr[0]['components'][0];
   $component_category = $response_arr[0]['components'][1];
   $component_type = $response_arr[0]['components'][2];
   $component_service = $response_arr[0]['components'][3]['service'];
   // print_r($component_category['category']);
}
function createUrlSlug($urlString)
{
   $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $urlString);
   return $slug;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <title>fairfooddata - API</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
   <link rel="stylesheet" href="./style.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

   <style>
      .modal-backdrop {
         background-color: #00000091;
      }

      .bannerHeader img {
         width: 100%;
         margin-bottom: 26px;
      }
   </style>
   <!-- partial -->
   <script src='https://code.jquery.com/jquery-3.3.1.slim.min.js'></script>
   <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js'></script>
</head>

<body>
   <!-- partial:index.partial.html -->
   <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
   <div class="bannerHeader">
      <img src="https://fairfooddata.org/food/solarpunk_essence_food_header.jpg">
   </div>
   <div class="container mt-5">
      <div class="row">
         <div class="col-md-12 col-xl-12 ">
            <?php if (!empty($foodData)) { ?>
               <div class="titleHead">
                  <!-- <a href="#"><b>VERIFY OUR DATA</b></a> -->
                  <h2>Welcome to the transparent food chain!</h2>
                  <p>We thought the below data could be essential to know what you eat, so we made it easily accessible. These values were published via Ethereum Swarm, the censorship-resistant decentralized storage.</p>
               </div>

               <!-- Tabs with Background on Card -->

               <div class="card">
                  <div class="card-header">
                     <ul class="nav nav-tabs nav-tabs-neutral justify-content-center" role="tablist" data-background-color="orange">
                        <?php
                        $i = 0;
                        foreach ($foodData as $key => $value) {
                           $class = "nav-link";
                           if ($i == 0) {
                              $class = "nav-link active";
                              $i++;
                           }
                        ?>
                           <li class="nav-item">
                              <a class="<?= $class ?>" data-toggle="tab" href='#<?php echo createUrlSlug($key); ?>' role="tab"><?php echo ucfirst($key); ?></a>
                           </li>
                        <?php } ?>
                     </ul>
                  </div>
                  <div class="card-body">
                     <!-- Tab panes -->
                     <div class="tab-content text-center">
                        <?php
                        $i = 0;
                        foreach ($foodData as $key => $value) {
                           $class = "tab-pane";
                           if ($i == 0) {
                              $class = "tab-pane active";
                              $i++;
                           }
                        ?>
                           <div class="<?= $class ?>" id="<?php echo createUrlSlug($key); ?>" role="tabpanel">
                              <div class="row">
                                 <div class="col-lg-6 bor-right p-r-30">
                                    <div class="titleHead" style="margin-bottom: 30px;">
                                       <div id="ingre_wait_<?php echo createUrlSlug($key); ?>"></div>
                                    </div>
                                    <div class="titleHead">
                                       <div class="col-lg-9 text-left"> <b> NUTRIENTS </b>% of RDI in the package</div>
                                       <div class="col-lg-3 text-right">100% <i class="fa fa-arrow-down"></i></div>
                                    </div>
                                    <div class="progressBar">
                                       <?php foreach ($value as $val) {

                                          if (array_key_exists('trait_type', $val)) {
                                             if ($val['trait_type'] == 'weight(g)') {
                                                $weight_label =  $val['trait_type'];
                                                $weight = $val['value'];
                                                // echo $component_category['category'];  $component_category['description'];                                        
                                             } else {
                                                $new_value = $val['value'] * 1;
                                                $new_value1 = min($new_value, 100);
                                       ?>
                                                <div class="progress" style="display: flex; align-items: center; position: relative;">
                                                   <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $new_value1; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $new_value1; ?>%">
                                                      &nbsp; &nbsp;
                                                   </div>
                                                   <p style="color: #000; font-weight: 500; margin-bottom: 0; position: absolute;"><?php echo ucfirst($val['trait_type']);
                                                                                                                                    echo '<span style="margin-left: .8rem;">' . $new_value; ?> </span></p>
                                                </div>
                                       <?php }
                                          }
                                       } ?>
                                    </div>
                                    <script>
                                       document.getElementById("ingre_wait_<?php echo createUrlSlug($key) ?>").innerHTML = '<div class="col-lg-6 text-left"> <b> <?= $weight_label ?> </b></div> <div class="col-lg-6 "><?= $weight ?></div>';
                                    </script>
                                 </div>
                                 <div class="col-lg-6 ">
                                    <div class="titleHead">
                                       <a href="#"><b>NON-FOOD COMPONENTS</b></a>
                                       <ul>
                                          <li><?php echo $component_category['category']; ?> <?php echo $component_category['description']; ?></li>
                                          <li><?php echo $component_type['trait_type']; ?> <?php echo $component_type['value']; ?></li>
                                          <?php foreach ($component_service as $key => $service) { ?>
                                             <li><?php echo $service['trait_type']; ?> <?php echo date('d-m-Y H:i', $service['value']); ?></li>
                                          <?php } ?>

                                       </ul>
                                    </div>
                                 </div>

                                 <!-- <div class="col-lg-6 p-l-30">
                                    <div class="titleHead">
                                       <a href="#"><b>ABOUT FAIR DATA</b></a>

                                    </div>
                                 </div> -->
                              </div>
                           </div>
                        <?php } ?>

                     </div>
                  </div>
                  <div class="button_data hide">
                     <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        About Fair Data</button>
                     <a class="addInfo" href="https://api.gateway.ethswarm.org/bzz/d0ca0b9f1247a11235baa7b6e2f552daf5012c23e7f91529879ce60b1a47cf20" target="_blank">you can verify here</a>.</p>
                  </div>
               </div>
            <?php } else { ?>
               <h3>No meta data found.</h3>
               <a class="btn btn-warning" href="http://fairfooddata.org/">Learn more about this webapp.</a>
            <?php } ?>
            <!-- End Tabs on plain Card -->
         </div>
      </div>
   </div>

   <!-- Button trigger modal -->

   <!-- Modal -->
   <div class="modal" id="exampleModal">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">About Fiar Data</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               The parameters are loaded straight from the ETH Swarn decentralized storage, where each update is recorded to uncover manipulation.
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
         </div>
      </div>
   </div>
   <footer class="footer text-center ">
      <p>Made with 🥦 by FAIRFOODDATA, LDA.</p>
   </footer>


</body>

</html>