<?php  
defined('C5_EXECUTE') or die("Access Denied.");

if (!$controller->hasApiKey()) {
    echo t('Please <a href="%s">add an API key</a> first.', URL::to('dashboard/d3_mailchimp/settings/'));
    return;
}

$lists_options = $controller->getListOptions();

if (count($lists_options) === 0) {
    echo t('<a target="_blank" href="%s">Please create a MailChimp list.</a>', 'https://admin.mailchimp.com/lists/');
    return;
}
?>

<div class="form-group">
	<?php  
    echo $form->label('list_id', t('List') .' *');
    ?>

	<div class="input">
		<?php  
        echo $form->select('list_id', $lists_options, $list_id);
        ?>
	</div>
</div>

<div class="form-group">
	<?php  
    echo $form->label('subscribe_action', t('Subscribe action') .' *');
    ?>
	
	<div class="input">
		<?php  
        echo $form->select('subscribe_action', array(
            'subscribed' => t('Subscribe without verification email'),
            'pending' => t('Subscribe with verification email'),
        ), $subscribe_action);
        ?>
	</div>
</div>

<hr />

<div class="form-group">
	<?php  
    echo $form->label('merge_fields', t('Merge fields (comma separated)'));
    ?>
    <i class="fa fa-question-circle launch-tooltip" title="<?php  echo t('Developers: If you have custom merge fields you need to override view.php.') ?>"></i>
	
	<div class="input">
		<?php  
        echo $form->text('merge_fields', $merge_fields, array('placeholder' => 'By default: FNAME, LNAME'));
        ?>
	</div>
</div>