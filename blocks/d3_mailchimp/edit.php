<?php  
defined('C5_EXECUTE') or die("Access Denied.");

if (!$hasApiKey) {
    echo t('Please <a href="%s">add an API key</a> first.', URL::to('dashboard/d3_mailchimp/settings/'));
    return;
}

if (count($listOptions) === 0) {
    echo t('<a target="_blank" href="%s">Please create a MailChimp list.</a>', 'https://admin.mailchimp.com/lists/');
    return;
}
?>

<div class="form-group">
	<?php  
    echo $form->label('listId', t('List') .' *');
    ?>

	<div class="input">
		<?php  
        echo $form->select('listId', $listOptions, $listId);
        ?>
	</div>
</div>

<div class="form-group">
	<?php  
    echo $form->label('subscribeAction', t('Subscribe action') .' *');
    ?>
	
	<div class="input">
		<?php  
        echo $form->select('subscribeAction', $subscribeActions, $subscribeAction);
        ?>
	</div>
</div>

<hr>

<div class="form-group">
	<?php  
    echo $form->label('merge_fields', t('Merge fields (comma separated)'));
    ?>
    <i class="fa fa-question-circle launch-tooltip"
	   title="<?php  echo t('Developers: If you have custom merge fields you need to override view.php.') ?>"
	></i>
	
	<div class="input">
		<?php  
        echo $form->text('mergeFields', $mergeFields, [
			'placeholder' => 'By default: FNAME, LNAME'
		]);
        ?>
	</div>
</div>
