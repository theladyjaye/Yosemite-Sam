<section class="wrap sign-up">
	<div class="column">
		<!-- <nav>						
		</nav> -->
		<section class="column-body">
			<div class="column-body-inner">	
				<div id="sign-up-container" class="view">										
					<h2><em>See what all the <a href="#" class="incomplete">buzz</a> is about.</em></h2>
					<h2>Sign up for <span class="peeq">peeq</span>!</h2>
					<form id="frm-sign-up" action="" method="post">
						<ul>
							<li>
								<ul>
									<li class="field">								
										<input type="text" name="firstname" maxlength="15" />
										<label for="firstname">First Name</label>
										<span class="hint">The name you go by.</span>
										<span class="icon icon-success"></span>
										<span class="icon icon-error"></span>
									</li>
									<li class="field">								
										<input type="text" name="lastname" maxlength="15" />
										<label for="lastname">Last Name</label>
										<span class="hint">The family name...</span>
										<span class="icon icon-success"></span>
										<span class="icon icon-error"></span>
									</li>
								</ul>
							</li>
							<li class="field">
								<input type="text" name="username" maxlength="15" />
								<label for="username">Username</label>
								<span class="hint">your <span class="peeq">peeq</span> login...</span>
								<span class="icon icon-success"></span>
								<span class="icon icon-error"></span>
							</li>
							<li class="field">
								<input type="email" name="email" />
								<label for="email">Email</label>
								<span class="hint">We don't spam.</span>
								<span class="icon icon-success"></span>
								<span class="icon icon-error"></span>
							</li>
							<li class="field">
								<input type="text" name="company" maxlength="15" />
								<label for="company">Company</label>
								<span class="hint">Who will be <span class="peeq">peeq</span>ing?</span>
								<span class="icon icon-success"></span>
								<span class="icon icon-error"></span>
							</li>
							<li class="field-domain">
								<div>
									<input type="text" name="domain" maxlength="15"/>								
									<span class="domain">.peeq.com</span>
									<p>
										<label for="domain">Domain</label>
										<span class="icon icon-success"></span>
										<span class="icon icon-error"></span>
									</p>
								</div>														
							</li>
						</ul>
						<a href="#" class="btn btn-signup btn-submit left clearboth">Sign Up</a>
					</form>
				</div>
				<div id="confirmation" class="view">
					<h2><em>Glad we could <span class="peeq">peeq</span> your interest!</em></h2>
					<h2>You'll be receiving an email shortly.</h2>
				</div>
			</div>
		</section>
	</div>
	<div class="column sidebar">
		<!-- <nav>
		</nav> -->
		<section class="column-body">
			<div class="column-body-inner">
				<div id="sign-in-container">
					<h3>Already have an account?</h3>
					<form id="frm-sign-in" action="" method="post">
						<p class="icon icon-error error-message invisible">Invalid Credientials</p>
						<p class="invisible msg-password-sent">Password Sent!</p>
						<ul>
							<li class="field">
								<input type="text" name="domain" />
								<label for="domain">Domain</label>
							</li>
							<li class="field">
								<input type="email" name="username" />
								<label for="username">Username/Email</label>
							</li>
							<li class="field">
								<input type="password" name="password" />
								<label for="password">Password</label>
								<a href="#" class="btn-forgot-password btn-submit forgot-password incomplete">forgot password?</a>
							</li>
						</ul>	
						<a href="#" class="btn btn-submit btn-sign-in left">Login</a>				
					</form>	
				</div>
				<div id="forgot-password-container">	    	
					<h3>Forgot Password</h3>
					<a href="#" class="btn-sign-in-form incomplete">&laquo; Nevermind, I remember.</a>
					<form id="frm-forgot-password" action="" method="post">
						<ul>
							<li class="field">
								<input type="text" name="domain" />
								<label for="domain">Domain</label>
							</li>
							<li class="field">
								<input type="email" name="email" />
								<label for="username">Email</label>
							</li>
						</ul>	
						<a href="#" class="btn btn-reset-password btn-submit left">Reset Password</a>				
					</form>
				</div>
			</div>
		</section>
	</div>
</section>