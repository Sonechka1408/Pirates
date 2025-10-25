/**
 * Chat Bot JavaScript
 * Простой чат-бот для сбора заявок
 */

class ChatBot {
    constructor() {
        this.isTyping = false;
        this.telegramWebhookUrl = 'http://localhost:5000/webhook/chat';
        
        // Состояние диалога - упрощенное
        this.dialogState = {
            step: 'greeting', // greeting, name, website_type, phone, email, complete
            collectedData: {
                name: null,
                phone: null,
                email: null,
                website_type: null,
                additional_questions: []
            }
        };
        
        this.initializeElements();
        this.bindEvents();
        this.showWelcomeMessage();
    }
    
    initializeElements() {
        this.btn = document.getElementById('discussBtn');
        this.overlay = document.getElementById('chatOverlay');
        this.closeBtn = document.getElementById('chatClose');
        this.input = document.getElementById('chatInput');
        this.sendBtn = document.getElementById('chatSend');
        this.messages = document.getElementById('chatMessages');
    }
    
    bindEvents() {
        if (this.btn) {
            this.btn.addEventListener('click', () => this.openChat());
        }
        
        if (this.overlay) {
            this.overlay.addEventListener('click', (e) => {
                if (e.target === this.overlay) {
                    this.closeChat();
                }
            });
        }
        
        if (this.closeBtn) {
            this.closeBtn.addEventListener('click', () => this.closeChat());
        }
        
        if (this.sendBtn) {
            this.sendBtn.addEventListener('click', () => this.sendMessage());
        }
        
        if (this.input) {
            this.input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.sendMessage();
                }
            });
        }
        
        // Закрытие по Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.overlay.classList.contains('show')) {
                this.closeChat();
            }
        });
    }
    
    openChat() {
        if (this.overlay) {
            this.overlay.classList.add('show');
            if (this.input) {
                setTimeout(() => this.input.focus(), 300);
            }
        }
    }
    
    closeChat() {
        if (this.overlay) {
            this.overlay.classList.remove('show');
        }
    }
    
    showWelcomeMessage() {
        if (this.messages && this.messages.children.length === 0) {
            this.addMessage('left', 'Здравствуйте! Я виртуальный помощник Pirats.studio. Помогу вам оформить заявку на создание сайта с игрофикацией. Как вас зовут?');
        }
    }
    
    addMessage(side, text) {
        if (!this.messages) return;
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-bubble ${side}`;
        messageDiv.textContent = text;
        
        this.messages.appendChild(messageDiv);
        this.messages.scrollTop = this.messages.scrollHeight;
    }
    
    async sendMessage() {
        if (!this.input || this.isTyping) return;
        
        const message = this.input.value.trim();
        if (!message) return;
        
        // Показываем сообщение пользователя
        this.addMessage('right', message);
        this.input.value = '';
        
        // Блокируем отправку
        this.setTyping(true);
        
        try {
            // Обрабатываем сообщение в зависимости от состояния диалога
            const reply = this.processMessage(message);
            
            // Показываем ответ бота
            this.addMessage('left', reply);
            
            // Если диалог завершен, отправляем данные в Telegram
            if (this.dialogState.step === 'complete') {
                await this.sendToTelegramBot();
            }
        } catch (error) {
            console.error('Chat error:', error);
            this.addMessage('left', 'Извините, произошла ошибка. Попробуйте позже или свяжитесь с нами напрямую по телефону: +7 909 150-34-44');
        } finally {
            this.setTyping(false);
        }
    }
    
    processMessage(message) {
        const { step, collectedData } = this.dialogState;
        
        switch (step) {
            case 'greeting':
                // Приветствие уже показано, переходим к сбору имени
                const name = this.extractName(message);
                if (name) {
                    collectedData.name = name;
                    this.dialogState.step = 'website_type';
                    return `Приятно познакомиться, ${name}! Расскажите, какой сайт вы хотели бы заказать? Например: лендинг, интернет-магазин, корпоративный сайт.`;
                } else {
                    return 'Пожалуйста, представьтесь. Как вас зовут?';
                }
                
            case 'website_type':
                const websiteType = this.extractWebsiteType(message);
                if (websiteType) {
                    collectedData.website_type = websiteType;
                    this.dialogState.step = 'phone';
                    return `Отлично! ${websiteType} - хороший выбор. Для оформления заявки мне нужен ваш номер телефона.`;
                } else {
                    // Сохраняем как свободную форму
                    collectedData.website_type = message;
                    this.dialogState.step = 'phone';
                    return 'Отлично! Для оформления заявки мне нужен ваш номер телефона.';
                }
                
            case 'phone':
                const phone = this.extractPhone(message);
                if (phone) {
                    collectedData.phone = phone;
                    this.dialogState.step = 'email';
                    return 'Спасибо! Теперь укажите ваш email для связи.';
                } else {
                    return 'Пожалуйста, введите корректный номер телефона в формате: +7 XXX XXX-XX-XX или 8 XXX XXX-XX-XX';
                }
                
            case 'email':
                const email = this.extractEmail(message);
                if (email || message.toLowerCase().includes('нет') || message.toLowerCase().includes('без')) {
                    collectedData.email = email || 'Не указан';
                    this.dialogState.step = 'complete';
                    return '✅ Отлично! Все данные собраны. Наш менеджер свяжется с вами в ближайшее время для обсуждения деталей проекта. Если у вас есть дополнительные вопросы, можете задать их сейчас.';
                } else {
                    return 'Пожалуйста, введите корректный email адрес или напишите "нет", если не хотите указывать.';
                }
                
            case 'complete':
                // После завершения - сохраняем дополнительные вопросы
                collectedData.additional_questions.push(message);
                return 'Спасибо за вопрос! Я передам его нашему менеджеру. Есть еще что-то, что вы хотели бы уточнить?';
                
            default:
                return 'Извините, что-то пошло не так. Давайте начнем сначала. Как вас зовут?';
        }
    }

    // Вспомогательные методы для извлечения данных
    extractName(message) {
        // Простое извлечение имени
        const trimmed = message.trim();
        const words = trimmed.split(/\s+/);
        
        // Имя должно быть от 1 до 3 слов и содержать только буквы
        if (words.length >= 1 && words.length <= 3 && words.every(word => /^[а-яёa-zА-ЯЁA-Z]+$/i.test(word))) {
            return words.map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(' ');
        }
        return null;
    }

    extractPhone(message) {
        // Ищем российские номера телефонов
        const phoneRegex = /(\+7|8|7)[\s\-]?\(?\d{3}\)?[\s\-]?\d{3}[\s\-]?\d{2}[\s\-]?\d{2}/;
        const match = message.match(phoneRegex);
        return match ? match[0] : null;
    }

    extractEmail(message) {
        const emailRegex = /\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/;
        const match = message.match(emailRegex);
        return match ? match[0] : null;
    }

    extractWebsiteType(message) {
        const messageLower = message.toLowerCase();
        
        if (messageLower.includes('лендинг') || messageLower.includes('одностраничн')) {
            return 'Лендинг';
        } else if (messageLower.includes('интернет-магазин') || messageLower.includes('магазин') || messageLower.includes('e-commerce') || messageLower.includes('shop')) {
            return 'Интернет-магазин';
        } else if (messageLower.includes('корпоративн')) {
            return 'Корпоративный сайт';
        } else if (messageLower.includes('блог')) {
            return 'Блог';
        } else if (messageLower.includes('портфолио') || messageLower.includes('портфель')) {
            return 'Портфолио';
        }
        
        return null;
    }
    
    setTyping(isTyping) {
        this.isTyping = isTyping;
        
        if (this.sendBtn) {
            this.sendBtn.disabled = isTyping;
            this.sendBtn.textContent = isTyping ? 'Отправка...' : 'Отправить';
        }
        
        if (this.input) {
            this.input.disabled = isTyping;
        }
    }

    async sendToTelegramBot() {
        try {
            const { collectedData } = this.dialogState;
            
            // Формируем данные для отправки
            const applicationData = {
                name: collectedData.name || 'Не указано',
                phone: collectedData.phone || 'Не указано',
                email: collectedData.email || 'Не указано',
                website_type: collectedData.website_type || 'Не указан',
                additional_questions: collectedData.additional_questions.length > 0 ? collectedData.additional_questions : undefined
            };
            
            // Отправляем в чат endpoint (не application, т.к. это из чата)
            const response = await fetch(this.telegramWebhookUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(applicationData)
            });
            
            const result = await response.json();
            
            if (result.success) {
                console.log('✅ Заявка из чата отправлена в Telegram');
            } else {
                console.log('⚠️ Ошибка отправки заявки в Telegram:', result.error || 'Unknown error');
            }
        } catch (error) {
            console.log('⚠️ Не удалось отправить заявку в Telegram бот:', error.message);
            // Не показываем ошибку пользователю, т.к. форма уже заполнена
        }
    }
}

// Инициализация чат-бота при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
    new ChatBot();
});

// Экспорт для использования в других скриптах
window.ChatBot = ChatBot;
