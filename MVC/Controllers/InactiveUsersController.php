<?php
namespace MVC\Controllers;

use \MVC\View;

class InactiveUsersController extends AbstractController
{

    function getInactiveUsersData($limit = null)
    {
        if ($limit) {
            $limit = "LIMIT 0, " . (int)$limit;
        }
        $date = '18 Aug 2015';
        if (isset($_POST['date'])) {
            $date = $_POST['date'];
        }
        $time = strtotime($date);

        $sql = "
            SELECT *, m.meta_value as last_login
            FROM `fu_users` as u
            LEFT JOIN `fu_usermeta` as m
            ON u.ID = m.user_id AND m.meta_key = 'wp-last-login'
            WHERE m.meta_value < $time OR m.meta_value IS NULL
            $limit
      ";

        global $wpdb;

        return ['result' => $wpdb->get_results($sql), 'date' => $date];
    }

    public function inactiveUsersAction()
    {
        return View::factory('admin/inactive_users', $this->getInactiveUsersData(50))->render();
    }

    public function inactiveUsersCsvAction()
    {
        $filename = 'user_stat.' . date('Y-m-d-H-i-s') . '.csv';
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Content-Type: text/csv; charset=' . get_option('blog_charset'), true);

        echo View::factory('admin/inactive_users_csv', $this->getInactiveUsersData())->render();
        exit;
    }

}