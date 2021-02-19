<?php

namespace A3020\D3Mailchimp;

use Exception;

class ListOptions
{
    /** @var Mailchimp */
    private $client;

    public function __construct(Mailchimp $client)
    {
        $this->client = $client;
    }

    /**
     * MailChimp Lists.
     *
     * See: http://kb.mailchimp.com/lists
     *
     * @return array (ID + Name)
     **/
    public function get()
    {
        $listOptions = [];

        try {
            $lists = $this->client->getLists();

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
}
