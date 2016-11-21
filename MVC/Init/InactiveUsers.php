<?php
namespace MVC\Init;

use MVC\Singleton;

if (!is_admin()) {
    return;
}

class InactiveUsers
{
    use Singleton;

    const PAGE_SLUG = 'inactive_users';

    protected $parent_slug = 'users.php';

    protected $capability = '';

    public function init()
    {
        add_action('admin_menu', [$this, 'menu']);

        if(isset($_POST['inactive_users_csv'])){
            echo \MVC\Controller::factory('InactiveUsers')->render('inactiveUsersCsv');
        }
    }

    public function menu()
    {
        add_submenu_page(
            $this->parent_slug,
            'Inactive Users',
            'Inactive Users',
            $this->capability,
            self::PAGE_SLUG,
            [$this, 'inactiveUsers']
        );
    }

    function inactiveUsers()
    {
        echo \MVC\Controller::factory('InactiveUsers')->render('inactiveUsers');
    }

}