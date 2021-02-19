<?php

namespace Concrete\Package\D3Mailchimp;

use A3020\D3Mailchimp\Installer;
use Concrete\Core\Package\Package;
use Concrete\Core\Support\Facade\Package as PackageFacade;

class Controller extends Package
{
    protected $pkgHandle = 'd3_mailchimp';
    protected $appVersionRequired = '8.0';
    protected $pkgVersion = '3.1.0';
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
        $pkg = PackageFacade::getByHandle($this->pkgHandle);

        $installer = $this->app->make(Installer::class);
        $installer->install($pkg);
    }
}
