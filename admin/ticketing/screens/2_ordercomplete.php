<?php

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

$uCode = isset($_COOKIE['uCode']) && !empty($_COOKIE['uCode']) && is_int(encoder($_COOKIE['uCode'])) ? encoder($_COOKIE['uCode']) : null;

if($uCode) {
	$profile = startUp()->memberStartup($uCode);
}



 ?>
 


<section class="d-flex flex-row gap-3 ">
	

					<input type="hidden" name="pending_order" id="pending_order" value="<?php print isset($_POST['order_id']) ? $_POST['order_id'] : '' ?>" />
				    <input type="hidden" name="identified_user" id="identified_user" value="" />
	    			<input type="hidden" name="counter" id="counter" value="5" />
	    			
	    			
	    			<?php
	    			foreach ($_POST as $key => $value) {
					    echo '<input type="hidden" id="order_' . $key . '" value="' . $value  . '" />';
					}
					?>
	    			
	    			
	    			
	    			
				    
	<style>
		.card-small {font-size:11px;font-style:italic;}
		.ticketForms h4 {color:var(--main);}
		#codeField input {    height: 50px;
    				width: 40px;
    font-size: 30px;
    text-align: center;
    font-weight: bold;}
		
	</style>
			<main class="ticketForms">
				<div id="headline">
				    <h5 class="text-center" data-lang="A,selThx">Big Thx</h5>
				    <p class="card-small text-center">OrderID: <?php print isset($_POST['order_id']) ? $_POST['order_id'] : '' ?> / <?php print $_POST['provider']; ?></p>
				    <p class="text-center" data-lang="A,selUser">Thank you for purchasing carlvon products</p>
				    <hr>
				</div>
				<article id="art-whois" style="display:<?php print isset($profile['id']) ? 'block' : 'none'; ?>">
							<div class="col-12 d-flex flex-column align-items-center justify-content-center">
									<div class="my-0 control cover w-avatar avatar_160 bg-cover " style="background-image:url('<?php print isset($profile['avatar']) && !empty($profile['avatar']) ? $profile['avatar'] : NOAVATAR;  ; ?>');"></div>
									<b class="my-2 card-title text-center" data-lang="A,l0-2,name:<?php print $profile['fullname']; ?>">Bist Du <?php print $profile['fullname']; ?>?
									</b>
							</div>
							<div class="buttonrow col-12 d-flex align-items-center justify-content-center sm-center sm-pt">
									<button onclick="javascript:Tick.loadArticle('switcher');" class="btn btn-sm btn-outline-dark me-3" data-lang="A,s-2">Nein</button>
									<button onclick="javascript:Tick.finishOrder(<?php print isset($profile['id']) ? $profile['id'] : '' ?>);" class="btn btn-sm btn-dark" data-lang="A,s-1">Ja</button>
							</div>	
				</article>
		
				<article id="art-switcher" style="display:<?php print !isset($profile['id']) ? 'block' : 'none'; ?>">
					        <h4 class="text-center" data-lang="A,selAccount">go to your Account</h4>	
							<h5 data-lang="A,l03-1h" class="text-center counterHead">Du bist bereits registriert?</h5>
							<a data-lang="A,l03-1a" onclick="javascript:Tick.loadArticle('login')"  class="btn btn-sm btn-dark btn-200">
									Login
							</a>
							<h5 data-lang="A,l03-3h" class="text-center counterHead">Du warst noch nie auf unserer Plattform?</h5>
							<a data-lang="A,l03-3a" onclick="javascript:Tick.loadArticle('register');" class="btn  btn-sm btn-outline-dark btn-200">
									Registrieren  
							</a>
				</article>
				
				<article id="art-login" style="display:none;width:300px;">
			                      <form id="form-login" class="d-flex flex-column">
			                      			    <h4 class="mb-2 text-center" data-lang="A,selLogin">Bitte trag deine Zugangskennung ein!</h4>
												<fieldset data-control="text">
													<div class="control">
								                          <input data-lang-placeholder="A,l05-1a" value="" data-variable="username" id="username" name="username" type="text" placeholder="Benutzername" required="">
								                          <label for="username" data-lang="A,l05-1a"></label>
						                         	</div>
						                        </fieldset>								
						                        <fieldset data-control="text">
						                        	<div class="control">
								                          <!-- pw input -->
								                          <input data-lang-placeholder="A,l05-1b" value="" data-variable="password" id="password" name="password" type="text" placeholder="Passwort" required="">
								                          <!-- label -->
								                          <label for="password" data-lang="A,l05-1b"></label>
						                            </div>
						                        </fieldset>
												<fieldset data-control="checkradio">
													<div class="my-0">
														<input checked="true" value="true" data-variable="remember_login" name="remember_login" id="remember_login_0" type="checkbox">
			                        					<label data-lang="A,l05-2" for="remember_login_0">Zugangsdaten merken!</label>
			                        				</div>
			                        			</fieldset>
												<a data-lang="A,l05-2a" onclick="javascript:Tick.checkLogin(this);"  class="btn btn-sm btn-dark btn-block">
														Login 
												</a>
			                      </form>
				</article>
			
			
				<article id="art-register" style="display:none;width:300px;">
						    <form id="form-register" class="d-flex flex-column">
		                      				
			                      			<!--h4 class="mb-3 text-center" data-lang="A,selControl">Wir brauchen noch ein paar Daten von Dir:</h4-->
				    
					                        <fieldset data-control="text">
					                          <div class="control">
						                          <input data-lang-placeholder="A,Q-13" value="<?php print isset($_POST['name']) ? $_POST['name'] : '' ?>" data-variable="reg_fullname" id="reg_fullname" name="reg_fullname" type="text" placeholder="Name" required="">
												  <label for="reg_fullname" data-lang="A,Q-13"> fullname</label>
											  </div>
					                        </fieldset>								
					                        <fieldset data-control="text">
					                          <div class="control">
						                          <input data-lang-placeholder="A,Q-10a" value="<?php print isset($_POST['email']) ? $_POST['email'] : '' ?>" data-variable="reg_email" id="reg_email" name="reg_email" type="text" placeholder="Email" required="">
												  <label for="reg_email" data-lang="A,Q-10a"></label>
											  </div>
					                        </fieldset>
					                        <h4 class="mt-3 mb-1 text-center" data-lang="A,selReg">Bitte such dir noch die Zugangsdaten aus:</h4>
					                        <p class="card-small my-1 text-center" data-lang="A,selReginfo">6 Buchstaben und Ziffern</p>
					                        <p class="card-small mb-3 text-center" data-lang="A,selReginfo1">keine Sonderzeichen</p>
					                        <fieldset data-control="text">
													<div class="control">
								                          <input data-lang-placeholder="A,l05-1a" value="" data-variable="reg_username" id="reg_username" name="reg_username" type="text" placeholder="Benutzername" required="">
								                          <label for="reg_username" data-lang="A,l05-1a"></label>
						                         	</div>
						                     </fieldset>								
						                     <fieldset data-control="text">
						                        	<div class="control">
								                          <!-- pw input -->
								                          <input data-lang-placeholder="A,l05-1b" value="" data-variable="reg_password" id="reg_password" name="reg_password" type="text" placeholder="Passwort" required="">
								                          <!-- label -->
								                          <label for="reg_password" data-lang="A,l05-1b"></label>
						                            </div>
						                     </fieldset>
					                        
											<fieldset data-control="checkradio" style="margin-top:0 !important;">
												<input checked="" value="true" data-variable="reg_remember_login" name="reg_remember_login" id="reg_remember_login_0" type="checkbox" />
		                        				<label data-lang="A,l05-2" for="reg_remember_login_0" style="line-height:1.1em;padding-top:5px;">Merken</label>
		                        			</fieldset>
		                        			
											<fieldset data-control="checkradio" style="margin-top:0 !important;">
												<input checked="" value="true" data-variable="reg_confirm" name="reg_confirm" id="reg_confirm_0" type="checkbox" />
		                        				<label data-lang="A,selAgb" for="reg_confirm_0" style="line-height:1.1em;padding-top:5px;">AGBs akzeptieren!</label>
		                        			</fieldset>
		
		                        			<a class="btn btn-dark btn-sm goon" data-lang="A,l03-3a" onclick="javascript:$.WOLF.ticket.checkRegister(this);">Registrieren</a>
							  </form>
				</article>
			
				<article id="art-lost" style="display:none;width:300px;">
					<form id="form-lost">
									<h4 class="text-center mb-2" data-lang="A,s-8">vergessen?</h4>
								   	<p data-lang="A,selLost">
				                    	Es passiert öfter als man denkt, dass man seine Zugangskennung vergisst - kein Problem ... . Trag bitte einfach deine Email-Adresse ein, wir schicken dir ein neues Passwort. 
				                    </p>
									<fieldset data-control="text" class="mb-1" >
										<div class="control">
									  		<input data-lang-placeholder="A,Q-10a" data-variable="lost_email" id="lost_email" name="lost_email" type="text" placeholder="Email Adresse">
											<label data-lang="A,Q-10a" for="lost_email" style="line-height:1.1em;padding-top:5px;">Email</label>
		                        		</div>
		                        	</fieldset>	
								
								<div class="d-flex align-items-center">
											<a data-lang="A,l02-2a" onclick="javascript:Tick.lostCredentials(this);" class="btn btn-sm btn-dark btn-block">
														Passwort zurücksetzen
											</a>											
								</div>
					</form>
				</article>
				
				<article id="art-lostcode" style="display:none;width:300px;">
						<h4 class="mb-2 text-center" data-lang="A,selCode">Bitte enter Code</h4>
						<p class="card-small text-center" data-lang="A,selCode1">Nimm Code von Email an <span id="sendToEmail"></span></p>
						
						<div id="codeField" class="hstack justify-content-center gap-1">
							<input type="text" id="c1" />
							<input type="text" id="c2" />
							<input type="text" id="c3" />
							<input type="text" id="c4" />
						</div>
						<p class="card-small mt-3 text-center" data-lang="A,selCode2">Max. 5 Versuche</p>
						
				</article>
				
				<article id="art-lostnew" style="display:none;width:300px;">
							<form id="form-lostnew">
								<h4 class="mt-3 mb-1 text-center" data-lang="A,selNew"></h4>
					            <p class="card-small my-1 text-center" data-lang="A,selReginfo">6 Buchstaben und Ziffern</p>
					            <p class="card-small mb-3 text-center" data-lang="A,selReginfo1">keine Sonderzeichen</p>
					            <fieldset data-control="text">
									<div class="control">
								               <input data-lang-placeholder="A,l05-1a" value="" data-variable="new_username" id="new_username" name="new_username" type="text" placeholder="Benutzername" required="">
								               <label for="new_username" data-lang="A,l05-1a"></label>
						           	</div>
						        </fieldset>								
						        <fieldset data-control="text">
						           	<div class="control">
								               <input data-lang-placeholder="A,l05-1b" value="" data-variable="new_password" id="new_password" name="new_password" type="text" placeholder="Passwort" required="">
								               <label for="new_password" data-lang="A,l05-1b"></label>
						            </div>
						        </fieldset>
						        <div class="d-flex align-items-center">
									<a data-lang="A,l02-2b" onclick="javascript:Tick.newCredentials(this);" class="btn btn-sm btn-dark btn-block">
											Benutzendaten neu anfordern!
									</a>											
								</div>	
							</form>
				</article>
				
				<article id="art-summary" style="display:none;width:300px;">
								<h4 class="mt-3 mb-1 text-center" xdata-lang="A,selNew">Summary</h4>
					            <p class="card-small my-1 text-center" xdata-lang="A,selReginfo" id="sum_user">6 Buchstaben und Ziffern</p>
					            <p class="card-small mb-3 text-center" xdata-lang="A,selReginfo1" id="sum_user">keine Sonderzeichen</p>
					            <div class="sum_pdf d-flex flex-row justify-content-around">
					            	<a onclick="Tick.make_invoice();">make invoice</a>
					            	<a onclick="Tick.send_invoice();">send invoice</a>
					            </div>
						        <div class="d-flex align-items-center">
									<a xdata-lang="A,l02-2b" onclick="javascript:Tick.enterPlatform(this);" class="btn btn-sm btn-dark btn-block">
											Enter Platform
									</a>											
								</div>	
							</form>
				</article>
				
				
				<div id="footerline">
					<hr>
					<div class="hstack justify-content-center gap-2">
						<span class="span-lost"><a data-lang="A,s-8" onclick="javascript:Tick.loadArticle('lost');">Passwort vergessen</a> &bull; </span>
						<span class="span-switcher"><a onclick="javascript:Tick.loadArticle('switcher');">switch</a> &bull; </span>
						<a href="javascript:window.location.href='https://carlvon.cloud/?ticketing'">redo</a>
					</div>
				</div>
	</main>
</section>



