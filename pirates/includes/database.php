<?php
/**
 * Database operations for chat bot
 * Работа с базой данных для чат-бота
 */

class ChatDatabase {
    private $pdo;
    
    public function __construct() {
        // Проверяем доступность PDO
        if (!class_exists('PDO')) {
            error_log("PDO not available, using file-based storage");
            $this->pdo = null;
            return;
        }
        
        $host = 'localhost';
        $dbname = 'pirats_chat';
        $username = 'root';
        $password = '';
        
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createTables();
        } catch(PDOException $e) {
            // Если база не существует, создаем её
            try {
                $this->pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
                $this->pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
                $this->pdo->exec("USE $dbname");
                $this->createTables();
            } catch(PDOException $e2) {
                error_log("Database connection failed: " . $e2->getMessage());
                // Fallback to file-based storage
                $this->pdo = null;
            }
        }
    }
    
    private function createTables() {
        if (!$this->pdo) return;
        
        $sql = "
        CREATE TABLE IF NOT EXISTS applications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id VARCHAR(255) NOT NULL,
            customer_name VARCHAR(255),
            phone VARCHAR(50),
            email VARCHAR(255),
            service_type VARCHAR(255),
            status VARCHAR(50) DEFAULT 'new',
            conversation_summary TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_user_id (user_id),
            INDEX idx_status (status)
        )";
        
        $this->pdo->exec($sql);
    }
    
    public function createApplication($user_id, $customer_name, $phone, $email = null, $service_type = null, $conversation_summary = null) {
        if (!$this->pdo) {
            // Fallback: save to file
            return $this->saveToFile($user_id, $customer_name, $phone, $email, $service_type, $conversation_summary);
        }
        
        try {
            $sql = "INSERT INTO applications (user_id, customer_name, phone, email, service_type, conversation_summary) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$user_id, $customer_name, $phone, $email, $service_type, $conversation_summary]);
            
            return $this->pdo->lastInsertId();
        } catch(PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
    
    public function getApplications($status = null) {
        if (!$this->pdo) return [];
        
        try {
            if ($status) {
                $sql = "SELECT * FROM applications WHERE status = ? ORDER BY created_at DESC";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$status]);
            } else {
                $sql = "SELECT * FROM applications ORDER BY created_at DESC";
                $stmt = $this->pdo->query($sql);
            }
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }
    
    public function updateApplicationStatus($id, $status) {
        if (!$this->pdo) return false;
        
        try {
            $sql = "UPDATE applications SET status = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$status, $id]);
        } catch(PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
    
    private function saveToFile($user_id, $customer_name, $phone, $email, $service_type, $conversation_summary) {
        $data = [
            'id' => uniqid(),
            'user_id' => $user_id,
            'customer_name' => $customer_name,
            'phone' => $phone,
            'email' => $email,
            'service_type' => $service_type,
            'conversation_summary' => $conversation_summary,
            'created_at' => date('Y-m-d H:i:s'),
            'status' => 'new'
        ];
        
        $file = __DIR__ . '/../data/applications.json';
        $dir = dirname($file);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        $applications = [];
        if (file_exists($file)) {
            $applications = json_decode(file_get_contents($file), true) ?: [];
        }
        
        $applications[] = $data;
        file_put_contents($file, json_encode($applications, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        return $data['id'];
    }
}
?>
