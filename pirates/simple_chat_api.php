<?php
/**
 * Упрощенный API для чат-бота (без Yandex GPT)
 * Простой fallback режим
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
        
        // Простые правила для ответов
        $bot_reply = getSimpleResponse($message);
        
        // Предлагаемые действия
        $suggested_actions = [];
        
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
        
        // Извлекаем контактные данные
        $contact_info = [];
        if (preg_match('/(\+7|8)[\s\-]?\(?\d{3}\)?[\s\-]?\d{3}[\s\-]?\d{2}[\s\-]?\d{2}/', $message, $matches)) {
            $contact_info['phone'] = $matches[0];
        }
        if (preg_match('/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/', $message, $matches)) {
            $contact_info['email'] = $matches[0];
        }
        
        // Отправляем ответ
        echo json_encode([
            'success' => true,
            'reply' => $bot_reply,
            'user_id' => $user_id,
            'suggested_actions' => $suggested_actions,
            'contact_info' => $contact_info
        ], JSON_UNESCAPED_UNICODE);
        
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

function getSimpleResponse($message) {
    $message = strtolower(trim($message));
    
    // Простые правила для ответов
    if (strpos($message, 'привет') !== false || strpos($message, 'здравствуйте') !== false) {
        return "Здравствуйте! Я виртуальный помощник Startapus. Помогу вам оформить заявку на создание сайта. Как вас зовут?";
    }
    
    // Если сообщение содержит имя (простая проверка) - должно быть ПЕРЕД общими правилами
    if (strlen($message) < 20 && !preg_match('/[0-9@]/', $message) && 
        !in_array($message, ['привет', 'здравствуйте', 'спасибо', 'благодарю', 'заказ', 'сайт', 'лендинг', 'хочу', 'купить', 'стоимость', 'цена'])) {
        return "Приятно познакомиться, " . ucfirst($message) . "! Расскажите, какой сайт вы хотели бы заказать?";
    }
    
    if (strpos($message, 'заказ') !== false || strpos($message, 'сайт') !== false || strpos($message, 'лендинг') !== false) {
        return "Отлично! Мы создаем сайты с игрофикацией, которые увеличивают конверсию до 70%. Для оформления заявки мне нужны ваши контактные данные. Какой у вас телефон?";
    }
    
    if (preg_match('/(\+7|8)[\s\-]?\(?\d{3}\)?[\s\-]?\d{3}[\s\-]?\d{2}[\s\-]?\d{2}/', $message)) {
        return "Спасибо за телефон! Теперь укажите ваш email для связи.";
    }
    
    if (strpos($message, '@') !== false) {
        return "Отлично! Теперь расскажите, какой тип сайта вас интересует? Мы создаем лендинги, интернет-магазины и корпоративные сайты с элементами игры.";
    }
    
    if (strpos($message, 'спасибо') !== false || strpos($message, 'благодарю') !== false) {
        return "Пожалуйста! Если у вас есть еще вопросы, я готов помочь. Также вы можете связаться с нами напрямую: @startupus или +7 909 150-34-44";
    }
    
    
    // Общий ответ
    return "Спасибо за сообщение! Я помогу вам с заказом сайта. Расскажите, что именно вас интересует?";
}
?>
