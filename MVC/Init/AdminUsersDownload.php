<?php
namespace MVC\Init;

use MVC\Singleton;

if (!is_admin()) {
    return;
}

class AdminUsersDownload
{
    use Singleton;

    const PAGE_SLUG = 'users_download';

    protected $parent_slug = 'users.php';
    protected $capability = 'view_users_download';

    public function init()
    {
        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_init', [$this, 'add_caps']);

        if(isset($_POST['export_downloaded_studio'])){
            echo \MVC\Controller::factory('Portal')->render('csvDownloadData');
        }
    }

    public function add_caps()
    {
        $role = get_role('administrator');
        if ($role) {
            $role->add_cap($this->capability);
        }
        $role = get_role('user_editor');
        if ($role) {
            $role->add_cap($this->capability);
        }
    }

    public function menu()
    {
        add_submenu_page(
            $this->parent_slug,
            'Data on who has Software Downloads',
            'Software Downloads',
            $this->capability,
            self::PAGE_SLUG,
            [$this, 'view']
        );
    }

    /**
     * @view downloads_stat.php
     */
    function view()
    {
        echo \MVC\Controller::factory('Portal')->render('adminDownloadData');
    }

}