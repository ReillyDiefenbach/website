<section class="ticket-control ticket-checkout">
	<div class="ticket-checkout__header logo-container">
		<img src="/_assets/logos/carlvon.svg" alt="CARLVON" />
		<i>Ticket Center</i>
	</div>

	<div class="ticket-checkout__grid">
		<section class="ticket-checkout__product" aria-label="Produktauswahl">
			<p class="ticket-checkout__eyebrow" data-lang="A,selInfo">Please check if this is want you want:</p>

			<div class="summaryImage">
				<img src="" id="summaryImage" alt="" />
			</div>

			<div class="summaryProdukt">
				<table id="summaryCheckout">
					<tr>
						<td data-lang="A,anzahl" class="quantityLabel"></td>
						<td>
							<div class="ticket-checkout__quantity">
								<span id="summaryQuantity" aria-live="polite">1</span>
								<button class="plusCounter" type="button" aria-label="Anzahl erhöhen">+</button>
								<button class="minusCounter" type="button" aria-label="Anzahl verringern" hidden>−</button>
							</div>
						</td>
					</tr>
					<tr>
						<td data-lang="A,produkt" class="productLabel"></td>
						<td id="productInfo">&nbsp;</td>
					</tr>
					<tr class="ticket-checkout__description">
						<td class="productLabel"></td>
						<td class="productInfo2"><span id="productInfo2">&nbsp;</span></td>
					</tr>
					<tr>
						<td data-lang="A,preis" class="priceLabel">Preis:</td>
						<td id="priceInfo"></td>
					</tr>
					<tr class="ticket-checkout__total">
						<td data-lang="A,preis_s" class="priceLabel">Preis:</td>
						<td id="priceSum"></td>
					</tr>
				</table>
			</div>
		</section>

		<section class="ticket-checkout__payment" aria-label="Bezahlung">
			<div class="sumPrice">
				<p data-lang="A,selInfoSum" class="ticket-checkout__eyebrow">Preis:</p>
				<div class="numberContainer">
					<h1 id="priceOverall" class="number"></h1>
				</div>
			</div>

			<p data-lang="A,selPay" class="summaryInfo"></p>

			<div class="ticket-checkout__providers">
				<button id="stripe-button" type="button">
					<span>Stripe Checkout</span>
				</button>
				<div id="paypal-button-container"></div>
			</div>
		</section>
	</div>

	<a data-link="admin/ticketing" class="xbackPricing" data-lang="A,selBack">Back</a>
			<script>
			
				
				
				paypal.Buttons({
				    createOrder: function(data, actions) {
				        // Preis, Menge etc. aus LocalStorage oder Server holen
				        const price = localStorage.getItem('selectedPrice') || '7.00';
				        const quantity = localStorage.getItem('selectedQuantity') || 1;
				        const total = (parseFloat(quantity) * parseInt(price)).toFixed(2);
				        const type = localStorage.getItem('selectedType');
				        const product = localStorage.getItem('selectedProduct') || 'Ticket';
				        const affiliate = localStorage.getItem('affiliate_id') || '';
						
				        return actions.order.create({
				            purchase_units: [{
				                description: "Carlvon Ticket Service",
				                amount: {
				                    currency_code: "EUR",
				                    value: total
				                },
				                custom_id: type + '|' + quantity + '|' + price + '|' + total + '|' + affiliate
				            }],
				            application_context: {
								shipping_preference: "GET_FROM_FILE" // 👈 Adresse vom PayPal-Konto holen
							}
				        });
				    },
				    onApprove: function(data, actions) {
				    	
				    	const voucher = localStorage.getItem('voucher') || 'false';
				    	
				    	const custom = {};
				    	custom['price'] = localStorage.getItem('selectedPrice') || '7.00';
				        custom['quantity'] = localStorage.getItem('selectedQuantity') || 1;
				        custom['total'] = (parseFloat(custom['quantity']) * parseInt(custom['price'])).toFixed(2);
				        custom['type'] = localStorage.getItem('selectedType');
				        custom['affiliate'] = localStorage.getItem('affiliate_id') || '';
				    	custom['voucher'] = voucher;
				    	custom['lang'] = $('html').attr('lang');
				    	custom['land'] = $('html').attr('land');
				    	
				        return actions.order.capture().then(function(details) {
				        	
				        	$.ajax({
								    url: '/',
								    method: 'POST',
								    dataType: 'json',
								    data: {
								          req: 'ticketing',
										  action: 'make_paypal_data',
										  order: 'success',
										  lang: $('html').attr('lang'),
										  land: $('html').attr('land'),
										  voucher: voucher,
										  order_id: data.orderID,
										  provider: 'paypal',
										  details: JSON.stringify(details), // ✅ jetzt als JSON-String
										  custom: custom,
										  payer: JSON.stringify(details.payer)
								    }
								})
								.done(function(response) { 
								        console.log(response);
								        if(response && response.order_id && response.status) {
									  
											    var msg = TT('A,plan-s1') + ' ' + response.order_id;//->Order completed: " + response.order_id
											    standardInfo(msg);
		
											    const $form = $('<form>', {
											        id: 'forwardForm',
											        action: 'https://carlvon.cloud',
											        method: 'POST'
											    });
											
											    // Basisparameter
											    $form.append($('<input>', { type: 'hidden', name: 'ticketing', value: 'true' }));
											    $form.append($('<input>', { type: 'hidden', name: 'orderprocess', value: response.status }));
											    $form.append($('<input>', { type: 'hidden', name: 'provider', value: 'paypal' }));
												$form.append($('<input>', { type: 'hidden', name: 'order_id', value: response.order_id }));
												$form.append($('<input>', { type: 'hidden', name: 'voucher', value: voucher}));
												$form.append($('<input>', { type: 'hidden', name: 'email', value: 'see response'}));
												$form.append($('<input>', { type: 'hidden', name: 'lang', value: $('html').attr('lang')}));
												$form.append($('<input>', { type: 'hidden', name: 'land', value: $('html').attr('land')}));
											
											    // Form anhängen und absenden
											    $('body').append($form);
											    $form.submit();
									
										  } else {
										    	var msg = TT('A,plan-pp-e1') + ' ' + TT('A,plan-e');//->Fehler beim Speichern der Bestellung
										    	standardError(msg);
										  }
								})
								.fail(function(xhr, status, error) {
									var msg = TT('A,plan-e2') + ' ' + TT('A,plan-e');//->Abwicklung der Bestellung fehlgeschlagen
									standardError(msg);
				    				try { return AX.site('about/pricing'); } catch (e) {}
								});

				        });
				    },
				    onCancel: function(data) {
				    	//alert("Paypal Payment cancelled!");
				    	console.error('POST to paypal canceled', data);
				    	var msg = TT('A,plan-e3') + ' ' + TT('A,plan-e');//->Bestellvorgang abgebrochen
						standardError(msg);
				    	return AX.site('about/pricing');
				        
				    },
				    onError: function(err) {
				    	//alert("Error with PayPal Payment");
				        console.error("PayPal Fehler:", err);
				        var msg = TT('A,plan-e4') + ' ' + TT('A,plan-e');//->Fehler bei der Bearbitung des Zahlungsabwicklers
						standardError(msg);
				        return AX.site('about/pricing');
				    }
				}).render('#paypal-button-container'); 
				
				document.getElementById('stripe-button').addEventListener('click', function() {
					const stripe = Stripe('pk_test_51RcpZbQTKUSR7jHiOPHetQrzd3bxrUKWgUTvcgP2uFdXJHrmz44L2lFqfxCBMW6kpi0IWCOoJNhDSIzAi5IHLrKt00wsCewjlO'); 
					// 🔑 Deine öffentliche Stripe-Publishable-Key
					
					// Hole Preis, Anzahl etc. aus localStorage
					const price = localStorage.getItem('selectedPrice') || '7.00';
					const quantity = localStorage.getItem('selectedQuantity') || 1;
					const type = localStorage.getItem('selectedType') || 't';
					const product = localStorage.getItem('selectedProduct') || 'Ticket';
				    const affiliate = localStorage.getItem('affiliate_id') || '';
				    const voucher = localStorage.getItem('voucher') || 'false';
				    const lang = $('html').attr('lang');
				    const land = $('html').attr('land');
				    
				    		$.ajax({
								    url: '/',
								    method: 'POST',
								    dataType: 'json',
								    data: {
        					            req: 'ticketing',
										action: 'start_stripe_sdk',
										lang: $('html').attr('lang'),
										land: $('html').attr('land'),
										price: price,
										quantity: quantity,
										voucher: voucher,
										type: type,
										productname: product,
										affiliate: affiliate
								    }
								})
								.done(function(response) {
								      console.log(response);
									    if (response.success && response.session_id) {
											stripe.redirectToCheckout({ sessionId: response.session_id });
										} else {
											//alert('Stripe Checkout konnte nicht gestartet werden.');
											var msg = TT('A,plan-e1') + ' ' + TT('A,plan-e');//->Fehler bei der Bearbitung des Zahlungsabwicklers
											standardError(msg);
											return AX.site('about/pricing');
										}
								})
								.fail(function(xhr, status, error) {
									alert("Payment failed");
									console.error('POST to stripe failed:', status, error);
								    console.error('Response:', xhr.responseText);
								    var msg = TT('A,plan-e2') + ' ' + TT('A,plan-e');//->Abwicklung der Bestellung fehlgeschlagen
									standardError(msg);
				    				return AX.site('about/pricing');
								});
				});
			</script>	
</section>



