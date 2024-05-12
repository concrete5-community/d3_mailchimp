<?php

use Concrete\Core\Error\ErrorList\Formatter\StandardFormatter;

defined('C5_EXECUTE') or die('Access Denied.');

/** @var Concrete\Core\Validation\CSRF\Token $token */
/** @var Concrete\Core\Error\ErrorList\ErrorList $errors */
/** @var string $message */
/** @var int $bID */
/** @var bool $showTermsCheckbox */
/** @var string $acceptTermsText */
?>

<div class="d3-mailchimp" data-block-id="<?php echo $bID; ?>">
	<?php 
	if (isset($errors) && $errors->has()) {
		$formatter = new StandardFormatter($errors);
		echo $formatter->render();
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

            <?php
            if ($showTermsCheckbox) {
                ?>
                <div class="accept-terms">
                    <label for="accept_terms">
                        <?php
                        echo $form->checkbox('accept_terms', 1, 0, [
                            'required' => 'required',
                        ]);
                        ?>
                        <?php echo $acceptTermsText; ?>
                    </label>
                </div>
                <?php
            }
            ?>

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
