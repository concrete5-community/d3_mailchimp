<?php  
namespace Concrete\Package\D3Mailchimp\Block\D3Mailchimp;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Error\Error;
use Concrete\Core\Http\Request;
use Concrete\Core\Package\Package;
use Concrete\Package\D3Mailchimp\Src\MailChimp;
use Core;
use Exception;

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

    /** @var MailChimp */
    protected $mc;

    /** @var Error */
    protected $error;

    protected $subscribeAction;
    protected $mergeFields;
    protected $listId;

    public function getBlockTypeName()
    {
        return t('MailChimp subscribe');
    }

    public function getBlockTypeDescription()
    {
        $p = Package::getByHandle('d3_mailchimp');

        return $p->getPackageDescription();
    }

    public function on_start()
    {
        $this->mc = new MailChimp($this->getApiKey());
        $this->error = Core::make('helper/validation/error');
    }

    public function view()
    {
        if (empty($this->listId)) {
            $this->error->add(t('No list has been selected'));
        }

        $this->set('errors', $this->error);
    }
    
    public function action_submit($bId = false)
    {
        if (!Request::isPost()) {
            $this->view();
            return;
        }

        if ($bId !== $this->bID) {
            $this->view();
            return;
        }

        if (!Core::make('helper/validation/strings')->email($this->post('email_address'))) {
            $this->error->add(t('Invalid email address'));
        }

        if (!Core::make('token')->validate('d3_mailchimp.subscribe')) {
            $this->error->add(t('Invalid token'));
        }

        $data = [
            'email' => $this->post('email_address'),
            'status' => $this->subscribeAction,
            'mergeFields' => $this->getMergeFieldData(),
        ];

        if (!$this->error->has()) {
            $this->subscribe($data);
        }

        $this->view();
    }

    public function edit()
    {
        $this->set('hasApiKey', $this->hasApiKey());
        $this->set('listOptions', $this->getListOptions());
        $this->set('subscribeActions', $this->getSubscribeActions());
    }

	/**
     * @param array $data
     */
    protected function subscribe($data)
    {
        $subscriptionStatus = $this->mc->getSubscriptionStatus($this->listId, $data);

        switch ($subscriptionStatus) {
            case 'subscribed':
                $this->error->add(t("You are already subscribed to this list"));
                break;
            case 'pending':
                $this->error->add(t("You are already subscribed. Please confirm the subscription email."));
                break;
            case 'cleaned':
                $this->error->add(t("This email address bounced. Please use another email address"));
                break;
        }

        if ($subscriptionStatus !== false && $subscriptionStatus !== 'unsubscribed') {
            return;
        }

        try {
            $this->mc->subscribe($this->listId, $data);

            if ($this->subscribeAction == 'subscribed') {
                $this->set("message", t("Thanks for your subscription!"));
            } else {
                $this->set("message", t("Please click on the link in the confirmation email to verify your subscription."));
            }
        } catch (Exception $e) {
            $this->error->add(t("Something went wrong. Error: %s", $e->getMessage()));
        }
    }

    /**
     * See: http://kb.mailchimp.com/merge-tags/getting-started-with-merge-tags
     *
     * @return array
     **/
    protected function getMergeFieldData()
    {
        $mergeFields = ['FNAME', 'LNAME'];

        if ($this->mergeFields) {
            $fields = explode(',', $this->mergeFields);

            if (count($fields) > 0) {
                $mergeFields = array_merge($mergeFields, $fields);
            }
        }

        $data = [];
        foreach ($mergeFields as $field) {
            $value = trim($this->post($field));
            if (!empty($value)) {
                $data[$field] = $value;
            }
        }

        return $data;
    }

    /**
     * MailChimp Lists.
     *
     * See: http://kb.mailchimp.com/lists
     *
     * @return array (ID + Name)
     **/
    protected function getListOptions()
    {
        $listOptions = [];

        try {
            $lists = $this->mc->getLists();

            if ($lists) {
                foreach ($lists as $list) {
                    $listOptions[$list['id']] = $list['name'];
                }
            }
        } catch (Exception $e) {
            $listOptions[''] = t('Lists not available');
        }

        return $listOptions;
    }

	/**
     * @return array
     */
    protected function getSubscribeActions()
    {
        return [
            'subscribed' => t('Subscribe without verification email'),
            'pending' => t('Subscribe with verification email'),
        ];
    }

	/**
     * @return bool
     */
    protected function hasApiKey()
    {
        return !empty($this->getApiKey());
    }

	/**
     * @return string
     */
    protected function getApiKey()
    {
        $config = Core::make('config');
        return (string) $config->get('d3_mailchimp.settings.api_key');
    }
}
