<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - MAKAI Assistant</title>
    <link rel="stylesheet" href="./styles/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .message-enter {
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm border-b dark:border-gray-700">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <a href="index.php" class="text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="flex items-center">
                    <div class="bg-primary-100 dark:bg-primary-900/40 p-2 rounded-lg mr-3">
                        <i class="fas fa-robot text-primary-600 dark:text-primary-400"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800 dark:text-white">MAKAI Assistant</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Ask me anything about Makerere University</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <button onclick="clearChat()" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 flex items-center">
                    <i class="fas fa-trash-alt mr-1"></i> Clear Chat
                </button>
                <span class="text-xs px-3 py-1 bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-400 rounded-full">
                    <i class="fas fa-circle text-xs mr-1"></i> Online
                </span>
            </div>
        </div>
    </header>

    <!-- Chat Container -->
    <div id="chatContainer" class="flex-1 overflow-hidden max-w-6xl mx-auto w-full px-4 py-6">
        <div id="messages" class="h-full overflow-y-auto space-y-6 pb-4">
            <!-- Welcome message -->
            <div class="message-enter">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                            <i class="fas fa-robot text-primary-600"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="bg-white dark:bg-gray-700 rounded-2xl rounded-tl-none p-5 shadow-sm border dark:border-gray-600">
                            <p class="text-gray-800 dark:text-gray-100">Hello! I'm MAKAI, your dedicated Makerere University assistant. I can help you with information about admissions, courses, fees, campus facilities, and more. What would you like to know?</p>
                            <div class="mt-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Quick questions:</p>
                                <div class="flex flex-wrap gap-2">
                                    <?php
                                    $quickQuestions = [
                                        "What are the admission requirements?",
                                        "How much are tuition fees?",
                                        "Tell me about campus facilities",
                                        "What courses are offered?",
                                        "When is the next intake?"
                                    ];
                                    foreach ($quickQuestions as $question):
                                    ?>
                                    <button onclick="quickQuestion('<?php echo $question; ?>')" 
                                            class="text-sm bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-full transition">
                                        <?php echo $question; ?>
                                    </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-600">
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <span>All processing happens locally on your machine for privacy</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-2 ml-2">Just now</div>
                    </div>
                </div>
            </div>

            <!-- Previous chat history -->
            <?php if (!empty($_SESSION['chat_history'])): ?>
                <?php foreach ($_SESSION['chat_history'] as $msg): ?>
                <div class="message-enter">
                    <div class="flex items-start space-x-3 <?php echo $msg['role'] === 'user' ? 'flex-row-reverse space-x-reverse' : ''; ?>">
                        <div class="flex-shrink-0">
                            <?php if ($msg['role'] === 'user'): ?>
                            <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <?php else: ?>
                            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center">
                                <i class="fas fa-robot text-primary-600 dark:text-primary-400"></i>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 <?php echo $msg['role'] === 'user' ? 'text-right' : ''; ?>">
                            <div class="<?php echo $msg['role'] === 'user' ? 'bg-primary-600 text-white rounded-2xl rounded-tr-none' : 'bg-white dark:bg-gray-700 border dark:border-gray-600 rounded-2xl rounded-tl-none'; ?> p-5 shadow-sm">
                                <p class="<?php echo $msg['role'] === 'user' ? '' : 'dark:text-gray-100'; ?>"><?php echo htmlspecialchars($msg['message']); ?></p>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-2 <?php echo $msg['role'] === 'user' ? 'text-right mr-2' : 'ml-2'; ?>">
                                <?php echo $msg['time']; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Input Area -->
    <div class="bg-white dark:bg-gray-800 border-t dark:border-gray-700">
        <div class="max-w-6xl mx-auto px-4 py-4">
            <div class="flex space-x-3">
                <div class="flex-1 relative">
                    <input type="text" 
                           id="userInput" 
                           placeholder="Ask about Makerere University..." 
                           class="w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-600 focus:border-transparent pr-12 placeholder-gray-400 dark:placeholder-gray-500"
                           autocomplete="off"
                           autofocus>
                    <button onclick="sendMessage()" 
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
                        <i class="fas fa-paper-plane text-xl"></i>
                    </button>
                </div>
                <button onclick="voiceInput()" 
                        class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 w-14 h-14 rounded-2xl flex items-center justify-center transition">
                    <i class="fas fa-microphone"></i>
                </button>
            </div>
            <div class="flex justify-between items-center mt-3">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-lightbulb mr-1"></i>
                    Press Enter to send • Shift+Enter for new line
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs px-3 py-1 bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-400 rounded-full">
                        <i class="fas fa-database mr-1"></i> Qdrant
                    </span>
                    <span class="text-xs px-3 py-1 bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-400 rounded-full">
                        <i class="fas fa-brain mr-1"></i> Ollama
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/markdown-it/13.0.1/markdown-it.min.js"></script>
    <script>
        const md = new markdownit();
        const messagesContainer = document.getElementById('messages');
        const userInput = document.getElementById('userInput');
        let isLoading = false;

        // Scroll to bottom of messages
        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Add message to chat
        function addMessage(content, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message-enter';
            
            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            if (isUser) {
                messageDiv.innerHTML = `
                    <div class="flex items-start space-x-3 flex-row-reverse space-x-reverse">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center">
                                <i class="fas fa-user text-white"></i>
                            </div>
                        </div>
                        <div class="flex-1 text-right">
                            <div class="bg-white dark:bg-gray-700 border dark:border-gray-600 rounded-2xl rounded-tr-none p-5 shadow-sm">
                                <p class="text-gray-800 dark:text-gray-100">${escapeHtml(content)}</p>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-2 mr-2">${time}</div>
                        </div>
                    </div>
                `;
            } else {
                const formattedContent = md.render(content);
                messageDiv.innerHTML = `
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center">
                                <i class="fas fa-robot text-primary-600 dark:text-primary-400"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="bg-white dark:bg-gray-700 border dark:border-gray-600 rounded-2xl rounded-tl-none p-5 shadow-sm">
                                <div class="prose prose-sm max-w-none dark:prose-dark">${formattedContent}</div>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-2 ml-2">${time}</div>
                        </div>
                    </div>
                `;
            }
            
            messagesContainer.appendChild(messageDiv);
            scrollToBottom();
        }

        // Show typing indicator
        function showTyping() {
            const typingDiv = document.createElement('div');
            typingDiv.id = 'typing-indicator';
            typingDiv.className = 'message-enter';
            typingDiv.innerHTML = `
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                            <i class="fas fa-robot text-primary-600"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="bg-white border rounded-2xl rounded-tl-none p-5 shadow-sm">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-gray-400 rounded-full animate-pulse"></div>
                                <div class="w-2 h-2 bg-gray-400 rounded-full animate-pulse delay-150"></div>
                                <div class="w-2 h-2 bg-gray-400 rounded-full animate-pulse delay-300"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            messagesContainer.appendChild(typingDiv);
            scrollToBottom();
        }

        // Remove typing indicator
        function removeTyping() {
            const typing = document.getElementById('typing-indicator');
            if (typing) typing.remove();
        }

        // Send message
        async function sendMessage() {
            const message = userInput.value.trim();
            if (!message || isLoading) return;
            
            userInput.value = '';
            addMessage(message, true);
            showTyping();
            isLoading = true;
            
            try {
                const response = await fetch('ask.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ question: message })
                });
                
                removeTyping();
                isLoading = false;
                
                const data = await response.json();
                
                if (data.success) {
                    addMessage(data.answer, false);
                    
                    // Add sources if available
                    if (data.sources && data.sources.length > 0) {
                        addSources(data.sources);
                    }
                } else {
                    addMessage(`Error: ${data.error}`, false);
                }
            } catch (error) {
                removeTyping();
                isLoading = false;
                addMessage('Sorry, there was an error connecting to the server.', false);
                console.error('Error:', error);
            }
        }

        // Add sources to chat
        function addSources(sources) {
            const sourcesDiv = document.createElement('div');
            sourcesDiv.className = 'message-enter mt-4';
            
            let sourcesHtml = `
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 mr-2"></i>
                        <span class="font-medium text-blue-700 dark:text-blue-300">Information Sources</span>
                    </div>
                    <div class="space-y-2">
            `;
            
            sources.forEach((source, index) => {
                sourcesHtml += `
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">${escapeHtml(source.source)}</span>
                            <span class="text-xs px-2 py-1 bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-400 rounded">${source.score}</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">${escapeHtml(source.content)}</p>
                    </div>
                `;
            });
            
            sourcesHtml += `
                    </div>
                    <div class="mt-3 pt-3 border-t border-blue-100 dark:border-blue-800 text-xs text-blue-600 dark:text-blue-400">
                        <i class="fas fa-database mr-1"></i> Retrieved from knowledge base
                    </div>
                </div>
            `;
            
            sourcesDiv.innerHTML = sourcesHtml;
            messagesContainer.appendChild(sourcesDiv);
            scrollToBottom();
        }

        // Quick question buttons
        function quickQuestion(question) {
            userInput.value = question;
            sendMessage();
        }

        // Clear chat
        function clearChat() {
            if (confirm('Are you sure you want to clear the chat history?')) {
                fetch('ask.php?action=clear', { method: 'POST' })
                    .then(() => {
                        location.reload();
                    });
            }
        }

        // Voice input (placeholder)
        function voiceInput() {
            alert('Voice input would be implemented here with Web Speech API');
        }

        // Helper functions
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Event listeners
        userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        // Initialize
        scrollToBottom();
    </script>
</body>
</html>