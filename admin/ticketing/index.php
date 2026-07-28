		  <?php if (!isset($_REQUEST['innerticketing'])): ?>
		  <section class="content topHead">
		  	<div>
		  		<p>CARLVON</p>
		  		<h1>Pricing Table</h1>
		  		<p>Ticket Center</p>
		  	</div>
		  </section>
		  <?php endif; ?>

		  <div id="mid"><?php include_once(__DIR__ . '/screens/0_pricingtable.php'); ?></div>
		  <script src="https://www.paypal.com/sdk/js?client-id=<?= PAYPAL_SANDBOX_CLIENT_ID ?>&currency=EUR"></script>
		  <script src="https://js.stripe.com/v3/"></script>	
		  
		  
		     


