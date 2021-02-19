<?php  
namespace Concrete\Package\D3Mailchimp\Src;

use Exception;

/**
 * MailChimp API v3.
 **/
class MailChimp
{
    protected $api_key;

    public function __construct($api_key = false)
    {
        $this->api_key = $api_key;
    }

    /**
     * @return bool
     */
    public function hasApiKey()
    {
        return !empty($this->api_key);
    }

    /**
     * Get information about a specific list member.
     *
     * @link http://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/#read-get_lists_list_id_members_subscriber_hash
     *
     * @param string $list_id
     * @param array $data
     *
     * @return false | string (subscribed, unsubscribed, cleaned, pending)
     **/
    public function getSubscriptionStatus($list_id, $data)
    {
        $subscriber_hash = md5(strtolower($data['email']));
        $endpoint = 'lists/' . $list_id . '/members/' . $subscriber_hash;

        try {
            $result = $this->sendRequest($endpoint);

            return $result['status'];
        } catch (Exception $e) {
            // MailChimp returns a 404 if the user isn't subscribed.
            return false;
        }
    }

    /**
     * Add or update a list member.
     *
     * @link http://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/#edit-put_lists_list_id_members_subscriber_hash
     *
     * @param string $list_id
     * @param array $data
     *
     * @throws Exception
     **/
    public function subscribe($list_id, $data)
    {
        $email_hash = md5(strtolower($data['email']));
        $endpoint = 'lists/' . $list_id . '/members/' . $email_hash;

        $arguments = array(
            'email_address' => $data['email'],
            'status' => $data['status'],
            'merge_fields' => $data['merge_fields'],
        );

        $this->sendRequest($endpoint, $arguments, 'PUT');
    }

    /**
     * Get information about all lists.
     *
     * @link http://developer.mailchimp.com/documentation/mailchimp/reference/lists/#read-get_lists
     *
     * @throws Exception
     *
     * @return array
     **/
    public function getLists()
    {
        $response = $this->sendRequest('lists');

        // An array of objects, each representing a list.
        return $response['lists'];
    }

    /**
     * @param string $endpoint
     * @param bool | array $arguments
     * @param string $method
     *
     * @throws Exception
     *
     * @return array
     */
    protected function sendRequest($endpoint, $arguments = false, $method = 'GET')
    {
        $dataCenter = substr($this->api_key, strpos($this->api_key, '-') + 1);

        if (empty($dataCenter)) {
            throw new Exception("Invalid API key");
        }

        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/'.$endpoint;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->api_key);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if (is_array($arguments)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arguments));
        }

        $result = curl_exec($ch);
        $result = json_decode($result, true);

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            throw new Exception($result['detail']);
        }

        return $result;
    }
}
