<?php
    $apiKey = core()->getConfigData('ai-support.general.api_key') ?: env('GROQ_API_KEY');
    if (! $apiKey) return;
?>

<div id="ai-chat-widget" style="position:fixed;bottom:24px;right:24px;z-index:9999;font-family:inherit">
    
    <button id="chat-toggle"
        onclick="toggleChat()"
        style="width:56px;height:56px;border-radius:50%;background:#1a1a2e;color:white;border:none;cursor:pointer;box-shadow:0 4px 16px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;font-size:24px"
        title="Chat with us">
        💬
    </button>

    
    <div id="chat-panel"
        style="display:none;position:absolute;bottom:70px;right:0;width:340px;background:white;border-radius:16px;box-shadow:0 8px 32px rgba(0,0,0,0.2);overflow:hidden;flex-direction:column">

        
        <div style="background:#1a1a2e;color:white;padding:16px 20px;display:flex;align-items:center;justify-content:space-between">
            <div>
                <div style="font-weight:600;font-size:15px">🤖 Support Assistant</div>
                <div style="font-size:12px;opacity:0.75">Powered by AI · Usually replies instantly</div>
            </div>
            <button onclick="toggleChat()" style="background:none;border:none;color:white;cursor:pointer;font-size:18px">✕</button>
        </div>

        
        <div id="chat-messages"
            style="height:320px;overflow-y:auto;padding:16px;display:flex;flex-direction:column;gap:10px;background:#f9fafb">
            <div class="msg-bubble msg-ai" style="background:#e8eaff;padding:10px 14px;border-radius:12px 12px 12px 4px;font-size:14px;max-width:85%;align-self:flex-start">
                Hi! 👋 How can I help you today? I can look up your orders, help with returns, or answer any questions.
            </div>
        </div>

        
        <div id="chat-typing" style="display:none;padding:0 16px 6px;font-size:12px;color:#888">AI is typing…</div>

        
        <div style="padding:12px 16px;border-top:1px solid #eee;display:flex;gap:8px;background:white">
            <input id="chat-input" type="text"
                placeholder="Type your message…"
                style="flex:1;border:1px solid #ddd;border-radius:8px;padding:8px 12px;font-size:14px;outline:none"
                onkeydown="if(event.key==='Enter')sendMessage()">
            <button onclick="sendMessage()"
                style="background:#1a1a2e;color:white;border:none;border-radius:8px;padding:8px 14px;cursor:pointer;font-size:18px">
                ➤
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    // Generate or retrieve session ID
    function getSessionId() {
        let id = localStorage.getItem('ai_chat_session');
        if (!id) {
            id = 'ws_' + Math.random().toString(36).substr(2, 12) + '_' + Date.now();
            localStorage.setItem('ai_chat_session', id);
        }
        return id;
    }

    const SESSION_ID = getSessionId();

    window.toggleChat = function() {
        const panel = document.getElementById('chat-panel');
        const isOpen = panel.style.display !== 'none';
        panel.style.display = isOpen ? 'none' : 'flex';
        if (!isOpen) {
            document.getElementById('chat-input').focus();
            loadHistory();
        }
    };

    async function loadHistory() {
        try {
            const res = await fetch('/api/ai-support/history?session_id=' + SESSION_ID);
            const data = await res.json();
            if (data.messages && data.messages.length > 0) {
                const container = document.getElementById('chat-messages');
                // Clear welcome message if history exists
                container.innerHTML = '';
                data.messages.forEach(msg => appendMessage(msg.role === 'customer' ? 'user' : 'ai', msg.content));
            }
        } catch(e) {}
    }

    window.sendMessage = async function() {
        const input = document.getElementById('chat-input');
        const text = input.value.trim();
        if (!text) return;

        input.value = '';
        appendMessage('user', text);
        showTyping(true);

        try {
            const res = await fetch('/api/ai-support/chat', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: text, session_id: SESSION_ID }),
            });
            const data = await res.json();
            showTyping(false);

            const reply = data.message || 'Sorry, I could not process your request.';
            appendMessage('ai', reply);
        } catch(e) {
            showTyping(false);
            appendMessage('ai', 'Connection error. Please try again.');
        }
    };

    function appendMessage(role, text) {
        const container = document.getElementById('chat-messages');
        const div = document.createElement('div');
        div.style.cssText = role === 'user'
            ? 'background:#1a1a2e;color:white;padding:10px 14px;border-radius:12px 12px 4px 12px;font-size:14px;max-width:85%;align-self:flex-end'
            : 'background:#e8eaff;padding:10px 14px;border-radius:12px 12px 12px 4px;font-size:14px;max-width:85%;align-self:flex-start';
        div.textContent = text;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }

    function showTyping(show) {
        document.getElementById('chat-typing').style.display = show ? 'block' : 'none';
    }

    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : '';
    }
})();
</script>
<?php /**PATH D:\aven\packages\Webkul\AiSupport\src\Providers/../Resources/views/shop/chat-widget.blade.php ENDPATH**/ ?>