<?php  
namespace Concrete\Package\D3Mailchimp\Controller\SinglePage\Dashboard;

use Concrete\Core\Page\Controller\DashboardPageController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class D3Mailchimp extends DashboardPageController
{
    public function view()
    {
        $response = new RedirectResponse($this->action('settings'));
        $response->send();
    }
}
