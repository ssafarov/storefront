<?php
namespace MVC\Models;

use \Exception;
use \WC_Product;
use \Nalpeiron\Services\GetLicenseCode;
use \Nalpeiron\Services\GetLicenseCodeActivity;
use \Nalpeiron\Services\GetSystemDetails;
use \MVC\Singleton;
use \WC_Order;

class Licenses
{
    use Singleton;

    const NOT_AVAILABLE = 'The license service is not available at the moment. Please check again in 5 minutes.';


    private function _calculateSerialCRC($sSerial)
    {
        if( !is_numeric($sSerial) )
            return -1;

        $aSerial = str_split($sSerial);
        $count = count($aSerial)-1;
        $sum = 0;
        $i = 0;
        while ($i <= $count) {
            $sum = $sum + ((($i % 2) == 1)? (int)$aSerial[$i] * 3 : (int)$aSerial[$i] * 1);
            $i++;
        }

        $i = 0;
        while ($i < $sum) {
            $i = $i + 10;
        }

        return $i - $sum;
    }

    /**
     * @return array
     */
    public function getVirtualProductIDs($blog_id = null)
    {
        static $result;
        if (!is_array($result)) {
            $result = [];
        }
        if (isset($result[$blog_id]) && $result[$blog_id]) {
            return $result[$blog_id];
        }

        if ((int)$blog_id) {
            $blog_id = (int)$blog_id;
            switch_to_blog($blog_id);
        }

        /*
        $result[$blog_id] = get_posts([
            'post_type' => ['product', 'product_variation'],
            'posts_per_page' => -1,
            'post_status' => ['publish', 'private'],
            'meta_query' => [
                [
                    'key' => '_visibility',
                    'value' => ['catalog', 'visible'],
                    'compare' => 'IN'
                ],
                [
                    'key' => '_virtual',
                    'value' => 'yes'
                ]
            ],
            'fields' => 'id=>parent'
        ]);
*/

        $prefix = (get_current_blog_id() == 4)?'fu_4_':'fu_';
        global $wpdb;
        $sql = "
              SELECT distinct p.ID
              FROM {$prefix}posts as p
              JOIN {$prefix}postmeta as m
                ON p.ID = m.post_id    
              WHERE ( p.post_type = 'product' OR p.post_type = 'product_variation' )
               AND ( p.post_status = 'publish' )
               AND ( m.meta_key ='_virtual' AND m.meta_value = 'yes' )
               AND ( m.meta_key ='_product_attributes' LIKE 'nalpeiron%' )
              ;
            ";
        $result[$blog_id] = [];
        foreach ($wpdb->get_results($sql) as $post) {
            $result[$blog_id][] = $post->ID;
        }

        if ($blog_id) {
            restore_current_blog();
        }

        return $result[$blog_id];
    }

    /**
     * @return array
     * @throws Exception
     */
    function getLicenseCodes($blog_id = null)
    {
        if ((int)$blog_id) {
            $blog_id = (int)$blog_id;
            switch_to_blog($blog_id);
        }

        $prefix = (get_current_blog_id() == 4)?'fu_4_':'fu_';
        global $wpdb;
        $result = $wpdb->get_results("
            SELECT *
            FROM `{$prefix}license`
            WHERE `order_user_id` = " . wp_get_current_user()->ID
        );

        $data = [];
        $unique = [];

        foreach ($result as $item) {
            $product = new WC_Product($item->product_id);
            $nalpeiron_productid = $product->get_attribute('nalpeiron_productid');
            if (!$nalpeiron_productid) {
                throw new Exception('Current product (' . $product->get_title() . ') does not have an attribute "nalpeiron_productid".');
            }

            $codes = explode(',', $item->codes);
            $datum = (array)$item;
            $datum['nalpeiron_productid'] = $nalpeiron_productid;
            $data[] = $datum;

            foreach ($codes as $code) {
                if (isset($unique[$nalpeiron_productid . '-' . $code])) {
                    continue;
                }

                $datum['code'] = $code;

                try {
                    $datum['license'] = GetLicenseCode::instance()->run($nalpeiron_productid, $code);
                } catch (Exception $e) {
                    $datum['license'] = $e->getCode() == 0 ? $e->getMessage() : self::NOT_AVAILABLE;
                }
                try {
                    $datum['activity'] = GetLicenseCodeActivity::instance()->run($nalpeiron_productid, $code);
                } catch (Exception $e) {
                    $datum['activity'] = $e->getCode() == 0 ? $e->getMessage() : self::NOT_AVAILABLE;
                }

                if (is_array($datum['activity'])) {
                    foreach ($datum['activity'] as &$activity) {
                        try {
                            $activity['info'] = GetSystemDetails::instance()
                                ->run($nalpeiron_productid, $activity['computerid']);
                        } catch (Exception $e) {
                            $activity['info'] = $e->getCode() == 0 ? $e->getMessage() : self::NOT_AVAILABLE;
                        }
                    }
                }

                $unique[$nalpeiron_productid . '-' . $code] = $datum;
            }
        }

        if ($blog_id) {
            restore_current_blog();
        }

        return [
            'licenseCodes' => $data,
            'uniqueLicenseCodes' => $unique,
        ];
    }

    /**
     * @return array
     */
    public function getUserSerials()
    {
        global $baweicmu_options, $current_user;

        $result = [];

        if (!$current_user) {
            $current_user = wp_get_current_user();
        }

        if ($current_user->exists() && isset($baweicmu_options['codes']) && is_array($baweicmu_options['codes'])) {
            foreach ($baweicmu_options['codes'] as $code => $item) {
                if (isset($item['users']) && is_array($item['users'])) {
                    foreach ($item['users'] as $user) {
                        if ($current_user->user_login == strtolower($user)) {
                            $result[] = $code . $this->_calculateSerialCRC($code);
                        }
                    }
                }
            }
        }

        return $result;
    }


    /**
     * Returns all licenses from the given order
     *
     * @param WC_Order $order
     * @return array
     */
    public function get_order_licenses($order)
    {
        $products = $order->get_items();
        $licenses = [];

        foreach ($products as $product) {
            if (!empty($product['item_meta'][NALPEIRON_HIDDEN_LICENSE_CODE])) {
                $codes = reset($product['item_meta'][NALPEIRON_HIDDEN_LICENSE_CODE]);
                $codes = explode(',', $codes);
                foreach ($codes as $code) {
                    $licenses[] = [
                        'name' => $product['name'],
                        'code' => \Nalpeiron::licenseEncode($code),
                    ];
                }


            }
        }

        return $licenses;
    }
}