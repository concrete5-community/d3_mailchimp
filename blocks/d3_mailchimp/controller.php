<?php

namespace Concrete\Package\D3Mailchimp\Block\D3Mailchimp;

use A3020\D3Mailchimp\ListOptions;
use A3020\D3Mailchimp\Mailchimp;
use A3020\D3Mailchimp\Subscribe;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Block\BlockController;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Http\Request;
use Exception;

class Controller extends BlockController
{
    protected $btInterfaceWidth = "450";
    protected $btInterfaceHeight = "400";
    protected $btWrapperClass = 'ccm-ui';
    protected $btTable = "btD3Mailchimp";
    protected $btDefaultSet = "form";
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = false;
    protected $btCacheBlockOutputOnPost = false;
    protected $btCacheBlockOutputForRegisteredUsers = false;

    /** @var Mailchimp */
    protected $client;

    /* @var $error \Concrete\Core\Error\ErrorList\ErrorList */
    protected $error;

    /** @var string */
    protected $subscribeAction;

    /** @var string */
    protected $mergeFields;

    /** @var string */
    protected $listId;

    /** @var int */
    protected $styling;

    public function getBlockTypeName()
    {
        return t('MailChimp Subscribe');
    }

    public function getBlockTypeDescription()
    {
        return t('Subscribe to MailChimp lists');
    }

    public function on_start()
    {
        $this->client = new MailChimp($this->getApiKey());
        $this->error = $this->app->make('helper/validation/error');
    }

    public function view()
    {
        $this->set('token', $this->app->make('token'));

        if (empty($this->listId)) {
            $this->error->add(t('No list has been selected.'));
        }

        $this->set('errors', $this->error);
    }

    public function registerViewAssets($outputContent = '')
    {
        $al = AssetList::getInstance();

        if ((int) $this->styling === 1) {
            $al->register('css', 'd3_mailchimp/styling', 'blocks/d3_mailchimp/css_files/basic.css', [], 'd3_mailchimp');
            $this->requireAsset('css', 'd3_mailchimp/styling');
        }
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

        if (!$this->app->make('token')->validate('d3_mailchimp.subscribe')) {
            $this->error->add(t('Invalid token'));
        }

        if (!$this->app->make('helper/validation/strings')->email($this->post('email_address'))) {
            $this->error->add(t('Invalid email address'));
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

    public function add()
    {
        $this->addEdit();
    }

    public function edit()
    {
        $this->addEdit();
    }

    private function addEdit()
    {
        $this->set('app', $this->app);
        $this->set('hasApiKey', $this->hasApiKey());
        $this->set('listOptions', $this->getListOptions());
        $this->set('subscribeActions', $this->getSubscribeActions());
    }

	/**
     * @param array $data
     */
    private function subscribe($data)
    {
        try {
            (new Subscribe($this->client))->subscribe($this->listId, $data);

            if ($this->subscribeAction === 'subscribed') {
                $this->set('message', t('Thanks for your subscription!'));
            } else {
                $this->set('message', t('Please click on the link in the confirmation email to verify your subscription.'));
            }
        } catch (Exception $e) {
            $this->error->add($e->getMessage());
        }
    }

    /**
     * See: http://kb.mailchimp.com/merge-tags/getting-started-with-merge-tags
     *
     * @return array
     **/
    private function getMergeFieldData()
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

    private function getListOptions()
    {
        return (new ListOptions($this->client))->get();
    }

	/**
     * @return array
     */
    private function getSubscribeActions()
    {
        return [
            'subscribed' => t('Subscribe without verification email'),
            'pending' => t('Subscribe with verification email'),
        ];
    }

	/**
     * @return bool
     */
    private function hasApiKey()
    {
        return !empty($this->getApiKey());
    }

	/**
     * @return string
     */
    private function getApiKey()
    {
        $config = $this->app->make(Repository::class);

        return (string) $config->get('d3_mailchimp.settings.api_key');
    }
}
