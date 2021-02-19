<?php

defined('C5_EXECUTE') or die('Access Denied.');

/** @var \Concrete\Core\Application\Application $app */
/** @var bool $hasApiKey */
/** @var array $listOptions */
/** @var string $listId */
/** @var array $subscribeActions */
/** @var string $subscribeAction */
/** @var string $showTermsCheckbox */
/** @var string $acceptTermsText */
/** @var string $mergeFields */
/** @var int $styling */

if (!$hasApiKey) {
    echo '<p>'.t('Please add an API key first.').'</p>';
    echo '<a class="btn btn-primary" target="_blank" href="'.$app->make('url/manager')->resolve(['/dashboard/system/mail/mailchimp']).'">'.t('Add API key').'</a>';
    return;
}

if (count($listOptions) === 0) {
    echo '<p>'.t('Please create a MailChimp List first.').'</p>';
    echo '<a class="btn btn-primary" target="_blank" href="https://admin.mailchimp.com/lists/">'.t('Create List').'</a>';
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

<div class="form-group">
    <?php
    echo $form->label('styling', t('Styling').' *');
    echo $form->select('styling', [
        1 => t('Basic styling'),
        0 => t('No styling'),
    ], $styling);
    ?>
</div>

<div class="form-group">
    <label>
        <?php
        echo $form->checkbox('showTermsCheckbox', 1, $showTermsCheckbox);
        ?>
        <?php echo t('Show accept terms checkbox'); ?>
    </label>
</div>

<div class="form-group">
    <?php
    echo $form->label('acceptTermsText', t('Accept terms text'));

    $editor = $app->make('editor');
    echo $editor->outputStandardEditor('acceptTermsText', $acceptTermsText);
    ?>
</div>

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
