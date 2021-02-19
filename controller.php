<?php  
namespace Concrete\Package\D3Mailchimp;

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Package\Package;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\Single;
use Exception;

class Controller extends Package
{
    protected $pkgHandle = 'd3_mailchimp';
    protected $appVersionRequired = '5.7.5';
    protected $pkgVersion = '2.0.2';

    protected $single_pages = [
        '/dashboard/d3_mailchimp' => [
            'cName' => 'MailChimp',
        ],
        '/dashboard/d3_mailchimp/settings' => [
            'cName' => 'MailChimp settings',
        ],
    ];

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

    public function upgradeCoreData()
    {
        if (version_compare($this->pkgVersion, 2.0, '<')) {
            throw new Exception(t("This version is not backwards compatible. Uninstall the previous version first to be able to install this version."));
        }

        parent::upgradeCoreData();
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
        if (!BlockType::getByHandle("d3_mailchimp")) {
            BlockType::installBlockType('d3_mailchimp', $pkg);
        }
    }

    protected function installPages($pkg)
    {
        foreach ($this->single_pages as $path => $value) {
            if (!is_array($value)) {
                $path = $value;
                $value = [];
            }

            $page = Page::getByPath($path);
            if (!$page || $page->isError()) {
                $single_page = Single::add($path, $pkg);

                if ($value) {
                    $single_page->update($value);
                }
            }
        }
    }
}
