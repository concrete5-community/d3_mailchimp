<?php  
namespace Concrete\Package\D3Mailchimp\Controller\SinglePage\Dashboard\D3Mailchimp;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Config\Repository\Repository;
use Core;

class Settings extends DashboardPageController
{
    public function save_success()
    {
        $this->set('message', t('Settings saved'));
    }

    public function save()
    {
        $config = Core::make(Repository::class);
        $token = Core::make('token');

        if ($token->validate('d3_mailchimp.settings.save')) {
            $config->save('d3_mailchimp.settings.api_key',  $this->post('api_key'));
            $this->redirect($this->action('save_success'));
        } else {
            $this->error->add($token->getErrorMessage());
        }
    }
}
