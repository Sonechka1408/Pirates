#!/usr/bin/env python3
"""
Автоматическое определение Chat ID без интерактивного ввода
"""

import json
import urllib.request
import time
import os

def find_latest_chat_id():
    """Находит последний Chat ID из истории сообщений"""
    
    bot_token = "8420622652:AAFJNVkLUNEUJ0OdfHqJJrVXRU_jLQmGuPY"
    url = f"https://api.telegram.org/bot{bot_token}/getUpdates"
    
    print("=" * 60)
    print("ПОИСК CHAT ID В ИСТОРИИ СООБЩЕНИЙ")
    print("=" * 60)
    print()
    print("Ищу последние сообщения...")
    
    try:
        response = urllib.request.urlopen(url, timeout=10)
        
        if response.status == 200:
            result = json.loads(response.read().decode('utf-8'))
            
            if result.get('ok') and result.get('result'):
                updates = result['result']
                
                if updates:
                    print(f"Найдено {len(updates)} сообщений в истории")
                    
                    # Ищем все уникальные Chat ID
                    chat_ids = set()
                    for update in updates:
                        if 'message' in update:
                            message = update['message']
                            chat = message['chat']
                            user = message['from']
                            
                            chat_id = chat['id']
                            user_name = user.get('first_name', 'Unknown')
                            username = user.get('username', 'No username')
                            message_text = message.get('text', 'No text')
                            
                            chat_ids.add((chat_id, user_name, username, message_text))
                    
                    if chat_ids:
                        print("\nНАЙДЕННЫЕ CHAT ID:")
                        print("-" * 40)
                        
                        for i, (chat_id, user_name, username, message_text) in enumerate(chat_ids, 1):
                            print(f"{i}. Chat ID: {chat_id}")
                            print(f"   Пользователь: {user_name}")
                            print(f"   Username: @{username}")
                            print(f"   Сообщение: {message_text[:50]}...")
                            print()
                        
                        # Берем последний (самый свежий) Chat ID
                        latest_chat_id = max(chat_ids, key=lambda x: x[0])[0]
                        print(f"ИСПОЛЬЗУЮ ПОСЛЕДНИЙ CHAT ID: {latest_chat_id}")
                        
                        # Обновляем config.env
                        update_config_file(latest_chat_id)
                        
                        return latest_chat_id
                    else:
                        print("Сообщения не найдены")
                        return None
                else:
                    print("История сообщений пуста")
                    print("Отправьте сообщение боту @Startapus_request_bot и попробуйте снова")
                    return None
            else:
                print(f"Ошибка API: {result.get('description', 'Unknown error')}")
                return None
        else:
            print(f"Ошибка HTTP: {response.status}")
            return None
            
    except Exception as e:
        print(f"Ошибка: {e}")
        return None

def update_config_file(chat_id):
    """Обновляет config.env с найденным Chat ID"""
    
    config_file = 'config.env'
    
    try:
        # Читаем существующий файл
        if os.path.exists(config_file):
            with open(config_file, 'r', encoding='utf-8') as f:
                lines = f.readlines()
        else:
            lines = []
        
        # Обновляем или добавляем TELEGRAM_CHAT_ID
        updated = False
        for i, line in enumerate(lines):
            if line.startswith('TELEGRAM_CHAT_ID='):
                lines[i] = f'TELEGRAM_CHAT_ID={chat_id}\n'
                updated = True
                break
        
        if not updated:
            lines.append(f'TELEGRAM_CHAT_ID={chat_id}\n')
        
        # Записываем обновленный файл
        with open(config_file, 'w', encoding='utf-8') as f:
            f.writelines(lines)
        
        print(f"Chat ID {chat_id} сохранен в {config_file}")
        
    except Exception as e:
        print(f"Ошибка при сохранении: {e}")

def test_telegram_sending(chat_id):
    """Тестирует отправку сообщения с найденным Chat ID"""
    
    bot_token = "8420622652:AAFJNVkLUNEUJ0OdfHqJJrVXRU_jLQmGuPY"
    url = f"https://api.telegram.org/bot{bot_token}/sendMessage"
    
    message = "Тест! Chat ID найден и работает!"
    
    data = {
        'chat_id': chat_id,
        'text': message,
        'parse_mode': 'HTML'
    }
    
    try:
        req = urllib.request.Request(url)
        req.add_header('Content-Type', 'application/json')
        
        json_data = json.dumps(data).encode('utf-8')
        response = urllib.request.urlopen(req, data=json_data, timeout=10)
        
        if response.status == 200:
            result = json.loads(response.read().decode('utf-8'))
            if result.get('ok'):
                print("ТЕСТ УСПЕШЕН! Сообщение отправлено в Telegram!")
                return True
            else:
                print(f"Ошибка отправки: {result.get('description')}")
                return False
        else:
            print(f"HTTP ошибка: {response.status}")
            return False
            
    except Exception as e:
        print(f"Ошибка тестирования: {e}")
        return False

if __name__ == "__main__":
    chat_id = find_latest_chat_id()
    
    if chat_id:
        print("\n" + "=" * 60)
        print("ТЕСТИРОВАНИЕ ОТПРАВКИ")
        print("=" * 60)
        
        if test_telegram_sending(chat_id):
            print("\nВСЕ ГОТОВО!")
            print("Проверьте Telegram - должно прийти тестовое сообщение")
            print("Теперь бот будет работать корректно!")
        else:
            print("\nТест не прошел, но Chat ID найден")
            print("Попробуйте перезапустить бота")
    else:
        print("\nНе удалось найти Chat ID")
        print("Инструкция:")
        print("1. Откройте Telegram")
        print("2. Найдите @Startapus_request_bot")
        print("3. Отправьте любое сообщение")
        print("4. Запустите скрипт снова")
