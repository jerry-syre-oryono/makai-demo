<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAKAI - Makerere AI Assistant</title>
    <link rel="stylesheet" href="./styles/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen font-sans selection:bg-primary-200 selection:text-primary-900 overflow-x-hidden relative">

    <!-- Background Morphing Blobs -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-emerald-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed w-full z-40 top-0 start-0 border-b border-white/20 glass transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center group cursor-pointer">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white shadow-lg shadow-primary-500/30 mr-3 group-hover:scale-105 transition-transform duration-300">
                            <i class="fas fa-graduation-cap text-lg"></i>
                        </div>
                        <div>
                            <span class="text-2xl font-bold text-gray-900 tracking-tight">MAKAI</span>
                            <span class="block text-xs font-medium text-primary-600 tracking-wide uppercase">Makerere AI Assistant</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="ingest.php" class="text-gray-600 hover:text-primary-600 px-4 py-2 text-sm font-semibold transition-colors flex items-center bg-white/50 hover:bg-white rounded-lg border border-transparent hover:border-gray-100">
                        <i class="fas fa-database mr-2 opacity-70"></i> Ingest Data
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Wrapper -->
    <div class="pt-28 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        
        <!-- Hero Section -->
        <div class="text-center py-16 lg:py-24 relative">
            <div class="animate-fade-in">
                <div class="inline-flex items-center bg-white/80 backdrop-blur-sm border border-primary-100 rounded-full px-4 py-1.5 shadow-sm mb-8 animate-slide-up">
                    <span class="flex h-2 w-2 relative mr-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                    </span>
                    <span class="text-xs font-semibold text-primary-700 tracking-wide uppercase">AI-Powered Campus Guide</span>
                </div>
                
                <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 mb-6 tracking-tight leading-tight animate-slide-up" style="animation-delay: 0.1s;">
                    Simplify Your <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-emerald-500">Makerere Journey</span>
                </h1>
                
                <p class="mt-6 text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed animate-slide-up" style="animation-delay: 0.2s;">
                    Instant answers about admissions, fees, courses, and campus life. Powered by advanced local AI for privacy and speed.
                </p>
                
                <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4 animate-slide-up" style="animation-delay: 0.3s;">
                    <button onclick="document.getElementById('chatToggle').click()" class="px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-2xl shadow-lg shadow-primary-500/30 hover:shadow-primary-500/40 transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center">
                        <i class="fas fa-comment-dots mr-2"></i> Start Chatting
                    </button>
                    <a href="#features" class="px-8 py-4 bg-white/80 hover:bg-white text-gray-700 font-bold rounded-2xl border border-gray-200 hover:border-gray-300 shadow-sm hover:shadow-md transform hover:-translate-y-1 transition-all duration-300 backdrop-blur-sm flex items-center justify-center">
                        <i class="fas fa-info-circle mr-2 opacity-50"></i> Learn More
                    </a>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div id="features" class="grid md:grid-cols-2 gap-8 mb-20 animate-slide-up" style="animation-delay: 0.4s;">
            <div class="glass-card rounded-3xl p-8 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="fas fa-search text-9xl text-primary-500 transform rotate-12"></i>
                </div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center mb-6 text-primary-600 shadow-sm">
                        <i class="fas fa-bolt text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Instant Information</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Get accurate answers about admissions, courses, fees, campus life, and more directly from the official knowledge base.
                    </p>
                </div>
            </div>
            
            <div class="glass-card rounded-3xl p-8 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="fas fa-brain text-9xl text-emerald-500 transform -rotate-12"></i>
                </div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center mb-6 text-emerald-600 shadow-sm">
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Private & Secure</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Powered by local AI with Ollama and vector search with Qdrant. Your queries are processed locally for maximum privacy.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Knowledge Base Status -->
        <div class="glass rounded-3xl p-8 md:p-10 mb-12 animate-slide-up" style="animation-delay: 0.5s;">
            <div class="flex items-center justify-between mb-8">
                <div>
                   <h2 class="text-2xl font-bold text-gray-900">Knowledge Base</h2>
                   <p class="text-gray-500 text-sm mt-1">Active documents loaded in the vector database</p>
                </div>
                <span class="px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-xs font-bold uppercase tracking-wider">
                    <?php echo count(glob("knowledge/*.{txt,pdf}", GLOB_BRACE)); ?> Documents
                </span>
            </div>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <?php
                $files = glob("knowledge/*.{txt,pdf}", GLOB_BRACE);
                foreach ($files as $file):
                    $filename = basename($file);
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    $size = round(filesize($file) / 1024, 1);
                    $icon = $ext === 'pdf' ? 'fa-file-pdf' : 'fa-file-alt';
                    $color = $ext === 'pdf' ? 'text-red-500' : 'text-primary-500';
                ?>
                <div class="bg-white/50 hover:bg-white rounded-xl p-4 border border-white/40 hover:border-white shadow-sm hover:shadow-md transition-all duration-300 text-center group cursor-default">
                    <div class="mb-3 transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas <?php echo $icon; ?> <?php echo $color; ?> text-3xl opacity-80"></i>
                    </div>
                    <p class="font-semibold text-gray-800 text-sm truncate px-2" title="<?php echo $filename; ?>"><?php echo $filename; ?></p>
                    <p class="text-xs text-gray-500 mt-1 font-medium"><?php echo $size; ?> KB</p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Floating Chat Button -->
    <button id="chatToggle" 
            class="fixed bottom-8 right-8 bg-gradient-to-r from-primary-600 to-primary-500 text-white w-16 h-16 rounded-2xl shadow-xl shadow-primary-600/30 hover:shadow-2xl hover:shadow-primary-600/40 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center z-50 group">
        <i class="fas fa-comment-dots text-2xl group-hover:scale-110 transition-transform"></i>
        <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-4 w-4">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500"></span>
        </span>
    </button>

    <!-- Chat Interface -->
    <div id="chatInterface" 
         class="fixed bottom-28 right-8 w-[24rem] md:w-[28rem] h-[36rem] glass-dark rounded-3xl shadow-2xl flex flex-col transition-all duration-500 transform translate-y-12 opacity-0 invisible z-50 overflow-hidden border border-white/20">
        
        <!-- Chat Header -->
        <div class="bg-gradient-to-r from-primary-600 to-emerald-600 text-white p-5 flex justify-between items-center shadow-md relative overflow-hidden">
            <div class="absolute inset-0 bg-white/10 pattern-dots opacity-20"></div>
            <div class="flex items-center relative z-10">
                <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center mr-3 border border-white/20">
                    <i class="fas fa-robot text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg leading-tight">MAKAI Assistant</h3>
                    <div class="flex items-center text-primary-100 text-xs mt-0.5">
                        <span class="w-1.5 h-1.5 bg-green-300 rounded-full mr-1.5 animate-pulse"></span>
                        Online & Ready
                    </div>
                </div>
            </div>
            <button id="closeChat" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors relative z-10">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        
        <!-- Chat Messages Container -->
        <div id="chatMessages" class="flex-1 p-5 overflow-y-auto bg-gray-50/50 space-y-4 scroll-smooth">
            <!-- Welcome message -->
            <div class="animate-fade-in">
                <div class="ai-bubble chat-bubble relative group">
                    <div class="absolute -left-2 top-0 w-2 h-2 bg-white skew-x-[20deg] rounded-bl"></div>
                    <div class="flex items-center mb-2">
                        <div class="w-6 h-6 rounded-lg bg-primary-100 flex items-center justify-center mr-2 text-primary-600">
                            <i class="fas fa-graduation-cap text-xs"></i>
                        </div>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">MAKAI</span>
                    </div>
                    <p class="text-sm text-gray-700">Hello! I'm MAKAI, your Makerere University assistant. How can I help you today?</p>
                    <div class="text-[10px] text-gray-400 mt-2 text-right">Just now</div>
                </div>
            </div>
            
            <!-- Example questions -->
            <div class="py-4">
                <p class="text-xs font-semibold text-gray-400 mb-3 ml-1 uppercase tracking-wider">Suggested Questions</p>
                <div class="flex flex-wrap gap-2">
                    <button class="example-question text-xs bg-white border border-gray-200 hover:border-primary-300 hover:bg-primary-50 text-gray-600 hover:text-primary-700 px-4 py-2 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md text-left">
                        Admission requirements?
                    </button>
                    <button class="example-question text-xs bg-white border border-gray-200 hover:border-primary-300 hover:bg-primary-50 text-gray-600 hover:text-primary-700 px-4 py-2 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md text-left">
                        How much is tuition?
                    </button>
                    <button class="example-question text-xs bg-white border border-gray-200 hover:border-primary-300 hover:bg-primary-50 text-gray-600 hover:text-primary-700 px-4 py-2 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md text-left">
                        Recover my password?
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Chat Input -->
        <div class="p-4 bg-white border-t border-gray-100">
            <div class="relative">
                <input type="text" 
                       id="chatInput" 
                       placeholder="Ask about Makerere University..." 
                       class="w-full bg-gray-50 border-0 rounded-xl pl-4 pr-12 py-4 focus:ring-2 focus:ring-primary-100 focus:bg-white transition-all text-sm shadow-inner placeholder-gray-400"
                       autocomplete="off">
                <button id="sendMessage" 
                        class="absolute right-2 top-2 bottom-2 w-10 bg-primary-600 hover:bg-primary-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                    <i class="fas fa-paper-plane text-sm"></i>
                </button>
            </div>
            <p class="text-[10px] text-gray-400 mt-3 text-center flex items-center justify-center">
                <i class="fas fa-shield-alt mr-1.5 text-primary-400"></i> Encrypted local processing
            </p>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/markdown-it/13.0.1/markdown-it.min.js"></script>
    <script>
        const md = new markdownit();
        let isChatOpen = false;
        
        // Chat toggle
        document.getElementById('chatToggle').addEventListener('click', () => {
            const chat = document.getElementById('chatInterface');
            isChatOpen = !isChatOpen;
            
            if (isChatOpen) {
                chat.classList.remove('invisible', 'opacity-0', 'translate-y-12');
                chat.classList.add('visible', 'opacity-100', 'translate-y-0');
                document.getElementById('chatInput').focus();
            } else {
                chat.classList.remove('visible', 'opacity-100', 'translate-y-0');
                chat.classList.add('invisible', 'opacity-0', 'translate-y-12');
            }
        });
        
        // Close chat
        document.getElementById('closeChat').addEventListener('click', () => {
            document.getElementById('chatInterface').classList.remove('visible', 'opacity-100', 'translate-y-0');
            document.getElementById('chatInterface').classList.add('invisible', 'opacity-0', 'translate-y-12');
            isChatOpen = false;
        });
        
        // Example question buttons
        document.querySelectorAll('.example-question').forEach(button => {
            button.addEventListener('click', () => {
                document.getElementById('chatInput').value = button.textContent.trim();
                sendMessage();
            });
        });
        
        // Send message on Enter
        document.getElementById('chatInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
        
        // Send message button
        document.getElementById('sendMessage').addEventListener('click', sendMessage);
        
        async function sendMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value.trim();
            
            if (!message) return;
            
            // Add user message to chat
            addMessageToChat('user', message);
            input.value = '';
            
            // Show typing indicator
            const typingId = showTypingIndicator();
            
            try {
                const response = await fetch('ask.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ question: message })
                });
                
                removeTypingIndicator(typingId);
                
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.error || 'Server error');
                }
                
                const data = await response.json();
                
                if (data.success) {
                    // Add AI response to chat
                    addMessageToChat('assistant', data.answer);
                    
                    // Show sources if available
                    if (data.sources && data.sources.length > 0) {
                        addSourcesToChat(data.sources);
                    }
                } else {
                    addMessageToChat('assistant', `Error: ${data.error}`);
                }
            } catch (error) {
                removeTypingIndicator(typingId);
                let errorMessage = 'Sorry, there was an error connecting to the server.';
                
                if (error.message.includes('timeout') || error.name === 'AbortError') {
                    errorMessage += ' The AI model may be loading for the first time, which can take up to 2 minutes. Please try again.';
                } else if (error.message) {
                    errorMessage += ` Error: ${error.message}`;
                }
                
                addMessageToChat('assistant', errorMessage);
                console.error('Error:', error);
            }
        }
        
        function addMessageToChat(sender, message) {
            const chatMessages = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'mb-4 animate-fade-in group';
            
            if (sender === 'user') {
                messageDiv.innerHTML = `
                    <div class="flex justify-end">
                        <div class="user-bubble chat-bubble relative shadow-lg shadow-primary-500/20">
                            <div class="absolute -right-2 top-0 w-2 h-2 bg-primary-600 skew-x-[-20deg] rounded-br"></div>
                            <div class="flex items-center justify-end mb-1">
                                <span class="text-xs font-bold text-primary-100 mr-2 uppercase tracking-wide">You</span>
                            </div>
                            <p class="text-sm font-medium">${escapeHtml(message)}</p>
                            <div class="text-[10px] text-primary-200 text-right mt-1.5 opacity-0 group-hover:opacity-100 transition-opacity">${getCurrentTime()}</div>
                        </div>
                    </div>
                `;
            } else {
                const formattedMessage = md.render(message);
                messageDiv.innerHTML = `
                    <div class="ai-bubble chat-bubble relative shadow-md">
                        <div class="absolute -left-2 top-0 w-2 h-2 bg-white skew-x-[20deg] rounded-bl"></div>
                        <div class="flex items-center mb-2">
                            <div class="w-6 h-6 rounded-lg bg-primary-100 flex items-center justify-center mr-2 text-primary-600">
                                <i class="fas fa-robot text-xs"></i>
                            </div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">MAKAI</span>
                        </div>
                        <div class="text-sm prose prose-sm max-w-none text-gray-700 prose-headings:text-primary-800 prose-a:text-primary-600 prose-strong:text-primary-700">${formattedMessage}</div>
                        <div class="text-[10px] text-gray-400 mt-2 opacity-0 group-hover:opacity-100 transition-opacity">${getCurrentTime()}</div>
                    </div>
                `;
            }
            
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        function addSourcesToChat(sources) {
            const chatMessages = document.getElementById('chatMessages');
            const sourcesDiv = document.createElement('div');
            sourcesDiv.className = 'mb-2 animate-fade-in ml-2 max-w-[85%]';
            
            let sourcesHtml = `
                    <div class="flex items-center mb-2 pl-1">
                        <i class="fas fa-quote-right text-primary-400 mr-2 text-xs"></i>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Sources</span>
                    </div>
                    <div class="space-y-2">
            `;
            
            sources.forEach((source, index) => {
                sourcesHtml += `
                    <div class="text-xs text-gray-600 p-3 bg-white/60 rounded-xl border border-gray-100 hover:border-primary-200 hover:bg-white hover:shadow-sm transition-all duration-200 cursor-help" title="Score: ${source.score}">
                        <div class="flex justify-between items-start mb-1">
                            <span class="font-bold text-primary-700 truncate w-3/4">${escapeHtml(source.source)}</span>
                            <span class="text-[10px] px-1.5 py-0.5 bg-green-100 text-green-700 rounded-md font-mono">${Math.round(source.score * 100)}%</span>
                        </div>
                        <p class="text-gray-500 italic line-clamp-2 leading-relaxed">"...${escapeHtml(source.content)}..."</p>
                    </div>
                `;
            });
            
            sourcesHtml += `
                    </div>
            `;
            
            sourcesDiv.innerHTML = sourcesHtml;
            chatMessages.appendChild(sourcesDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        function showTypingIndicator() {
            const chatMessages = document.getElementById('chatMessages');
            const typingDiv = document.createElement('div');
            typingDiv.id = 'typing-indicator';
            typingDiv.className = 'mb-4 animate-fade-in';
            typingDiv.innerHTML = `
                <div class="ai-bubble chat-bubble w-20 relative">
                     <div class="absolute -left-2 top-0 w-2 h-2 bg-white skew-x-[20deg] rounded-bl"></div>
                    <div class="flex items-center justify-center py-1">
                        <div class="flex space-x-1.5">
                            <div class="w-1.5 h-1.5 bg-primary-400 rounded-full animate-bounce"></div>
                            <div class="w-1.5 h-1.5 bg-primary-400 rounded-full animate-bounce delay-100"></div>
                            <div class="w-1.5 h-1.5 bg-primary-400 rounded-full animate-bounce delay-200"></div>
                        </div>
                    </div>
                </div>
            `;
            
            chatMessages.appendChild(typingDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            return 'typing-indicator';
        }
        
        function removeTypingIndicator(id) {
            const typing = document.getElementById(id);
            if (typing) {
                typing.remove();
            }
        }
        
        function getCurrentTime() {
            const now = new Date();
            return now.getHours().toString().padStart(2, '0') + ':' + 
                   now.getMinutes().toString().padStart(2, '0');
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Auto-open chat on page load after 3 seconds
        setTimeout(() => {
            if (!isChatOpen) {
                document.getElementById('chatToggle').click();
            }
        }, 1500);
    </script>
</body>
</html>