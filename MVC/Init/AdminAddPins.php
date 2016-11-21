<?php
namespace MVC\Init;

use MVC\Singleton;

if (!is_admin()) {
    return;
}

class AdminAddPins
{
    use Singleton;

    const PAGE_SLUG = 'add-pins-to-distributor';

    protected $parent_slug = null; // hidden admin menu
    protected $capability = 'create_pins';

    public function init()
    {
        add_filter('user_row_actions', [$this, 'filter_user_row_actions'], 10, 2);
        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_init', [$this, 'add_caps']);
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

    public function filter_user_row_actions(array $actions, \WP_User $user)
    {
        if ($user->has_cap('distributor')) {
            $link = '?page=' . self::PAGE_SLUG . '&user_id=' . $user->ID;
            $actions['add_pins'] = '<a href="' . $link . '">' . 'Add&nbsp;Pins' . '</a>';
        }

        return $actions;
    }

    public function menu()
    {
        add_submenu_page(
            $this->parent_slug,
            'Add Pins to user',
            'Add Pins',
            $this->capability,
            self::PAGE_SLUG,
            [$this, 'view_add_pins']
        );
    }

    function view_add_pins()
    {
        echo \MVC\Controller::factory('Maps')->render('edit');
    }

}