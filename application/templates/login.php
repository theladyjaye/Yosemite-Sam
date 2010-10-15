<section class="modal modal-view-login">
	<section>
		<h2 class="title">Login</h2>
		<a class="btn-modal-close" href="#">Close</a>
		
		<div id="login-container">
			<h3>Already have an account?</h3>
			<form id="frm-login" action="" method="post">
				<p class="login-message">Invalid Credientials</p>
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
						<a href="#" class="btn-forgot-password forgot-password incomplete">forgot password?</a>
					</li>
				</ul>	
				<a href="#" class="btn btn-submit btn-login left">Login</a>				
			</form>	
		</div>
		<div id="forgot-password-container">	    	
			<h3>Forgot Password</h3>
			<a href="#" class="btn-login-form incomplete">&laquo; Nevermind, I remember.</a>
			<form id="frm-forgot-password" action="" method="post">
				<p class="login-message">Password Sent!</p>
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
	</section>
</section>