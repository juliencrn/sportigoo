<?php

$pluginUrl = plugins_url('', dirname(__DIR__));

echo '<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,700&amp;subset=latin,latin-ext" rel="stylesheet" property="stylesheet" type="text/css">' .
	'<link rel="stylesheet" property="stylesheet" type="text/css" href="' . $pluginUrl . '/assets/bootstrap.min.css" />' .
	'<link rel="stylesheet" property="stylesheet" type="text/css" href="' . $pluginUrl . '/assets/style.css" />';

?><div class="wrap" id="content">

	<?php if ($options['active']) { ?>
		<main role="main">
			<div id="third">
				<section class="top-bar">
					<div class="text-center">
						<img src="<?= $pluginUrl ?>/images/logo.png" alt="smartsupp logo" />
					</div>
				</section>
				<section class="deactivate">
					<div class="row">
						<p class="bold">
							<span class="left"><?= isset($options['email']) ? $options['email'] : '' ?></span>
							<span class="right"><a class="js-action-disable" href="javascript: void(0);"><?= __('Deactivate Chat', 'smartsupp-live-chat') ?></a></span>
						</p>
						<div class="clear"></div>
						<section class="intro">
                            <h4>
                                <strong class="green"><?= __('Smartsupp’s chat box is now visible on your website.', 'smartsupp-live-chat') ?></strong> <br /><br />
                                <?= __('Go to Smartsupp to chat with visitors, customize chat box design and access all features.', 'smartsupp-live-chat') ?>
                            </h4>
							<div class="intro--btn">
								<a href="https://dashboard.smartsupp.com?utm_source=Wordpress&utm_medium=integration&utm_campaign=link" target="_blank" class="js-register btn btn-primary btn-xl"><?= __('Go to Smartsupp', 'smartsupp-live-chat') ?></a>
							</div>
							<p class="tiny text-center bigger-m"><?= __('(This will open a new browser tab)', 'smartsupp-live-chat')?></p>
						</section>
					</div>
				</section>
				<section>
					<div class="settings-container">
						<div class="section--header">
							<h3 class="no-margin bold"><?= __('Advanced settings', 'smartsupp-live-chat') ?></h3>
							<p><?= __('Don\'t put the chat code here — this box is for (optional) advanced customizations via <a href="https://developers.smartsupp.com?utm_source=Wordpress&utm_medium=integration&utm_campaign=link" target="_blank">Smartsupp API</a>', 'smartsupp-live-chat') ?></p>
						</div>
						<div class="section--body">
							<form action="" method="post" id="settingsForm" class="js-code-form form-horizontal" autocomplete="off">
								<div class="alerts">
									<?php if ($message) { ?>
										<div class="alert alert-success">
											<?= __($message, 'smartsupp-live-chat') ?>
										</div>
									<?php } ?>
								</div>
								<div class="form-group">
									<div class="col-xs-12">
										<textarea name="code" id="textAreaSettings" cols="30" rows="10"><?= stripcslashes($options['optional-code']) ?></textarea>
									</div>
								</div>
								<div class="form-group">
									<div class="col-xs-12">
										<div class="loader"></div>
										<button type="submit" class="btn btn-primary btn-lg" name="_submit">
											<?= __('Save changes', 'smartsupp-live-chat') ?>
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</section>
			</div>
		</main>
	<?php } else { ?>
		<main role="main" class="sections" id="home"<?php if ($formAction) { ?> style="display: none;"<?php } ?>>
			<div id="first">
				<section class="top-bar">
					<div class="">
						<img src="<?= $pluginUrl ?>/images/logo.png" alt="smartsupp logo" />
						<a href="javascript: void(0);" class="js-login btn btn-default"><?= __('Connect existing account', 'smartsupp-live-chat') ?></a>
					</div>
				</section>
				<section class="intro">
					<div class="">
						<h1 class="lead"><?= __('Free live chat with visitor recording', 'smartsupp-live-chat') ?></h1>
						<h3><?= __('Your customers are on your website right now.', 'smartsupp-live-chat') ?><br/><?= __('Chat with them and see what they do.', 'smartsupp-live-chat') ?></h3>
						<div class="intro--btn">
							<a href="javascript: void(0);" class="js-register btn btn-primary btn-xl"><?= __('Create a free account', 'smartsupp-live-chat') ?></a>
						</div>
						<div class="intro--image">
							<img src="https://www.smartsupp.com/assets/images/dash/en.png" alt="intro" />
						</div>
					</div>
				</section>
				<section>
					<div class=" text-center">
						<div class="section--header">
							<h2><?= __('Enjoy unlimited agents and chats forever for free<br />or take advantage of premium packages with advanced features.', 'smartsupp-live-chat') ?></h2>
							<p><?= __('<strong>See all features on </strong><a href="https://www.smartsupp.com?utm_source=Wordpress&utm_medium=integration&utm_campaign=link" target="_blank"> our website.', 'smartsupp-live-chat') ?></a></p>
						</div>
						<div class="section--body boxies">
							<div class="row">
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 box box-bubble">
									<p class="bold"><?= __('Chat with visitors in real-time', 'smartsupp-live-chat') ?></p>
									<p class="tiny"><?= __('Answering questions right away improves loyalty and helps you build closer relationships with your customers.', 'smartsupp-live-chat') ?></p>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 box box-graph">
									<p class="bold"><?= __('Increase online sales', 'smartsupp-live-chat') ?></p>
									<p class="tiny"><?= __('Turn your visitors into customers.<br />Visitors who chat with you buy up to 5x more often - measurable in Google Analytics.', 'smartsupp-live-chat') ?></p>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 box box-mouse">
									<p class="bold"><?= __('Visitor screen recording', 'smartsupp-live-chat') ?></p>
									<p class="tiny"><?= __('Watch visitor\'s behavior on your store.<br />You see his screen, mouse movement, clicks and what he filled into forms.', 'smartsupp-live-chat') ?></p>
								</div>
							</div>
						</div>
						<?= $customers ?>
					</div>
				</section>
			</div>
		</main>
		<main role="main" class="sections" id="connect"<?php if (!$formAction) { ?> style="display: none;"<?php } ?>>
			<div id="second">
				<section class="top-bar">
					<div>
						<a href="javascript: void(0);" class="js-close-form">
							<img src="<?= $pluginUrl ?>/images/logo.png" alt="smartsupp logo" />
							<a href="javascript: void(0);" class="btn btn-default" data-toggle-form data-multitext data-register="<?= __('Connect existing account', 'smartsupp-live-chat') ?>" data-login="<?= __('Create a free account', 'smartsupp-live-chat') ?>">
								<?= __($formAction === 'register' ? 'Connect existing account' : 'Create a free account', 'smartsupp-live-chat') ?>
							</a>
						</a>
					</div>
				</section>
				<section id="signUp">
					<div class="text-center">
						<div class="form-container">
							<div class="section--header">
								<h1 class="lead" data-multitext data-login="<?= __('Connect existing account', 'smartsupp-live-chat') ?>" data-register="<?= __('Create a free account', 'smartsupp-live-chat') ?>">
									<?= __($formAction === 'login' ? 'Connect existing account' : 'Create a free account', 'smartsupp-live-chat') ?>
								</h1>
							</div>
							<div class="section--body">
								<div class="form--inner">
									<form action="" method="post" id="signUpForm" class="form-horizontal<?= $formAction ? (' js-' . $formAction . '-form') : '' ?>" data-toggle-class autocomplete="off">
										<div class="alerts">
											<?php if ($message) { ?>
												<div class="alert alert-danger js-clear">
													<?= __($message, 'smartsupp-live-chat') ?>
												</div>
											<?php } ?>
										</div>
										<div class="form-group">
											<label class="visible-xs control-label col-xs-12" for="frm-signUp-form-email"><?= __('E-mail', 'smartsupp-live-chat') ?></label>
											<div class="col-xs-12">
												<div class="input-group">
													<span class="input-group-addon hidden-xs" style="min-width: 150px;"><?= __('E-mail', 'smartsupp-live-chat') ?></span>
													<input type="email" class="form-control input-lg" name="email" id="frm-signUp-form-email" required="" value="<?= isset($email) ? $email : '' ?>">
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="visible-xs control-label col-xs-12" for="frm-signUp-form-password"><?= __('Password', 'smartsupp-live-chat') ?></label>
											<div class="col-xs-12">
												<div class="input-group">
													<span class="input-group-addon hidden-xs" style="min-width: 150px;"><?= __('Password', 'smartsupp-live-chat') ?></span>
													<input type="password" class="form-control input-lg" name="password" autocomplete="off" id="frm-signUp-form-password" required="">
												</div>
											</div>
										</div>
                                        <div class="gdpr checkbox">
                                            <label for="frm-signUp-form-termsConsent">
                                                <input <?= !empty($termsConsent) ? 'checked="checked"' : '' ?> value="1" type="checkbox" name="termsConsent" id="frm-signUp-form-termsConsent" required="">&nbsp;<?= __('I have read and agree with <a href="https://www.smartsupp.com/terms" target="_blank">Terms</a> and <a href="https://www.smartsupp.com/dpa" target="_blank">DPA</a>', 'smartsupp-live-chat'); ?>
                                            </label>
                                        </div>
										<div class="form-group">
											<div class="col-xs-12 form-button">
												<div class="loader"></div>
												<button type="submit" class="btn btn-primary btn-lg btn-block" name="_submit" data-multitext data-login="<?= __('Connect account', 'smartsupp-live-chat') ?>" data-register="<?= __('Sign up', 'smartsupp-live-chat') ?>">
													<?= $formAction === 'login' ? __('Connect account', 'smartsupp-live-chat') : __('Sign up', 'smartsupp-live-chat') ?>
												</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<?= $customers ?>
					</div>
				</section>
			</div>
		</main>
	<?php } ?>

</div>

<?php echo '<script src="' . $pluginUrl . '/assets/script.js" />'; ?>
