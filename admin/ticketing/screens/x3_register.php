<!-- SIGN IN -->
<article id="art-register">
	
	 <form id="form-register" class="pt-2 wolf-form">
                      	
                      	<div class="row wolf-field">
                      		    <h5 data-lang="A,l07-1" class="pb-3 text-center">Bitte registriere Dich:</h5>
                      			<p data-lang="A,l07-2a">Für den Zugang zu unserer Software brauchen wir eine eindeutige Zuordnung deiner Person. Bitte gib uns zunächst die wichtigsten Informationen bekannt.</p>
                      			<div class="col-sm-6">
											<fieldset data-control="text">
					                          		<input data-lang-placeholder="A,f-10" data-variable="reg_email" id="reg_email" name="reg_email" class="wolf-input" type="text" placeholder="Deine Email Adresse">
					                          		<label for="reg_email"><i class="fas fa-at"></i></label>
					                        </fieldset>	
								</div>
                      			<div class="col-sm-6">
											<fieldset data-control="text">
					                          		<input data-lang-placeholder="A,f-13" data-variable="reg_fullname" id="reg_fullname" name="reg_fullname" class="wolf-input" type="text" placeholder="Dein Vor- und Zuname">
					                          		<label for="reg_fullname"><i class="fas fa-fingerprint"></i></label>
					                        </fieldset>	
								</div>
						</div>
                      	
                      	
                      	<div class="row wolf-field">
                      			<h5 data-lang="A,l07-3"class="pb-3 text-center" data-i18n="l1b_1">Wähle eine Zugangskennung:</h5>
                      			<p data-lang="A,l07-4">Sowohl der Benutzername wie auch das Passwort müssen mindestens 5 Stellen (Buchstaben, Ziffern, keine Umlaute und Sonderzeichen) lang sein.</p>
								<div class="col-sm-6">
									<fieldset>
			                          <!-- username input -->
			                          <input data-lang-placeholder="A,f-27" data-variable="reg_username" id="reg_username" name="reg_username" class="wolf-input" type="text" placeholder="Benutzername" required="">
			                          <!-- label -->
			                          <label for="reg_username"><i class="fas fa-user"></i></label>
			                        </fieldset>								
			                    </div>
								<div class="col-sm-6">
			                        <fieldset>
			                          <!-- pw input -->
			                          <input data-lang-placeholder="A,f-28" data-variable="reg_password" id="reg_password" name="reg_password" class="wolf-input" type="text" placeholder="Passwort" required="">
			                          <!-- label -->
			                          <label for="reg_password"><i class="fas fa-lock"></i></label>
			                        </fieldset>
								</div>

								<div class="col-sm-8 hstack align-items-center justify-content-start">
									<fieldset data-control="checkradio">
										<input checked="true" value="true" data-variable="reg_remember_login" name="reg_remember_login" id="reg_remember_login_0" type="checkbox">
                        				<label data-lang="A,l05-2" for="reg_remember_login_0">Zugangsdaten merken!</label>
                        			</fieldset>
                        		</div>
								<div class="col-sm-4 d-flex align-items-center justify-content-end sm-center sm-pt">
									<a data-lang="A,s-11" onclick="_Log.registerSeminar(this);"  class="btn btn-sm btn-main" style="min-width:150px;">
										Registrieren  
									</a>
								</div>
						</div>

        
     </form>
</article>
