<?php   
defined('C5_EXECUTE') or die('Access Denied.');

$token = \Core::make('token');
?>

<form class="form-horizontal" method="post" action="<?php   echo $controller->action('save') ?>">
	<?php   $token->output('d3_mailchimp.settings.save'); ?>
	
	<div class="form-group">
		<label class="control-label col-sm-3">
			<?php   echo  t('API key').' *' ?>
		</label>

		<div class="col-sm-9">
			<?php  
			echo $form->text('api_key', $cfg->get('d3_mailchimp.settings.api_key'));
			?>
		</div>
	</div>
	
	
	<div class="ccm-dashboard-form-actions-wrapper">
		<div class="ccm-dashboard-form-actions">
			<button class="pull-right btn btn-primary" type="submit"><?php   echo t('Save') ?></button>
		</div>
	</div>
</form>
