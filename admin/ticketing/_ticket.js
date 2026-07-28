$.WOLF.ticket = Tick = {
	loc: frame + '_ticketing/',
	new_order: function () {
		clearLocal();
		let comesfrom = 'internal';
		if($('body').hasClass('is_login')) comesfrom = 'ticketing';
		let _self = this;
		$('main').attr('id', 'mid');
		let location = $('#mid');
		localStorage.setItem('comesfrom', comesfrom);
		localStorage.setItem('admin_id', $('#admin_id').val());
		localStorage.setItem('lang', $('html').attr('lang'));
		localStorage.setItem('land', $('html').attr('land'));
		
		$(location).load('/?innerticketing', function () {
			    let paypalID = $('#infoID').val();
			    let scripts = '<div id="scriptbox"><script src="https://www.paypal.com/sdk/js?client-id=' + paypalID + '&currency=EUR"></script><script src="https://js.stripe.com/v3/"></script></div>';
			    $(location).fadeIn(400, function () {
					$.WOLF.reinit();
				});
				if(!$('#scriptbox').length) {
			    	$(scripts).insertAfter(location);
			    } 
		});
	},
	load_orders: function () {  
		let user_id = $('#admin_id').val();
		if(!user_id) return standardError('no user_id found');
		$.post('/', {
			    req: 'ticketing',
			    action: 'load_orders',
			    lang: $('html').attr('lang'),
			    user_id: user_id || 0
			}, function (html) {
			    $('#mid').html(html);
			});
	},
	order_completed: function (obj) {  
		
		    localStorage.setItem('order_id', $('#pending_order').val());
		
		    standardError('order_completed for ' + localStorage.getItem('order_id'));
		    const rawKeys = $(obj).data('keys');
		    const requiredKeys = normalizeKeys(rawKeys);
		    let missing = [];
		
		    requiredKeys.forEach(function(key) {
		        let value = localStorage.getItem(key);
		
		        if (!value || value === 'null') {
		            const input = $(`input[type=hidden][name="${key}"]`);
		            if (input.length && input.val()) {
		                value = input.val();
		                localStorage.setItem(key, value);
		                //standardSuccess(`${key} aus Hidden-Field übernommen: ${value}`);
		            } else {
		                //standardError(`${key} is missing`);
		                missing.push(key);
		            }
		        } /*else {
		            standardInfo(`${key} aus localStorage übernommen: ${value}`);
		        }*/
		    });
		    
		    if(localStorage.getItem('comesfrom') === 'internal') {
		    	//standardSuccess('internal');
		    	$(obj).addClass('isInternal');
		    }
		    if(localStorage.getItem('voucher') === true || localStorage.getItem('voucher') == "true") {
		    	//standardSuccess('voucher');
		    	$(obj).addClass('hasVoucher');
		    }
		    localStorage.setItem('comesfrom', '');
		    return missing.length === 0;
	},
	search_order:function (keyword = null) {
		if(!keyword) keyword = $('#searchOrder').val().trim();
		if (!keyword) {
			$('#searchOrder').select();
			return errorTip($('#searchOrder'), TT("A,OL-99"));
			//standardError('Bitte Order-ID oder Voucher eingeben');
		}
	
		$.post('/', {
			req: 'ticketing',
			action: 'search_order',
			lang: $('html').attr('lang'),
			keyword: keyword
		}, function(html) {
			$('#mid').html(html);
		});
	},
	send_invoice: function () {
		//standardInfo('sending:' + $('#pending_order').val() + '->' + $('#identified_user').val());
		user = $('#identified_user').val();
		order = $('#pending_order').val();
		if(!user || !order) return standardError(TT('A,sel-all'));
		
		$.ajax({
			url: '/',
			type: 'POST',
			dataType: 'json',
			data: {
				req: 'ticketing',
				action: 'send_invoice',
				order_id: order,
				user_id: user,
				lang: $('html').attr('lang')
			},
			success: function (response) {
				response = testResponse(response);
				if (response.success && response.user && response.order && response.email) {
					swupAlert('<h5>' + response.order + '</h5><p>verschickt an: ' + response.user + ' ---> ' + response.email + '</p>');
					$('#sum_user').text(response.user);
					$('#sum_order').text(response.order);
					Tick.loadArticle('summary');
				} else {
					swupError(response.error);
				}
			},
			error: function (response) {
				response = testResponse(response);
				swupError(response.error);
			}
		});
	},
	make_invoice: function (orderId = null) {
		if (!orderId) orderId = $('#pending_order').val();
		if (!orderId) return standardError('Fehlende Daten');
		
		$.ajax({
		  url: '/',
		  method: 'POST',
		  xhrFields: {
		    responseType: 'blob' // 🔥 wichtig!
		  },
		  data: {
		    req: 'ticketing',
		    action: 'download_invoice',
		    order_id: orderId,
		    lang: $('html').attr('lang')
		  },
		  success: function (blob, status, xhr) {
		    const fileName = xhr.getResponseHeader('Content-Disposition')?.split('filename=')[1] ?? 'invoice.pdf';
		
		    const link = document.createElement('a');
		    link.href = window.URL.createObjectURL(blob);
		    link.download = fileName.replace(/"/g, '');
		    link.click();
		
		    standardSuccess('Rechnung wurde heruntergeladen.');
		  },
		  error: function () {
		    standardError('Download fehlgeschlagen.');
		  }
		});
	},
	loadScreen: function (screen, location = 'mid') {
		let _self = this;
		location = $('#' + location);
	    $(location).fadeOut(200, function () {
			$(location).load(_self.loc + 'screens/' + screen + '.php', function () {
				register(location);
				
				$(location).fadeIn(400, function () {
					if (typeof _self.init[screen] === 'function') {
						_self.init[screen](); // z.B. init['1_payment']()
					}
					$(location).find('input:visible:enabled, textarea:visible:enabled').first().focus();
				});
			});
		});
	},
	loadArticle: function (article) {
		
		let newArticle = $('article#art-' + article);
		$('main').animate({opacity:0}, 200, function () {
			$('article').hide();
			$(newArticle).show();
			setTimeout(function () {
				$('main').animate({opacity:1}, 200, function () {
					register(newArticle);
				});
			}, 400);
		});
	},
	proceedOrder: function () {
		standardError($('#pending_order').val() + ' ---> ' + $('#identified_user').val());
	},
	enterPlatform: function (id = null) {
		let starter = '';
		if(!id) id = localStorage.getItem('admin_id');
		if(!id) id = $('#identified_user').val();
		
		if(!id) return standardError(TT('A,sel-all'));
		
		if($('#dummyForm').length > 0) $('#dummyForm').remove();
		$('body').append('<form id="dummyForm" name="dummyForm" method="POST" action="/" style="display:none;"><input type="text" name="admin_id" id="admin_id" value="' + id + '"></form>');
		return document.dummyForm.submit();
	},
	createVoucher: function () {
		function showAftercreation() {
			let vID = localStorage.getItem('voucher_id');
			$('#voucherId').html(vID);
			$('#orderId').html(localStorage.getItem('order_id'));
			$('.beforeCreation, #headline').hide();
			$('.afterCreation, #voucherline').fadeIn();
			let link = 'https://carlvon.cloud?code=' + vID;
			$('#voucherBox .qrLink').html(link);
			$('#voucherBox .qrCode').attr('data-content', link);
			$('#voucherBox .qrLink').html(link);
			$('#voucherBox .voucherId').html(vID).click(function () {
				return window.location.href = link;
			});
			$.WOLF.reinit();
		}
		let order_id = $('#pending_order').val().trim();
		
		if(!order_id) order_id = localStorage.getItem('order_id');
		if(!order_id) standardError('Keine Order ID');
			    
	    // --- AJAX Call ---
	    $.ajax({
	        url: '/',
	        type: 'POST',
	        dataType: 'json',
	        data: {
	            req: 'ticketing',
	            action: 'create_voucher',
	            order_id: order_id
	        },
	        success: function (response) {
	            console.log(response);
	            if (response.success && response.voucher_id) {
	                localStorage.setItem('order_id', response.order_id);
	                localStorage.setItem('voucher_id', response.voucher_id);
	                showAftercreation();
	            } else {
	                standardError(TT('A,plan-ev1'));
	                showAftercreation();
	            }
	        },
	        error: function (response) {
	            console.error('AJAX Fehler:', response);
	            standardError(TT('A,sel-oproblem'));
	        }
	    });
	},
	downloadVoucher: function () {
		let orderId = $('#pending_order').val().trim();
	
		if (!orderId) {
			standardError(TT("A,e-3") + ' (A unique order has not been received.)');
			return;
		}
	
		$.ajax({
			url: '/',
			method: 'POST',
			xhrFields: {
				responseType: 'blob' // 📦 PDF als Blob behandeln
			},
			data: {
				req: 'ticketing',
				action: 'download_voucher',
				order_id: orderId,
				lang: $('html').attr('lang')
			},
			success: function (blob, status, xhr) {
				const disposition = xhr.getResponseHeader('Content-Disposition');
				const fileName = disposition?.split('filename=')[1]?.replace(/["']/g, '') || 'voucher.pdf';
	
				const link = document.createElement('a');
				link.href = window.URL.createObjectURL(blob);
				link.download = fileName;
				link.click();
	
				standardSuccess(TT("A,ei-1"));
			},
			error: function () {
				standardError(TT("A,e-3") + ' (Failure while downloading your voucher.)');
			}
		});
	},
	sendVoucher: function () {
		let order_id = 	localStorage.getItem('order_id');
		let voucher_id = localStorage.getItem('voucher_id');
	    let name = $('#voucher_name').val().trim();
	    let email = $('#voucher_email').val().trim();
	
	    // --- Validation ---
	    if (!order_id || !voucher_id) {
	        return standardError(TT("A,plan-ev"));
	    }
	
	    if (name && name.length < 3) {
	        return errorTip($('#voucher_name').closest('fieldset'), TT("A,e-10n"));
	    }
	
	    if (email && !validateEmail(email)) {
	        return errorTip($('#voucher_email').closest('fieldset'), TT("A,e-10a"));
	    }
	
	    // --- AJAX Call ---
	    $.ajax({
	        url: '/',
	        type: 'POST',
	        dataType: 'json',
	        data: {
	            req: 'ticketing',
	            action: 'send_voucher',
	            order_id: order_id,
	            voucher_id: voucher_id,
	            lang: $('html').attr('lang'),
	            name: name || null,
	            email: email || null
	        },
	        success: function (response) {
	            
	            response = check(response);
	            console.log(response);
	            if (response.success && response.voucher_id) {
	                standardSuccess(TT("A,plan-ivs"));
	                return Tick.downloadVoucher();
	            } else {
	            	let msg = response.error ?? '';
	            	return standardError(TT("A,plan-evs") + msg);
	            }
	        },
	        error: function (response) {
	            console.error('AJAX Fehler:', response);
	            return standardError(TT("A,plan-evs"));
	        }
	    });
	},
	finishOrder: function (id) {
		$('#identified_user').val(id);

		let user = id ? id : localStorage.getItem('user_id');
		let order = localStorage.getItem('order_id');
		
		if(!user) { return alert('no user identified');}
		if(!order) { return alert('no order identified');}
		
		let isInternal = localStorage.getItem('from') == 'internal' ? true : false;
		
		if(!user || !order) return standardError(TT('A,sel-all'));
		
		$.ajax({
			url: '/',
			type: 'POST',
			dataType: 'json',
			data: {
				req: 'ticketing',
				action: 'update_order_user',
				order_id: order,
				user_id: user
			},
			success: function (response) {
				console.log(response);
				if (response.success && response.user && response.order) {
					$('#sum_user').text(response.user);
					$('#sum_order').text(response.order);
					if(user) {
						$('body').append('<form id="dummyForm" name="dummyForm" method="POST" action="/" style="display:none;"><input type="text" name="admin_id" id="admin_id" value="' + user + '"></form>');
						return document.dummyForm.submit();
					} else {
						Tick.loadArticle('summary');
					}
				} else {
					standardError(TT('A,sel-oproblem'));
				}
			},
			error: function (response) {
				console.error('error', response);
				standardError(TT('A,sel-oproblem'));
			}
		});
	},
	xcheckLogin: function (obj) { 
    	
	    	function handleError (response) {
				return swupAlert('<h5>' + TT("A,l9-12") + '</h5><p>' + TT("A,l9-12a") + '</p>', {afterClose: function () { $('#username, #password').addClass('hasError');$('#username').select();}});
			}
					
			$('.is-missing, .is-invalid').removeClass('is-missing is-invalid');
			if(!$('#username').val()) 	   { $('#username').select();return errorTip($('#username').closest('fieldset'), TT("A,l05-f1"));} 
			else if(!$('#password').val()) { $('#password').select();return errorTip($('#password').closest('fieldset'), TT("A,l05-f2"));}
			$.WOLF.log.button(obj, true);
			
			return jQuery.ajax({   
							type: 'POST',
			                url: BACKBONE,
			                dataType: 'json',
			                data: { 
			                     	randy: getRandom(), 
			                      	req: 'login_checkLogin', 
			                      	counter: $('#counter').val(), 
			                      	username: $('#username').val(), 
			                      	password: $('#password').val(), 
			                      	remember_login: $('[name="remember_login"]').prop('checked') },
			                success: function (response) {  
			                		clogs('checkLogin:', response);
				                    $.WOLF.log.button(obj, false);
				                    /* counter runterzählen */
									if(response && (response.counter || response.counter == 0)) _Log.counter(response.counter);
				                    try {
					                    return response.id ? Tick.finishOrder(response.id) : handleError(response);
					                } catch (e) { 
					                	clogs('error - checkLogin', e);
					                 	return _Log.handleStandards(2, e.message);
					                }
							},
				            error: function(jqXHR, textStatus, errorThrown) {
				            		clogs('error - checkLogin', textStatus);
								    return _Log.handleStandards(1, textStatus + '_' + errorThrown);
							},
							beforeSend: function(jqXHR, settings) {
						        	console.log('Before Send checkLogin: ', settings.data);
						    }
			}); 
	}, 
	checkLogin: function (obj) { 
		if(!obj) obj = $('#art-sign_in').find('a.btn');
    	
	    	function handleSuccess (response) {
	    		    if(response.id) {
	    		    	localStorage.setItem('user_id',response.id);
	    		    	Tick.loadArticle('summary');
	    		    }
					return swupAlert('<h5>' + TT("A,l9-12") + '</h5><p>' + TT("A,l9-12a") + '</p>', {afterClose: function () { $('#username, #password').addClass('hasError');$('#username').select();}});
			}
			
			function handleError (response) {
				return swupAlert('<h5>' + TT("A,l9-12") + '</h5><p>' + TT("A,l9-12a") + '</p>', {afterClose: function () { $('#username, #password').addClass('hasError');$('#username').select();}});
			}

					
			var msg = '', title = 'Error';
			$('.is-missing, .is-invalid').removeClass('is-missing is-invalid');
			if(!$('#username').val()) 	   { $('#username').select();return errorTip($('#username').closest('fieldset'), TT("A,l05-f1"));} 
			else if(!$('#password').val()) { $('#password').select();return errorTip($('#password').closest('fieldset'), TT("A,l05-f2"));}
			$.WOLF.log.button(obj, true);
			
			let voucher = localStorage.getItem('voucher_id') ?? null;
			let order = localStorage.getItem('order_id') ?? null;
			
			return jQuery.ajax({   
							type: 'POST',
			                url: BACKBONE,
			                dataType: 'json',
			                data: { 
			                     	randy: getRandom(), 
			                      	req: 'login_checkLogin', 
			                      	counter: $('#counter').val(), 
			                      	username: $('#username').val(), 
			                      	password: $('#password').val(), 
			                      	voucher_id: voucher,
			                      	order_id: order,
			                      	remember_login: $('[name="remember_login"]').prop('checked') },
			                success: function (response) {  
			                		clogs('checkLogin:', response);
				                    $.WOLF.log.button(obj, false);
				                    /* counter runterzählen */
									if(response && (response.counter || response.counter == 0)) _Log.counter(response.counter);
				                    try {
				                    	return response.code == '1' || response.code == 1 ? handleSuccess(response) : handleError(response);
					                } catch (e) { 
					                	clogs('error - checkLogin', e);
					                 	return _Log.handleStandards(2, e.message);
					                }
							},
				            error: function(jqXHR, textStatus, errorThrown) {
				            		clogs('error - checkLogin', textStatus);
								    return _Log.handleStandards(1, textStatus + '_' + errorThrown);
							},
							beforeSend: function(jqXHR, settings) {
						        	console.log('Before Send checkLogin: ', settings.data);
						    }
			});
	},
	checkRegister: function (obj) {
		    function validEmail(email) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email); }
		    function validString(str) { return /^[A-Za-z0-9]{5,12}$/.test(str); }
			function minString(str, min = 5) { return str.trim().length >= min; }
			function handleError (response) {
				switch (parseInt(response.status)) {
					case -1:    return errorTip($('#reg_email').closest('fieldset'), TT('A,ereg-emex'));break;
					case -2:    return errorTip($('#reg_username').closest('fieldset'), TT('A,ereg-upex'));break;
					default: 	return standardError(TT('A,e-2') + ' (ErrCode log/cC: ' + response.status + ')');
				}
				return;
			}
			
			$('.is-missing, .is-invalid').removeClass('is-missing is-invalid');
			if(!$('#reg_fullname').val()) 	       { $('#reg_fullname').select();return errorTip($('#reg_fullname').closest('fieldset'), TT("A,sel-ffn"));} 
			else if(!minString($('#reg_fullname').val())) 	       { $('#reg_fullname').select();return errorTip($('#reg_fullname').closest('fieldset'), TT("A,sel-ffnx"));} 
			else if(!$('#reg_email').val())        { $('#reg_email').select();return errorTip($('#reg_email').closest('fieldset'), TT("A,sel-fem"));}
			else if(!validEmail($('#reg_email').val())) { $('#reg_email').select();return errorTip($('#reg_email').closest('fieldset'), TT("A,sel-fex"));}
			else if(!$('#reg_username').val()) 	   { $('#reg_username').select();return errorTip($('#reg_username').closest('fieldset'), TT("A,sel-f1"));} 
			else if(!validString($('#reg_username').val())) 	   { $('#reg_username').select();return errorTip($('#reg_username').closest('fieldset'), TT("A,sel-f1v"));} 
			else if(!$('#reg_password').val())     { $('#reg_password').select();return errorTip($('#reg_password').closest('fieldset'), TT("A,sel-f2"));}
			else if(!validString($('#reg_password').val())) 	   { $('#reg_password').select();return errorTip($('#reg_password').closest('fieldset'), TT("A,sel-f2v"));} 
			else if(!$('[name="reg_confirm"]').prop('checked')) { $('[name="reg_confirm"]').prop('checked', 'true');return errorTip($('#reg_confirm_0').closest('fieldset'), TT("A,sel-agb"));}
			$.WOLF.log.button(obj, true);
			
			
			let voucher = localStorage.getItem('voucher_id') ?? null;
			
			return jQuery.ajax({   
							type: 'POST',
			                url: BACKBONE,
			                dataType: 'json',
			                data: { 
			                     	randy: getRandom(), 
			                      	req: 'login_register', 
			                      	username: $('#reg_username').val(), 
			                      	password: $('#reg_password').val(), 
			                      	fullname: $('#reg_fullname').val(), 
			                      	email: $('#reg_email').val(), 
			                      	nationcode: $('html').attr('land'), 
			                      	langcode: $('html').attr('lang'), 
			                      	voucher_id: voucher,
			                      	remember_login: $('[name="reg_remember_login"]').prop('checked') },
			                success: function (response) {  
			                		console.log('checkRegister:', response);
				                    $.WOLF.log.button(obj, false);
				                    /* counter runterzählen */
									//if(response && (response.counter || response.counter == 0)) _Log.counter(response.counter);
				                    try {
				                    	if(voucher) {
				                    		return response.id ? _Log.finishVoucher(response.id) : handleError(response);
				                    	} else {
				                    		return response.id ? Tick.finishOrder(response.id) : handleError(response);
				                    	}
					                } catch (e) { 
					                	console.log('error - checkLogin', e);
					                 	return _Log.handleStandards(2, e.message);
					                }
							},
				            error: function(jqXHR, textStatus, errorThrown) {
				            		clogs('error - checkLogin', textStatus);
								    return _Log.handleStandards(1, textStatus + '_' + errorThrown);
							},
							beforeSend: function(jqXHR, settings) {
						        	console.log('Before Send checkLogin: ', settings.data);
						    }
			}); 
		
	},
	newCredentials: function (obj) {
			function validEmail(email) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email); }
		    function validString(str) { return /^[A-Za-z0-9]{5,12}$/.test(str); }
			function minString(str, min = 5) { return str.trim().length >= min; }
			function handleError (response) {
				switch (parseInt(response.status)) {
					case -1:    return errorTip($('#new_username').closest('fieldset'), TT('A,ereg-upex'));break;
					default: 	return standardError(TT('A,e-2') + ' (ErrCode log/cC: ' + response.status + ')');
				}
				return;
			}
		
		    let stored_code = localStorage.getItem('lostCode');
			let stored_email = localStorage.getItem('lostEmail');
			if(!stored_code || !stored_email) return standardError(TT('A,sel-all'));
		    $('.is-missing, .is-invalid').removeClass('is-missing is-invalid');
			if(!$('#new_username').val()) 	   						{ $('#new_username').select();return errorTip($('#new_username').closest('fieldset'), TT("A,sel-f1"));} 
			else if(!validString($('#new_username').val())) 	   	{ $('#new_username').select();return errorTip($('#new_username').closest('fieldset'), TT("A,sel-f1v"));} 
			else if(!$('#new_password').val())     					{ $('#new_password').select();return errorTip($('#new_password').closest('fieldset'), TT("A,sel-f2"));}
			else if(!validString($('#new_password').val())) 	   	{ $('#new_password').select();return errorTip($('#new_password').closest('fieldset'), TT("A,sel-f2v"));} 
			$.WOLF.log.button(obj, true);
		
			return jQuery.ajax({   
							type: 'POST',
			                url: BACKBONE,
			                dataType: 'json',
			                data: { 
			                     	randy: getRandom(), 
			                      	req: 'login_newCredentials', 
			                      	username: $('#new_username').val(), 
			                      	password: $('#new_password').val(), 
									email: stored_email,
									code: stored_code },
			                success: function (response) {  
			                		clogs('newCredentials:', response);
				                    $.WOLF.log.button(obj, false);
				                    try {
					                    return response.id ? Tick.finishOrder(response.id) : handleError(response);
					                } catch (e) { 
					                	clogs('error - newCredentials', e);
					                 	return _Log.handleStandards(2, e.message);
					                }
							},
				            error: function(jqXHR, textStatus, errorThrown) {
				            		clogs('error - newCredentials', textStatus);
								    return _Log.handleStandards(1, textStatus + '_' + errorThrown);
							},
							beforeSend: function(jqXHR, settings) {
						        	console.log('Before Send newCredentials: ', settings.data);
						    }
			}); 
	},
	lostCredentials: function () {  
		
		function validEmail(email) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email); }
		
		function handleError (response) {
			switch (parseInt(response.status)) {
				case -1: 	return errorTip($('#lost_email').closest('fieldset'), TT("A,l02-f1"));//'Die E-Mail Adresse ist unbekannt bzw. ist keinem User zugeordnet!');
				case -2: 	return errorTip($('#lost_email').closest('fieldset'), TT("A,l02-f2"));//'<b>' + TT("A,l02-f2") + '</b><br><br>' + TT("A,l02-f3") + '<br>' + TT("A,l02-f4"));
				case -3: 	return errorTip($('#lost_email').closest('fieldset'), TT("A,l02-f3"));//_Log.finale('<b>' + TT("A,l02-f5") + '</b><br><br>' + TT("A,l02-f3") + '<br>' + TT("A,l02-f4"));
				case -4: 	return errorTip($('#lost_email').closest('fieldset'), TT("A,l02-f5"));
				default: 	return swupAlert('<h5>' + TT("A,l02-f7") + '</h5><p>' + TT("A,l02-f8") + '</p>', { afterClose: function () { $('#lost_email').select();}});
			}
			return;
		}
		
		function codeInput (reset = false) {
			const codeInputs = $('#codeField input[type="text"]');
		    const maxAttempts = 3;
		    const blockTime = 2 * 60 * 1000; // 2 Minuten in Millisekunden
		    if(reset === true) return codeInputs.val('');
		    
		    function getCode() {
		        return codeInputs.map(function () {
		            return $(this).val();
		        }).get().join('');
		    }
		
		    function resetFields() {
		        codeInputs.val('');
		        codeInputs.first().focus();
		    }
		
		    function isBlocked() {
		        const blockedUntil = localStorage.getItem('codeBlockedUntil');
		        return blockedUntil && Date.now() < parseInt(blockedUntil);
		    }
		
		    function remainingTime() {
		        const until = parseInt(localStorage.getItem('codeBlockedUntil'));
		        const diff = Math.ceil((until - Date.now()) / 1000);
		        return diff > 0 ? diff : 0;
		    }
		
		    function showLockMessage() {
	    	      return swupAlert('<p>Bitte warte noch ' + remainingTime() + ' Sekunden, bevor du es erneut versuchst.</p>');
		    }
		
		    codeInputs.on('input', function () {
		        if (isBlocked()) {
		            resetFields();
		            showLockMessage();
		            return;
		        }
		
		        const $this = $(this);
		        $this.val($this.val().replace(/\D/g, '')); // Nur Ziffern
		        if ($this.val().length === 1) {
		            $this.next('input').focus();
		        }
		
		        if (getCode().length === 4) {
		            const correct = localStorage.getItem('lostCode');
		            const currentCode = getCode();
		
		            if (currentCode === correct) {
		            	localStorage.removeItem('codeAttempts');
		                localStorage.removeItem('codeBlockedUntil');
		                swupAlert('<p> Perfekt, danke! Gib jetzt bitte die neuen Zugangsdaten ein</p>', { afterClose: function () { Tick.loadArticle('lostnew');}});
		                
		                // Weiterleitung oder freischalten…
		            } else {
		                let attempts = parseInt(localStorage.getItem('codeAttempts') || '0') + 1;
		                localStorage.setItem('codeAttempts', attempts);
		                resetFields();
		
		                if (attempts >= maxAttempts) {
		                    const lockUntil = Date.now() + blockTime;
		                    localStorage.setItem('codeBlockedUntil', lockUntil);
		                    return swupAlert('<p>' + TT('A,sel-ec3') + '</p>');
		                } else {
		                    return swupAlert('<p>' + TT('A,sel-ec2,anz:' + (maxAttempts - attempts)) + '</p>', { afterClose: function () { codeInputs.val('');codeInputs.first().focus();}});
		                }
		            }
		        }
		    });
		
		    codeInputs.on('keyup', function (e) {
		        if (e.key === 'Backspace' && $(this).val() === '') {
		            $(this).prev('input').focus();
		        }
		    });
		
		    if (isBlocked()) {
		        showLockMessage();
		        resetFields();
		    } else {
		        codeInputs.first().focus();
		    }
		}
		
		function handleSuccess (response) {  
			if(response && response.code)  localStorage.setItem('lostCode', response.code);
			if(response && response.email) localStorage.setItem('lostEmail', response.email);
			Tick.loadArticle('lostcode');
			return codeInput();
		}
		
		codeInput(true);
		let email = $('#lost_email').val();
		if(!email) return errorTip($('#lost_email').closest('fieldset'), TT("A,l9-13a"));
		else if(!validEmail(email)) return errorTip($('#lost_email').closest('fieldset'), TT("A,l9-13b"));
		else {
			
			return jQuery.ajax({   
					type: 'POST',
			        url: BACKBONE,
			        dataType: 'json',
			        data: { 
			          	randy: getRandom(), 
			          	req: 'login_lostCredentials', 
			           	email: email, 
			           	counter: $('#counter').val()
			        },
			        success: function (response) {
			        	console.log(response);
			            try {	
				          	return (response && response.code && response.email) ? handleSuccess(response) : handleError(response);
					    } catch (e) { 
					      	return _Log.handleStandards(2, e.message);
					    }
					},
				    error: function(jqXHR, textStatus, errorThrown) {
							return _Log.handleStandards(1, textStatus + '_' + errorThrown);
					},
					beforeSend: function(jqXHR, settings) {
					        console.log('Before Send lostCredentials: ', settings.data);
					}
			});
		}
	},
	forget: function (obj) {
		standardError($('#log_username').val() + ' ---> ' + $('#log_password').val());
	},
	init: {
		'1_ticketdata': function () {  
			let price = localStorage.getItem('selectedPrice');
		    let type  = localStorage.getItem('selectedType');
			let quantity  = localStorage.getItem('selectedQuantity');
			let sum = localStorage.getItem('selectedSum');
			
		    price = parseFloat(price).toFixed(2);
			sum = parseFloat(sum).toFixed(2);
			
		    let names = 'A,plan_' + type;
		    let infos = names + '2';
		    let typeInfo = {
		        "t": TT('A,plan_t2'),
		        "m": TT('A,plan_m2'),
		        "y": TT('A,plan_c2')
		    }[type] || '';
		    
		    let imageSrc = '/_assets/img/webs/';
		    if(type === 't') imageSrc += 'ticket';
		    else if(type === 'm') imageSrc += 'month';
			else if(type === 'y') imageSrc += 'year';
			else imageSrc += 'ticket';
			imageSrc += '.jpg';
		    
		    // Anzeige aktualisieren
		    $('#summaryQuantity').text(quantity);
			$('#productInfo').html(TT(names));
		    $('#productInfo2').html(TT(infos));
		    $('#priceInfo').text(price + ' €');
			$('#priceSum').text(sum + ' €');
			$('#priceOverall').html(parseInt(sum) + '<sup class="priceSup">€</sup>');
			$('#summaryImage').attr('src', imageSrc).animate({opacity:1}, 300);
			
			$.WOLF.reinit();
			$('.backPricing').click(function () { 
				scrollStart();
				return Tick.new_order();
			});	
		},
		'99_finish': function () {
			let _self = this;
			return $('#mid').load(_self.loc + 'screens/1_data.php?type=' + type);
		}
	}
}
