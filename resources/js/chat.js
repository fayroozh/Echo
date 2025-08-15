// Chat functionality
import './bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    // Listen for new messages
    const userId = document.querySelector('meta[name="user-id"]')?.content;
    
    if (userId) {
        // Listen for private messages
        window.Echo.private(`chat.${userId}`)
            .listen('.message.sent', (e) => {
                // Add notification for new message
                showNotification(e.message.sender.name, e.message.content);
                
                // Update chat if we're in the conversation
                updateChatIfActive(e.message);
            })
            .listen('.user.typing', (e) => {
                showTypingIndicator(e.user);
            })
            .listen('.message.read', (e) => {
                updateReadStatus(e.message_id);
            });
    }
    
    // Setup typing indicator
    const messageInput = document.getElementById('message-input');
    if (messageInput) {
        let typingTimer;
        const doneTypingInterval = 1000;
        
        messageInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            
            const conversationId = this.dataset.conversation;
            if (conversationId) {
                // Send typing event
                axios.post('/chat/typing', {
                    conversation_id: conversationId
                });
                
                typingTimer = setTimeout(() => {
                    // Send stopped typing event
                    axios.post('/chat/stopped-typing', {
                        conversation_id: conversationId
                    });
                }, doneTypingInterval);
            }
        });
    }
    
    // Mark messages as read when viewed
    const chatContainer = document.getElementById('chat-messages');
    if (chatContainer) {
        const conversationId = chatContainer.dataset.conversation;
        if (conversationId) {
            axios.post(`/chat/${conversationId}/read`);
            
            // Also mark as read when window gets focus
            window.addEventListener('focus', function() {
                axios.post(`/chat/${conversationId}/read`);
            });
        }
    }
    
    // Quote sharing functionality
    setupQuoteSharing();
});

function showNotification(sender, message) {
    if (!("Notification" in window)) {
        return;
    }
    
    if (Notification.permission === "granted") {
        const notification = new Notification(`رسالة جديدة من ${sender}`, {
            body: message,
            icon: '/logo.png'
        });
        
        notification.onclick = function() {
            window.focus();
            this.close();
        };
    } else if (Notification.permission !== "denied") {
        Notification.requestPermission();
    }
}

function updateChatIfActive(message) {
    const chatContainer = document.getElementById('chat-messages');
    if (!chatContainer) return;
    
    const conversationId = chatContainer.dataset.conversation;
    if (conversationId && message.conversation_id == conversationId) {
        // Add the new message to the chat
        const messageHtml = createMessageElement(message);
        chatContainer.insertAdjacentHTML('beforeend', messageHtml);
        
        // Scroll to bottom
        chatContainer.scrollTop = chatContainer.scrollHeight;
        
        // Mark as read
        axios.post(`/chat/${conversationId}/read`);
    }
}

function createMessageElement(message) {
    const isOwn = message.user_id == document.querySelector('meta[name="user-id"]').content;
    const alignment = isOwn ? 'justify-end' : 'justify-start';
    const bgColor = isOwn ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700';
    
    return `
    <div class="flex ${alignment} mb-4" id="message-${message.id}">
        <div class="${bgColor} rounded-lg px-4 py-2 max-w-[70%]">
            ${message.content}
            <div class="text-xs mt-1 ${isOwn ? 'text-blue-100' : 'text-gray-500 dark:text-gray-400'}">
                ${new Date(message.created_at).toLocaleTimeString('ar-SA')}
                ${isOwn ? `<span class="read-status ml-2">${message.is_read ? 'تم القراءة' : 'تم الإرسال'}</span>` : ''}
            </div>
        </div>
    </div>`;
}

function showTypingIndicator(user) {
    const chatContainer = document.getElementById('chat-messages');
    if (!chatContainer) return;
    
    let typingIndicator = document.getElementById('typing-indicator');
    
    if (!typingIndicator) {
        typingIndicator = document.createElement('div');
        typingIndicator.id = 'typing-indicator';
        typingIndicator.className = 'text-sm text-gray-500 dark:text-gray-400 italic mb-2';
        chatContainer.parentNode.insertBefore(typingIndicator, chatContainer.nextSibling);
    }
    
    typingIndicator.textContent = `${user.name} يكتب الآن...`;
    typingIndicator.style.display = 'block';
    
    // Hide after 3 seconds if no new typing event
    setTimeout(() => {
        typingIndicator.style.display = 'none';
    }, 3000);
}

function updateReadStatus(messageId) {
    const readStatus = document.querySelector(`#message-${messageId} .read-status`);
    if (readStatus) {
        readStatus.textContent = 'تم القراءة';
    }
}

function setupQuoteSharing() {
    // Add event listeners to quote buttons
    document.querySelectorAll('.quote-button').forEach(button => {
        button.addEventListener('click', function() {
            const quoteId = this.dataset.quoteId;
            const conversationId = this.dataset.conversationId;
            
            if (quoteId && conversationId) {
                axios.post(`/quotes/${quoteId}/share`, {
                    conversation_id: conversationId
                })
                .then(response => {
                    if (response.data.success) {
                        // Show success message
                        const successMessage = document.createElement('div');
                        successMessage.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg';
                        successMessage.textContent = 'تم مشاركة الاقتباس بنجاح';
                        document.body.appendChild(successMessage);
                        
                        setTimeout(() => {
                            successMessage.remove();
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.error('Error sharing quote:', error);
                });
            }
        });
    });
}