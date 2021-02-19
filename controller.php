<?php  
namespace Concrete\Package\D3Mailchimp;

use Package;
use BlockType;

/**
 * @author akodde
 * 
**/
class Controller extends Package 
{
	protected $pkgHandle = 'd3_mailchimp';
	protected $appVersionRequired = '5.7.0.4';
	protected $pkgVersion = '1.0.1';
	
    protected $single_pages = array(
        '/dashboard/d3_mailchimp' => array(
            'cName' => 'MailChimp'
        ),
        '/dashboard/d3_mailchimp/settings' => array(
            'cName' => 'MailChimp settings'
        )
    );
	
	public function getPackageName() 
	{
		return t('Form - MailChimp Subscribe');
	}
	
	public function getPackageDescription() 
	{
		return t('Subscribe to MailChimp lists');
	}
	
	public function install()
	{
		$pkg = parent::install();
		
		$this->installEverything($pkg);
	}
	
	public function upgrade()
	{
		$pkg = parent::getByHandle($this->pkgHandle);
		
		$this->installEverything($pkg);
	}
	
	public function installEverything($pkg)
	{
		$this->installBlockTypes($pkg);
		$this->installPages($pkg);
	}
	
	public function installBlockTypes($pkg)
	{
		if( !BlockType::getByHandle("d3_mailchimp") ){
			BlockType::installBlockTypeFromPackage('d3_mailchimp', $pkg);
		}
	}
	
    protected function installPages($pkg)
    {
        foreach ($this->single_pages as $path => $value) {
            if (!is_array($value)) {
                $path = $value;
                $value = array();
            }
            $page = \Page::getByPath($path);
            if (!$page || $page->isError()) {
                $single_page = \SinglePage::add($path, $pkg);

                if ($value) {
                    $single_page->update($value);
                }
            }
        }
    }
}