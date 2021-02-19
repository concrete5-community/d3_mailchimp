<?php  
defined('C5_EXECUTE') or die('Access Denied.');

$token = Core::make('token');
?>

<form method="post" action="<?php   echo $controller->action('save') ?>">
	<?php   $token->output('d3_mailchimp.settings.save'); ?>

	<div class="form-group">
		<?php  
        echo $form->label('api_key', t('API key').' *');
        echo $form->text('api_key', $cfg->get('d3_mailchimp.settings.api_key'), array('style' => 'max-width: 700px'));
        ?>
	</div>
	
	<div class="ccm-dashboard-form-actions-wrapper">
		<div class="ccm-dashboard-form-actions">
			<button class="pull-right btn btn-primary" type="submit"><?php   echo t('Save') ?></button>
		</div>
	</div>
</form>
