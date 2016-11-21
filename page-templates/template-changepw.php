<?php  
/*
Template Name: Change Password */
 
get_header()
?>

<style> .message, .error_login { width:615px; margin-left:auto; margin-right:auto;}
.success1 {color:#090 !important; font-weight:bold;}  input {width:100% !important; clear:both !important;} </style>
	<div class="wrapper">

        <div style="width:650px;margin:0 auto;">

   <h4 style="text-align: center;"><?php _e('Update your account details', 'storefront'); ?></h4>
<div class="splitter"></div><br /><br />

<?php echo do_shortcode('[wppb-edit-profile]'); ?>


</div>
</div>
 
<?php get_footer() ?>