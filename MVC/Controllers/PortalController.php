<?php
namespace MVC\Controllers;

use MVC\View;
use MVC\Models\Portal;
use MVC\Models\Licenses;
use Nalpeiron\Services\DeleteLicenseCodeActivity;

class PortalController extends AbstractController
{

    protected static $accountData;
    const LICENSE_TYPE_PERPETUAL = 0;
    
    protected function getAccountData()
    {
        if (!self::$accountData) {
            $model = Portal::instance();
            self::$accountData = [
                'quickBuyProducts' => $model->quickBuyProducts(),
                'quickBuy' => $model->quickBuy(),
                'quickBuyIDs' => $model->quickBuyIDs(),
                'info' => $model->customer_info(),
            ];
        }

        return self::$accountData;
    }

    public function myAccountAction()
    {
        return View::factory('portal/my-account', $this->getAccountData())->render();
    }

    /**
     * @see portal_add_to_cart_redirect()
     *
     * @return string
     * @throws \Exception
     */
    public function licensesAndSoftwareAction()
    {
        portal_add_to_cart_redirect('/portal/');

        $model = Licenses::instance();

        $data = [
            'blog' => [
                1 => $model->getLicenseCodes(),
                4 => $model->getLicenseCodes(4),
            ],
            'virtualProductIDs' => $model->getVirtualProductIDs(getBlogIdByIp()), 
            'userSerials' => $model->getUserSerials(), // all
            'quickBuy' => Portal::instance()->quickBuy(),
        ];

        return View::factory('portal/licenses-and-software', $data)->render();
    }

    public function subscriptionsAction()
    {
        $data = [
            'blog' => [1,4],
        ];
        return View::factory('portal/subscriptions', $data)->render();
    }

    /**
     * ajax
     * @see /public/wp-content/themes/storefront/MVC/Views/licenses-and-software.js
     * @throws \Exception
     */
    public function deleteLicenseCodeActivityAction()
    {
        extract(isset($_POST['data']) ? $_POST['data'] : []);

        if (!isset($productid) || !isset($code) || !isset($computerid)) {
            throw new \Exception('Must be set productid & code');
        }

        try {
            DeleteLicenseCodeActivity::instance()->run($productid, $code, $computerid);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * ajax
     * @see /public/wp-content/themes/storefront/MVC/Views/licenses-and-software.js
     * @throws \Exception
     */
    public function cancelSubscriptionAction()
    {
        extract(isset($_POST['data']) ? $_POST['data'] : []);

        if (!isset($key)) {
            throw new \Exception('Must be set subscription key');
        }

        \WC_Subscriptions_Manager::cancel_subscription(get_current_user_id(), $key);
    }


    public function helpAndSupportAction()
    {
        $data = [];

        return View::factory('portal/help-and-support', $data)->render();
    }


    public function productsAndInvoicesAction()
    {
        return View::factory('portal/product-and-invoices', $this->getAccountData())->render();
    }


    /**
     * ajax
     * @see /public/wp-content/themes/storefront/MVC/Views/licenses-and-software.js
     * @throws \Exception
     */
    public function downloadSaveDataAction()
    {
        extract(isset($_POST['data']) ? $_POST['data'] : []);

        try {
            if (!isset($href)) {
                throw new \Exception('Must be set href', 400);
            }
            if (!isset($user_id)) {
                throw new \Exception('Must be set user_id', 400);
            }
            if (!get_current_user_id()) {
                throw new \Exception('Access denied', 403);
            }

            if ($user_id != get_current_user_id()) {
                throw new \Exception('Must be set user_id', 403);
            }

            add_user_meta(get_current_user_id(), '_download_time', time());
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param int $hours
     * @return array|null|object
     */
    function getDownloadedData($hours = null)
    {
        /**
         * @var \wpdb $wpdb
         */
        global $wpdb;

        $result = $wpdb->get_results("
        SELECT
          tm.umeta_id as meta_id,
          users.ID as user_id,
          user_email as email,
          user_login as login,
          tm.meta_value as `time`,
          fname.meta_value as fname,
          lname.meta_value as lname,
          COUNT(user_email) as amount_downloaded
        FROM {$wpdb->prefix}users as users
        RIGHT JOIN {$wpdb->prefix}usermeta as tm
        ON tm.user_id = users.ID
        LEFT JOIN {$wpdb->prefix}usermeta as fname
        ON fname.user_id = users.ID
        LEFT JOIN {$wpdb->prefix}usermeta as lname
        ON lname.user_id = users.ID
        WHERE tm.meta_key = '_download_time'
          AND fname.meta_key = 'first_name'
          AND lname.meta_key = 'last_name'
        GROUP BY user_email
        ORDER BY amount_downloaded DESC;
        ");

        return $result;
    }

    public function adminDownloadDataAction()
    {

        $data = ['result' => $this->getDownloadedData()];

        return View::factory('admin/downloads_stat', $data)->render();
    }

    public function csvDownloadDataAction()
    {
        $data = ['result' => $this->getDownloadedData()];

        $sitename = sanitize_key(get_bloginfo('name'));
        if (!empty($sitename)) {
            $sitename .= '.';
        }

        $filename = $sitename . 'data_downloaded.' . date('Y-m-d-H-i-s') . '.csv';
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Content-Type: text/csv; charset=' . get_option('blog_charset'), true);

        echo View::factory('admin/downloads_csv', $data)->render();
        exit;
    }

}