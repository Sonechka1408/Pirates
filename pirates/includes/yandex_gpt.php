<?php
/**
 * Yandex GPT API Integration
 * Класс для работы с Yandex GPT API
 */

class YandexGPTAssistant {
    private $api_key;
    private $folder_id;
    private $base_url = "https://llm.api.cloud.yandex.net/foundationModels/v1/completion";
    private $conversations = [];
    
    public function __construct() {
        // Загружаем конфигурацию из файла
        $this->loadConfig();
        
        $this->api_key = $this->api_key ?: 'your_api_key_here';
        $this->folder_id = $this->folder_id ?: 'your_folder_id_here';
    }
    
    private function loadConfig() {
        $config_file = __DIR__ . '/../config.env';
        if (file_exists($config_file)) {
            $lines = file($config_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                    list($key, $value) = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim($value);
                    
                    if ($key === 'YANDEX_API_KEY') {
                        $this->api_key = $value;
                    } elseif ($key === 'YANDEX_FOLDER_ID') {
                        $this->folder_id = $value;
                    }
                }
            }
        }
    }
    
    private function getHeaders() {
        return [
            "Content-Type: application/json",
            "Authorization: Api-Key " . $this->api_key
        ];
    }
    
    private function createSystemPrompt() {
        return [
            "role" => "system",
            "text" => "Ты - вежливый виртуальный помощник компании Pirats.studio по созданию сайтов с игрофикацией. 
Твои задачи:
1. Приветствовать пользователя и представиться
2. Помочь оформить заявку на создание сайта
3. Собрать необходимые данные: имя, номер телефона, email, тип проекта
4. Отвечать на вопросы о компании вежливо и кратко
5. Рассказывать о преимуществах игрофикации на сайтах
6. Подтверждать оформление заявки

Всегда будь дружелюбным и профессиональным. Собирай данные постепенно, не требуй всё сразу.
В конце диалога обязательно предоставь контакты:
- Telegram: @startupus
- Телефон: 8 928 398-23-10

Не придумывай несуществующую информацию о компании."
        ];
    }
    
    private function addToConversation($user_id, $role, $text) {
        if (!isset($this->conversations[$user_id])) {
            $this->conversations[$user_id] = [$this->createSystemPrompt()];
        }
        
        $this->conversations[$user_id][] = [
            "role" => $role,
            "text" => $text
        ];
        
        // Ограничиваем историю последними 10 сообщениями
        if (count($this->conversations[$user_id]) > 11) {
            $this->conversations[$user_id] = array_merge(
                [$this->conversations[$user_id][0]], 
                array_slice($this->conversations[$user_id], -10)
            );
        }
    }
    
    public function sendMessage($user_id, $user_message) {
        // Добавляем сообщение пользователя в историю
        $this->addToConversation($user_id, "user", $user_message);
        
        // Формируем запрос к API
        $payload = [
            "modelUri" => "gpt://" . $this->folder_id . "/yandexgpt-lite",
            "completionOptions" => [
                "stream" => false,
                "temperature" => 0.3,
                "maxTokens" => 1000
            ],
            "messages" => $this->conversations[$user_id]
        ];
        
        // Проверяем доступность curl
        if (!function_exists('curl_init')) {
            return $this->getFallbackResponse($user_message);
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->base_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code === 200) {
            $result = json_decode($response, true);
            if (isset($result['result']['alternatives'][0]['message']['text'])) {
                $bot_reply = $result['result']['alternatives'][0]['message']['text'];
                
                // Добавляем ответ ассистента в историю
                $this->addToConversation($user_id, "assistant", $bot_reply);
                
                return $bot_reply;
            }
        }
        
        // В случае ошибки возвращаем fallback ответ
        return $this->getFallbackResponse($user_message);
    }
    
    public function extractContactInfo($user_id) {
        if (!isset($this->conversations[$user_id])) {
            return [];
        }
        
        $history_text = "";
        foreach ($this->conversations[$user_id] as $msg) {
            $history_text .= " " . $msg['text'];
        }
        
        $info = [];
        
        // Поиск email
        if (preg_match('/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/', $history_text, $matches)) {
            $info['email'] = $matches[0];
        }
        
        // Поиск телефона (русские форматы)
        if (preg_match('/(\+7|8)[\s\-]?\(?\d{3}\)?[\s\-]?\d{3}[\s\-]?\d{2}[\s\-]?\d{2}/', $history_text, $matches)) {
            $info['phone'] = $matches[0];
        }
        
        return $info;
    }
    
    public function getConversationSummary($user_id) {
        if (!isset($this->conversations[$user_id])) {
            return "";
        }
        
        $summary = "";
        foreach ($this->conversations[$user_id] as $msg) {
            if ($msg['role'] !== 'system') {
                $summary .= $msg['role'] . ": " . $msg['text'] . "\n";
            }
        }
        
        return $summary;
    }
    
    private function getFallbackResponse($user_message) {
        $message = strtolower(trim($user_message));
        
        // Простые правила для fallback режима
        if (strpos($message, 'привет') !== false || strpos($message, 'здравствуйте') !== false) {
            return "Здравствуйте! Я виртуальный помощник Pirats.studio. Помогу вам оформить заявку на создание сайта с игрофикацией. Как вас зовут?";
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
            return "Пожалуйста! Если у вас есть еще вопросы, я готов помочь. Также вы можете связаться с нами напрямую: @startupus или 8 928 398-23-10";
        }
        
        // Общий ответ
        return "Спасибо за сообщение! Я помогу вам с заказом сайта. Расскажите, что именно вас интересует?";
    }
}
?>
