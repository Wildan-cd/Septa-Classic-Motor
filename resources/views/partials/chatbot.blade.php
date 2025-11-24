<link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">

<div class="chat-float-btn" onclick="toggleChat()">
    <i class="fas fa-robot"></i> 
</div>

<div class="chat-box" id="chatBox">
    <div class="chat-header">
        <h4>Septa AI Assistant 🤖</h4>
        <span class="chat-close" onclick="toggleChat()">×</span>
    </div>
    
    <div class="chat-messages" id="chatMessages">
        <div class="message bot">
            Halo! 👋 Saya asisten AI Septa Classic Motor. Ada yang bisa saya bantu seputar motor atau produk kami?
        </div>
    </div>
    
    <div class="typing-indicator" id="typingIndicator">Septa AI sedang mengetik...</div>

    <div class="chat-input-area">
        <input type="text" id="userMessage" class="chat-input" placeholder="Tanya sesuatu..." onkeypress="handleEnter(event)">
        <button class="chat-send-btn" onclick="sendMessage()">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

<script>
    function toggleChat() {
        const box = document.getElementById('chatBox');
        if (box.style.display === 'none' || box.style.display === '') {
            box.style.display = 'flex';
            setTimeout(() => document.getElementById('userMessage').focus(), 100);
        } else {
            box.style.display = 'none';
        }
    }

    function handleEnter(e) {
        if (e.key === 'Enter') sendMessage();
    }

    async function sendMessage() {
        const input = document.getElementById('userMessage');
        const message = input.value.trim();
        const chatArea = document.getElementById('chatMessages');
        const typing = document.getElementById('typingIndicator');

        if (!message) return;

        // 1. Tampilkan Pesan User
        const userBubble = document.createElement('div');
        userBubble.className = 'message user';
        userBubble.innerText = message;
        chatArea.appendChild(userBubble);
        
        input.value = '';
        chatArea.scrollTop = chatArea.scrollHeight;
        typing.style.display = 'block';

        try {
            const response = await fetch("{{ route('chat.send') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    // Pastikan CSRF Token terbaca dengan benar
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({ message: message })
            });

            // --- BAGIAN DEBUGGING ---
            // Kita baca sebagai TEXT dulu, bukan JSON langsung
            const textResponse = await response.text();
            
            let data;
            try {
                // Coba ubah text jadi JSON
                data = JSON.parse(textResponse);
            } catch (err) {
                // JIKA GAGAL JSON, BERARTI ERROR HTML (Misal 500 Server Error)
                console.error("Error Raw:", textResponse);
                throw new Error("Server Error: Cek Console (F12) untuk detail merah.");
            }

            if (!response.ok) {
                throw new Error(data.reply || "Terjadi kesalahan di server.");
            }

            // 4. Tampilkan Balasan AI
            const botBubble = document.createElement('div');
            botBubble.className = 'message bot';
            botBubble.innerHTML = data.reply.replace(/\n/g, '<br>'); 
            chatArea.appendChild(botBubble);

        } catch (error) {
            const errorBubble = document.createElement('div');
            errorBubble.className = 'message bot';
            errorBubble.style.color = 'red';
            // Tampilkan pesan error ASLI ke chatbox
            errorBubble.innerText = "Error: " + error.message;
            chatArea.appendChild(errorBubble);
            console.error(error);
        } finally {
            typing.style.display = 'none';
            chatArea.scrollTop = chatArea.scrollHeight;
        }
    }
</script>