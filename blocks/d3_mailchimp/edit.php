<?php  
defined('C5_EXECUTE') or die("Access Denied.");
?>

<div class="form-group">
	<?php  
    echo $form->label('list_id', t('List') .' *');
    ?>

	<div class="input">
		<?php  
        $options = $controller->getListOptions();

        if (count($options) === 0) {
            $options[0] = t('Please create a list in MailChimp first');
        }

        echo $form->select('list_id', $options, $list_id);
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

<div class="form-group">
	<?php  
    echo $form->label('merge_fields', t('Merge fields (comma separated)'));
    ?>
	
	<div class="input">
		<?php  
        echo $form->text('merge_fields', $merge_fields, array('placeholder' => 'By default: FNAME, LNAME'));
        ?>
	</div>
</div>

<p>
	<?php     echo t('If you have custom merge fields you can override the view.php and add the merge fields above.') ?>
</p>