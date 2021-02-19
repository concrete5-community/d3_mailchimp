<?php      
defined('C5_EXECUTE') or die("Access Denied.");

$token = Core::make('token');
?>

<div class="d3-mailchimp d3-mailchimp-<?php  echo $bID ?>" id="b<?php  echo $bID ?>">
	<?php 
	if (isset($errors)) {
		$errors->output();
	}
	
	if (isset($message)) {
		echo '<p class="message">'.$message.'</p>';
	} else {
		?>
		<form method="post" action="<?php  echo $this->action('submit') ?>#b<?php  echo $bID ?>">
			<?php  $token->output('d3_mailchimp.subscribe'); ?>
	
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
					'class' => 'button'
				]);
				?>
			</div>
		</form>
		<?php    
	}
	?>
</div>
