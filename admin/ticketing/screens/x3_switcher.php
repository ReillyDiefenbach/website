<article id="art-switcher" class="<?php print !isset($code) && !isset($uCode) ? 'show' : ''; ?>">
	<div class="master-row">
			<h5 data-lang="A,l03-1h" class="counterHead">Du bist bereits registriert?</h5>
			<a data-lang="A,l03-1a" onclick="javascript:Tick.loadScreen('3_login')"  class="btn btn-sm btn-dark btn-200">
					Zugangsdaten
			</a>
			<h5 data-lang="A,l03-3h" class="counterHead">Du warst noch nie auf unserer Plattform?</h5>
			<a data-lang="A,l03-3a" onclick="javascript:Tick.loadScreen('3_register');" class="btn  btn-sm btn-outline-dark btn-200">
					Registrieren  
			</a>
	</div>
</article>
