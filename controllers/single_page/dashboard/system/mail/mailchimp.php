<?php  

namespace Concrete\Package\D3Mailchimp\Controller\SinglePage\Dashboard\System\Mail;

use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Page\Controller\DashboardPageController;

class Mailchimp extends DashboardPageController
{
    public function view()
    {
        $config = $this->app->make(Repository::class);

        $this->set('token', $this->app->make('token'));
        $this->set('apiKey', $config->get('d3_mailchimp.settings.api_key'));
    }

    public function save()
    {
        $config = $this->app->make(Repository::class);
        $token = $this->app->make('token');

        if ($token->validate('d3_mailchimp.settings.save')) {
            $config->save('d3_mailchimp.settings.api_key',  $this->post('api_key'));
            $this->redirect($this->action('save_success'));
        } else {
            $this->error->add($token->getErrorMessage());
        }
    }

    public function save_success()
    {
        $this->set('message', t('API key has been saved. You can now add a MailChimp form on a page.'));

        $this->view();
    }
}
