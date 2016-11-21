<?php
/*
Template Name: New Portal
*/
?>

<?php if (!is_user_logged_in()) {
  header("Location:" . site_url() . "/process/");

} ?>

<?php get_header(); ?>

<?php
  //to check if user is logged in
  $downloadlink = home_url('software-download');
  $userlink = home_url('user-guides');
  $exist = false;

  if (is_user_logged_in()) {
    // get logged in user data and user name
    $current_user = wp_get_current_user();
    $current_user = $current_user->data;
    $username = $current_user->user_login;
    // get all users list and check in each user list for each code
    $cuser = $baweicmu_options["codes"];


    if ( is_array($cuser) ) {
      foreach ($cuser as $k => $v) {
        is_array($v["users"]) ? $list = $v["users"] : $list = array();
        if (in_array($username, $list)) {
          // print False elae true.
          $exist = true;
        }
      }
    }
  }


$blogusers = get_users();
$all_users = array();
$invite_users = array();
foreach ($blogusers as $k => $v) {
  $u_name = $v->data;
  $u_name = $u_name->user_login;
  array_push($all_users, $u_name);
}
(is_array($baweicmu_options['codes'])) ? : $baweicmu_options['codes'] = array();
foreach ($baweicmu_options['codes'] as $l => $p) {
  if (!empty($p['users'])) {
    foreach ($p['users'] as $m => $n) {
      (is_array($all_users)) ? : $all_users = array();
      if (in_array($n, $all_users)) {
        if (!empty($invite_users[$n])) {
          (is_array($invite_users[$n])) ? : $invite_users[$n] = array();
          if (!in_array($l, $invite_users[$n])) {
            $invite_users[$n][] = $l;
          }
        } else {
          $invite_users[$n][] = $l;
        }
      }
    }
  }

}

$current_user = wp_get_current_user();
$current_user = $current_user->data;
$username = $current_user->user_login;

$televen = array("634100200211", "634100200203", "634100200209", "634100200206", "634100200215", "634100200212", "634100200223", "634100200227", "634100200220", "634100200226", "634100200217", "123456789", "17021988");
foreach ($televen as $m => $p) {
  (is_array($invite_users[$username])) ? : $invite_users[$username] = array();
  if (in_array($p, $invite_users[$username])) {
    //
    $downloadlink = home_url('software-download-1-6');
    $userlink = home_url('user-guides-1-6');
    //  echo $downloadlink;
  }
}


// print_r ($cuser);
?>
<div class="site-main container">
<?php if ( $exist && is_page('portal') ) : ?>

      <div class="row">
        <div class="col-sm-12">
          <div class="portal-topbar">
            <h2><?php _e( 'User Portal', 'storefront' ); ?></h2>
          </div>
        </div>
      </div>

      <div class="row">
      
        <div class="col-sm-12 col-md-4">
          <div class="account-box">
            <a class="btn btn-primary" href="<?php echo $downloadlink; ?>">
              <span class="span-link link-download"><?php _e( 'SOFTWARE DOWNLOAD', 'storefront' ); ?></span></a>
          </div>
        </div>

        <div class="col-sm-12 col-md-4">
          <div class="account-box">
            <a class="btn btn-primary" href="<?php echo $userlink; ?>">
              <span class="span-link link-training"><?php _e( 'TRAINING', 'storefront' ); ?></span></a>
          </div>
        </div>

        <div class="col-sm-12 col-md-4">
          <div class="account-box">
            <a class="btn btn-primary" href="https://fuel3d.zendesk.com/login" target="_blank">
              <span class="span-link link-faq"><?php _e( 'FAQ', 'storefront' ); ?></span></a>
          </div>
        </div>


    </div>

    <div class="row">

        <div class="col-sm-12 col-md-4">
          <div class="account-box">
            <a class="btn btn-primary" href="<?php echo home_url('my-account'); ?>">
              <span class="span-link link-acount"><?php _e( 'EDIT ACCOUNT', 'storefront' ); ?></span></a>
          </div>
        </div>

        <div class="col-sm-12 col-md-4">
          <div class="account-box">
            <a class="btn btn-primary" href="https://fuel3d.zendesk.com/account/dropboxes/20255471"
             onClick="script: Zenbox.show(); return false;">
              <span class="span-link link-help"><?php _e( 'HELP', 'storefront' ); ?></span></a>
          </div>
        </div>

      </div>
  </div>
  <?php if (function_exists('the_zendesk_dropbox')) the_zendesk_dropbox(); ?>



<?php elseif ( !is_page('portal') ) : ?>
  
  <?php if ( have_posts()) : while (have_posts()) : the_post() ?>

    <div class="row">
      <div class="col-sm-12">
        <div class="portal-topbar">
          <a class="back-btn" title="<?php _e( 'Back', 'storefront' ); ?>" href="<?php echo home_url('portal'); ?>"><?php _e( 'Back', 'storefront' ); ?></a>
          <h2><?php the_title(); ?></h2>
        </div>
      </div>
    </div>

    <?php
      global $more;
      $more = 0;
      the_content();
    ?>

  <?php endwhile; endif; ?>

<?php else : ?>





  <div>

    <h1><?php _e( 'Register your device and download your software', 'storefront' ); ?></h1>

    <hr>
    <br><br>

    <p><?php _e( 'Please enter your scanner serial number.', 'storefront' ); ?></p>

    <p><?php _e( 'You will find this on the back of your scanner.', 'storefront' ); ?></p>


    <?php

    echo do_shortcode('[cr_custom_registration]'); ?>


    <br>
    <br>


    <p><?php _e( 'Having any problems?', 'storefront' ); ?></p>

    <input name="Submit" type="submit" value="<?php _e( 'Click here for support', 'storefront' ); ?>" class="btn btn-primary">


  </div>


<?php endif; ?>

</div>

<?php wp_link_pages(); ?>


<?php get_footer(); ?>
