<?php 

$tickets = explode(';',TICKETPRICES);

if(isset($_REQUEST['innerticketing'])) {
	print '<input id="infoID" value="' . PAYPAL_SANDBOX_CLIENT_ID. '" type="hidden" />';
}	
		
?>
		<h4 data-lang="A,plan_head">Du kannst die Tickets entweder für dich oder für jemanden anderes buchen.</h4>
		<div class="toggle-wrapper voucher-wrapper">
						<div class="toggle-button">
					    <div class="toggle-switch"></div>
							    <div class="toggle-option active" data-plan="forme" data-lang="A,plan_forme">For me</div>
							    <div class="toggle-option" data-plan="voucher" data-lang="A,plan_voucher">Voucher</div>
					  	</div>
		</div>
		<p class="h5" data-lang="A,plan_head2">Ein Gutschein ist jederzeit übertragbar.</p>
		<p class="h5" data-lang="A,plan_head3">Den Code kannst du weiterschicken oder selbst verwenden.</p>

		<section class="ticket-table"> 
			<div class="ticket-card card single-color" >

				<div class="card-body">
					<div class="number-wrapper">
						<hr class="numerLine">
						<div class="numberContainer tick-single">
							<h1 id="priceOverall" class="number"><?php print $tickets[0]; ?><sup class="priceSup">€</sup></h1>
						</div>
						<hr class="numerLine">
					</div>
				    <h3 class="card-title" data-lang="A,plan_t">Single Ticket</h3>
				    <p class="card-text"data-lang="A,plan_t2">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
				    <a class="btn btn-single btn-sm buyTicket" data-lang="A,plan_tb" data-price="<?php print $tickets[0]; ?>" data-type="t">get Ticket</a>
				    <div class="imgHolder">
				    	<img class="imgVoucher" src="<?php print ASSETPATH . "img/webs/voucher.png"; ?>" style="max-width:100%;" />
				    	<img class="imgTicket" src="<?php print ASSETPATH . "img/webs/ticket.jpg"; ?>" style="max-width:100%;" />
				    </div>
				    
				</div>
				<div class="card-body features">
					<h3>Features</h3>
					<div class="bi bi-check">1 Test</div>
					<div class="bi bi-check">Daten stehen 1 Monat lang zur Verfügung</div>
					<div class="bi bi-check">direkter Vergleich mit verlinkten Mitgliedern</div>
					<div class="bi bi-check">Fremdeinschätzung</div>
				</div>
			</div>
			<div class="ticket-card card month-color">
				<div class="card-body">
					<div class="number-wrapper">
						<hr class="numerLine">
						<div class="numberContainer tick-month">
							<h1 id="priceOverall" class="number"><?php print $tickets[1]; ?><sup class="priceSup">€</sup></h1>
						</div>
						<hr class="numerLine">
					</div>	
				    <h3 class="card-title" data-lang="A,plan_m">Monthly Ticket</h3>
				    <p class="card-text" data-lang="A,plan_m2">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
				    <a class="btn btn-month btn-sm buyTicket" data-lang="A,plan_mb" data-price="<?php print $tickets[1]; ?>" data-type="m">get Month</a>
				    <div class="imgHolder">
				    	<img class="imgVoucher" src="<?php print ASSETPATH . "img/webs/voucher.png"; ?>" style="max-width:100%;" />
				    	<img class="imgTicket" src="<?php print ASSETPATH . "img/webs/month.jpg"; ?>" style="max-width:100%;" />
				    </div>
				    
				</div>
				<div class="card-body features">
					<h3>Features</h3>
					<div class="bi bi-check">unlimited Tests</div>
					<div class="bi bi-check">Daten stehen 1 Monat lang nach Ablauf zur Verfügung</div>
					<div class="bi bi-check">direkter Vergleich mit verlinkten Mitgliedern</div>
					<div class="bi bi-check">Fremdeinschätzung</div>
				</div>

			</div>
			<div class="ticket-card card year-color">
				<div class="card-body">
					<div class="number-wrapper">
						<hr class="numerLine">
						<div class="numberContainer tick-year">
							<h1 id="priceOverall" class="number"><?php print $tickets[2]; ?><sup class="priceSup">€</sup></h1>
						</div>
						<hr class="numerLine">
					</div>			    
				    <h3 class="card-title" data-lang="A,plan_y">Year Ticket</h3>
				    <p class="card-text" data-lang="A,plan_y2">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
				    <a class="btn btn-year btn-sm buyTicket" data-lang="A,plan_yb" data-price="<?php print $tickets[2]; ?>" data-type="y">get Year</a>
				    <div class="imgHolder">
				    	<img class="imgVoucher" src="<?php print ASSETPATH . "img/webs/voucher.png"; ?>" style="max-width:100%;" />
				    	<img class="imgTicket" src="<?php print ASSETPATH . "img/webs/year.jpg"; ?>" style="max-width:100%;" />
				    </div>
				    
				</div>
								<div class="card-body features">
					<h3>Features</h3>
					<div class="bi bi-check">unlimited Tests</div>
					<div class="bi bi-check">Daten stehen 1 Monat lang nach Ablauf zur Verfügung</div>
					<div class="bi bi-check">direkter Vergleich mit verlinkten Mitgliedern</div>
					<div class="bi bi-check">Fremdeinschätzung</div>
				</div>

			</div>
	</section>
