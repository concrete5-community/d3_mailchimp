<?php

defined('C5_EXECUTE') or die('Access Denied.');

/** @var Concrete\Core\Validation\CSRF\Token $token */
/** @var Concrete\Core\Error\ErrorList\ErrorList $errors */
/** @var string $message */
/** @var int $bID */
?>

<div class="d3-mailchimp" data-block-id="<?php echo $bID; ?>">
	<?php 
	if (isset($errors)) {
		$errors->output();
	}
	
	if (isset($message)) {
		echo '<p class="message">'.$message.'</p>';
	} else {
		?>
		<form method="post" action="<?php echo $this->action('submit') ?>" onsubmit="d3_mailchimp_submit(this); return false;">
			<?php $token->output('d3_mailchimp.subscribe'); ?>
	
			<div class="first-name">
				<?php    
				echo $form->text('FNAME', '', [
					'placeholder' => t('First name'),
				]);
				?>
			</div>
	
			<div class="last-name">
				<?php    
				echo $form->text('LNAME', '', [
					'placeholder' => t('Last name'),
				]);
				?>
			</div>
			
			<div class="email-address">
				<?php    
				echo $form->email('email_address', '', [
					'required' => 'required',
					'placeholder' => t('Email address'),
				]);
				?>
			</div>

			<div class="submit-button">
				<?php 
				echo $form->submit('submit', t('Submit'), [
					'class' => 'button',
				]);
				?>
			</div>
		</form>
		<?php    
	}
	?>
</div>
