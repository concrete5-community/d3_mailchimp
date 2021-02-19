<?php

namespace A3020\D3Mailchimp;

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Entity\Package;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\Single;

class Installer
{
    public function install(Package $pkg)
    {
        $this->installBlockTypes($pkg);
        $this->installPages($pkg);
        $this->deleteOldPages();
    }

    private function installBlockTypes($pkg)
    {
        if (BlockType::getByHandle('d3_mailchimp')) {
            return;
        }

        BlockType::installBlockType('d3_mailchimp', $pkg);
    }

    private function installPages($pkg)
    {
        $path = '/dashboard/system/mail/mailchimp';

        /** @var Page $page */
        $page = Page::getByPath($path);
        if ($page && !$page->isError()) {
            return;
        }

        $single_page = Single::add($path, $pkg);
        $single_page->update([
            'cName' => 'MailChimp',
        ]);
    }

    private function deleteOldPages()
    {
        foreach([
            '/dashboard/d3_mailchimp',
            '/dashboard/d3_mailchimp/settings',
        ] as $path) {
            /** @var Page $page */
            $page = Page::getByPath($path);
            if ($page && !$page->isError()) {
                $page->delete();
            }
        }
    }
}
