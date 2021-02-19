<?php   
namespace Concrete\Package\D3Mailchimp\Controller\SinglePage\Dashboard\D3Mailchimp;

use Concrete\Package\D3Mailchimp\Src\MailChimp;
use Config;

use Concrete\Core\Page\Controller\DashboardPageController;

class Settings extends DashboardPageController
{
	protected $mc;
	protected $cfg;
	
    public function __construct($c)
    {
        parent::__construct($c);
    }
	
    public function save_success()
    {
        $this->set('message', t('Settings saved'));
    }
	
	public function on_start()
	{
		$this->error = \Core::make('helper/validation/error');
		$this->cfg = \Core::make('config/database');
		
		$this->mc = new MailChimp($this->cfg->get('d3_mailchimp.settings.api_key'));
		
		$this->set('cfg', $this->cfg);
	}
	
	public function save() 
	{
		if (\Core::make('token')->validate('d3_mailchimp.settings.save')) {
			$this->cfg->save('d3_mailchimp.settings.api_key', $this->post('api_key'));
			
			$this->redirect($this->action('save_success'));
		} else {
			$this->error->add(\Core::make('token')->getErrorMessage());
		}
	}
}