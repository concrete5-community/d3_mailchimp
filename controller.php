<?php

namespace Concrete\Package\D3Mailchimp;

use A3020\D3Mailchimp\Installer;
use Concrete\Core\Package\Package;
use Concrete\Core\Package\PackageService;

class Controller extends Package
{
    protected $pkgHandle = 'd3_mailchimp';
    protected $appVersionRequired = '9.0';
    protected $pkgVersion = '4.0.0';
    protected $pkgAutoloaderRegistries = [
        'src/D3Mailchimp' => '\A3020\D3Mailchimp',
    ];

	public function getPackageName()
    {
        return t('MailChimp Subscribe');
    }

    public function getPackageDescription()
    {
        return t('Subscribe to MailChimp lists');
    }

    public function install()
    {
        $pkg = parent::install();

        $installer = $this->app->make(Installer::class);
        $installer->install($pkg);
    }

    public function upgrade()
    {
	    $pkg = $this->app->make(PackageService::class)->getByHandle($this->pkgHandle);

        $installer = $this->app->make(Installer::class);
        $installer->install($pkg);
    }
}
