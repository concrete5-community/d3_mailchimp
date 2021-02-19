<?php     
namespace Concrete\Package\D3Mailchimp\Block\D3Mailchimp;

use Core;
use Concrete\Package\D3Mailchimp\Src\MailChimp;
use Concrete\Core\Block\BlockController;
use Exception;
use Package;

class Controller extends BlockController 
{
	protected $btInterfaceWidth = "450";
	protected $btInterfaceHeight = "350";
	protected $btWrapperClass = 'ccm-ui';

	protected $btTable = "btD3Mailchimp";
	protected $btDefaultSet = "form";
	
	protected $btCacheBlockRecord = true;
	protected $btCacheBlockOutput = true;
	protected $btCacheBlockOutputOnPost = false;
	protected $btCacheBlockOutputForRegisteredUsers = false;
	protected $btCacheBlockOutputLifetime = CACHE_LIFETIME;
	
	protected $mc;
	protected $cfg;
	
	public function on_start()
	{
		$this->cfg = Core::make('config/database');
		
		$this->mc = new MailChimp($this->cfg->get('d3_mailchimp.settings.api_key'));	
	}
	
	public function getBlockTypeName() 
	{
		return t('MailChimp subscribe');
	}
	
	public function getBlockTypeDescription() 
	{
		$p = Package::getByHandle('d3_mailchimp');
		return $p->getPackageDescription();
	}
	
	public function view($bId = false)
	{
		$error = Core::make('helper/validation/error');
		
		if (empty($this->list_id)) {
			$error->add(t('No list has been selected'));
		}
		
		if ($this->request->isPost() && $this->post($this->bID.'bID') == $this->bID) {
			if (!Core::make('helper/validation/strings')->email($this->post($this->bID.'email_address'))) {
				$error->add(t('Invalid email address'));
			}
		
			if (!Core::make('token')->validate('d3_mailchimp.subscribe')) {
				$error->add(t('Invalid token'));	
			}
		
			if (!$error->has()) {
				$data = array(
					'email' => $this->post($this->bID.'email_address'),
					'status' => $this->subscribe_action,
					'merge_fields' => $this->getMergeFieldData()				
                );
			
				$subscriptionStatus = $this->mc->getSubscriptionStatus($this->list_id, $data);
			
				switch ($subscriptionStatus) {
					case 'subscribed':
						$error->add(t("You are already subscribed to this list"));
						break;
					case 'pending':
						$error->add(t("You are already subscribed. Please confirm the subscription email."));
						break;
					case 'cleaned':
						$error->add(t("This email address bounced. Please use another email address"));
						break;
				}
			
				/*
				Subscribe if email address isn't on the list (or not anymore)
				*/
				if ($subscriptionStatus === false OR $subscriptionStatus === 'unsubscribed') {
					try {
						$this->mc->subscribe($this->list_id, $data);
			
						if ($this->subscribe_action == 'subscribed') {
							$this->set("message", t("Thanks for your subscription!"));
						} else {
							$this->set("message", t("Please click on the link in the confirmation email to verify your subscription."));
						}
					} catch (Exception $e) {
						$error->add(t("Something went wrong. Error: %s", $e->getMessage()));
					}
				}
			}
		}
		
		$this->set('errors', $error);
	}
	
	
	/**
	 * 
	 * @return array
	 **/
	public function getMergeFieldData()
	{
		$merge_fields = array('FNAME', 'LNAME');
		
		if ($this->merge_fields) {
			$fields = explode(',', $this->merge_fields);
			
			if (count($fields) > 0) {
				$merge_fields = array_merge($merge_fields, $fields);
			}
		}
		
		$data = array();
		foreach ($merge_fields as $field) {
			$data[$field] = trim($this->post($this->bID.$field));
		}
		
		return $data;
	}
	
	/**
	 * @return array (ID + Name)
	 **/
	public function getListOptions()
	{
		$list_options = array();
		
		try {
			$lists = $this->mc->getLists();

			if ($lists) {
				foreach ($lists as $list) {
					$list_options[$list['id']] = $list['name'];
				}
			}
		} catch (Exception $e) {
			$list_options[''] = t('Lists not available');
		}

		return $list_options;
	}
}