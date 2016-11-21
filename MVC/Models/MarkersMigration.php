<?php
namespace MVC\Models;

use \MVC\Singleton;

class MarkersMigration
{
    use Singleton;

    protected $info_keys = ['email', 'first_name', 'last_name', 'company', 'phone',];
    protected $address_keys = ['country', 'state', 'city', 'address_1', 'address_2', 'postcode',];

    public function run()
    {
        // get_users()
        $query_args = array();
        $query_args['fields'] = array('ID', 'display_name');
        $query_args['role'] = 'distributor';
        //$query_args['number'] = '10000';
        $users = get_users($query_args);

        foreach ($users as $u) {
            //$users_array[$user->ID] = $user->display_name;
            $user = new \WP_User($u->ID);
            if (get_user_meta($u->ID, User::MARKER_META_KEY)) {
                continue;
            }

            $user_info = \MVC\Helpers\Arr::merge(
                [
                    'id' => $u->ID,
                    'email' => $user->user_email,
                ],
                $this->getUserMeta($u->ID, 'billing_', $this->info_keys)
            );

            $address = $this->getUserMeta($u->ID, 'billing_', $this->address_keys);
            $address = implode(', ', $address);
            if (!$address) {
                $address = $this->getUserMeta($u->ID, 'billing3c_', $this->address_keys);
                $address = implode(', ', $address);
            }
            if (!$address) {
                $address = $this->getUserMeta($u->ID, 'billing3c3c_', $this->address_keys);
                $address = implode(', ', $address);
            }

            if (!$address) {
                continue;
            }

            try {
                list($lat, $lng, $formatted_address, $state, $country) = GeoCoding::instance()->getLocation($address);
                if (!$formatted_address) {
                    continue;
                }
            } catch (\Exception $e) {
                if ($e->getMessage() == 'OVER_QUERY_LIMIT') {
                    throw new \Exception('OVER_QUERY_LIMIT');
                }
                continue;
            }

            $info = '';
            if (isset($user_info['first_name']) && isset($user_info['last_name'])) {
                $info .= $user_info['first_name'] . ' ' . $user_info['last_name'] . '<br/>';
            } else {
                $info .= $user->last_name . ' ' . $user->first_name;
            }
            if (isset($user_info['company'])) {
                $info .= 'Company: ' . $user_info['company'] . '<br/>';
            }
            if (isset($user_info['phone'])) {
                $info .= 'Phone: ' . $user_info['phone'] . '<br/>';
            }

            $data = [
                'user_id' => $u->ID,
                'addr' => $formatted_address,
                'email' => $user_info['email'],
                'info' => $info,
                'lat' => $lat,
                'lng' => $lng,
                'formatted_address' => $formatted_address,
            ];

            User::instance()->addMarker($u->ID, $data);
        }

        if (!WP_DEBUG) {
            update_option(User::OPTION_NAME_IS_MIGRATION_MARKERS, 'done');
        }

        return;
    }

    /**
     * @param int $user_id
     * @param string $prefix
     * @param array $keys
     * @return array
     */
    protected function getUserMeta($user_id, $prefix, array $keys)
    {
        $data = [];
        foreach ($keys as $item) {
            $value = get_user_meta($user_id, $prefix . $item, true);
            if ($value) {
                $data[$item] = $value;
            }
        }

        return $data;
    }

    //delete  FROM `fu_usermeta` WHERE `meta_key` LIKE '_markers' ORDER BY `user_id` DESC

}