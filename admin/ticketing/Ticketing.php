<?php   


class Ticket {  
      private $con;
	  private $db; 
	  private $land ='UK';
	  private $lang = 'en';
	  private $voucher = true;
	  private $email;
	  private $fullname;
	  private $message = []; 
      public function __construct()  
      {
	  }
	  
	  public function dispatch($action, $params) {

		if (!in_array($action, ['start_stripe_sdk', 'start_paypal'], true) && function_exists('mysqli_connect')) {
			$this->db = DB::start();
		}

        switch ($action) {
            case 'start_paypal':
                return $this->start_paypal($params);
            case 'start_stripe_sdk':
                return $this->start_stripe_sdk($params);
			case 'make_paypal_data':
                return $this->make_paypal_data($params);
			case 'make_stripe_data':
                return $this->make_stripe_data($params['session_id'] ?? '');
			case 'update_order_user': /* wird direkt angesteuert */
				return $this->update_order_user($params['order_id'], $params['user_id']);
			case 'create_voucher': 
				return $this->create_voucher($params);
			case 'download_voucher': 
				return $this->download_voucher($params['order_id']);
			case 'redeem_voucher': /* wird direkt angesteuert */
				return $this->redeem_voucher($params['voucher_id'], $params['user_id']);
			case 'send_voucher': 
				return $this->send_voucher($params);
			case 'build_invoice': 
				return $this->build_invoice($params['order_id']);
			case 'download_invoice': 
				return $this->download_invoice($params['order_id']);
			case 'send_invoice': 
				return $this->send_invoice($params['order_id']);	
			case 'load_orders': 
				$userId = (int)($_POST['user_id'] ?? 0);
				$this->load_orders($userId);
				exit;
			case 'search_order':
			    $keyword = $_POST['keyword'] ?? '';
			    $this->search_order($keyword);
			    exit;	
            default:
                return ['error' => 'Invalid action'];
        }
    }
	  
	  
		private function post_to_index($order_id, $provider = 'stripe') {
		    header('Content-Type: text/html; charset=utf-8'); // WICHTIG für HTML-Verarbeitung
		
		    echo '<!DOCTYPE html>';
		    echo '<html><head><title>Weiterleitung...</title></head><body>';
		    echo '<form id="postForm" method="POST" action="https://carlvon.cloud/index.php">';
		    echo '<input type="hidden" name="ticketing" value="true">';
		    echo '<input type="hidden" name="orderprocess" value="success">';
		    echo '<input type="hidden" name="provider" value="' . htmlspecialchars($provider) . '">';
		    echo '<input type="hidden" name="order_id" value="' . htmlspecialchars($order_id) . '">';
			echo '<input type="hidden" name="lang" value="' . $this->lang . '">';
			echo '<input type="hidden" name="land" value="' . $this->land . '">';
			echo '<input type="hidden" name="voucher" value="' . $this->voucher . '">';
		    echo '<input type="hidden" name="email" value="' . $this->email . '">';
		    echo '<input type="hidden" name="fullname" value="' . $this->fullname . '">';
		    echo '<input type="hidden" name="comesfrom" value="internal">';
		    echo '</form>';
		    echo '<script>document.getElementById("postForm").submit();</script>';
		    echo '</body></html>';
		
		    exit;
	  }

	  public function store_order_data(array $post): array {
		    $fields = [
		        'order_id', 'provider', 'provider_order_id', 'payment_intent_id',
		        'email', 'name', 'country', 'postal_code', 'city', 'address',
		        'amount', 'price', 'type', 'product', 'quantity', 'affiliate',
		        'user_id', 'user_name', 'lang', 'status'
		    ];
		
		    $orderData = [];
		
		    foreach ($fields as $field) {
		        $orderData[$field] = $post[$field] ?? null;
		    }
		
		    // Sanitize / Cast critical types
		    $orderData['amount']   = floatval($orderData['amount']);
		    $orderData['price']    = floatval($orderData['price']);
		    $orderData['quantity'] = intval($orderData['quantity']);
		    $orderData['user_id']  = intval($orderData['user_id']);
		
		    // Optional: validate required
		    if (empty($orderData['order_id']) || empty($orderData['provider'])) {
		        return ['success' => false, 'error' => 'Wichtige Felder fehlen.'];
		    }
		
		    // Jetzt INSERT
		    $sql = "INSERT INTO orders (
		        order_id, provider, provider_order_id, payment_intent_id,
		        email, name, country, postal_code, city, address,
		        amount, price, type, product, quantity, affiliate,
		        user_id, user_name, lang, status, created_at
		    ) VALUES (
		        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()
		    )";
		
		    $stmt = $this->db->prepare($sql);
		    if (!$stmt) {
		        return ['success' => false, 'error' => 'DB Prepare-Fehler: ' . $this->db->error];
		    }
		
		    $stmt->bind_param(
		        "ssssssssssdssdssisss",
		        $orderData['order_id'],
		        $orderData['provider'],
		        $orderData['provider_order_id'],
		        $orderData['payment_intent_id'],
		        $orderData['email'],
		        $orderData['name'],
		        $orderData['country'],
		        $orderData['postal_code'],
		        $orderData['city'],
		        $orderData['address'],
		        $orderData['amount'],
		        $orderData['price'],
		        $orderData['type'],
		        $orderData['product'],
		        $orderData['quantity'],
		        $orderData['affiliate'],
		        $orderData['user_id'],
		        $orderData['user_name'],
		        $orderData['lang'],
		        $orderData['status']
		    );
		
		    if ($stmt->execute()) {
		    	$insertedId = $this->db->insert_id();
				$voucher = encodeVoucher($insertedId);
				
			    // Update dieser Zeile mit voucher_id
			    $updateStmt = $this->db->prepare("UPDATE orders SET voucher_id = ? WHERE id = ?");
			    if ($updateStmt) {
			        $updateStmt->bind_param("si", $voucher, $insertedId);
			        $updateStmt->execute();
			    }
		        return ['status' => 'success', 'success' => true, 'stored' => true, 'order_id' => $orderData['order_id'], 'voucher_id' => $voucher];
		    } else {
		        return ['status' => 'failed', 'success' => false, 'error' => 'Insert fehlgeschlagen: ' . $stmt->error];
		    }
		}

		public function load_orders($user_id): void {
		    $stmt = $this->db->prepare("
		        SELECT 
				    o.id,
				    o.order_id,
				    o.*,
				    o.provider_order_id,
				    o.user_id,
				    o.created_at,
				    o.status,
				    mi.avatar,
				    m.fullname as fullname,
				    m.exp_date,
				    m.tickets,
				     CASE 
				        WHEN m.active = 1 AND m.exp_date > NOW() THEN 1
				        ELSE 0
				    END AS member_valid,
				    (SELECT SUM(o.amount) 
						FROM orders o
						WHERE o.status = 'paid' AND o.user_id = ?) AS overall
				FROM orders o
				LEFT JOIN member m ON o.user_id = m.id
				LEFT JOIN member_info mi ON o.user_id = mi.id
				WHERE o.user_id = ?
				ORDER BY o.created_at DESC
				LIMIT 100
		    ");
		
		    $stmt->bind_param("ii", $user_id, $user_id);
		    $stmt->execute();
		    $result = $stmt->get_result();
		
		    if ($result->num_rows === 0) {
		        echo '<div class="no-orders">' . LL("A","OL-98") . '</div>';
		        return;
		    }
			
			echo '<div id="orderList" class="main-sm">';
			$listRow = "<div class='chapter pt-3 pb-0 my-0'>
					<hr>
					<div>" . LL("A","OL-80") . "</div>
					<hr>
				</div>";
				
			$singleRows = '';
			while ($row = $result->fetch_assoc()) {
		        $voucher_id = encodeVoucher($row['id']);
		        $masterRow = '';	
				$masterRow .= "<div id='orderListing'>
						<div class='controlbox flex-shrink-1 text-center justify-content-center align-items-center pt-4 pe-3'>
						<div class='control cover w-avatar avatar_100 bg-cover' style='margin:0 auto !important;background-image:url(\"" . $row['avatar'] . "\");'></div>
						</div>
						<div class='flex-grow-1 d-flex flex-column justify-content-center'>
						";
				$masterRow .= "<p class='mb-0 text-bold mb-0 pb-0'>" . htmlspecialchars($row['fullname']) . "</p><hr>";
				$masterRow .= '<p class="mb-1 hstack"><span class="me-auto">' . LL("A","OL-81") . '</span><span class="text-center">' . $row['tickets'] . '</span></p>';
				
				$masterRow .= '<p class="mb-1 hstack"><span class="me-auto">' . LL("A","OL-83") . '</span><span>' . LL("A","OL-83_" . $row['member_valid']) . '</span></p>';
				$masterRow .= '<p class="mb-1 hstack"><span class="me-auto">' . LL("A","OL-82") . '</span><span>' . date("F j, Y", strtotime($row['exp_date'])) . '</span></p>';
				$masterRow .= '<p class="mb-1 hstack"><span class="me-auto">' . LL("A","OL-84") . '</span><span class="text-bold">' . $row['overall'] . ' &euro;</span></p>';
								$masterRow .= "</div></div>";
		        $singleRows .= '<div class="order-row gap-3 my-3 px-3">';
		        $singleRows .= '<div class="me-auto d-flex flex-column">
		        					<div><strong> ' . htmlspecialchars($row['order_id']) . '</strong> &bull; ' . htmlspecialchars($voucher_id) . ' &bull;'. date("F j, Y / H:i", strtotime($row['created_at'])) .'</div>';
		        $singleRows .= '<div class="text-truncate">' . $row['quantity'] . ' ' . LL("product", "art-" . $row['type']). ' &bull; unit: ' . $row['price'] . ' &euro; &bull;  total: ' . $row['amount'] . ' &euro;</div>';
		        $singleRows .= '<div class="text-truncate">' . $row['name'] . ' &bull; ' . $row['address'] . ', ' . $row['city'] . ' &bull; ' . $row['country'] . ' </div>';
		        $singleRows .= '<div class="text-truncate">' . LL("A","OL-5") . ': ' . $row['provider'] . ' ( status:  ' . $row['status'] . ' ) </div>';
		        $singleRows .= '</div>
		        		<div class="d-flex flex-row justify-content-center gap-1">';
		        	$singleRows .= "<a class='btn btn-sm btn-outline-dark btn-icon' onclick='javascript:Tick.search_order(\"" . htmlspecialchars($row['order_id']) . "\")'><span class='bi bi-info-lg'></span></a>";
					$singleRows .= "<a class='btn btn-sm btn-dark btn-icon' onclick='javascript:Tick.make_invoice(\"" . htmlspecialchars($row['order_id']) . "\")'><span class='bi bi-download'></span></a>";
					$singleRows .= '</div>';
				$singleRows .= '</div>';
		    }
		    
			echo '<div class="order-detail vstack">';
			echo $masterRow . $listRow . $singleRows;			
			echo '</div>';
		}
		
		public function search_order($keyword): void {
		    $keyword = trim($keyword);
		
		    // Ist das ein gültiger Voucher?
		    $decodedId = decodeVoucher($keyword); // Gibt int oder false zurück
		
		    if (is_numeric($decodedId)) {
		        $where = "id = ?";
		        $value = (int)$decodedId;
		    } else {
		        // Dann nehmen wir an, es ist eine order_id
		        $where = "order_id = ?";
		        $value = $keyword;
		    }
		
		    $stmt = $this->db->prepare("
		        SELECT * FROM orders
		        WHERE $where
		        LIMIT 1
		    ");
		    $stmt->bind_param(is_int($value) ? "i" : "s", $value);
		    $stmt->execute();
		    $result = $stmt->get_result();
		
		    if ($result->num_rows === 0) {
		        echo '<div class="no-orders">' . L("A", "OL-0", LANG, ['order' => $keyword ]) . '</div>';   //Es konnten keine Bestellungen gefunden werden
		        return;
		    }
		
		    $order = $result->fetch_assoc();
		    $voucher = encodeVoucher($order['id']);
			
		    
			echo '<div class="main main-sm" id="orderBox">';
			echo '<div class="chapter pt-0 mt-0">
					<hr>
					<h4>Data Sheet: ' . $keyword . '</h4>
					<hr>
				</div>';
			
		    echo '<div class="order-detail row"><div class="col-sm-6">';
		    echo '<div><strong>' . LL("A", "OL-1") . '</strong><br> ' . htmlspecialchars($order['order_id']) . ' / ' . htmlspecialchars($voucher) . '</div>';
		    echo '<div class="mt-3"><strong>' . LL("A", "OL-2") . '</strong><br/>' . $order['name'] . '<br/>'  . $order['email'] . '<br/>' . $order['address'] . '<br/>' . $order['country'] . ' &bull; ' . $order['city'] . ', ' . $order['postal_code'] . '<br/></div> '; 
		    echo '<div class="mt-3"><i>' . htmlspecialchars(L("A", "OL-3", LANG, ["name" => $order['user_name']], false)) . '</i></div>';
		    echo '</div><div class="col-sm-6">';
		    echo '<div><strong>' . LL("A", "OL-6") . '</strong><br>' . date("F j, Y / H:i", strtotime($order['created_at'])) . '</div>';
		    echo '<div class="mt-3"><strong>' . LL("A", "OL-7") . '</strong><br>' . htmlspecialchars($order['quantity']) . ' * ' . number_format($order['price'], 2) . ' €<br>total: ' . number_format($order['amount'], 2) . ' € (' . htmlspecialchars($order['status']) . ')</div>';
		    echo '<div class="mt-3"><strong>' . LL("A", "OL-4") . '</strong><br>' . htmlspecialchars($order['quantity']) . ' ' . LL("product", "art-" . $order['type']) . '<br>' . LL("product", "artinfo-" . $order['type']) . '</div>';
		    echo '</div><div class="col-12 mt-3">';
		    
		    echo '<div><strong>' . LL("A", "OL-5") . '</strong><br> ' 
		    			. htmlspecialchars($order['provider']) . ' &bull; ' . date("F j, Y / H:i", strtotime($order['created_at'])) . '<br><i>provider_order_id: '
		             . htmlspecialchars($order['provider_order_id']);
		    if(isset($order['payment_intent_id'])) echo '<br>payment_intent_id:' . htmlspecialchars($order['payment_intent_id']);
		    echo '</i></div></div>';
			echo "<div class='mt-3 text-center'><a style='max-width:200px;margin:0 auto;' class='btn btn-sm btn-outline-dark' onclick='javascript:Tick.make_invoice(\"" . htmlspecialchars($order['order_id']) . "\")'>Download</a></div>";
			echo '</div>';
			
			echo '</div>';
		}
				

			  
		public function make_paypal_data($params): array {
		    $details = json_decode($params['details'], true); // ✅ korrektes Array erzeugen
		
		    $payer = $details['payer'] ?? [];
		    $purchaseUnit = $details['purchase_units'][0] ?? [];
		    $shipping = $purchaseUnit['shipping']['address'] ?? [];
		
		    $custom_id_parts = explode('|', $purchaseUnit['custom_id'] ?? '');
		    $type = $custom_id_parts[0] ?? 't';
		    $quantity = max(1, (int)($custom_id_parts[1] ?? 1));
		    $unitPrice = (float)($custom_id_parts[2] ?? 0);
		    $total = (float)($custom_id_parts[3] ?? 0);
		    $affiliate = $custom_id_parts[4] ?? ($_COOKIE['affiliate_id'] ?? '');
		    $product = $params['productname'] ?? ($params['product'] ?? 'Ticket');
		    $user_id = is_numeric($params['user_id'] ?? '') ? (int)$params['user_id'] : null;
		
		    $price = $unitPrice > 0 ? $unitPrice : floatval($purchaseUnit['amount']['value'] ?? 0);
		    $amount = $total > 0 ? $total : ($price * $quantity);
		
		    $orderData =  [
		        'order_id'           => strtoupper('P_' . date('yzHis') . rand(100, 999)),
		        'provider'           => 'paypal',
		        'provider_order_id'  => $details['id'] ?? '',
		        'payment_intent_id'  => '',
		        'email'              => $payer['email_address'] ?? '',
		        'name'               => trim(($payer['name']['given_name'] ?? '') . ' ' . ($payer['name']['surname'] ?? '')),
		        'country'            => $shipping['country_code'] ?? '',
		        'postal_code'        => $shipping['postal_code'] ?? '',
		        'city'               => $shipping['admin_area_2'] ?? '',
		        'address'            => $shipping['address_line_1'] ?? '',
		        'amount'             => $amount,
		        'price'              => $price,
		        'type'               => $type,
		        'product'            => $product,
		        'quantity'           => $quantity,
		        'affiliate'          => $affiliate,
		        'user_id'            => $user_id,
		        'user_name'          => $payer['name']['given_name'] ?? '',
		        'lang'               => $params['lang'] ?? 'en',
		        'status'             => 'paid'
		    ];
			
				$order = $this->store_order_data($orderData);
				$order_id = $order['order_id'];
				if($order_id) {
					$this->send_invoice($order_id);
				}
				return $order;
				
		}


		public function make_stripe_data(string $session_id): array {
			    require_once STRIPEPATH . 'vendor/autoload.php';
			    \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
			
			    $session = \Stripe\Checkout\Session::retrieve($session_id);
			    $customer = $session->customer_details ?? null;
			    $meta = $session->metadata ?? new stdClass();
				
				$this->lang = $meta->lang ?? 'en';
				$this->land = $meta->land ?? 'UK';
				$this->voucher = $meta->voucher ?? 'false';
				$this->fullname = $customer->name ?? '';
				$this->email = $customer->email ?? '';
			
			    $orderData = [
			        'order_id'           => strtoupper('S_' . date('yzHis') . rand(100, 999)),
			        'provider'           => 'stripe',
			        'provider_order_id'  => $session->id,
			        'payment_intent_id'  => $session->payment_intent ?? '',
			        'email'              => $customer->email ?? '',
			        'name'               => $customer->name ?? '',
			        'country'            => $customer->address->country ?? '',
			        'postal_code'        => $customer->address->postal_code ?? '',
			        'city'               => $customer->address->city ?? '',
			        'address'            => $customer->address->line1 ?? '',
			        'amount'             => ($session->amount_total ?? 0) / 100,
			        'price'              => $meta->price ?? 0,
			        'type'               => $meta->type ?? 't',
			        'product'            => $meta->product_name ?? 'Ticket',
			        'quantity'           => $meta->quantity ?? 1,
			        'affiliate'          => $meta->affiliate ?? ($_COOKIE['affiliate_id'] ?? ''),
			        'user_id'            => $meta->user_id ?? null,
			        'user_name'          => $meta->user_name ?? '',
			        'lang'               => $_GET['lang'] ?? 'en',
			        'status'             => $session->payment_status ?? 'paid'
			    ];
				
				$order = $this->store_order_data($orderData);
				$order_id = $order['order_id'];
				if($order_id) {
					$this->send_invoice($order_id);
					$this->post_to_index($order_id, 'stripe');
				}
	  }
	  
	  public function start_stripe_sdk($params) {
	  	
	  	
			require_once STRIPEPATH . 'vendor/autoload.php';
		
			\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
		
			$type     = $params['type'] ?? 't';
			$price    = $params['price'] ?? 7;
			$quantity = $params['quantity'] ?? 1;
			$product  = $params['productname'] ?? 'Ticket';
			$affiliate= $params['affiliate'] ?? '';
			$voucher  = $params['voucher'] ?? '';
			$land     = $params['land'] ?? 'UK';
			$lang     = $params['lang'] ?? 'en';
			$langTag  = strtolower(str_replace('_', '-', trim((string) $lang)));
			$stripeLocales = [
				'bg', 'cs', 'da', 'de', 'el', 'en', 'en-gb', 'es', 'es-419', 'et',
				'fi', 'fil', 'fr', 'fr-ca', 'hr', 'hu', 'id', 'it', 'ja', 'ko',
				'lt', 'lv', 'ms', 'mt', 'nb', 'nl', 'pl', 'pt', 'pt-br', 'ro',
				'ru', 'sk', 'sl', 'sv', 'th', 'tr', 'vi', 'zh', 'zh-hk', 'zh-tw'
			];
			$checkoutLocale = in_array($langTag, $stripeLocales, true) ? $langTag : 'auto';
			$brandLogo = rtrim(WEBSITE, '/') . '/_assets/logos/carlvon_analytica.png';
			
			if($type === 't') { $image = DOMAIN . '/_assets/self/payment/single.jpg'; }
			else if($type === 'm') { $image = DOMAIN . '/_assets/self/payment/month.jpg'; }
			else if($type === 'y') { $image = DOMAIN . '/_assets/self/payment/year.jpg'; }
			else { $image = DOMAIN . '/_assets/self/payment/ticket.jpg'; }
		
			try {
				$session = \Stripe\Checkout\Session::create([
					'payment_method_types' => ['card'],
					'mode' => 'payment',
					'locale' => $checkoutLocale,
					'submit_type' => 'pay',
					'branding_settings' => [
						'background_color' => '#ffffff',
						'button_color' => '#101114',
						'border_style' => 'rectangular',
						'display_name' => 'CARLVON',
						'font_family' => 'inter',
						'logo' => [
							'type' => 'url',
							'url' => $brandLogo
						]
					],
					'success_url' => DOMAIN . '?req=ticketing&action=make_stripe_data&provider=stripe&session_id={CHECKOUT_SESSION_ID}&lang=' . $lang . '&land=' . $land . '&voucher=' . $voucher,
					'cancel_url'  => DOMAIN . '?order=cancelled',
					'line_items' => [[
						'price_data' => [
							'currency' => 'eur',
							'product_data' => [
								'name' => $product,
								'images' => [$image]
							],
							'unit_amount' => intval($price * 100)
						],
						'quantity' => $quantity
					]],
					'metadata' => [
						'type' => $type,
						'product_name' => $product,
						'price' => $price,
						'quantity' => $quantity,
						'affiliate' => $affiliate,
						'voucher' => $voucher,
						'lang' => $lang,
						'land' => $land
					],
					'billing_address_collection' => 'required'
				], [
					'stripe_version' => '2025-09-30.clover'
				]);
		
				return ['success' => true, 'session_id' => $session->id, 'lang' => $lang, 'land' => $land];
			} catch (Exception $e) {
				return ['error' => 'Stripe-Error: ' . $e->getMessage()];
			}
		}
			  
		public function build_invoice(string $orderId): string {
			
			// 1. Orderdaten auslesen
		    $stmt = $this->db->prepare("SELECT 
			    o.*, 
			    m.tickets as user_tickets, 
			    m.fullname as user_name, 
			    m.exp_date as user_exp_date
			FROM 
			    orders o
			LEFT JOIN 
			    member m ON o.user_id = m.id
			WHERE 
			    o.order_id = ?");
			$stmt->bind_param("s", $orderId);
		    $stmt->execute();
		    $result = $stmt->get_result();
		    $order = $result->fetch_assoc();
			
			$datetime = new DateTime($order['created_at']); // z. B. '2025-07-02 07:13:41'
			$formatted = $datetime->format('F j, Y / h:i A');
			
			$order['date'] = $formatted;
			
			$type = $order['type'];
			$order['productname'] = LL('A', 'plan_' . $type);
			$order['productinfo'] = LL('A', 'plan_' . $type . '2');
		
		    if (!$order) {
		        throw new Exception("Keine Bestellung mit order_id = $orderId gefunden.");
		    }
			
			return startInvoice()->setData($order)->generate(); // gibt den Pfad zur PDF zurück
		}

		public function download_invoice(string $orderId): string {
			// 1. Orderdaten holen
			$stmt = $this->db->prepare("SELECT 
			    o.*, 
			    m.tickets as user_tickets, 
			    m.fullname as user_name, 
			    m.exp_date as user_exp_date,
    			EXISTS(SELECT 1 FROM news WHERE news.email = o.email) AS news
			FROM 
			    orders o
			LEFT JOIN 
			    member m ON o.user_id = m.id
			WHERE 
			    o.order_id = ?");
			$stmt->bind_param("s", $orderId);
		    $stmt->execute();
		    $result = $stmt->get_result();
		    $order = $result->fetch_assoc();
		    
			if (!$order) {
				throw new Exception("Order nicht gefunden: $orderId");
			}
			
			if($order['news'] == 1) $unsub = true; else $unsub = false;
			$datetime = new DateTime($order['created_at']); // z. B. '2025-07-02 07:13:41'
			$formatted = $datetime->format('F j, Y / h:i A');
			
			$order['date'] = $formatted;
			
			$type = $order['type'];
			$order['productname'] = LL('A', 'plan_' . $type);
			$order['productinfo'] = LL('A', 'plan_' . $type . '2');
		
			// 4. E-Mail verschicken
			$lang = LANG ?? 'en'; // oder fallback auf 'en'
			$pdfPath = startInvoice()->setData($order)->generate();
			
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment; filename="invoice_' . $orderId . '.pdf"');
			readfile($pdfPath);
			unlink($pdfPath); // optional
			exit;


		}
		
		public function send_invoice(string $orderId): string {

				// 1. Orderdaten holen
				$stmt = $this->db->prepare("SELECT 
				    o.*, 
				    m.tickets as user_tickets, 
				    m.fullname as user_name, 
				    m.exp_date as user_exp_date,
	    			EXISTS(SELECT 1 FROM news WHERE news.email = o.email) AS news
				FROM 
				    orders o
				LEFT JOIN 
				    member m ON o.user_id = m.id
				WHERE 
				    o.order_id = ?");
				$stmt->bind_param("s", $orderId);
			    $stmt->execute();
			    $result = $stmt->get_result();
			    $order = $result->fetch_assoc();
		    
		
			if (!$order) {
				return json_encode(['error' => L('error', 'noorderid', LANG, ['order_id' => $orderId], false) ]);
			}
			
			if($order['news'] == 1) $unsub = true; else $unsub = false;
			$datetime = new DateTime($order['created_at']); // z. B. '2025-07-02 07:13:41'
			$formatted = $datetime->format('F j, Y / h:i A');
			
			$order['date'] = $formatted;
			
			$type = $order['type'];
			$order['productname'] = LL('A', 'plan_' . $type);
			$order['productinfo'] = LL('A', 'plan_' . $type . '2');
		
			// 4. E-Mail verschicken
			$lang = LANG ?? 'en'; // oder fallback auf 'en'
			try {
				$invoice = startInvoice()->setData($order)->generate();
			} catch (Exception $e) {
			    return json_encode([
			        'error' => 'PDF-Erstellung fehlgeschlagen: ' . $e->getMessage()
			    ]);
			}
		    // 6. Mail senden
		    try {
					$emailObj = startEmail();
			        $emailObj->unsubscribe = $unsub;
			        $emailObj->subject(LL('email','inv-subject',$lang));//$area, $baustein
			        $emailObj->h(LL('email','inv-h1',$lang));
			        $emailObj->h($order['quantity'] . ' * ' . $order['productname']);
			        $emailObj->p(L('email','inv-1',$lang, ['name' => $order['name']], false));
			        $emailObj->p(LL('email','inv-2',$lang));
			        //$emailObj->button($subscribeLink, LL('email','nl-button',$lang));
					$emailObj->p(LL('email','inv-3',$lang));
					$emailObj->p(L('email','inv-4',$lang, ['provider' => $order['provider']], false));
					$emailObj->p(LL('email','inv-5',$lang));
					$emailObj->p(LL('email','inv-6',$lang));
					//$emailObj->ps(L('email','inv-ps',$lang, ['orderid' => $order['order_id']], false));
			        $emailObj->anEmail = $order['email'];
					$emailObj->att($invoice);
					$emailObj->send();
			
			// ✅ Danach sicher löschen
				if (file_exists($invoice)) {
				    unlink($invoice); // löscht die Datei vom Server
				}
		    } catch (Exception $e) {
		    	if (file_exists($invoice)) {
				    unlink($invoice); // löscht die Datei vom Server
				}
			    return json_encode([
			        'error' => 'Email Versand fehlgeschlagen: ' . $e->getMessage()
			    ]);
			}
			return json_encode(['success' => true, 'order' => $order['id'], 'user' => $order['name'], 'email' => $order['email']]);
		    

		}

		public function update_order_user(string $order_id, int $user_id) {
		    $user_id  = intval($user_id); // Optional redundant, da schon `int` getypt
		    $order_id = trim($order_id);
		
		    if (!$user_id || !$order_id) {
		        return ['success' => false, 'error' => 'Fehlende Parameter'];
		    }
		
		    // 1. Userdaten holen
		    $stmtUser = $this->db->prepare("SELECT * FROM member WHERE id = ?");
		    $stmtUser->bind_param("i", $user_id);
		    $stmtUser->execute();
		    $resultUser = $stmtUser->get_result();
		    $user = $resultUser->fetch_assoc();
		
		    if (!$user) {
		        return ['success' => false, 'error' => 'User nicht gefunden'];
		    }
		
		    // 2. Orderdaten holen
		    $stmtOrder = $this->db->prepare("SELECT * FROM orders WHERE order_id = ?");
		    $stmtOrder->bind_param("s", $order_id);
		    $stmtOrder->execute();
		    $resultOrder = $stmtOrder->get_result();
		    $order = $resultOrder->fetch_assoc();
			
		    if (!$order) {
		        return ['success' => false, 'error' => 'Order nicht gefunden'];
		    }
			
			$order['qty'] = $order['quantity'];
			$order['date'] = $order['created_at'];
			
			// 2. InvoiceBuilder starten
		    $builder = startInvoice(); // oder new InvoiceBuilder('invoice.docx')
		    // 3. Template mit Daten füllen
		    $outputfile = $builder->setData($order)->generate(); // gibt den Pfad zur PDF zurück
		
		    // 3. Order updaten → user_id setzen
		    $user_name = $user['fullname'] ? $user['fullname'] : $order['name'];
		    $stmtUpdateOrder = $this->db->prepare("UPDATE orders SET user_id = ?, user_name = ? WHERE order_id = ?");
		    $stmtUpdateOrder->bind_param("iss", $user_id, $user_name, $order_id);
		    $stmtUpdateOrder->execute();
			
			//$this->build_invoice($order, $user_id);
		
		    // 4. Userdaten anpassen je nach Typ
		    $newTickets = $user['tickets'];
		    $newExp     = $user['exp_date'];
		    $now        = new DateTime();
		    $exp        = new DateTime($user['exp_date']);
		    $type       = $order['type'];
		    $amount     = intval($order['quantity']);
		
		    if ($type === 't') {
		        $newTickets += $amount;
		    }
		
		    if ($type === 'm') {
		        if ($exp < $now) $exp = $now;
		        $exp->modify("+1 month");
		        $newExp = $exp->format('Y-m-d');
		    }
		
		    if ($type === 'y') {
		        if ($exp < $now) $exp = $now;
		        $exp->modify("+1 year");
		        $newExp = $exp->format('Y-m-d');
		    }
		
		    // 5. Update member
		    $stmtMemberUpdate = $this->db->prepare("UPDATE member SET tickets = ?, exp_date = ? WHERE id = ?");
		    $stmtMemberUpdate->bind_param("isi", $newTickets, $newExp, $user_id);
		    $stmtMemberUpdate->execute();
		
		    // 6. Erfolg zurückgeben
		    return [
		        'success' => true,
		        'user'    => $user['fullname'],
		        'order'   => $order_id,
		        'tickets' => $newTickets,
		        'exp_date'=> $newExp
		    ];
		}

		public function create_voucher($params) {
			    $order_id = trim($params['order_id'] ?? '');
			
			    if (!$order_id) {
			        return ['success' => false, 'error' => 'Fehlende order_id'];
			    }
			
			    // 1. Order prüfen, ob vorhanden
			    $stmtOrder = $this->db->prepare("SELECT order_id, voucher_id FROM orders WHERE order_id = ?");
			    $stmtOrder->bind_param("s", $order_id);
			    $stmtOrder->execute();
			    $resultOrder = $stmtOrder->get_result();
			    $order = $resultOrder->fetch_assoc();
			
			    if (!$order) {
			        return ['success' => false, 'error' => 'Order nicht gefunden'];
			    }
				
				$voucher_id = $order['voucher_id'];
				
			    // 2. Prüfen, ob für diese Order schon ein Voucher existiert
			    $stmtCheckVoucher = $this->db->prepare("SELECT id FROM vouchers WHERE order_id = ?");
			    $stmtCheckVoucher->bind_param("s", $order_id);
			    $stmtCheckVoucher->execute();
			    $resultVoucher = $stmtCheckVoucher->get_result();
			
			    if ($resultVoucher->num_rows > 0) {
			        return ['success' => false, 'error' => 'Für diese Bestellung existiert bereits ein Voucher'];
			    }
			
			    // 4. Voucher eintragen
			    $stmtInsert = $this->db->prepare("
			        INSERT INTO vouchers (order_id, voucher_id, user_id, active, created_at)
			        VALUES (?, ?, 0, 1, NOW())
			    ");
			    $stmtInsert->bind_param("ss", $order_id, $voucher_id);
			    $success = $stmtInsert->execute();
			
			    if (!$success) {
			        return ['success' => false, 'error' => 'Fehler beim Einfügen des Vouchers'];
			    }
				
				// 5. Orders.credit auf 1 setzen
			    $stmtUpdateCredit = $this->db->prepare("UPDATE orders SET credit = 1 WHERE order_id = ?");
			    $stmtUpdateCredit->bind_param("s", $order_id);
			    $successUpdate = $stmtUpdateCredit->execute();
			
			    if (!$successUpdate) {
			        return ['success' => false, 'error' => 'Voucher wurde erstellt, aber Kredit konnte nicht aktualisiert werden'];
			    }
				
			    // 5. Erfolg zurückgeben mit voucher_id
			    
			    return [
			        'success' => true,
			        'voucher_id' => $voucher_id,
			        'order_id' => $order_id
			    ];
			}

			
			private function build_voucher(string $order_id): string {
			    // --- Daten holen ---
			    $stmt = $this->db->prepare("
			        SELECT 
			            o.*, 
			            v.voucher_id,
			            v.redeemed_at,
			            v.active
			        FROM orders o
			        JOIN vouchers v ON o.order_id = v.order_id
			        WHERE v.order_id = ? AND v.active = 1
			    ");
			    $stmt->bind_param("s", $order_id);
			    $stmt->execute();
			    $result = $stmt->get_result();
			    $voucher = $result->fetch_assoc();
			
			    if (!$voucher) {
			        throw new Exception("Kein aktiver Voucher gefunden für Order-ID = $order_id");
			    }
			
			    // --- Daten aufbereiten ---
			    $datetime = new DateTime($voucher['created_at']);
			    $voucher['date'] = $datetime->format('F j, Y');
			
			    $type = $voucher['type'] ?? '';
			    $voucher['productname'] = LL('A', 'plan_' . $type);
				$voucher['productinfo'] = LL('A', 'plan_' . $type . '2');				
				
				/* textergänzungen */
				$voucher['vhow-1'] = LL('A', 'vhow-1');
				$voucher['vhow-2'] = LL('A', 'vhow-2');
				$voucher['vhow-3'] = LL('A', 'vhow-3');
				$voucher['vhow-4'] = LL('A', 'vhow-4');
				$voucher['vhow-5'] = LL('A', 'vhow-5');
				$voucher['vhow-6'] = LL('A', 'vhow-6');
				$voucher['vhow-7'] = LL('A', 'vhow-7');
				$voucher['vhow-8'] = LL('A', 'vhow-8');
				$voucher['vhow-9'] = LL('A', 'vhow-9');
			
			    $voucherId = $voucher['voucher_id'];
			    $qrData = MEMBERDOMAIN . '/?code=' . $voucherId;
			
			    // --- QR-Code generieren ---
			    $qrImage = $this->generateQrCodeImage($qrData); 
			    $voucher['voucher_link'] = $qrData;
			
			    require_once VOUCHERBUILDER;
			
			    // --- PDF erzeugen ---
			    $builder = new Voucher();
			    $pdfPath = $builder
			        ->setData($voucher)
			        ->setQrCode($qrImage)
			        ->generate();
			
			    // --- Aufräumen ---
			    if (file_exists($qrImage)) {
			        unlink($qrImage);
			    }
			
			    return $pdfPath;
			}





		public function download_voucher(string $orderId): void
		{
		    $pdfPath = $this->build_voucher($orderId);
		
		    if (!file_exists($pdfPath)) {
		        throw new Exception("PDF konnte nicht erzeugt werden.");
		    }
		
		    // Voucher-ID extrahieren (für Dateiname)
		    $stmt = $this->db->prepare("SELECT voucher_id FROM vouchers WHERE order_id = ?");
		    $stmt->bind_param("s", $orderId);
		    $stmt->execute();
		    $stmt->bind_result($voucherId);
		    $stmt->fetch();
		    $stmt->close();
		
		    $filename = 'voucher_' . ($voucherId ?: $orderId) . '.pdf';
		
		    header('Content-Type: application/pdf');
		    header('Content-Disposition: attachment; filename="' . $filename . '"');
		    readfile($pdfPath);
		    unlink($pdfPath);
		    exit;
		}
		
		public function send_voucher(array $params): string {
			    $orderId = trim($params['order_id'] ?? '');
				$lang = $params['lang'] ?? 'en';
			
			    if (!$orderId) {
			        return json_encode(['error' => 'no orderid']);
			    }
			
			    $userName = trim($params['name'] ?? '');
			    $email = trim($params['email'] ?? '');
			
			    // 1. Daten holen über order_id
			    $stmt = $this->db->prepare("
			        SELECT 
			            o.*, 
			            v.voucher_id,
			            v.redeemed_at,
			            m.fullname AS user_name, 
			            m.exp_date AS user_exp_date,
			            m.tickets AS user_tickets,
			            EXISTS(SELECT 1 FROM news WHERE news.email = o.email) AS news
			        FROM vouchers v
			        JOIN orders o ON v.order_id = o.order_id
			        LEFT JOIN member m ON o.user_id = m.id
			        WHERE o.order_id = ? AND v.active = 1
			        LIMIT 1
			    ");
			    $stmt->bind_param("s", $orderId);
			    $stmt->execute();
			    $result = $stmt->get_result();
			    $voucher = $result->fetch_assoc();
			
			    if (!$voucher) {
			        return json_encode(['error' => L('A','ev-sendv2', $lang, ['oderid' => $orderId], false)]);
			    }
			
			    // Fallback: E-Mail & Name ggf. aus UI überschreiben
			    if (!empty($email)) {
			        $voucher['email'] = $email;
			    }
			    if (!empty($userName)) {
			        $voucher['name'] = $userName;
			    }
			
			    if (empty($voucher['email'])) {
			        return json_encode(['error' => LL('A','ev-sendv1', $lang)]); 
			    }
			
			    $voucherId = $voucher['voucher_id'];
			    $voucher['voucher_link'] = MEMBERDOMAIN . '/?code=' . $voucherId;
			
			    // ✨ Zusätzliche Daten fürs Template
			    $datetime = new DateTime($voucher['created_at']);
			    $voucher['date'] = $datetime->format('F j, Y');
			    $type = $voucher['type'];
			    $voucher['productname'] = LL('product', 'art-' . $type);
			    $voucher['productinfo'] = LL('product', 'artinfo-' . $type);
			
			    
			    $unsub = ($voucher['news'] ?? 0) == 1;
			
			    try {
			        // QR generieren
			        $qrImage = $this->generateQrCodeImage($voucher['voucher_link']);
			
			        // PDF erstellen
			        require_once VOUCHERBUILDER;
			        $pdf = (new Voucher())
			            ->setData($voucher)
			            ->setQrCode($qrImage)
			            ->generate();
			
			        if (file_exists($qrImage)) {
			            unlink($qrImage);
			        }
			    } catch (Exception $e) {
			        return json_encode(['error' => LL('A','ev-pdf') . ' ' . $e->getMessage()]);
			    }
			
			    // 4. E-Mail senden
			    try {
			        $emailObj = startEmail();
			        $emailObj->unsubscribe = $unsub;
			        $emailObj->subject(LL('email', 'voucher-subject', $lang));
			        $emailObj->h(LL('email', 'voucher-h1', $lang));
					$emailObj->h($voucher['quantity'] . ' * ' . $voucher['productname']);
			        $emailObj->p(L('email', 'voucher-1', $lang, ['name' => $voucher['name']], false));
			        $emailObj->p(LL('email', 'voucher-2', $lang));
			        $emailObj->p(LL('email', 'voucher-3', $lang));
			        $emailObj->p(LL('email', 'voucher-4', $lang));
			        $emailObj->anEmail = $voucher['email'];
			        $emailObj->att($pdf);
			        $emailObj->send();
			
			        if (file_exists($pdf)) {
			            unlink($pdf);
			        }
			    } catch (Exception $e) {
			        return json_encode(['error' => 'E-Mail Versand fehlgeschlagen: ' . $e->getMessage()]);
			    }
			
			    ob_end_clean(); // löscht alles bisherige Ausgabe
				header('Content-Type: application/json; charset=utf-8');
			    return json_encode([
			        'success' => true,
			        'lang' => $lang,
			        'voucher_id' => $voucherId,
			        'order_id' => $voucher['order_id'],
			        'user' => $voucher['name'],
			        'email' => $voucher['email']
			    ]);
			}


		private function generateQrCodeImage(string $data): string {
		    require_once PROGRAMS . 'phpqrcode/qrlib.php'; // nur falls noch nicht global eingebunden
		
		    $file = tempnam(sys_get_temp_dir(), 'qr_') . '.png';
		    QRcode::png($data, $file, QR_ECLEVEL_H, 6); // hohe Fehlerkorrektur, Größe 6
		    return $file;
		}
		
		public function validate_voucher(string $voucherId): string {
			$voucherId = trim($voucherId);
			
			// 1. Voucher suchen
		    $stmt = $this->db->prepare("
		        SELECT v.*, 
		        	o.id AS order_row_id,
		        	o.type AS order_type, 
    				o.quantity AS order_quantity
		        FROM vouchers v
		        JOIN orders o ON o.order_id = v.order_id
		        WHERE v.voucher_id = ?
		        LIMIT 1
		    ");
		    $stmt->bind_param("s", $voucherId);
		    $stmt->execute();
		    $result = $stmt->get_result();
		    $voucher = $result->fetch_assoc();
		
		    if (!$voucher) {
		        return 'notexists';//json_encode(['success' => false, 'error' => 'Gutschein existiert nicht']);
		    }
		
		    if ((int)$voucher['active'] !== 1) {
		        return 'redeemed';//json_encode(['success' => false, 'error' => 'Gutschein wurde bereits eingelöst']);
		    }
			return 'ok';
		}	

		public function redeem_voucher(string $voucherId, int $userId): string {
		    // DB-Schutz
		    $voucherId = trim($voucherId);
		
		    // 1. Voucher suchen
		    $stmt = $this->db->prepare("
		        SELECT v.*, 
		        	o.id AS order_row_id,
		        	o.type AS order_type, 
    				o.quantity AS order_quantity
		        FROM vouchers v
		        JOIN orders o ON o.order_id = v.order_id
		        WHERE v.voucher_id = ?
		        LIMIT 1
		    ");
		    $stmt->bind_param("s", $voucherId);
		    $stmt->execute();
		    $result = $stmt->get_result();
		    $voucher = $result->fetch_assoc();
		
		    if (!$voucher) {
		        return json_encode(['success' => false, 'error' => 'Gutschein existiert nicht']);
		    }
		
		    if ((int)$voucher['active'] !== 1) {
		        return json_encode(['success' => false, 'error' => 'Gutschein wurde bereits eingelöst']);
		    }
		
		    // 2. Member-Daten holen
		    $stmt = $this->db->prepare("SELECT fullname, nationcode, langcode FROM member WHERE id = ?");
		    $stmt->bind_param("i", $userId);
		    $stmt->execute();
		    $result = $stmt->get_result();
		    $member = $result->fetch_assoc();
		
		    if (!$member) {
		        return json_encode(['success' => false, 'error' => 'Benutzer nicht gefunden']);
		    }
		
		    // 3. Voucher aktualisieren
		    $stmt = $this->db->prepare("
		        UPDATE vouchers
		        SET active = 0, user_id = ?, redeemed_at = NOW()
		        WHERE voucher_id = ?
		    ");
		    $stmt->bind_param("is", $userId, $voucherId);
		    $successVoucher = $stmt->execute();
		
		    // 4. Order aktualisieren
		    $stmt = $this->db->prepare("
		        UPDATE orders
		        SET 
		            user_id = ?, 
		            user_name = ?, 
		            lang = ?, 
		            status = 'voucher'
		        WHERE order_id = ?
		    ");
		    $stmt->bind_param(
		        "isss",
		        $userId,
		        $member['fullname'],
		        $member['langcode'],
		        $voucher['order_id']
		    );
		    $successOrder = $stmt->execute();
		
		    if (!$successVoucher || !$successOrder) {
		        return json_encode(['success' => false, 'error' => 'Fehler beim Einlösen']);
		    }

			/* members updaten */
			// Annahme: $voucher wurde bereits durch SELECT geladen
			$type     = $voucher['order_type'];
			$quantity = (int)$voucher['order_quantity'];
			
			// Hole aktuelle Member-Daten (mit exp_date, tickets, etc.)
			$stmt = $this->db->prepare("SELECT tickets, exp_date FROM member WHERE id = ?");
			$stmt->bind_param("i", $userId);
			$stmt->execute();
			$memberResult = $stmt->get_result();
			$memberData = $memberResult->fetch_assoc();
			
			if (!$memberData) {
			    return json_encode(['success' => false, 'error' => 'Mitglied nicht gefunden']);
			}
			
			// Standardwerte
			$newExpDate = null;
			$newTickets = null;
			
			if ($type === 't') {
			    // Tickets erhöhen
			    $newTickets = (int)$memberData['tickets'] + $quantity;
			
			    $stmt = $this->db->prepare("UPDATE member SET tickets = ? WHERE id = ?");
			    $stmt->bind_param("ii", $newTickets, $userId);
			    $stmt->execute();
			}
			elseif ($type === 'm' || $type === 'y') {
			    $intervalType = $type === 'm' ? 'MONTH' : 'YEAR';
			    $now = new DateTime();
			
			    $currentExp = !empty($memberData['exp_date']) ? new DateTime($memberData['exp_date']) : null;
			
			    $expStart = (!$currentExp || $currentExp < $now) ? $now : $currentExp;
			
			    $expStart->modify("+$quantity $intervalType");
			    $newExpDate = $expStart->format('Y-m-d');
			
			    $stmt = $this->db->prepare("UPDATE member SET exp_date = ? WHERE id = ?");
			    $stmt->bind_param("si", $newExpDate, $userId);
			    $stmt->execute();
			}  

			return json_encode([
			    'success' => true,
			    'voucher_id' => $voucher['voucher_id'],
			    'order_id' => $voucher['order_id'],
			    'user_id' => $userId,
			    'action' => $type === 't' ? 'ticket-update' : 'exp-update',
			    'new_value' => $type === 't' ? $newTickets : $newExpDate
			]);
		}


}


