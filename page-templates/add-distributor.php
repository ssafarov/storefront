<?php
/*
Template Name: Add Distributor Page
*/

/**
 * Let's process received data first
 * Load the registration file.
 */

require_once(ABSPATH . WPINC . '/registration.php');
require_once(ABSPATH . 'wp-admin/includes/user.php' );

$error = [];
$newUserID = null;
$selectedAction = $_POST['action']?$_POST['action']:'do-add-distributor';

global $current_user;

if ($current_user->has_cap('sub-distributor')){
    wp_redirect(get_distributor_portal_url());
    exit;
}

/* Delete profile, delete user. */
if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) && $_POST['action'] == 'delete-distributor' && !empty($_POST['distributor-id']) && is_numeric($_POST['distributor-id']))
{
    if (check_if_action_allowed ($_POST['distributor-id'])){

        $done = wp_delete_user($_POST['distributor-id']);

        if (!$done)
        {
            $error[] = __('There was an error during the user deletion.', 'profile');
        }

    } else {
        $error[] = __('You do not have permissions to edit this distributor.', 'profile');
    }

    /* Redirect so the page will show updated info.*/
    if (count($error) == 0) {
        //action hook for plugins and extra fields saving
		do_action('delete_user', $_POST['distributor-id']);
    } else {
        foreach ($error as $key=>$value){
            wc_add_notice ($value, 'error');
        }
    }

    wp_redirect(get_distributor_portal_url());
    exit;

}


/* New profile, add new user. */
if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) && $_POST['action'] == 'add-distributor')
{

	/* Add user fields. */
	if (check_post ($error)){

        $newUserID = wp_create_user($_POST['user-login'], $_POST['pass1'], $_POST['email1']);

        if (is_int($newUserID) && ($newUserID > 0))
        {
            update_user_meta($newUserID, 'first_name', esc_attr($_POST['first-name']));
            update_user_meta($newUserID, 'last_name', esc_attr($_POST['last-name']));
            add_user_meta($newUserID, '_sub-distributor-of', $current_user->ID);

            $user = get_userdata($newUserID);
            $user->add_role('distributor');
            $user->add_cap( 'sub-distributor' );

            if (isset($_POST['myDistrCap'])||isset($_POST['plcOrderCap'])||isset($_POST['hlpSupportCap'])||isset($_POST['shrInfoCap'])||isset($_POST['mdAssetsCap']))
            {

                if (isset($_POST['myDistrCap']) && $_POST['myDistrCap'] == 1)
                {
                    $user->add_cap( 'can_view_my_distributor_tab' );
                }

                if (isset($_POST['plcOrderCap']) && $_POST['plcOrderCap'] == 1)
                {
                    $user->add_cap( 'can_view_place_order_tab' );
                }

                if (isset($_POST['hlpSupportCap']) && $_POST['hlpSupportCap'] == 1)
                {
                    $user->add_cap( 'can_view_help_support_tab' );
                }

                if (isset($_POST['shrInfoCap']) && $_POST['shrInfoCap'] == 1)
                {
                    $user->add_cap( 'can_view_share_info_tab' );
                }

                if (isset($_POST['mdAssetsCap']) && $_POST['mdAssetsCap'] == 1)
                {
                    $user->add_cap( 'can_view_media_assets_tab' );
                }
            }

        } else {
            $error[] = __('Unable to create user', 'profile');
        }

    }

	/* Redirect so the page will show updated info.*/
	if (count($error) == 0) {
		//action hook for plugins and extra fields saving
//		do_action('add_user', $current_user->ID);
		wp_redirect(get_distributor_portal_url());
		exit;
	} else {
		foreach ($error as $key=>$value){
			wc_add_notice ($value, 'error');
		}
	}
}

/* If profile was saved, update profile. */
if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) && $_POST['action'] == 'save-distributor' && !empty($_POST['distributor-id']) && is_numeric($_POST['distributor-id']))
{
    if (check_if_action_allowed ($_POST['distributor-id'])){
        $selectedUser = get_userdata($_POST['distributor-id']);
        //$selectedUser->add_role('distributor');
        //$selectedUser->add_cap( 'sub-distributor' );
        if ($selectedUser === false){
            $error[] = __('Unable to fetch distributor info.', 'profile');
        } else {
            /* Add user fields. */
            if (check_post ($error) ){

                if ( !empty($_POST['user-login']))
                {
                    $usnmExistsID = username_exists(esc_attr($_POST['user-login']));
                    if ($usnmExistsID === false)
                    {
                        wp_update_user(array('ID' => $selectedUser->ID, 'user-login' => esc_attr($_POST['user-login'])));
                    } elseif ($usnmExistsID != $selectedUser->ID) {
                        $error[] = __('This user name (login) is already used by another user. Please try a different one.', 'profile');
                    }
                }

                if ( ! empty($_POST['email1']) && !empty($_POST['email2']))
                {
                    if ($_POST['email1'] == $_POST['email2'])
                    {
                        $emlExistsID = email_exists(esc_attr($_POST['email1']));
                        if ($emlExistsID === false)
                        {
                            $_POST['email'] = $_POST['email1'];
                            wp_update_user(array('ID' => $selectedUser->ID, 'user_email' => esc_attr($_POST['email1'])));
                        } elseif ($emlExistsID != $selectedUser->ID) {
                            $error[] = __('This email is already used by another user. Please try a different one. '.$selectedUser->ID, 'profile');
                        }
                    }
                }

                if ( ! empty($_POST['pass1']) && !empty($_POST['pass2']))
                {
                    if ($_POST['pass1'] == $_POST['pass2'])
                    {
                        wp_update_user(array('ID' => $selectedUser->ID, 'user_pass' => esc_attr($_POST['pass1'])));
                    }
                    else
                    {
                        $error[] = __('The passwords you entered do not match.  Your password was not updated.', 'profile');
                    }
                }


                if ( !empty($_POST['first-name']))
                {
                    update_user_meta($selectedUser->ID, 'first_name', esc_attr($_POST['first-name']));
                }

                if ( !empty($_POST['last-name']))
                {
                    update_user_meta($selectedUser->ID, 'last_name', esc_attr($_POST['last-name']));
                }


                if (isset($_POST['myDistrCap']) && $_POST['myDistrCap'] == 1)
                {
                    $selectedUser->add_cap( 'can_view_my_distributor_tab' );
                } else {
                    $selectedUser->remove_cap( 'can_view_my_distributor_tab' );
                }

                if (isset($_POST['plcOrderCap']) && $_POST['plcOrderCap'] == 1)
                {
                    $selectedUser->add_cap( 'can_view_place_order_tab' );
                } else {
                    $selectedUser->remove_cap( 'can_view_place_order_tab' );
                }

                if (isset($_POST['hlpSupportCap']) && $_POST['hlpSupportCap'] == 1)
                {
                    $selectedUser->add_cap( 'can_view_help_support_tab' );
                } else {
                    $selectedUser->remove_cap( 'can_view_help_support_tab' );
                }

                if (isset($_POST['shrInfoCap']) && $_POST['shrInfoCap'] == 1)
                {
                    $selectedUser->add_cap( 'can_view_share_info_tab' );
                } else {
                    $selectedUser->remove_cap( 'can_view_share_info_tab' );
                }

                if (isset($_POST['mdAssetsCap']) && $_POST['mdAssetsCap'] == 1)
                {
                    $selectedUser->add_cap( 'can_view_media_assets_tab' );
                } else {
                    $selectedUser->remove_cap( 'can_view_media_assets_tab' );
                }

            }

        }

    } else {
        $error[] = __('You do not have permissions to edit this distributor.', 'profile');
    }


    /* Redirect so the page will show updated info.*/
    if (count($error) == 0)
    {
        wp_redirect(get_distributor_portal_url());
        exit;
    } else {
        foreach ($error as $key=>$value){
            wc_add_notice ($value, 'error');
        }
    }
}


/* If profile was saved, update profile. */
if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) && $_POST['action'] == 'edit-distributor' && !empty($_POST['distributor-id']) && is_numeric($_POST['distributor-id']))
{
    /* Add user fields. */
    if (check_if_action_allowed ($_POST['distributor-id'])){

        $selectedUser = get_userdata($_POST['distributor-id']);

        if ($selectedUser === false){
            $error[] = __('Unable to fetch distributor info.', 'storefront');
        }

    } else {
        $error[] = __('You do not have permissions to edit this distributor.', 'profile');
    }

    if (count($error) >= 0)
    {
        foreach ($error as $key=>$value){
            wc_add_notice ($value, 'error');
        }
    }
}

function check_if_action_allowed ($subDistribID)
{
    global $current_user;
    return is_numeric($subDistribID) && $current_user->ID == get_user_meta($subDistribID, '_sub-distributor-of', true);
}

function check_post (&$error)
{
    $doneLogin = !empty($_POST['user-login']);
    if (!$doneLogin)
    {
        $error[] = __('The login you have entered is not valid. Please try again.', 'profile');
    }

    $doneFName = !empty($_POST['first-name']);
    if (!$doneFName)
    {
        $error[] = __('The first name you have entered is not valid. Please try again.', 'profile');
    }

    $doneLName = !empty($_POST['last-name']);
    if (!$doneFName)
    {
        $error[] = __('The last name you have entered is not valid. Please try again.', 'profile');
    }

    $donePass = ($_POST['action'] == 'save-distributor') && empty($_POST['pass1']) && empty($_POST['pass2']);
    $donePass = $donePass || (( !empty($_POST['pass1']) && !empty($_POST['pass2'])) && ($_POST['pass1'] == $_POST['pass2']));
    if (!$donePass)	{
        $error[] = __('The passwords you have entered do not match. Password is the required.', 'profile');
    }

    $doneEmail = ( !empty($_POST['email1']) && !empty($_POST['email2']))&&($_POST['email1'] == $_POST['email2'])&&(is_email($_POST['email1']));
    if (!$doneEmail)
    {
        $error[] = __('The email you have entered is not valid. Please try again.', 'profile');
    } else {
        $emlExistsID = email_exists(esc_attr($_POST['email1']));
        $doneEmail = $doneEmail &&  $emlExistsID === false;
        if ($doneEmail)
        {
            $_POST['email'] = $_POST['email1'];
        } elseif ($emlExistsID != $_POST['distributor-id']) {
            $error[] = __('This email is already used by another user. Please try a different one. ', 'profile');
        } else {
            $doneEmail = true;
        }
    }

    return $doneLogin && $donePass && $doneEmail && $doneFName && $doneLName;
}

$info = distributor_info();

get_header();
include_once "add-edit-distributor-form.tpl.php";
get_footer();