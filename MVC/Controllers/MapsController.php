<?php
namespace MVC\Controllers;

use \MVC\View;
use \MVC\Models\User;

class MapsController extends AbstractController
{
    public static $CAP = ['admin', 'create_pins']; // distributor

    public function isAdmin()
    {
        return did_action('admin_init');
    }

    public function isAccess()
    {
        foreach (self::$CAP as $cap) {
            if (wp_get_current_user()->has_cap($cap)) {
                return true;
            }
        }

        return false;
    }

    public function getUserId()
    {
        if (!$this->isAccess()) {
            throw new \Exception('Access denied');
        }

        if ($this->isAdmin()) {
            if (!isset($_REQUEST['user_id']) && !isset($this->post['user_id'])) {
                throw new \Exception('user_id must be set');
            }

            return isset($_REQUEST['user_id']) ? (int)$_REQUEST['user_id'] : (int)$this->post['user_id'];
        }

        return get_current_user_id();
    }

    public function getUser()
    {
        return new \WP_User($this->getUserId());
    }

    /**
     * ajax
     */
    public function addMarkerAction()
    {
        if (!isset($this->post['data']) || !is_array($this->post['data'])) {
            throw new \Exception('Data is empty');
        }

        $data = [];
        foreach ($this->post['data'] as $item) {
            $data[$item['name']] = $item['value'];
        }

        $user_id = (int)$data['user_id'];
        if (!$user_id) {
            throw new \Exception('user_id is empty');
        }

        if (!$this->isAccess()) {
            throw new \Exception('Access denied', 403);
        }

        if (!$this->getUser()->has_cap('distributor')) {
            throw new \Exception('User is not distributor', 403);
        }

        $save_data = array_flip(['user_id', 'addr', 'email', 'info', 'lat', 'lng', 'formatted_address']);
        foreach ($save_data as $key => $datum) {
            $save_data[$key] = $data[$key];
        }

        $save_data['info'] = nl2br(substr(strip_tags($save_data['info']), 0, 1000));

        User::instance()->addMarker($this->getUserId(), $save_data);

        return $this->editMarkersAction();
    }

    /**
     * @return string
     */
    public function editAction()
    {
        return View::factory('maps/edit', [
            'context' => $this,
        ])->render();
    }

    /**
     * ajax & include
     */
    public function editMarkersAction()
    {
        $data = [];
        $data['markers'] = User::instance()->getAllDistributorsMarkers($this->getUserId());

        return View::factory('maps/edit-markers', $data)->render();
    }

    public function deleteMarkerAction()
    {
        if (!isset($this->post['data']['id']) && !(int)$this->post['data']['id']) {
            throw new \Exception('id is empty');
        }
        $id = (int)$this->post['data']['id'];
        User::instance()->deleteMarker($id, $this->getUserId());
    }

    public function distributorMapAction()
    {
        $data = [];
        $data['markers'] = User::instance()->getAllDistributorsMarkers();

        return View::factory('maps/distributor', $data)->render();
    }

}