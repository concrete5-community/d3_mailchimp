<?php   
namespace Concrete\Package\D3Mailchimp\Src;

use Illuminate\Support\Collection;

/**
 * MailChimp API v3
 **/
class MailChimp 
{
	protected $api_key;
	
	public function __construct($api_key = false) 
	{
		$this->api_key = $api_key;
	}
	
	/**
	 * @param string $list_id
	 * @param array $data
	 * @return boolean
	 **/
	public function getSubscriptionStatus($list_id, $data) 
	{
		$email_hash = md5(strtolower($data['email']));
		$endpoint = 'lists/' . $list_id . '/members/' . $email_hash;
		
		$arguments = json_encode([
	        'email_address' => $data['email'],
	    ]);
		
		try {
			$result = $this->sendRequest($endpoint, $arguments, 'GET');			
		} catch (\Exception $e) {
			// MailChimp returns a 404 if the user isn't subscribed.
			return false;
		}
		
		return true;
	}
	
	/**
	 * @param string $list_id
	 * @param array $data
	 **/
	public function subscribe($list_id, $data) 
	{
		$email_hash = md5(strtolower($data['email']));
		$endpoint = 'lists/' . $list_id . '/members/' . $email_hash;
		
		$arguments = json_encode([
	        'email_address' => $data['email'],
	        'status'        => $data['status'],
	        'merge_fields'  => $data['merge_fields']
	    ]);
		
		$this->sendRequest($endpoint, $arguments, 'PUT');
	}
	
	/**
	 * @return array
	 **/
	public function getLists()
	{
		$result = $this->sendRequest('lists');

		return $result->get('lists');
	}
	
	/**
	 * @return Collection
	 * @throws Exception
	 */
	public function sendRequest($endpoint, $arguments = false, $method = 'GET') 
	{
		$dataCenter = substr($this->api_key, strpos($this->api_key,'-')+1);
		
		if (empty($dataCenter)) {
			throw new \Exception("Invalid API key");
		}
		
		$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/'.$endpoint;
		
		$ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->api_key);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		if ($arguments) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $arguments);                                                                                   
		}
		              		
	    $result = curl_exec($ch);
		$result = json_decode($result, true);
		
	    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);
		
		if ($http_code != 200) {
			throw new \Exception($result['detail']);
		}
		
        $collection = new Collection(
			$result
		);
		
		return $collection;
	}
}