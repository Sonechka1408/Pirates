#!/usr/bin/env python3
# -*- coding: utf-8 -*-

"""
Тестовый скрипт для проверки интеграции Telegram бота
"""

import json
import urllib.request
import sys

# URL бота
BOT_URL = 'http://localhost:5000'

def test_health():
    """Проверка работоспособности бота"""
    print("=" * 60)
    print("Тест 1: Проверка работоспособности бота")
    print("=" * 60)
    
    try:
        response = urllib.request.urlopen(f'{BOT_URL}/health', timeout=5)
        result = json.loads(response.read().decode('utf-8'))
        
        if result.get('status') == 'ok':
            print("✅ Бот работает нормально")
            print(f"   Status: {result.get('status')}")
            print(f"   Bot: {result.get('bot')}")
            print(f"   Timestamp: {result.get('timestamp')}")
            return True
        else:
            print("❌ Бот вернул неожиданный статус")
            return False
            
    except Exception as e:
        print(f"❌ Ошибка соединения: {e}")
        print(f"   Убедитесь что бот запущен: python telegram_bot.py")
        return False


def test_form_application():
    """Тест заявки с формы сайта"""
    print("\n" + "=" * 60)
    print("Тест 2: Отправка тестовой заявки с формы")
    print("=" * 60)
    
    test_data = {
        'name': 'Тестовый Пользователь',
        'phone': '+7 900 123-45-67',
        'email': 'test@example.com',
        'service_type': 'Тестовая заявка с формы',
        'application_id': 'TEST_FORM_001'
    }
    
    try:
        json_data = json.dumps(test_data).encode('utf-8')
        req = urllib.request.Request(f'{BOT_URL}/webhook/application')
        req.add_header('Content-Type', 'application/json')
        
        response = urllib.request.urlopen(req, data=json_data, timeout=10)
        result = json.loads(response.read().decode('utf-8'))
        
        if result.get('success'):
            print("✅ Заявка с формы отправлена успешно")
            print(f"   Сообщение: {result.get('message')}")
            print("\n   Проверьте Telegram - должно прийти сообщение:")
            print("   🎯 НОВАЯ ЗАЯВКА С ФОРМЫ")
            return True
        else:
            print(f"❌ Ошибка: {result.get('error')}")
            return False
            
    except Exception as e:
        print(f"❌ Ошибка отправки: {e}")
        return False


def test_chat_application():
    """Тест заявки из чат-бота"""
    print("\n" + "=" * 60)
    print("Тест 3: Отправка тестовой заявки из чата")
    print("=" * 60)
    
    test_data = {
        'name': 'Чат Тестер',
        'phone': '+7 900 987-65-43',
        'email': 'chat@example.com',
        'website_type': 'Лендинг',
        'additional_questions': [
            'Сколько стоит разработка?',
            'Какие сроки реализации?'
        ]
    }
    
    try:
        json_data = json.dumps(test_data).encode('utf-8')
        req = urllib.request.Request(f'{BOT_URL}/webhook/chat')
        req.add_header('Content-Type', 'application/json')
        
        response = urllib.request.urlopen(req, data=json_data, timeout=10)
        result = json.loads(response.read().decode('utf-8'))
        
        if result.get('success'):
            print("✅ Заявка из чата отправлена успешно")
            print(f"   Сообщение: {result.get('message')}")
            print("\n   Проверьте Telegram - должно прийти сообщение:")
            print("   💬 ЗАЯВКА ИЗ ЧАТ-БОТА")
            return True
        else:
            print(f"❌ Ошибка: {result.get('error')}")
            return False
            
    except Exception as e:
        print(f"❌ Ошибка отправки: {e}")
        return False


def test_additional_question():
    """Тест дополнительного вопроса из чата"""
    print("\n" + "=" * 60)
    print("Тест 4: Отправка дополнительного вопроса")
    print("=" * 60)
    
    test_data = {
        'type': 'additional_question',
        'question': 'Можно ли добавить анимации на сайт?',
        'user_data': {
            'name': 'Чат Тестер',
            'phone': '+7 900 987-65-43',
            'email': 'chat@example.com'
        }
    }
    
    try:
        json_data = json.dumps(test_data).encode('utf-8')
        req = urllib.request.Request(f'{BOT_URL}/webhook/chat')
        req.add_header('Content-Type', 'application/json')
        
        response = urllib.request.urlopen(req, data=json_data, timeout=10)
        result = json.loads(response.read().decode('utf-8'))
        
        if result.get('success'):
            print("✅ Дополнительный вопрос отправлен успешно")
            print(f"   Сообщение: {result.get('message')}")
            print("\n   Проверьте Telegram - должно прийти сообщение:")
            print("   ❓ ВОПРОС ИЗ ЧАТА")
            return True
        else:
            print(f"❌ Ошибка: {result.get('error')}")
            return False
            
    except Exception as e:
        print(f"❌ Ошибка отправки: {e}")
        return False


def main():
    """Запуск всех тестов"""
    print("\n" + "🤖 ТЕСТИРОВАНИЕ ИНТЕГРАЦИИ TELEGRAM БОТА" + "\n")
    
    results = []
    
    # Тест 1: Health check
    results.append(test_health())
    
    if not results[0]:
        print("\n" + "=" * 60)
        print("❌ Бот не запущен. Остальные тесты пропущены.")
        print("   Запустите бота: python telegram_bot.py")
        print("=" * 60)
        sys.exit(1)
    
    # Тест 2: Заявка с формы
    results.append(test_form_application())
    
    # Тест 3: Заявка из чата
    results.append(test_chat_application())
    
    # Тест 4: Дополнительный вопрос
    results.append(test_additional_question())
    
    # Итоги
    print("\n" + "=" * 60)
    print("📊 РЕЗУЛЬТАТЫ ТЕСТИРОВАНИЯ")
    print("=" * 60)
    
    passed = sum(results)
    total = len(results)
    
    print(f"\nПройдено: {passed}/{total} тестов")
    
    if passed == total:
        print("\n✅ Все тесты пройдены успешно!")
        print("   Интеграция работает корректно.")
    else:
        print(f"\n⚠️  Не пройдено тестов: {total - passed}")
        print("   Проверьте логи бота: bot.log")
    
    print("\n" + "=" * 60)
    print("\n💡 Совет: Проверьте ваш Telegram на наличие сообщений от бота.")
    print("=" * 60 + "\n")


if __name__ == '__main__':
    try:
        main()
    except KeyboardInterrupt:
        print("\n\n⚠️  Тестирование прервано пользователем")
        sys.exit(0)

