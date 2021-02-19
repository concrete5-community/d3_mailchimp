<?php

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Support\Facade\Url;
?>
<p><?php echo t('Congratulations, the add-on has been installed!'); ?></p>
<br>

<p>
    <strong><?php echo t('In order to configure MailChimp, go to:'); ?></strong><br>
    <a class="btn btn-default" href="<?php echo Url::to('/dashboard/system/mail/mailchimp') ?>">
        <?php
        echo t('System & Settings / Email / MailChimp');
        ?>
    </a>
</p>
