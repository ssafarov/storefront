<?php
/*
Template Name: Shop Redirect
*/
?>
<?php
# https://www.fuel-3d.com/shop-redirect/

$aParsedUrl = parse_url($_SERVER['REQUEST_URI']);
$sUQry      = ($aParsedUrl !== false) && isset($aParsedUrl['query']) ? '?' . $aParsedUrl['query'] : '';

if (getBlogIdByIp() == 4) {
    wp_redirect('/usa/shop/'.$sUQry);
} else {
    wp_redirect('/shop/'.$sUQry);
}
exit;
