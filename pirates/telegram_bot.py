#!/usr/bin/env python3
# -*- coding: utf-8 -*-

"""
Telegram бот для Pirats.studio
Принимает данные из форм сайта и чат-бота, отправляет их администратору в Telegram
"""

import os
import json
import logging
from datetime import datetime
from http.server import HTTPServer, BaseHTTPRequestHandler
import urllib.request
import re

# Настройка логирования
logging.basicConfig(
    format='%(asctime)s - %(levelname)s - %(message)s',
    level=logging.INFO,
    handlers=[
        logging.FileHandler('bot.log', encoding='utf-8'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

# Конфигурация (можно менять здесь или через config.env)
BOT_TOKEN = '8420622652:AAFJNVkLUNEUJ0OdfHqJJrVXRU_jLQmGuPY'
CHAT_ID = '1062366418'

# Попытка загрузить из config.env
if os.path.exists('config.env'):
    try:
        with open('config.env', 'r', encoding='utf-8') as f:
            for line in f:
                if '=' in line and not line.startswith('#'):
                    key, value = line.strip().split('=', 1)
                    if key == 'TELEGRAM_BOT_TOKEN':
                        BOT_TOKEN = value.strip()
                    elif key == 'TELEGRAM_CHAT_ID':
                        CHAT_ID = value.strip()
        logger.info("Конфигурация загружена из config.env")
    except Exception as e:
        logger.warning(f"Не удалось загрузить config.env: {e}")

# URL для отправки сообщений в Telegram
TELEGRAM_API_URL = f"https://api.telegram.org/bot{BOT_TOKEN}/sendMessage"


def send_to_telegram(message, parse_mode='HTML'):
    """Отправка сообщения в Telegram"""
    try:
        # Если сообщение слишком длинное, разбиваем на части
        max_length = 4096
        if len(message) > max_length:
            parts = [message[i:i+max_length] for i in range(0, len(message), max_length)]
            for part in parts:
                if not _send_telegram_message(part, parse_mode):
                    return False
            return True
        else:
            return _send_telegram_message(message, parse_mode)
            
    except Exception as e:
        logger.error(f"Ошибка отправки в Telegram: {e}")
        return False


def _send_telegram_message(message, parse_mode='HTML'):
    """Вспомогательная функция для отправки одного сообщения"""
    try:
        data = {
            'chat_id': CHAT_ID,
            'text': message,
            'parse_mode': parse_mode
        }
        
        json_data = json.dumps(data).encode('utf-8')
        req = urllib.request.Request(TELEGRAM_API_URL)
        req.add_header('Content-Type', 'application/json')
        
        response = urllib.request.urlopen(req, data=json_data, timeout=10)
        result = json.loads(response.read().decode('utf-8'))
        
        if result.get('ok'):
            logger.info("Сообщение отправлено в Telegram")
            return True
        else:
            logger.error(f"Ошибка Telegram API: {result}")
            return False
            
    except Exception as e:
        logger.error(f"Ошибка отправки сообщения: {e}")
        return False


class WebhookHandler(BaseHTTPRequestHandler):
    """Обработчик входящих запросов от сайта"""
    
    def do_POST(self):
        """Обработка POST запросов"""
        if self.path == '/webhook/application':
            self.handle_form_data()
        elif self.path == '/webhook/chat':
            self.handle_chat_data()
        else:
            self.send_error(404, "Not Found")
    
    def do_GET(self):
        """Обработка GET запросов"""
        if self.path == '/health':
            self.send_response(200)
            self.send_header('Content-type', 'application/json')
            self.end_headers()
            self.wfile.write(json.dumps({
                'status': 'ok',
                'bot': 'running',
                'timestamp': datetime.now().isoformat()
            }).encode('utf-8'))
        else:
            self.send_error(404, "Not Found")
    
    def do_OPTIONS(self):
        """Обработка OPTIONS запросов для CORS"""
        self.send_response(200)
        self.send_header('Access-Control-Allow-Origin', '*')
        self.send_header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS')
        self.send_header('Access-Control-Allow-Headers', 'Content-Type')
        self.end_headers()
    
    def handle_form_data(self):
        """Обработка данных из всплывающей формы на сайте"""
        try:
            # Читаем данные
            content_length = int(self.headers.get('Content-Length', 0))
            post_data = self.rfile.read(content_length)
            data = json.loads(post_data.decode('utf-8'))
            
            logger.info(f"Получена заявка с формы: {data.get('name', 'N/A')}")
            
            # Формируем красивое сообщение для Telegram
            message = "🎯 <b>НОВАЯ ЗАЯВКА С ФОРМЫ</b>\n"
            message += "━━━━━━━━━━━━━━━━━━━━\n\n"
            
            # Обязательные поля
            message += f"👤 <b>Имя:</b> {self._format_value(data.get('name'))}\n"
            message += f"📞 <b>Телефон:</b> {self._format_value(data.get('phone'))}\n"
            message += f"📧 <b>Email:</b> {self._format_value(data.get('email'))}\n"
            
            # Дополнительные поля
            if data.get('service_type'):
                message += f"🎨 <b>Услуга:</b> {data['service_type']}\n"
            
            if data.get('note'):
                message += f"📝 <b>Заметка:</b> {data['note']}\n"
            
            if data.get('application_id'):
                message += f"\n🔖 <b>ID заявки:</b> <code>{data['application_id']}</code>\n"
            
            if data.get('conversation_summary'):
                summary = data['conversation_summary'][:200]
                if len(data['conversation_summary']) > 200:
                    summary += '...'
                message += f"\n💭 <b>Комментарий:</b>\n<i>{summary}</i>\n"
            
            message += f"\n🕐 <b>Время:</b> {datetime.now().strftime('%d.%m.%Y %H:%M:%S')}"
            
            # Отправляем в Telegram
            if send_to_telegram(message):
                self._send_json_response(200, {
                    'success': True,
                    'message': 'Заявка успешно отправлена администратору'
                })
            else:
                self._send_json_response(500, {
                    'success': False,
                    'error': 'Не удалось отправить сообщение в Telegram'
                })
                
        except json.JSONDecodeError as e:
            logger.error(f"Ошибка декодирования JSON: {e}")
            self._send_json_response(400, {
                'success': False,
                'error': 'Неверный формат данных'
            })
        except Exception as e:
            logger.error(f"Ошибка обработки формы: {e}", exc_info=True)
            self._send_json_response(500, {
                'success': False,
                'error': str(e)
            })
    
    def handle_chat_data(self):
        """Обработка данных из чат-бота 'Обсудить проект'"""
        try:
            # Читаем данные
            content_length = int(self.headers.get('Content-Length', 0))
            post_data = self.rfile.read(content_length)
            data = json.loads(post_data.decode('utf-8'))
            
            # Проверяем тип сообщения
            msg_type = data.get('type', 'application')
            
            if msg_type == 'additional_question':
                # Это дополнительный вопрос от клиента
                self._handle_additional_question(data)
            else:
                # Это полноценная заявка из чата
                self._handle_chat_application(data)
                
        except json.JSONDecodeError as e:
            logger.error(f"Ошибка декодирования JSON: {e}")
            self._send_json_response(400, {
                'success': False,
                'error': 'Неверный формат данных'
            })
        except Exception as e:
            logger.error(f"Ошибка обработки чата: {e}", exc_info=True)
            self._send_json_response(500, {
                'success': False,
                'error': str(e)
            })
    
    def _handle_chat_application(self, data):
        """Обработка полноценной заявки из чата"""
        logger.info(f"Получена заявка из чата: {data.get('name', 'N/A')}")
        
        # Формируем сообщение для Telegram
        message = "💬 <b>ЗАЯВКА ИЗ ЧАТ-БОТА</b>\n"
        message += "━━━━━━━━━━━━━━━━━━━━\n\n"
        
        # Основные данные
        message += f"👤 <b>Имя:</b> {self._format_value(data.get('name'))}\n"
        message += f"📞 <b>Телефон:</b> {self._format_value(data.get('phone'))}\n"
        message += f"📧 <b>Email:</b> {self._format_value(data.get('email'))}\n"
        
        if data.get('service_type') or data.get('website_type'):
            service = data.get('service_type') or data.get('website_type')
            message += f"🎨 <b>Тип сайта:</b> {service}\n"
        
        # Дополнительные вопросы
        if data.get('additional_questions'):
            questions = data['additional_questions']
            if isinstance(questions, list) and len(questions) > 0:
                message += f"\n❓ <b>Дополнительные вопросы:</b>\n"
                for i, q in enumerate(questions, 1):
                    message += f"{i}. {q}\n"
        
        # Краткое содержание диалога
        if data.get('conversation_summary'):
            summary = data['conversation_summary']
            # Ограничиваем длину
            if len(summary) > 500:
                summary = summary[:500] + '...'
            message += f"\n💭 <b>Диалог:</b>\n<i>{summary}</i>\n"
        
        message += f"\n🕐 <b>Время:</b> {datetime.now().strftime('%d.%m.%Y %H:%M:%S')}"
        
        # Отправляем в Telegram
        if send_to_telegram(message):
            self._send_json_response(200, {
                'success': True,
                'message': 'Заявка отправлена администратору'
            })
        else:
            self._send_json_response(500, {
                'success': False,
                'error': 'Не удалось отправить сообщение в Telegram'
            })
    
    def _handle_additional_question(self, data):
        """Обработка дополнительного вопроса из чата"""
        logger.info(f"Получен дополнительный вопрос")
        
        question = data.get('question', '')
        user_data = data.get('user_data', {})
        
        # Формируем сообщение
        message = "❓ <b>ВОПРОС ИЗ ЧАТА</b>\n"
        message += "━━━━━━━━━━━━━━━━━━━━\n\n"
        
        message += f"💬 <b>Вопрос:</b>\n<i>{question}</i>\n\n"
        
        # Данные пользователя, если есть
        if user_data.get('name'):
            message += f"👤 <b>Имя:</b> {user_data['name']}\n"
        if user_data.get('phone'):
            message += f"📞 <b>Телефон:</b> {user_data['phone']}\n"
        if user_data.get('email'):
            message += f"📧 <b>Email:</b> {user_data['email']}\n"
        
        message += f"\n🕐 <b>Время:</b> {datetime.now().strftime('%d.%m.%Y %H:%M:%S')}"
        
        # Отправляем в Telegram
        if send_to_telegram(message):
            self._send_json_response(200, {
                'success': True,
                'message': 'Вопрос отправлен администратору'
            })
        else:
            self._send_json_response(500, {
                'success': False,
                'error': 'Не удалось отправить сообщение в Telegram'
            })
    
    def _format_value(self, value):
        """Форматирование значений для отображения"""
        if value is None or value == '':
            return '<i>Не указано</i>'
        return str(value)
    
    def _send_json_response(self, status_code, data):
        """Отправка JSON ответа"""
        self.send_response(status_code)
        self.send_header('Content-type', 'application/json; charset=utf-8')
        self.send_header('Access-Control-Allow-Origin', '*')
        self.end_headers()
        self.wfile.write(json.dumps(data, ensure_ascii=False).encode('utf-8'))
    
    def log_message(self, format, *args):
        """Отключаем стандартный лог HTTP запросов"""
        pass


if __name__ == '__main__':
    logger.info("=" * 60)
    logger.info("🤖 Telegram Bot для Pirats.studio")
    logger.info("=" * 60)
    logger.info(f"📡 Bot Token: {BOT_TOKEN[:10]}...")
    logger.info(f"💬 Chat ID: {CHAT_ID}")
    logger.info("")
    logger.info("🌐 Сервер работает на: http://localhost:5000")
    logger.info("")
    logger.info("📮 Endpoints:")
    logger.info("  POST /webhook/application - Заявки с форм сайта")
    logger.info("  POST /webhook/chat        - Заявки из чат-бота")
    logger.info("  GET  /health              - Проверка работоспособности")
    logger.info("  OPTIONS /*                - CORS preflight")
    logger.info("=" * 60)
    logger.info("")
    
    # Проверяем соединение с Telegram
    try:
        test_message = "✅ Telegram Bot для Pirats.studio запущен и готов принимать заявки!"
        if send_to_telegram(test_message):
            logger.info("✅ Соединение с Telegram установлено успешно")
        else:
            logger.warning("⚠️  Не удалось отправить тестовое сообщение в Telegram")
    except Exception as e:
        logger.error(f"❌ Ошибка при проверке соединения с Telegram: {e}")
    
    logger.info("")
    logger.info("🚀 Бот запущен и ожидает заявок...")
    logger.info("")
    
    # Запускаем сервер
    server = HTTPServer(('localhost', 5000), WebhookHandler)
    
    try:
        server.serve_forever()
    except KeyboardInterrupt:
        logger.info("\n")
        logger.info("🛑 Получен сигнал остановки...")
        server.shutdown()
        logger.info("👋 Сервер остановлен")
        logger.info("=" * 60)

