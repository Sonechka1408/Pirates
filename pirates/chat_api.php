<?php
/**
 * Chat Bot API
 * API для обработки сообщений чат-бота
 */
 
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Обработка preflight запросов
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'includes/yandex_gpt.php';
require_once 'includes/database.php';

// Простая система сессий для user_id
session_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = uniqid('user_', true);
}

$user_id = $_SESSION['user_id'];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['message'])) {
            throw new Exception('Неверный формат запроса');
        }
        
        $message = trim($input['message']);
        if (empty($message)) {
            throw new Exception('Сообщение не может быть пустым');
        }
        
        // Инициализируем ассистента
        $assistant = new YandexGPTAssistant();
        
        // Получаем ответ от бота
        $bot_reply = $assistant->sendMessage($user_id, $message);
        
        // Извлекаем контактные данные
        $contact_info = $assistant->extractContactInfo($user_id);
        
        // Предлагаемые действия
        $suggested_actions = [];
        
        // Проверяем, нужно ли предложить контакты
        if (preg_match('/заказ|купить|сайт|лендинг|проект|стоимость|цена/i', $bot_reply)) {
            $suggested_actions[] = [
                'type' => 'contact',
                'text' => '📞 Связаться с нами',
                'data' => [
                    'telegram' => '@startupus',
                    'phone' => '8 928 398-23-10'
                ]
            ];
        }
        
        // Проверяем, есть ли достаточно данных для создания заявки
        if (!empty($contact_info['phone']) && !empty($contact_info['email'])) {
            $suggested_actions[] = [
                'type' => 'application',
                'text' => '📝 Оформить заявку',
                'data' => $contact_info
            ];
        }
        
        // Отправляем ответ
        echo json_encode([
            'success' => true,
            'reply' => $bot_reply,
            'user_id' => $user_id,
            'suggested_actions' => $suggested_actions,
            'contact_info' => $contact_info
        ], JSON_UNESCAPED_UNICODE);
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
        
        if ($_GET['action'] === 'create_application') {
            $name = $_GET['name'] ?? '';
            $phone = $_GET['phone'] ?? '';
            $email = $_GET['email'] ?? '';
            $service_type = $_GET['service_type'] ?? 'Сайт с игрофикацией';
            
            if (empty($name) || empty($phone)) {
                throw new Exception('Имя и телефон обязательны');
            }
            
            $db = new ChatDatabase();
            $assistant = new YandexGPTAssistant();
            $conversation_summary = $assistant->getConversationSummary($user_id);
            
            $application_id = $db->createApplication(
                $user_id,
                $name,
                $phone,
                $email,
                $service_type,
                $conversation_summary
            );
            
            if ($application_id) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Заявка успешно создана! Мы свяжемся с вами в ближайшее время.',
                    'application_id' => $application_id
                ], JSON_UNESCAPED_UNICODE);
            } else {
                throw new Exception('Ошибка при создании заявки');
            }
        }
        
    } else {
        throw new Exception('Неверный запрос');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
