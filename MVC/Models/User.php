<?php
namespace MVC\Models;

use \MVC\Singleton;

class User
{
    use Singleton;

    const MARKER_META_KEY = '_markers';
    const OPTION_NAME_IS_MIGRATION_MARKERS = 'IS_MIGRATION_MARKERS';

    /**
     * @param int $user_id
     * @param array $data
     * @throws \Exception
     */
    public function addMarker($user_id, array $data)
    {
        if (!(int)$user_id) {
            throw new \Exception('user_id is empty');
        }
        //$data = json_encode($data);
        add_user_meta($user_id, User::MARKER_META_KEY, $data);
    }

    public function deleteMarker($meta_id, $user_id)
    {
        if (!(int)$meta_id || !(int)$user_id) {
            throw new \Exception('Must be set meta_id and user_id');
        }
        global $wpdb;
        $wpdb->delete('fu_usermeta', ['umeta_id' => $meta_id, 'user_id' => $user_id]);
    }

    /**
     * @return array
     */
    public function getAllDistributorsMarkers($user_id = null)
    {
//        if (!get_option(self::OPTION_NAME_IS_MIGRATION_MARKERS)) {
//            MarkersMigration::instance()->run();
//        }

        $sql = "
            SELECT *
            FROM `fu_usermeta`
            WHERE `meta_key` = '" . self::MARKER_META_KEY . "'
        ";
        if ((int)$user_id) {
            $user_id = (int)$user_id;
            $sql .= "AND user_id={$user_id}";
        }
        global $wpdb;
        $result = $wpdb->get_results($sql);

        $data = [];
        foreach ($result as $item) {
            $datum = @unserialize($item->meta_value);
            if ($datum) {
                $data[$item->user_id][$item->umeta_id] = $datum;
            }
        }

        return $data;
    }
}