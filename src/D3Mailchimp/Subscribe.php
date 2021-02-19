<?php

namespace A3020\D3Mailchimp;

use Exception;

class Subscribe
{
    /** @var Mailchimp */
    private $client;

    public function __construct(Mailchimp $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $listId
     * @param array $data
     *
     * @throws Exception
     */
    public function subscribe($listId, array $data)
    {
        $subscriptionStatus = $this->client->getSubscriptionStatus($listId, $data);

        switch ($subscriptionStatus) {
            case 'subscribed':
                throw new Exception(t("You are already subscribed to this list."));
            case 'pending':
                throw new Exception(t("You are already subscribed. Please confirm the subscription email."));
            case 'cleaned':
                throw new Exception(t("This email address bounced. Please use another email address."));
        }

        try {
            $this->client->subscribe($listId, $data);
        } catch (Exception $e) {
            throw new Exception(t("Something went wrong. Error: %s", $e->getMessage()));
        }
    }
}
