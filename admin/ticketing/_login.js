$.WOLF.quick = {
	    noThanks: function () { //-> wenn man nicht will abbrechen
			return window.location.href='https://carlvon.com'; 
		},
		betterNo: function (type = false) {
			$('article[data-article]').hide();
			if(type === true) {
				$('article[data-article=test]').fadeIn();
			} else {
				$('article[data-article=betterNo]').fadeIn();
			}
		},
		linkTo: function (link = 'home') {
			if(link == "home") return window.location.href="https://carlvon.com";
			else if(link == "ticket") return window.location.href="https://carlvon.com?site=admin/ticketing";
		},
		manageValues: function () {
			localStorage.setItem('codetype', $('#codetype').val());
			//standardInfo(localStorage.getItem('codetype'));
		},
	    sequenz: function (id = null) {
			$('#art-start').find('.sequenz').not('#' + id).removeClass('active');
			$('#art-start').find('#' + id).addClass('active');
			if(id == 'know_no' || id == 'accept_no') {
				setTimeout(function () {return window.location.href='https://carlvon.com';}, 2000);
			}
		},
		setAnonym: function (type = true) {
			if(type === false) { 
				Value.set('anonym', 1);  return this.startQuick(); 
			}
			else if(Value.get('rater_name') == '') { 
				Value.set('anonym', 1); return this.startQuick(); 
			}
			else { 
				Value.set('anonym', 0); this.goLogin(); 
			}
		},
		finish: function (type = 'anonym') {  
			
			    let codetype = $('#codetype').val();
			    localStorage.setItem('codetype', 'fremd');
			
				function nowLoad(stData = {}) {
				    
				    if ($('#dummyForm').length > 0) { $('#dummyForm').remove(); }
				
				    // Schritt 1: Basiskonfiguration aus stData
				    let fields = {
				        member: stData['member'] ?? '',
				        module: stData['module'] ?? '',
				        k_id: stData['k_id'] ?? '',
				        w_id: stData['w_id'] ?? '',
				        rater_name: stData['rater_name'] ?? '',
				        rater_avatar: stData['rater_avatar'] ?? '',
				        rater_id: stData['rater_id'] ?? '0',
				        score_id: stData['score_id'] ?? '0',
				        ref: stData['score_id'] ?? '0',
				        rater: stData['rater_id'] ?? '0',
				        anonym: stData['anonym'] ?? 1,
				        codetype: stData['codetype'] ?? '',
				        force: stData['force'] ?? ''
				    };
				
				    // Schritt 2: Alle hidden inputs mit class "admin" ins fields-Objekt holen
				    $('.admin').each(function () {
				        const name = $(this).attr('name');
				        const value = $(this).val();
				
				        if (name && value !== '') {
				            if(!fields[name]) {
				            
					            if (name.startsWith("user_") || name.startsWith("module_") ) {
					            	fields[name] = value;
					            }
					        }
				        }
				    });
				    
				    // Schritt 3: Alles ins localStorage speichern
					$.each(fields, function (name, value) {
					    if (name && value !== '') {
					        localStorage.setItem(name, value);
					    }
					});
				
				    // Schritt 3: Neues Formular bauen
				    let $form = $('<form>', {
				        id: 'dummyForm',
				        name: 'dummyForm',
				        method: 'POST',
				        action: '/',
				        style: 'display:none;'
				    });
				
				    // Schritt 4: Alle collected fields als Input anfügen
				    $.each(fields, function (name, value) {
				        $('<input>', {
				            type: 'hidden',
				            name: name,
				            id: name,
				            value: value
				        }).appendTo($form);
				    });
				    
				    // Anhängen und absenden
				    $('body').append($form);
				    $form.submit();
				}
			let storage = {};
			/* new Fields */
			storage['codetype'] = Value.get('codetype');
			storage['member'] = Value.get('user_id');
			storage['module'] = Value.get('module_id');
			storage['score_id'] = Value.get('score_id');
			storage['k_id'] = Value.get('know');
			storage['w_id'] = Value.get('where');
			
			if(type == 'anonym') {
				storage['anonym'] = 1; 
				storage['rater_id'] = 0; 
				storage['rater_name'] = 'anonymous';
				storage['force'] = 'fremd';
			} else if (type === 'name') {
				storage['anonym'] = 1; 
				storage['rater_id'] = 0; 
				storage['rater_name'] = $('#rater_name').val() ? $('#rater_name').val() : 'anonymous'; 
				storage['force'] = 'fremd';
			} else if (type === 'account') {
				storage['anonym'] = 0; 
				storage['rater_id'] = localStorage.getItem('rater_id'); 
				storage['rater_name'] = localStorage.getItem('rater_name');
				storage['rater_avatar'] = localStorage.getItem('rater_avatar'); 
				storage['force'] = 'fremd';
			}
			return nowLoad(storage);
		},
		linked: function () {

		    let linkedOwner = Value.get('user_id');
		    let linkedViewer = localStorage.getItem('rater_id');
		
		    if (!linkedOwner || !linkedViewer || linkedOwner === linkedViewer)
		        return standardError(TT("A,L-f1"));
		
		    return jQuery.ajax({
		        type: 'POST',
		        url: BACKBONE,
		        data: {
		            randy: getRandom(),
		            req: 'link_accounts',
		            lang: $('html').attr('lang'),
		            owner: linkedOwner,
		            viewer: linkedViewer
		        },
		        dataType: 'json',
		        success: function (response) {
		            console.log(response);
		
		            if (response.success) {
		            	$('#succButton').click(function () {
		            		return _Log.dummyLoad(linkedViewer);
		            	});
		            	$('#succMessage').html(response.message);
		            	$.WOLF.quick.sequenz('after_linked');
		            	return;
		                //return standardSuccess(response.message || '✅ Verlinkung erfolgreich');
		            } else {
		                return standardError(response.error || '❌ Fehler bei Verlinkung');
		            }
		        },
		        error: function (jqXHR, textStatus, errorThrown) {
		            console.error('❌ AJAX Fehler:', jqXHR.responseText);
		            return standardError('❌ Netzwerkfehler oder ungültige Serverantwort');
		        }
		    });
		}

}

$.WOLF.log = {
	loc: '/__wolfi/_frameMaker/_login/',
	startup: function () {
					
			const url = new URL(window.location.href);
		    const code = url.searchParams.get("code");
		    localStorage.setItem('code', code);
		    const cleanUrl = window.location.origin + window.location.pathname;
        	window.history.replaceState({}, document.title, cleanUrl);
		    if (code && code.toLowerCase().startsWith("v")) {
    			localStorage.setItem('code', code);
    			_Log.fixSwitcher('code');
    			_Log.checkCode();
		    } else {
					if($('#loginScreen').hasAttr('data-reset')) {
						_Log.switcher('reset'); 
					}
					else if (Value.get('uCode')) {
						if(Value.get('id')) _Log.switcher('whois'); 
						else _Log.switcher('code');
					} else if(Value.get('code')) {
						_Log.switcher('code');
						$('#codeChecker').click();
					} else { 
						_Log.switcher('switcher');
					}
			}
	},
	whois: function (type = true) {
		
		if(type == true) {
			if($('#loginScreen').attr('data-code') == 'error') {
					_Log.switcher('code');
					return _Log.checkCode();
			} else if($('#loginScreen').attr('data-code') == 'event') {
				    _Log.switcher('code');
					return _Log.checkCode();
			} else if($('#loginScreen').attr('data-code') == 'same') {
					setTimeout(function () { _Log.switcher('sign_in'); }, 2000);
				    return _Log.checkLogin();
			} else if($('#loginScreen').attr('data-code') == 'personal') {
					$('#password').val('');
					$('#username').val('');
				    return _Log.switcher('sign_in');
			} else {
				    //setTimeout(function () { _Log.switcher('sign_in'); }, 2000);
				    return _Log.checkLogin();
			}
			return;
		} else {
			$('#password').val('');
			$('#username').val('');
			$('#log_password').val('');
			$('#log_username').val('');
			if($('#loginScreen').attr('data-code') == 'error') {
					_Log.switcher('code');
					return _Log.checkCode();
			} else if($('#loginScreen').attr('data-code') == 'event') {
				    _Log.switcher('code');
					return _Log.checkCode();
			} else if($('#loginScreen').attr('data-code') == 'personal') {
				    _Log.switcher('sign_in');
				    return _Log.checkLogin();
			} else {
				_Log.switcher('switcher');
			}
		}
	},
	reset: function () {
	  	   $('#username').val("");
		   $('#password').val("");
	},
	checkSwitcher: function (switcher) {
		if(switcher == 'register') {
			let voucher = localStorage.getItem('voucher_id') ?? null;
			if(voucher) { return _Log.switcher('voucher-register');}
			else {return window.location.href = '/?site=admin/ticketing'; }
		}
	},
	fixSwitcher: function (id = 'code') {
		$('html').attr('data-log', id);
		$('#form_login > article').not('#art-' + id).hide().removeClass('show');
		$('#form_login > article#art-' + id).show();
		$('[data-variable]:visible:first').select();
	},
	switcher: function (id, pass = true) {
		$('html').attr('data-log', id);
		if(pass === true && id === 'sign_in') {  // dann durchstarten
			if(Value.get('uCode') && Value.get('username') && Value.get('password')) {
				return _Log.checkLogin();
			} 
		}
		$('#form_login > article').not('#art-' + id).fadeOut(600);//.hide().removeClass('show');
		$('#restarter').css('opacity', 0);
		setTimeout(function () {
			$('#form_login > article').not('#art-' + id).removeClass('show');
			$('#form_login > article#art-' + id).fadingIn(600);
			setTimeout(function () { $('[data-variable]:visible:first').select(); $('#restarter').css('opacity', 1);}, 200);
		}, 700);
	},
	button: function (obj, bool = false) {
		if(!obj) obj = $('.disabledButton');
		if(bool == true) $(obj).addClass('disabledButton').attr('disabled', true);
		else $(obj).removeClass('disabledButton').removeAttr('disabled');
		return;
	},
	handleStandards: function (id, msg = null) {
		console.log(msg);
		if(id == 1)  return standardError(TT('A,e-2') + ' (Error hs1)');//'Please retry in a few seconds','Server Connection failed!');
		if(id == 2)  return standardError(TT('A,e-3') + ' --> (Error hs2' + msg + ')');
		return;
	},
	finale: function (txt, head = "Tut uns leid!") {
		if(!txt) return false;
		$('#finalHead').html(head);
		$('#finalText').html(txt);
		$('p#restarter').hide();
		return this.switcher('finale');
	},
	counter: function (counter = null) {
		if(counter && counter == 'x') {
			destroyTip();	
			swupAlert('<h5>' + TT('A,l9-2') + '</h5><p>' + TT('A,l9-2a') + '</p><p>' + TT('A,l9-2b') + '</p>');
			return false;
		}
		else if(counter || counter == 0) {
			$('#counter').val(counter);
			if($('#counter').val() == 0) {
				destroyTip();
				setTimeout(function () { $('#counter').val(3);}, 30000);
				swupAlert('<h5>' + TT('A,l9-1') + '</h5><p>' + TT('A,l9-1a') + '</p><p>' + TT('A,l9-1b') + '</p>' );
				return false;
			}
		} else return true;
	},
	checkMissingVals: function (field = null, fieldset = null) { 
		
		function validEmail(email) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email); }
		function validAge(age, min = 10, max = 99) { 
			    var isInt = Number.isInteger(age);
			    if(isInt && age >= min && age <= max) return true;
			    else return false;
		}
		
		if(field === 'basics') {
		if(!Value.get('age')) 									    return errorTip($('#age'), TT('A,l08-f1'));
			else if(!validAge(parseInt(Value.get('age')), 10, 99))  return errorTip($('#age'), TT('A,l08-f2'));//'Bitte überprüfe das eingegebene Alter!');
			else if(!Value.get('gender')) 							return errorTip($('[data-control=checkicons]'),  TT('A,l08-f3'));//'Bitte wähle dein Geschlecht aus!');
		} else if(field === 'avatar') {
			if(!Value.get('avatar')) {
				if(!$('#avatar').hasClass('isAvatar')) {  //standardimage speichern
					var imagePath = $('#avatar').closest('fieldset').data('standardimage');
					$('.person .control').css('background-image', 'url("' + imagePath + '")');
					$('#avatar').addClass('isAvatar').attr('data-value', imagePath);
					AX.update($('[name=avatar]'));
					return this.checkMissings();
				}
			}
		} else  if(field === 'confirm') {
			if(!$('#confirm').is(':checked')) 
					return errorTip($('#confirm'),  TT('A,l08-f5'));//'Bitte bestätige, dass du mit den Datenschutzbestimmungen einverstanden bist!');
		} 
		return this.checkMissings();
	},
	checkMissings: function (response = null) {
		/*if(response && response.id)  {
			if(response.admin_id) 	Value.set('admin_id', response.admin_id);
		    if(response.id) 		Value.set('id', response.id);
			$('#loginScreen').load($.WOLF.log.loc + 'missings.php?id=' + response.id);
		}
		return;*/
		
		function missSwitch (item) {
			_Log.switcher('missings');
			$('#art-missings div[data-name]').not('[data-name="' + item + '"]').fadingOut();
			return $('#art-missings div[data-name="' + item + '"]').fadingIn();
		}
		

		if(response) {
			    if(response.id)  
			    	$('#art-missings').attr('data-id', response.id);
				if(response.confirm) 	Value.set('confirm', response.confirm);
				if(response.fullname) 	Value.set('fullname', response.fullname);
		    	if(response.aka) 		Value.set('aka', response.aka);
		    	if(response.email) 		Value.set('email', response.email);
		    	if(response.age && response.age > 0) 	Value.set('age', response.age);
		    	if(response.gender) 	Value.set('gender', response.gender);
		    	if(response.avatar) 	Value.set('avatar', response.avatar);
				$('[name="avatar"], [name="confirm"], [name="aka"], [name="gender"], [name="age"]').not('.registrated').addClass('registrated').change(function () {  AX.update(this); });
		    	if(response.admin_id) 	Value.set('admin_id', response.admin_id);
		    	if(response.id) 		Value.set('id', response.id);
		    	localStorage.setItem('id', response.id);
		    	localStorage.setItem('fullname', response.fullname);
		    	localStorage.setItem('avatar', response.avatar);
		}
		
		if(!Value.get('gender')) return missSwitch('basics');
		if(!Value.get('avatar')) return missSwitch('avatar');
		if(!Value.get('confirm')) return missSwitch('confirm');
		
		if(localStorage.getItem('codetype')) {
			standardInfo(localStorage.getItem('codetype'));
			if(localStorage.getItem('codetype') === 'fremd') {
				return this.dummyFremd();
			}
			else if(localStorage.getItem('codetype') === 'linked') {
				return this.dummyLinked();
			} 
			else if(localStorage.getItem('codetype') === 'voucher') {
				return this.dummyLoad(Value.get('admin_id'));
			}
		} else {
    		return this.dummyLoad(Value.get('admin_id'), $('seminarID').val());
    	}
    },
    checkCode: function (obj) {
		
		/* client proof */
		
		let code = $.trim($('#code').val());
		if(!code) code = localStorage.getItem('code');
		if(!code) {
			_Log.switcher('code');
			return errorTip($('#code'), TT('A,l04-f1'));
		}
		
		if($('#counter').val() <= 0) 	return _Log.counter('x');
		
		function handleError (response) {
			switch (parseInt(response.status)) {
				case -999:  return standardError(TT('A,e-2') + ' (' + response.status +  ')');break;
				case -998:  return standardError(TT('A,e-2') + ' (' + response.status +  ')');break;
				case -99:   return _Log.counter(response.counter) ?? errorTip($('#code').closest('fieldset'), TT('A,l04-f1'));break;
				case -98:   _Log.switcher('code');
							setTimeout(function () {
								_Log.counter(response.counter) ?? errorTip($('#code').closest('fieldset'), TT("A,l04-f2"));
							},800);
							return $('#code').select();
							break;
				case -97: 	_Log.counter(response.counter) ?? errorTip($('#code').closest('fieldset'), TT("A,l04-f2"));
							return $('#code').select();
							break;
				case -1: 	_Log.counter(response.counter) ?? errorTip($('#code').closest('fieldset'), TT("A,l04-f2"));
				default: 	if(response.error) return errorTip($('#code').closest('fieldset'), response.error);
							else return standardError(TT('A,e-2') + ' (ErrCode log/cC: ' + response.status + ')');
			}
			return;
		}
		
		function handlePersonal (response) {
			switch (parseInt(response.status)) {
				case -1: return _Log.finale('<b>' + TT("A,l9-3") + '</b><br><br>' + TT("A,l9-3a"));
				case -2: return _Log.finale('<b>' + TT("A,l9-4") + '</b><br><br>' + TT("A,l9-4a"));
				
				case 1: if(response.confirm) 	Value.set('confirm', response.confirm);
						if(response.fullname) 	Value.set('pers_fullname', response.fullname);
		    			if(response.aka) 		Value.set('aka', response.aka);
		    			if(response.avatar) 	Value.set('pers_avatar', response.avatar);
						if(response.email) 		Value.set('pers_email', response.email);
						if(response.id) 		Value.set('id', response.id);
						if(response.code) 	    $('#personalCode').val(response.code);
						$('[name="avatar"], [name="confirm"], [name="aka"], [name="fullname"]').addClass('registrated').change(function () {  AX.update(this); });
		    			if(response.admin_id) 	Value.set('admin_id', response.admin_id);
						//code is already in!
						if(!response.username || !response.password) {
							return _Log.switcher('codenocred');
						}
					    else {
					    	return _Log.switcher('sign_in');
					    }
					    break;
				default: return swupAlert('<h5>' + TT("A,l9-5") + '</h5><p>' + TT("A,l9-5a") + '</p>', {afterClose: function () {$('#code').select();}});
			}
			return;
		}
		
		function handleEvent (response) {
			switch (parseInt(response.status)) {
				case -1: 	return swupAlert('<h5>' + TT("A,l9-6") + '</h5><p>' + TT("A,l9-6a") + '</p>');
				case -2: 	return swupAlert('<h5>' + TT("A,l9-7") + '</h5><p>' + TT("A,l9-7a") + '</p><p>' + TT("A,l9-7b") + ' ' + formatDate(response.end) + '</p>');
				case -3: 	return swupAlert('<h5>' + TT("A,l9-8") + '</h5><p>' + TT("A,l9-8a") + '</p><p>' + TT("A,l9-8b") + ' ' + formatDate(response.start) + '</p>' );
				case 1: 	response.title && $('.seminarTitle').text(response.title);
							response.title && $('#seminarTitle').val(response.title);
							response.id && $('#seminarID').val(response.id);
							response.code && $('#seminarCode').val(response.code);
							$('#messageBox').html('Seminar ' + response.title);
							return _Log.switcher('seminarprooved');
				default: 	return swupAlert('<h5>' + TT("A,l9-9") + '</h5><p>' + TT("A,l9-9a") + '</p>', {afterClose: function () {$('#code').select();}});
			}
			return;
		}
		
		function handleFremd (response) { //redirect
			if(response && response.error) {
				return standardError(response.error);
			}
			else if(response && response.code && !response.error) {
				if($('#dummyForm').length > 0) $('#dummyForm').remove();
				$('body').append('<form id="dummyForm" name="dummyForm" method="POST" action="/" style="display:none;"><input type="text" name="code" id="code" value="' + response.code + '"></form>');
				return document.dummyForm.submit();
			}
			else return swupAlert('<h5>' + TT("A,l9-9") + '</h5><p>' + TT("A,l9-9a") + '</p>', {afterClose: function () {$('#code').select();}});
		}
		
		function handleVoucher (response) { //redirect
			
			console.log('handleVoucher', response);
			
			if(response && response.error) {
				    if(!$('#code').is(':visible')) {
				    	_Log.switcher('code');
				    	setTimeout(function () {
							errorTip($('#code').closest('fieldset'), response.error);
						}, 1200);
				    } else {
				    	return errorTip($('#code').closest('fieldset'), response.error);
				    }
					
					
					
					//swupAlert('<h5>' + TT("A,e-acht") + '</h5><p>' + response.error + '</p>');
					//return _Log.switcher('code');
			}
			else if(response && response.code && !response.error) {
				localStorage.setItem('voucher_id', response.code);
				localStorage.setItem('orders_id', response.orders_id);
				localStorage.setItem('codetype', 'voucher');
				$('body').addClass('hideCode');
				swupAlert('<h5>' + TT("A,plan-vok") + '</h5><p class="mt-3">' + TT("A,plan-vok1") + '</p>');
				if(Value.get('id')) {
					return _Log.switcher('voucher'); 
				} else {
					return _Log.switcher('switcher');
				}
				/*if($('#dummyForm').length > 0) $('#dummyForm').remove();
				$('body').append('<form id="dummyForm" name="dummyForm" method="POST" action="/" style="display:none;"></form>');
				return document.dummyForm.submit();*/
			}
			else return swupAlert('<h5>' + TT("A,l9-9") + '</h5><p>' + TT("A,l9-9a") + '</p>', {afterClose: function () {$('#code').select();}});
		}
		
		return jQuery.ajax({   
			type: 'POST',
		    url: BACKBONE,
		    data: { randy: getRandom(), 
		            req: 'login_checkCode', 
		            lang: $('html').attr('lang'),
		            code: code,
		            counter: $('#counter').val() },
		    dataType: 'json',
		    success: function (response) {  
		    	console.log(response);
			   	try {
			   			/* counter runterzählen */
						if(response && response.counter && (response.counter == 'x' || response.counter == 0)) 
							return _Log.counter(response.counter);
			
			        	switch(response.type) {
			        		case 'error': 			return handleError(response);break;
			        		case 'event': 			return handleEvent(response);break;
			        		case 'fremd': 			return handleFremd(response);break;
			        		case 'voucher': 		return handleVoucher(response);break;
			        		case 'personal': 		return handlePersonal(response);break;
			        		default: 				return handleError(response);break;
			        	}
			     } catch (e) {
			     		clogs('catch - checkCode', e);
			        	return _Log.handleStandards(2, e.message);
			     }
			},
			error: function(jqXHR, textStatus, errorThrown) {
						clogs('error - checkCode', textStatus);
				        return _Log.handleStandards(1, textStatus + '_' + errorThrown);
			},
			beforeSend: function(jqXHR, settings) {
						clogs('Before Send checkCode: ', settings.data);
			}
		}); 
		
	},
	finishVoucher: function (admin_id = null) {
		clearLocal();
		localStorage.setItem('admin_id', admin_id);
		if($('#dummyForm').length > 0) $('#dummyForm').remove();
		$('body').append('<form id="dummyForm" name="dummyForm" method="POST" action="/" style="display:block;"><input type="text" name="id" id="id" value="' + id + '" /></form>');
		return document.dummyForm.submit();
	},
	/* after voucher is set */
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
			
			function handleSuccess (response) {
				return _Log.checkMissings(response);
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
			let order = localStorage.getItem('order_id') ?? null;
			
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
			                      	order_id: order, 
			                      	remember_login: $('[name="reg_remember_login"]').prop('checked') },
			                success: function (response) {  
			                		console.log('checkRegister:', response);
				                    $.WOLF.log.button(obj, false);
				                    /* counter runterzählen */
									//if(response && (response.counter || response.counter == 0)) _Log.counter(response.counter);
				                    try {
				                    	/*if(voucher) {
				                    		return response.id ? _Log.finishVoucher(response.admin_id) : handleError(response);
				                    	} else if (order) {
				                    		return response.id ? Tick.finishOrder(response.admin_id) : handleError(response);
				                    	} else {*/
				                    		return response.id ? handleSuccess(response) : handleError(response);
				                    	//}
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
	quickLogin: function (uCode) {
		this.checkLogin();
	},
	/* the regular login procedure */
	checkLogin: function (obj) { 
		if(!obj) obj = $('#art-sign_in').find('a.btn');
    	
    	function handleError (response) {
			switch (parseInt(response.status)) {
				case -2: 	return _Log.finale('<b>' + TT("A,l9-10") + '</b><br><br>' + TT("A,l9-10a"));
				case -1: 	return _Log.finale('<b>' + TT("A,l9-11") + '</b><br><br>' + TT("A,l9-11a"));
				default: 	return swupAlert('<h5>' + TT("A,l9-12") + '</h5><p>' + TT("A,l9-12a") + '</p>', {afterClose: function () { $('#username, #password').addClass('hasError');$('#username').select();}});
			}
			return;
		}
		
		function handleSuccess (response) {
			
			let codetype = localStorage.getItem('codetype');
			
			if(codetype === 'fremd') {
				localStorage.setItem('rater_id', response.id);
				localStorage.setItem('rater_name', response.fullname);
				localStorage.setItem('rater_avatar', response.avatar);
				return $.WOLF.quick.finish('account');
			} else if(codetype === 'linked') {
				localStorage.setItem('rater_id', response.id);
				localStorage.setItem('rater_name', response.fullname);
				localStorage.setItem('rater_avatar', response.avatar);
				return $.WOLF.quick.linked();
			}
			
			
			if($(obj).closest('#art-login').length) {
				localStorage.setItem('user_id', response.id);
				let user = response.id;
				$('body').append('<form id="dummyForm" name="dummyForm" method="POST" action="/" style="display:none;"><input type="text" name="admin_id" id="admin_id" value="' + user + '"></form>');
				return document.dummyForm.submit();
			}
			_Log.checkMissings(response);
		}
					
			var msg = '', title = 'Error';
			$('.is-missing, .is-invalid').removeClass('is-missing is-invalid');
			if(!$('#username').val()) 	   { $('#username').select();return errorTip($('#username').closest('fieldset'), TT("A,l05-f1"));} 
			else if(!$('#password').val()) { $('#password').select();return errorTip($('#password').closest('fieldset'), TT("A,l05-f2"));}
			$.WOLF.log.button(obj, true);
			
			let voucher = localStorage.getItem('voucher_id') ?? null;
			let order = localStorage.getItem('order_id') ?? null;
			
			if($('#counter').length == 0) {
				$('body').append('<input id="counter" name="counter" type="hidden" value="5" />');
			}
			
			return jQuery.ajax({   
							type: 'POST',
			                url: BACKBONE,
			                dataType: 'json',
			                data: { 
			                     	randy: getRandom(), 
			                      	req: 'login_checkLogin', 
			                      	lang: $('html').attr('lang'), 
			                      	counter: $('#counter').val(), 
			                      	username: $('#username').val(), 
			                      	password: $('#password').val(), 
			                      	voucher_id: voucher,
			                      	order_id: order,
			                      	remember_login: $('[name="remember_login"]').prop('checked') },
			                success: function (response) {  
			                		console.log('checkLogin:', response);
				                    $.WOLF.log.button(obj, false);
				                    /* counter runterzählen */
									if(response && (response.counter || response.counter == 0)) _Log.counter(response.counter);
				                    try {
				                    	return response.code == '1' || response.code == 1 ?  handleSuccess(response) : handleError(response);
					                } catch (e) { 
					                	console.log('error - checkLogin', e);
					                 	return _Log.handleStandards(2, e.message);
					                }
							},
				            error: function(jqXHR, textStatus, errorThrown) {
				            		console.log(errorThrown);
								    console.log(textStatus);
								    console.log(jqXHR);
				            	    console.log(textStatus, errorThrown);
								    return _Log.handleStandards(1, textStatus + '_' + errorThrown);
							},
							beforeSend: function(jqXHR, settings) {
						        	console.log('Before Send checkLogin: ', settings.data);
						    }
			}); 
	}, 
	checkRegex: function (input) {
			//let hasDigitsAndLetters = /^(?=(?:\D*\d){1})(?=(?:\d*\D){4})[\dA-Za-z]{5,12}$/,
			let hasDigitsAndLetters = /^[a-zA-Z\d]{4,16}$/,
				value = $(input).val();
    		return hasDigitsAndLetters.test(value) ? true : false;
	},
	validEmail: function (email) { 
		return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email); 
	},
	decisionEmail: function (email = null, redir = 'login') {
		let div = $('<div class="row"></div>');
		
		$(div).append('<div class="col-12"><p>' + TT("A,l9-14a") + '</p></div>');
		$(div).append('<div class="col-12 text-center py-2"><a class="fixbutton btn btn-sm btn-main" href="javascript:_Log.prepare_lostCredentials(\'' + email + '\');$.fancybox.close();">' + TT("A,l9-14b") + '</a></div>');

		$(div).append('<div class="col-12"><p>' + TT("A,l9-14c") + '</p></div>');
		$(div).append('<div class="col-12 text-center py-2"><a class="fixbutton btn btn-sm btn-main" onclick="javascript:_Log.switcher(\'' + redir + '\');$.fancybox.close();">' + TT("A,l9-14d") + '</a></div>');

		$(div).append('<div class="col-12"><p>' + TT("A,l9-14e") + '</p></div>');
		$(div).append('<div class="col-12 text-center py-2"><a class="fixbutton wolf-btn wolf-btn-sm" onclick="$.fancybox.close()" ;">' + TT("A,l9-14f") + '</a></div>');
		
		return '<p>' + TT("A,l9-14") + '</p>' + $(div).html();
		
	},
	registerSeminar: function () {
		
		let pw, 
			un, 
			email = $('#reg_email').val().trim(),
			fullname = $('#reg_fullname').val().trim(),
			successhandleEmail = function () { 
				setTimeout(function () {  
					$('#reg_email').select().closest('fieldset').addClass('state-error');
					$('#reg_email').on('keypress', function () { $('#reg_email').closest('fieldset').removeClass('state-error'); });
					$.WOLF.jump.set_field_focus('[data-variable=reg_email]'); }, 200);};
		
		
		
		function handleError (response) {
    			if(response.status == -99) return standardError(TT('A,e-2'));
    			else if(response.status == -98) return standardError(TT('A,e-1'));
    			else if(response.status == -10) {
    				let content = '<h5>' + TT("A,l9-13i") + '</h5>' + _Log.decisionEmail(email);
					return swupAlert(content, { afterClose: successhandleEmail }); }
				else if(response.status == -11) {
    				let content = '<h5>' + TT("A,l9-13j") + '</h5>' + _Log.decisionEmail(email);
					return swupAlert(content, { afterClose: successhandleEmail }); }
    			else if(response.status == -12) {
    				return errorTip($('#reg_username').closest('fieldset'), TT("A,l9-13k"));
    			}
    			if(response.counter) $('#counter').val(response.counter);
    	}
		
		/* email und fullname */
		
		if(!email) return errorTip($('#reg_email').closest('fieldset'), TT("A,l9-13a"));
		else if(!_Log.validEmail(email)) return errorTip($('#reg_email').closest('fieldset'), TT("A,l9-13b"));
		
		if(!fullname) return errorTip($('#reg_fullname').closest('fieldset'), TT("A,l9-13c"));
		else if(fullname.length < 5) return errorTip($('#reg_fullname').closest('fieldset'), TT("A,l9-13d"));
		
		/* Username check 1. vorhanden, 2. ok, 3. nicht regex 
		if(!Value.get('reg_username')) {
			if(Value.get('reg_email')) Value.set('reg_username', Value.get('reg_email'));
			else return errorTip($('#reg_username'), TT("A,l9-13e"));
		}
		if(Value.get('reg_email') !== Value.get('reg_username')) {
			if(!_Log.checkRegex($('#reg_username'))) errorTip($('#reg_username').closest('fieldset'), TT("A,l9-13f"));
			un = $('#reg_username').val();
			else {
				return errorTip($('#reg_username').closest('fieldset'), TT("A,l9-13f"));
			}
		}*/
		
		un = $('#reg_email').val();
		
		/* Passwort check 1. vorhanden, 2. ok, 3. nicht regex */ 
		if(!Value.get('reg_password')) return errorTip($('#reg_password'), TT("A,l9-13g"));
		if(_Log.checkRegex($('#reg_password'))) pw = $('#reg_password').val();
		else {
			return errorTip($('#reg_password').closest('fieldset'), TT("A,l9-13h"));
		}
		
		let lang = $('html').hasAttr('lang') ? $('html').attr('lang') : 'en',
			country = $('html').hasAttr('land') ? $('html').attr('land') : 'US';
		
		return jQuery.ajax({   
							type: 'POST',
			                url: BACKBONE,
			                dataType: 'json',
			                data: { 
			                     	randy: getRandom(), 
			                      	req: 'login_registerSeminar', 
			                      	email: email,
			                      	fullname: fullname,
			                      	username: un, 
			                      	password: pw, 
			                      	nationcode: country,
			                      	langcode: lang,
			                      	seminar: $('#seminarID').val(),
			                      	remember_login: $('[name="reg_remember_login"]').prop('checked') },
			                success: function (response) {  
			                		console.log(response);
			                		clogs('success: registerSeminar:', response);
									try {
				                        return response.code == '1' || response.code == 1 ? _Log.checkMissings(response) : handleError(response);
					                 } catch (e) { 
					                 	clogs('catch error: registerSeminar', e);
					                 	return _Log.handleStandards(2, e.message);
					                }
							},
				            error: function(jqXHR, textStatus, errorThrown) {
				            	    clogs('error - registerSeminar', textStatus);
				            	    console.log(jqXHR);
				            	    console.log(textStatus, errorThrown);
								    return _Log.handleStandards(1, textStatus + '_' + errorThrown);
							},
							beforeSend: function(jqXHR, settings) {
									console.log('Before Send registerSeminar: ', settings.data);
							}
			}); 
		
		

	},
	
	renewPassword: function () {
		
		function handleError (response) {
    			return standardError(TT("A,e1"));//'Please try again - an error has occurred! <br><i>Errhandle #99</i>');
    	}
		
		if(!$('#new_password').val()) {
			return errorTip($('#new_password'), TT("A,l9-13ga"));//'Bitte dein neues Passwort eintragen!');
		}
		
		if(_Log.checkRegex($('#new_password'))) {
			pw = $('#new_password').val();
		} else {
			return errorTip($('#new_password').closest('fieldset'), TT("A,l9-13h"));//'Bitte wähle einen Passwort mit 5 bis 15 Stellen ohne Umlaute und Sonderzeichen!');
		}
		
		let code = $('#reset_code').val();
		
		if(!pw || !id) {
			return errorTip($('#new_password').closest('fieldset'), TT("A,l9-15"));//'OOps - ein Fehler ist aufgetreten!'); 
		}
		 return jQuery.ajax({   
							type: 'POST',
			                url: BACKBONE,
			                dataType: 'json',
			                data: { 
			                     	randy: getRandom(), 
			                      	req: 'login_renewPassword', 
			                      	code: code, 
			                      	password: pw
			                },
			                success: function (response) {  
			                		console.log(response);
				                    try {
				                         return response.code == '1' || response.code == 1 ? _Log.checkMissings(response) : handleError(response);
					                 } catch (e) { 
					                 	return _Log.handleStandards(2, e.message);
					                }
							},
				            error: function(jqXHR, textStatus, errorThrown) {
				            	    console.log(jqXHR);
				            	    console.log(textStatus, errorThrown);
								    return _Log.handleStandards(1, textStatus + '_' + errorThrown);
							},
							beforeSend: function(jqXHR, settings) {
						        console.log('Before Send renewPassword: ', settings.data);
						        //jqXHR.setRequestHeader("Mein-Header", "Wert");
						    }
			});
	},
	
	setCredentials: function () {
		
		/**** ist das da??? achtung was ist wenn email addresse bereits existiert ***/
		
		
		function handleError (response) {  
    			if(response.status == -99) 
    				return standardError(TT('A,e-2') + ' sC / 99');
    			else if(response.status == -98) 
    				return standardError(TT('A,e-2') + ' sC / 98');
    			else if(response.status == -10) {
    				let content = '<h5>' + TT("A,l9-13i") + '</h5>' + _Log.decisionEmail(email, 'sign_in');
					return swupAlert(content, { afterClose: successhandleEmail }); }
				else if(response.status == -11) {
    				let content = '<h5>' + TT("A,l9-13j") + '</h5>' + _Log.decisionEmail(email, 'sign_in');
					return swupAlert(content, { afterClose: successhandleEmail }); }
    			else if(response.status == -12) {
    				return errorTip($('#pers_username').closest('fieldset'), TT("A,l9-13k"));
    			}
    	}
		
		let pw, 
			un, 
			email = $('#pers_email').val().trim(),
			fullname = $('#pers_fullname').val().trim(),
		successhandleEmail = function () { 
			setTimeout(function () {  
				$('#pers_email').select().closest('fieldset').addClass('state-error');
				$('#pers_email').on('keypress', function () { $('#pers_email').closest('fieldset').removeClass('state-error'); });
				$.WOLF.jump.set_field_focus('[data-variable=pers_email]'); }, 200);},
		successhandleUN = function () { 
			setTimeout(function () { 
				$('#pers_username').select().closest('fieldset').addClass('state-error');
				$('#pers_username').on('keypress', function () { $('#pers_username').closest('fieldset').removeClass('state-error'); });
				$.WOLF.jump.set_field_focus('[data-variable=pers_username]'); }, 200);};
		
		/* email und fullname */
		
		if(!email) return errorTip($('#pers_email').closest('fieldset'), TT("A,l9-13a"));
		else if(!_Log.validEmail(email)) return errorTip($('#pers_email').closest('fieldset'), TT("A,l9-13b"));//'Die Email-Adresse scheint nicht korrekt zu sein';
		
		if(!fullname) return errorTip($('#pers_fullname').closest('fieldset'), TT("A,l9-13c"));//'Bitte gib uns deinen Vor- und Nachnamen bekannt!');
		else if(fullname.length < 5) return errorTip($('#pers_fullname').closest('fieldset'), TT("A,l9-13d"));//'Bitte gib den vollständigen Namen an!');
		
		
		/* username und password */
		if(!$('#pers_username').val()) return errorTip($('#pers_username'), TT("A,l9-13e"));//'Bitte einen Benutzernamen wählen!');
		if(_Log.checkRegex($('#pers_username'))) un = $('#pers_username').val();
		else {
			return errorTip($('#pers_username').closest('fieldset'), TT("A,l9-13f"));//'Bitte wähle einen Benutzernamen mit 5 bis 15 Stellen ohne Umlaute und Sonderzeichen!');
		}
		
		if(!$('#pers_password').val()) return errorTip($('#pers_password'), TT("A,l9-13g"));//'Bitte ein Passwort eintragen!');
		if(_Log.checkRegex($('#pers_password'))) pw = $('#pers_password').val();
		else {
			return errorTip($('#pers_password').closest('fieldset'), TT("A,l9-13h"));//'Bitte wähle einen Passwort mit 5 bis 15 Stellen ohne Umlaute und Sonderzeichen!');
		}
		
		let lang = $('html').hasAttr('lang') ? $('html').attr('lang') : 'en',
			country = $('html').hasAttr('land') ? $('html').attr('land') : 'US';
		
		return jQuery.ajax({   
							type: 'POST',
			                url: BACKBONE,
			                dataType: 'json',
			                data: { 
			                     	randy: getRandom(), 
			                      	req: 'login_setCredentials', 
			                      	email: email, 
			                      	fullname: fullname, 
			                      	username: un, 
			                      	password: pw, 
			                      	langcode: lang,
			                      	nationcode: country,
			                      	id: Value.get('id'),
			                      	code: $('#personalCode').val(),
			                      	remember_login: $('[name="pers_remember_login"]').prop('checked') },
			                success: function (response) {  
			                		clogs('success setCredentials', response);
				                    try {
				                         return response.code == '1' || response.code == 1 ? _Log.checkMissings(response) : handleError(response);
					                 } catch (e) { 
					                 	return _Log.handleStandards(2, e.message);
					                }
							},
				            error: function(jqXHR, textStatus, errorThrown) {
				            	    //console.log(jqXHR);
				            	    clogs(textStatus, errorThrown);
								    return _Log.handleStandards(1, textStatus + '_' + errorThrown);
							},
							beforeSend: function(jqXHR, settings) {
						        clogs('Before Send setCredentials: ', settings.data);
						        //jqXHR.setRequestHeader("Mein-Header", "Wert");
						    }
			}); 
		
		

	},
	
	loginSeminar: function () {

				
    	function handleError (response) {
    		
			switch (parseInt(response.code)) {
				case -9: 	return errorTip($('#log_username'), TT("A,l05-f1"));//'Bitte gib deinen Usernamen bekannt!');
				case -8: 	return errorTip($('#log_password'), TT("A,l05-f2"));//'Bitte trage das Passwort ein!');
				case -7:    return _Log.counter('x');
				case -2: 	return _Log.finale('<b>' + TT("A,l9-10") + '</b><br><br>' + TT("A,l9-10a"));//_Log.finale('<b>Dein Account wurde deaktiviert.</b><br><br>Der Zugang wurde verweigert. <br>Bei Fragen wende dich bitte an den Administrator oder Seminarleiter.');
				case -1: 	return _Log.finale('<b>' + TT("A,l9-11") + '</b><br><br>' + TT("A,l9-11a"));// _Log.finale('<b>Deine Zugriffsberechtigung ist abgelaufen.</b><br><br>Der Zugang wurde verweigert. <br>Bei Fragen wende dich bitte an den Administrator oder Seminarleiter.');
				default: 	return swupAlert('<h5>' + TT("A,l9-12") + '</h5><p>' + TT("A,l9-12a") + '</p>', {afterClose: function () { $('#username, #password').addClass('hasError');$('#username').select();}});
				/*$('#username, #password').addClass('hasError');
							return swupAlert('<h5>Achtung - fehlerhafter Eintrag</h5><p>Die eingebene Zugangskennung konnte nicht gefunden werden - bitte überprüfe deine Eintragungen!</p>');*/
			}
			return;
		}
		
		let un = $('#log_username').val(), 
			pw = $('#log_password').val(), 
			seminar = $('#seminarID').val(); 
					
		if(!un) return errorTip($('#log_username'), TT("A,l05-f1"));//'Bitte gib deinen Usernamen bekannt!');
		if(!pw) return errorTip($('#log_password'), TT("A,l05-f2"));//'Bitte trage das Passwort ein!');
		
		return jQuery.ajax({   
					type: 'POST',
			        url: BACKBONE,
			        dataType: 'json',
			        data: { 
			          	randy: getRandom(), 
			          	req: 'login_loginSeminar', 
			           	username: un, 
			           	password: pw, 
			           	counter: $('#counter').val(), 
			            seminar: $('#seminarID').val(),
			            remember_login: $('[name="log_remember_login"]').prop('checked') 
			        },
			        success: function (response) {  
			            if(response && (response.counter || response.counter == 0)) _Log.counter(response.counter);
				        try {	
				          	return response && (response.code == '1' || response.code == 1) ? _Log.checkMissings(response) : handleError(response);
					    } catch (e) { 
					      	return _Log.handleStandards(2, e.message);
					    }
					},
				    error: function(jqXHR, textStatus, errorThrown) {
							return _Log.handleStandards(1, textStatus + '_' + errorThrown);
					},
					beforeSend: function(jqXHR, settings) {
					        console.log('Before Send loginSeminar: ', settings.data);
					        //jqXHR.setRequestHeader("Mein-Header", "Wert");
					}
			}); 
	},
	remember_login:function (obj) {   
		return jQuery.ajax({   type: 'POST',  url: BACKBONE,  data: { randy: getRandom(), req: 'login_remember',  remember_login: $('[name="remember_login"]').prop('checked') } });
	},
	dummyFremd: function () {
		let inputs = '<input type="text" name="force" value="fremd" />';// -> startet durch über fremd
		inputs += '<input type="text" name="rater_name" value="' + localStorage.getItem('fullname') + '" />';
		inputs += '<input type="text" name="rater_id" value="' + localStorage.getItem('id') + '" />';
		inputs += '<input type="text" name="rater_avatar" value="' + localStorage.getItem('avatar') + '" />';
		inputs += '<input type="text" name="g" value="' + localStorage.getItem('g') + '" />';
		inputs += '<input type="text" name="k_id" value="' + localStorage.getItem('k_id') + '" />';
		inputs += '<input type="text" name="w_id" value="' + localStorage.getItem('w_id') + '" />';
		
		$.each(localStorage, function(key, value) {
				if (key.startsWith("user_")) {
			        inputs += '<input type="text" name="' + key + '" value="' + value + '" />';
			    }
				else if (key.startsWith("module_")) {
			        inputs += '<input type="text" name="' + key + '" value="' + value + '" />';
			    }
		});
		
		if($('#dummyForm').length > 0) $('#dummyForm').remove();
		$('body').append('<form id="dummyForm" name="dummyForm" method="POST" action="/" style="display:block;">' + inputs + '<input type="text" name="id" id="id" value="' + id + '" /></form>');
				
		localStorage.clear(); // löscht alles im localStorage
		return document.dummyForm.submit();
	},
	dummyVoucher: function () {  alert('dummyvoucher ??????');
		let voucher = localStorage.getItem('voucher_id');
		let id = localStorage.getItem('id');
		let admin_id = localStorage.getItem('admin_id');
		standardSuccess(id);
		if($('#dummyForm').length > 0) $('#dummyForm').remove();
		$('body').append('<form id="dummyForm" name="dummyForm" method="POST" action="/" style="display:block;"><input type="text" name="id" id="id" value="' + id + '" /></form>');
		localStorage.clear();
		return document.dummyForm.submit();
	},
	dummyLinked: function () { 
		let successHandle = function () {
			
			if($('#dummyForm').length > 0) $('#dummyForm').remove();
			let admin_id = localStorage.getItem('admin_id'),
				id = localStorage.getItem('id'),
				starter = '';
				
				standardSuccess('in DummyLoad' + admin_id);
			$('body').append('<form id="dummyForm" name="dummyForm" method="POST" action="/" style="display:none;">' + starter + '<input type="text" name="admin_id" id="admin_id" value="' + id + '"></form>');
			localStorage.clear(); // löscht alles im localStorage
			localStorage.setItem('linked', m1);
			return document.dummyForm.submit();
		}
		
		let m1 = localStorage.getItem('user_id'),
			m2 = localStorage.getItem('id');
		if(!m1 || !m2) return standardError('Es fehlen die zu verlinkenden Members');
		if (m1 === m2) {
		    return standardError('Ein Mitglied kann nicht mit sich selbst verlinkt werden.');
		}

		return jQuery.ajax({   
							type: 'POST',
			                url: BACKBONE,
			                dataType: 'json',
			                data: { 
			                     	randy: getRandom(), 
			                      	req: 'login_linkMembers', 
									member1: m1,
									member2: m2 },
			                success: function (response) {  
			                	    console.log(response);
			                	    return successHandle(response);
							},
				            error: function(jqXHR, textStatus, errorThrown) {
				            	    console.log('Error Send dummyLoad: ', errorThrown);
								    return _Log.handleStandards(1, textStatus + '_' + errorThrown);
							},
							beforeSend: function(jqXHR, settings) {
						        	console.log('Before Send dummyLoad: ', settings.data);
						    }
			});
		
		
		
		
		
	},
	dummyLoad: function (id = null, semID = null) {  
		//standardError('dummyLoad');
		
		let successHandle = function () {
				let starter = '';
			    if($('#seminarID').val()) 
			    	starter = '<input type="text" name="start_sem" id="start_sem" value="' + $('#seminarID').val() + '"><input type="text" name="seminar_id" id="seminar_id" value="' + $('#seminarID').val() + '">';
				if($('#dummyForm').length > 0) $('#dummyForm').remove();
				$('body').append('<form id="dummyForm" name="dummyForm" method="POST" action="/" style="display:none;">' + starter + '<input type="text" name="admin_id" id="admin_id" value="' + id + '"></form>');
				localStorage.clear(); // löscht alles im localStorage
				return document.dummyForm.submit();
		}
		
		if(!id) return alert('no user!!');
		if(!$('#seminarID').val()) return successHandle();
		else jQuery.ajax({   
							type: 'POST',
			                url: BACKBONE,
			                dataType: 'json',
			                data: { 
			                     	randy: getRandom(), 
			                      	req: 'login_signupSeminar', 
									seminar: $('#seminarID').val(),
									member: id },
			                success: function (response) {  
			                	    clogs('Success dummyLoad: ', response);
			                		return successHandle();
							},
				            error: function(jqXHR, textStatus, errorThrown) {
				            	    clogs('Error Send dummyLoad: ', errorThrown);
								    return _Log.handleStandards(1, textStatus + '_' + errorThrown);
							},
							beforeSend: function(jqXHR, settings) {
						        	clogs('Before Send dummyLoad: ', settings.data);
						    }
			});
				
	},
	clickField: function (id) {
		$('#' + id).trigger('click');
	},
	prepare_lostCredentials: function (email) {
		_Log.switcher('lost');
		$('#lost_email').val(email);
		_Log.lostCredentials();
	},
	lostCredentials: function () {
		
		function validEmail(email) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email); }
		
		function handleError (response) {
    		
			switch (parseInt(response.status)) {
				case -1: 	return errorTip($('#lost_email').closest('fieldset'), TT("A,l02-f1"));//'Die E-Mail Adresse ist unbekannt bzw. ist keinem User zugeordnet!');
				case -2: 	return _Log.finale('<b>' + TT("A,l02-f2") + '</b><br><br>' + TT("A,l02-f3") + '<br>' + TT("A,l02-f4"));
				case -3: 	return _Log.finale('<b>' + TT("A,l02-f5") + '</b><br><br>' + TT("A,l02-f3") + '<br>' + TT("A,l02-f4"));
				case -4: 	return errorTip($('#lost_email').closest('fieldset'), TT("A,l02-f5"));
				default: 	return swupAlert('<h5>' + TT("A,l02-f7") + '</h5><p>' + TT("A,l02-f8") + '</p>', { afterClose: function () { $('#lost_email').select();}});
			}
			return;
		}
		
		function handleSuccess (response) {
	    	_Log.switcher('switcher');//Eine Email mit deinen Zugangsdaten wurde an Dich versendet
	    	return _Log.finale(TT("A,l02-f10") + '<br><br><b> ' + response.fullname + ' ( ' + response.email + ' )</b><br><br>', TT("A,l02-f11"));//'Ein Email ist schon am Weg!')
	    	//return swupAlert('<h5>Email schon unterwegs!</h5><p>Eine Email mit den Zugangsdaten wurde an ' + response.fullname + ' ( ' + response.email + ' ) verschickt! Mit dem Link gelangst du direkt auf unsere Plattform.</p>')
	    }
		
		
		let email = $('#lost_email').val();
		if(!email) return errorTip($('#lost_email').closest('fieldset'), TT("A,l9-13a"));//'Bitte gib deine Email-Adresse ein!');
		else if(!validEmail(email)) return errorTip($('#lost_email').closest('fieldset'), TT("A,l9-13b"));//'Achtung, da kann etwas nicht stimmen - bitte überprüfe die Email-Adresse!');
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
			            if(response && (response.counter || response.counter == 0)) _Log.counter(response.counter);
				        try {	
				          	return response && (response.code == '1' || response.code == 1) ? handleSuccess(response) : handleError(response);
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
	loadAvatarSelection: function (type = 'stylish') {
		    $.ajax({
		        url: '/_assets/avatars/getAvatars.php', // URL des Server-Endpunkts
		        method: 'GET',
		        dataType: 'json', // Stellt sicher, dass die Antwort als JSON interpretiert wird
		        success: function(response) {
		            var gallery = $('<div class="container"></div>');
		            $.each(response, function(folder, images) {
		                $.each(images, function(index, image) {
		                    var fullPath = '/_assets/avatars/' + folder + '/' + image; // Vollständiger Pfad zum Bild
		                    var imgElement = $('<img>').attr('src', fullPath);
		                    $(gallery).append(imgElement);
		                });
		    		});
		            // Öffne die Fancybox nach dem erfolgreichen Laden der Bilder
		            $.fancybox.open({
				            src: '<div id="fancy-content" style="width:800px;"><div id="gallery">' + gallery.html() + '</div></div>',
				            type: 'html',
				            opts: {
				                    animationEffect: "zoom-in-out",
				                    animationDuration: 600,
				                    transitionDuration: 1200,
				                    buttons: [
				                        "zoom",
				                        "slideShow",
				                        "thumbs",
				                        "close",
				                    ],
				                }
				      });//end fancy
				            
				      $(document).on('click', '#gallery img', function() {
				                var imagePath = $(this).attr('src');
							    $('.person .control').css('background-image', 'url("' + imagePath + '")');
							    $('#avatar').addClass('isAvatar').attr('data-value', imagePath);
							    let ID = Value.get('admin_id') ? $('#admin_id').val() : $('#id').val();
							    AX.update($('[name=avatar]'));
							    $.fancybox.close();
				      }); //end click
		        },
		        error: function() {
		            	console.error('Fehler beim Laden der Daten');
		        },
				beforeSend: function(jqXHR, settings) {
						clogs('Before Send loadAvatarSelection: ', settings.data);
				}
    		}); //end ajax
    	}
}
