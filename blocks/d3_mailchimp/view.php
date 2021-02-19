<?php      
defined('C5_EXECUTE') or die("Access Denied.");

$token = Core::make('token');
?>

<div class="d3-mailchimp d3-mailchimp-<?php  echo $bID ?>">
	<?php    
	if (isset($errors)) {
		$errors->output();
	}
	
	if (isset($message)) {
		echo '<p class="message">'.$message.'</p>';
	} else {
		?>
		<form method="post">
			<?php   echo $form->hidden($bID.'bID', $bID); ?>
			<?php   $token->output('d3_mailchimp.subscribe'); ?>
	
			<div class="first-name">
				<?php    
				echo $form->text($bID.'FNAME', '', array('placeholder' => t('First name')));
				?>
			</div>
	
			<div class="last-name">
				<?php    
				echo $form->text($bID.'LNAME', '', array('placeholder' => t('Last name')));
				?>
			</div>
			
			<div class="email-address">
				<?php    
				echo $form->email($bID.'email_address', '', array('required' => 'required', 'placeholder' => t('Email address')));
				?>
			</div>
			
			<?php    
			echo $form->submit('submit', t('Submit'), array('class' => 'button'));
			?>
		</form>
		<?php    
	}
	?>
</div>