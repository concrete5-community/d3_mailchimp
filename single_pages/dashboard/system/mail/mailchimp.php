<?php

defined('C5_EXECUTE') or die('Access Denied.');

/** @var \Concrete\Core\Validation\CSRF\Token $token */
/** @var string $apiKey */
?>

<form method="post" action="<?php echo $this->action('save') ?>">
	<?php $token->output('d3_mailchimp.settings.save'); ?>

    <?php
    if (empty($apiKey)) {
        ?>
        <p style="margin-bottom: 30px;">
            <?php
            echo t('Please copy the API key from your <a href="%s" target="_blank">MailChimp account</a>.',
                'https://admin.mailchimp.com/account/api/'
            );
            ?>
        </p>
        <?php
    }
    ?>

	<div class="form-group">
		<?php  
        echo $form->label('api_key', t('API key').' *');
        echo $form->text('api_key', $apiKey, [
			'style' => 'max-width: 700px',
            'autofocus' => 'autofocus',
		]);
        ?>
	</div>
	
	<div class="ccm-dashboard-form-actions-wrapper">
		<div class="ccm-dashboard-form-actions">
			<button class="pull-right btn btn-primary" type="submit"><?php echo t('Save') ?></button>
		</div>
	</div>
</form>
