<?php

class DB {
    private static $instance = null;
    public $con;
	private $results = [];

    private function __construct() {
        $this->con = mysqli_connect(DBHOST, DBUSER, DBPASS, DBASE);
        if (!$this->con) {
            die('Database Connection Error ' . mysqli_connect_error($this->con));
        }
        $this->con->set_charset("utf8mb4");
    }
	
	public function getLastError() {
        return mysqli_error($this->con);
    }

 	public function __destruct() {
        if ($this->con) {
            $this->con->close();
        }
    }

    public static function start() {
        if (self::$instance == null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }
	
	public static function connect() {
        return self::$instance->con;
    }
	
	public function escape($string) {
	    return mysqli_real_escape_string($this->con, $string);
	}
	
	public function select($query, $type = 'assoc') {
			if($type == 'single') {
				$query = str_replace('FROM', ' AS name FROM', $query);
			}
		    $result = mysqli_query($this->con, $query);
		    
		    $this->results[] = $result;
		    if (!$result) { return false; }
			else if ($type == 'assoc') { return $result->fetch_assoc();  }
		    else if ($type == 'single') {
		    	 //return $result->fetch_object()->name;
				 $row = $result->fetch_row();
				 return $row[0];
				/*if ($result && $result->num_rows > 0) {
				    
				} else {
				    echo "Kein Ergebnis gefunden oder Fehler bei der Abfrage.";
				}*/
			}
		    else if ($type == 'rows') { return $result; }
		    
		    return null; // für unbekannte Typen oder andere Fehlerfälle
	}
	
	public function prepare($query) {
        return $this->con->prepare($query);
    }
	
	public function update($query, $values = null) {
		if (is_null($values)) {
	        return mysqli_query($this->con, $query);
	    }
			
		$stmt = $this->con->prepare($query);
	    if ($stmt) {
	    	$types = str_repeat('s', count($values));
			$refValues = [];
			foreach ($values as $key => $value) {
			    $refValues[$key] = &$values[$key];
			}
			$params = array_merge([$types], $refValues);
			
			call_user_func_array([$stmt, 'bind_param'], $params);
			
	        $result = $stmt->execute();
	        $stmt->close();
	        return $result;
	    }
	}
	
	public function delete($query, $values = []) {
		if (!empty($values)) {
		} else {
			return mysqli_query($this->con, $query);
		}
	}
	
	public function insert($query, $values = null) {
		
		if (is_null($values)) {
	        if (mysqli_query($this->con, $query)) {
	            return $this->con->insert_id;
	        }
	        return 0;
	    }
		
		 $stmt = $this->con->prepare($query);
		 if ($stmt) {
		        if (!empty($values)) {
		            $stmt->bind_param(str_repeat('s', count($values)), ...$values);
		        }
		        $stmt->execute();
		        $insert_id = $this->con->insert_id;
		        $stmt->close();
		        return $insert_id;
		    }
		    return 0;
	
	    
	    return false;
	}
	
	public function insert_id() {
        return $this->con->insert_id;
    }
	
	public function execute($query) {
		
	        if (mysqli_query($this->con, $query)) {
	            return 1;
	        }
	        return 0;
	}
	
	public function inValue($value) {
	    if (is_array($value)) {
	        return implode(',', $value);  // Wenn es ein Array ist, Werte mit Komma trennen
	    }
	    return $value;  // Ansonsten den Wert direkt zurückgeben
	}
	
	public function insertOrUpdate($table, $data, $updateData) {
	    // Spalten und Werte vorbereiten
	    $columns = implode(", ", array_keys($data));
	    $placeholders = implode(", ", array_fill(0, count($data), "?"));
	    $updatePairs = implode(", ", array_map(fn($key) => "$key = ?", array_keys($updateData)));
	
	    // SQL-Anweisung
	    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders) ON DUPLICATE KEY UPDATE $updatePairs";
	
	    // Statement vorbereiten
	    $stmt = $this->con->prepare($sql);
	    if (!$stmt) {
	        return false;
	    }
	
	    // Bindet die Werte für das INSERT und das UPDATE
	    $values = array_merge(array_values($data), array_values($updateData));
	    $types = str_repeat("s", count($values)); // Angenommen, alle Felder sind Strings; passe dies ggf. an.
	    $stmt->bind_param($types, ...$values);
	
	    // Ausführen und Ergebnis zurückgeben
	    $result = $stmt->execute();
	    $stmt->close();
	    return $result;
	}
	
	
	
	
	
}
?>