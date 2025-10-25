#!/usr/bin/env python3
"""
Скрипт для получения вашего Chat ID
"""

import json
import urllib.request
import time

def get_chat_id():
    """Получение Chat ID через API"""
    
    bot_token = "8420622652:AAFJNVkLUNEUJ0OdfHqJJrVXRU_jLQmGuPY"
    url = f"https://api.telegram.org/bot{bot_token}/getUpdates"
    
    print("Looking for your Chat ID...")
    print("First, send any message to @Startapus_request_bot")
    print("Waiting 10 seconds...")
    
    time.sleep(10)
    
    try:
        response = urllib.request.urlopen(url, timeout=10)
        
        if response.status == 200:
            result = json.loads(response.read().decode('utf-8'))
            
            if result.get('ok') and result.get('result'):
                updates = result['result']
                
                if updates:
                    # Берем последнее сообщение
                    last_update = updates[-1]
                    if 'message' in last_update:
                        chat_id = last_update['message']['chat']['id']
                        user_name = last_update['message']['from'].get('first_name', 'Unknown')
                        
                        print(f"\nSUCCESS! Found Chat ID!")
                        print(f"User: {user_name}")
                        print(f"Chat ID: {chat_id}")
                        print(f"\nAdd this ID to config.env:")
                        print(f"TELEGRAM_CHAT_ID={chat_id}")
                        
                        return chat_id
                    else:
                        print("ERROR: No messages found")
                        print("Make sure you sent a message to the bot")
                        return None
                else:
                    print("ERROR: No updates found")
                    print("Send a message to @Startapus_request_bot and try again")
                    return None
            else:
                print(f"ERROR API: {result.get('description', 'Unknown error')}")
                return None
        else:
            print(f"ERROR HTTP: {response.status}")
            return None
            
    except Exception as e:
        print(f"ERROR: {e}")
        return None

if __name__ == "__main__":
    print("=" * 50)
    print("Getting Chat ID for Telegram Bot")
    print("=" * 50)
    
    chat_id = get_chat_id()
    
    if chat_id:
        print(f"\nSUCCESS! Your Chat ID: {chat_id}")
    else:
        print("\nFAILED: Could not get Chat ID")
        print("\nInstructions:")
        print("1. Open Telegram")
        print("2. Find @Startapus_request_bot")
        print("3. Send any message (e.g., /start)")
        print("4. Run this script again")
