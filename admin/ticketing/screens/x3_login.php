<!-- SIGN IN -->
<article id="art-login">
	
	
					  
                      <!-- contact form -->
                      <form id="form-login" class="pt-2">
                      	
                      <div class="d-flex flex-column">	
                      			<!--div class="col-12">
                      					<p>Sowohl der Benutzername wie auch das Passwort müssen mindestens 5 Stellen lang sein - bestehend aus mindestens 4 Buchstaben und zumindest einer Ziffer! Umlaute und Sonderzeichen sind nicht erlaubt.</p>
                      			</div-->
                      			<h5 class="pb-3 text-center"  data-lang="A,l05-1">Bitte trag deine Zugangskennung für das Seminar ein!</h5>
									<fieldset data-control="text">
			                          <!-- username input -->
			                          <input data-lang-placeholder="A,l05-1a" value="<?php print isset($profile['username']) ? $profile['username'] : ''; ?>" data-variable="log_username" id="log_username" name="log_username" class="wolf-input" type="text" placeholder="Benutzername" required="">
			                          <!-- label -->
			                          <label for="log_username"><i class="fas fa-user"></i></label>
			                        </fieldset>								
			                        <fieldset data-control="text">
			                          <!-- pw input -->
			                          <input data-lang-placeholder="A,l05-1b" value="<?php print isset($profile['password']) ? $profile['password'] : ''; ?>" data-variable="log_password" id="log_password" name="log_password" class="wolf-input" type="text" placeholder="Passwort" required="">
			                          <!-- label -->
			                          <label for="log_password"><i class="fas fa-lock"></i></label>
			                        </fieldset>
						
						
                      	
                        <!-- button -->
                        
						
									<fieldset data-control="checkradio">
										<input checked="true" value="true" data-variable="log_remember_login" name="log_remember_login" id="log_remember_login_0" type="checkbox">
                        				<label data-lang="A,l05-2" for="log_remember_login_0">Zugangsdaten merken!</label>
                        			</fieldset>
                        		
								<div class="col-sm-4 sm-pt">
									<a data-lang="A,l05-2a" onclick="javascript:Tick.login(this);"  class="btn btn-sm btn-main btn-block">
										Login 
									</a>
								</div>
						
                        </div>
                      </form>
                      <!-- contact form end -->

        
</article>
