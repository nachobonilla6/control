<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - {{ $bot_id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
          extend: {
            colors: {
              indigo: {
                50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc', 400: '#818cf8',
                500: '#6366f1', 600: '#4f46e5', 700: '#4338ca', 800: '#3730a3', 900: '#312e81',
              },
            },
          },
        },
      };
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        #chatContainer { scroll-behavior: smooth; }
        .animate-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="h-full flex bg-slate-950 text-slate-200 overflow-hidden">

    <!-- Sidebar (ChatGPT style) -->
    <aside class="w-72 bg-slate-900 border-r border-slate-800 flex flex-col hidden md:flex">
        <div class="p-4 border-b border-slate-800 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 text-pink-400 hover:text-indigo-300 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="text-sm font-bold uppercase tracking-tighter">Back to System</span>
            </a>
            <form action="{{ route('chat.new') }}" method="POST">
                @csrf
                <button type="submit" class="p-2 hover:bg-slate-800 rounded-lg text-slate-400 hover:text-white transition-all" title="New Chat">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </form>
        </div>
        
        <div class="flex-1 overflow-y-auto p-3 space-y-2">
            <div class="text-[10px] font-bold text-slate-500 px-3 py-2 uppercase tracking-[0.2em]">Recent Conversations</div>
            @if(count($threads) > 0)
                @foreach($threads as $thread)
                    <a href="{{ route('dashboard.chat', ['bot' => $bot_id, 'chatId' => $thread->chat_id]) }}" 
                       class="block px-3 py-3 rounded-xl hover:bg-slate-800/50 cursor-pointer text-sm transition-all {{ $current_chat_id == $thread->chat_id ? 'bg-pink-600/10 text-pink-400 border border-pink-500/20 shadow-lg' : 'text-slate-400' }}">
                        <div class="truncate font-bold text-xs mb-1">{{ $thread->username ?? 'Anonymous' }}</div>
                        <div class="truncate text-[11px] opacity-75">{{ Str::limit($thread->message, 45, '...') }}</div>
                        <div class="truncate text-[9px] opacity-50 mt-1">ID: {{ Str::limit($thread->chat_id, 20, '...') }}</div>
                    </a>
                @endforeach
            @else
                <div class="px-3 py-10 text-center text-xs text-slate-600 italic">No history found</div>
            @endif
        </div>

        <div class="p-4 border-t border-slate-800 bg-slate-900/50">
            <div class="flex items-center space-x-3 p-2 rounded-xl bg-slate-800/30 border border-slate-800">
                <div class="w-9 h-9 rounded-lg bg-pink-600 flex items-center justify-center text-white text-xs font-black shadow-lg shadow-pink-600/20 uppercase">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex flex-col overflow-hidden">
                    <span class="text-sm font-bold text-slate-200 truncate">{{ Auth::user()->name }}</span>
                    <span class="text-[10px] text-slate-500 truncate uppercase tracking-tighter">Administrator</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col relative h-full">
        <!-- Navbar -->
        <nav class="h-16 bg-slate-950/80 backdrop-blur-md border-b border-slate-900/50 flex items-center justify-between px-6 sticky top-0 z-30">
            <div class="flex items-center space-x-3">
                <div class="md:hidden">
                     <a href="{{ route('dashboard') }}" class="text-slate-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                </div>
                <span class="text-xs font-bold uppercase tracking-widest text-pink-400">{{ str_replace('-', ' ', $bot_id) }}</span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard.chat') }}" class="flex items-center space-x-2 px-4 py-2 bg-pink-600/10 hover:bg-pink-600/20 border border-pink-500/20 rounded-xl transition-all group">
                    <svg class="w-4 h-4 text-pink-400 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span class="text-[10px] font-black text-pink-400 uppercase tracking-widest">Copilot</span>
                </a>
                @include('dashboard.components.theme-toggle')
                <button id="notifBtn" class="relative p-2 text-slate-400 hover:text-pink-400 transition-colors focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span id="notifBadge" class="absolute top-1 right-1 bg-pink-600 text-[10px] font-bold text-white rounded-full w-4 h-4 flex items-center justify-center border-2 border-slate-950 hidden">0</span>
                </button>
                <span class="w-px h-6 bg-slate-800"></span>
                <div class="relative">
                    <button id="accountBtn" class="flex items-center justify-center w-8 h-8 bg-slate-800/50 border border-slate-800 rounded-full hover:border-pink-500/30 transition-all focus:outline-none overflow-hidden group">
                        @if(Auth::user()->profile_photo_url)
                            <img src="{{ asset(Auth::user()->profile_photo_url) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-pink-600 flex items-center justify-center text-white text-[10px] font-black uppercase">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                    </button>
                    <!-- Simple dropdown for chat page -->
                    <div id="accountDropdown" class="absolute right-0 mt-3 w-48 bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl opacity-0 invisible transition-all z-50 p-2">
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 text-xs text-red-500 hover:bg-red-500/10 rounded-xl transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <span class="font-bold uppercase tracking-widest">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Chat Area -->
        <div id="chatContainer" class="flex-1 overflow-y-auto p-4 md:p-10 space-y-10">
            <div id="messagesWrapper" class="w-full max-w-4xl mx-auto space-y-12 pb-20">
                @if(count($chat_history) == 0)
                    <div id="emptyState" class="flex flex-col items-center justify-center space-y-6 opacity-30 mt-20">
                        <div class="w-20 h-20 rounded-3xl bg-pink-600/10 flex items-center justify-center text-pink-400 border border-pink-500/20 rotate-12 transition-transform hover:rotate-0 duration-500">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" stroke-width="1.5"/></svg>
                        </div>
                        <div class="text-center">
                            <h2 class="text-2xl font-black text-slate-400 uppercase tracking-tighter italic">How can I help you?</h2>
                            <p class="text-xs text-slate-600 uppercase tracking-widest mt-2">New thread initialized</p>
                        </div>
                    </div>
                @else
                    @foreach($chat_history as $msg)
                        <div class="flex items-start space-x-6 {{ $msg->role == 'user' ? 'justify-end' : '' }}">
                            @if($msg->role != 'user')
                                <div class="w-9 h-9 rounded-xl bg-pink-600 flex items-center justify-center text-white text-[11px] font-black flex-shrink-0 shadow-lg shadow-pink-600/30">JD</div>
                            @endif
                            <div class="relative group max-w-[80%]">
                                <div class="px-5 py-3 rounded-2xl transition-all {{ $msg->role == 'user' ? 'bg-pink-600/10 border border-pink-500/30 text-slate-100 shadow-xl shadow-pink-900/10' : 'bg-transparent text-slate-300' }}">
                                    <p class="leading-relaxed text-sm whitespace-pre-wrap">{{ $msg->message }}</p>
                                </div>
                                <span class="absolute -bottom-6 {{ $msg->role == 'user' ? 'right-2' : 'left-2' }} text-[9px] text-slate-600 font-bold uppercase tracking-tighter opacity-0 group-hover:opacity-100 transition-opacity">
                                    {{ $msg->created_at->diffForHumans() }}
                                </span>
                            </div>
                            @if($msg->role == 'user')
                                <div class="w-9 h-9 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-pink-400 text-[11px] font-black flex-shrink-0 shadow-xl">U</div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-6 md:p-10 bg-gradient-to-t from-slate-950 via-slate-950 to-transparent sticky bottom-0 z-20">
            <div class="max-w-3xl mx-auto relative">
                <form action="{{ route('chat') }}" method="POST" id="chatForm">
                    @csrf
                    <input type="hidden" name="bot_id" value="{{ $bot_id }}">
                    <div class="relative flex items-center">
                        <input type="text" name="message" id="chatInput" required autocomplete="off" 
                               class="w-full bg-slate-900 border border-slate-800 rounded-2xl px-6 py-5 pr-16 focus:outline-none focus:ring-2 focus:ring-pink-600/50 focus:border-pink-600 transition-all shadow-[0_20px_50px_rgba(0,0,0,0.5)] placeholder-slate-600 text-slate-200" 
                               placeholder="Type a message to {{ str_replace('-', ' ', $bot_id) }}...">
                        <button type="submit" id="sendBtn" class="absolute right-3.5 p-2.5 bg-pink-600 hover:bg-pink-500 text-white rounded-xl transition-all shadow-lg active:scale-95 disabled:opacity-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                </form>
                <div class="flex justify-center space-x-6 mt-4 opacity-20 hover:opacity-100 transition-opacity">
                    <span class="text-[9px] text-slate-500 uppercase tracking-[0.3em] font-bold">Secure Pipeline</span>
                    <span class="text-[9px] text-slate-500 uppercase tracking-[0.3em] font-bold">Encrypted Endpoints</span>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Notificaciones -->
    <div id="notifModal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md transition-all duration-300">
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-3xl shadow-[0_50px_100px_rgba(0,0,0,0.5)] overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="notifContainer">
                <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-11 h-11 rounded-2xl bg-pink-600/10 flex items-center justify-center text-pink-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-white uppercase tracking-tighter">Console Notifications</h2>
                            <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Latest Broadcasts</p>
                        </div>
                    </div>
                    <button id="closeModal" class="p-2 hover:bg-slate-800 rounded-xl text-slate-500 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>
                <div id="notifList" class="max-h-[50vh] overflow-y-auto p-4 space-y-3">
                    <div class="flex items-center justify-center py-10">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-pink-500"></div>
                    </div>
                </div>
                <div class="p-6 bg-slate-900 border-t border-slate-800 text-center">
                    <button class="text-[10px] font-black text-pink-400 hover:text-indigo-300 transition-colors uppercase tracking-[0.2em]">Mark All Visualized</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const btn = document.getElementById('notifBtn');
        const modal = document.getElementById('notifModal');
        const container = document.getElementById('notifContainer');
        const list = document.getElementById('notifList');
        const close = document.getElementById('closeModal');
        const badge = document.getElementById('notifBadge');
        const chatContainer = document.getElementById('chatContainer');
        const chatForm = document.getElementById('chatForm');
        const sendBtn = document.getElementById('sendBtn');
        const chatInput = document.getElementById('chatInput');

        const messagesWrapper = document.getElementById('messagesWrapper');

        const appendMessage = (role, message) => {
            const emptyState = document.getElementById('emptyState');
            if (emptyState) emptyState.remove();

            const isUser = role === 'user';
            const html = `
                <div class="flex items-start space-x-6 ${isUser ? 'justify-end' : ''} animate-in fade-in slide-in-from-bottom-4 duration-500">
                    ${!isUser ? '<div class="w-9 h-9 rounded-xl bg-pink-600 flex items-center justify-center text-white text-[11px] font-black flex-shrink-0 shadow-lg shadow-pink-600/30">JD</div>' : ''}
                    <div class="relative group max-w-[80%]">
                        <div class="px-5 py-3 rounded-2xl transition-all ${isUser ? 'bg-pink-600/10 border border-pink-500/30 text-slate-100 shadow-xl shadow-pink-900/10' : 'bg-transparent text-slate-300'}">
                            <p class="leading-relaxed text-sm whitespace-pre-wrap">${message}</p>
                        </div>
                        <span class="absolute -bottom-6 ${isUser ? 'right-2' : 'left-2'} text-[9px] text-slate-600 font-bold uppercase tracking-tighter opacity-100">
                            Just now
                        </span>
                    </div>
                    ${isUser ? '<div class="w-9 h-9 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-pink-400 text-[11px] font-black flex-shrink-0 shadow-xl">U</div>' : ''}
                </div>
            `;
            messagesWrapper.insertAdjacentHTML('beforeend', html);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        };

        const showTyping = () => {
            const html = `
                <div id="typingIndicator" class="flex items-start space-x-6 animate-in fade-in duration-300">
                    <div class="w-9 h-9 rounded-xl bg-pink-600 flex items-center justify-center text-white text-[11px] font-black flex-shrink-0 shadow-lg shadow-pink-600/30">JD</div>
                    <div class="px-5 py-3 bg-transparent text-slate-500 italic text-sm">
                        <div class="flex space-x-1 mt-2">
                            <div class="w-1.5 h-1.5 bg-pink-500 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                            <div class="w-1.5 h-1.5 bg-pink-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                            <div class="w-1.5 h-1.5 bg-pink-500 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                        </div>
                    </div>
                </div>
            `;
            messagesWrapper.insertAdjacentHTML('beforeend', html);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        };

        const removeTyping = () => {
            const indicator = document.getElementById('typingIndicator');
            if (indicator) indicator.remove();
        };

        // Scroll to bottom
        chatContainer.scrollTop = chatContainer.scrollHeight;

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = chatInput.value.trim();
            if (!message) return;

            // Optimistic UI
            appendMessage('user', message);
            chatInput.value = '';
            sendBtn.disabled = true;

            showTyping();

            try {
                const response = await fetch('https://n8n.srv1137974.hstgr.cloud/webhook-test/776f766b-f300-4727-a778-c3be64254f8f', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message: message, bot_id: '{{ $bot_id }}' })
                });

                const data = await response.json();
                removeTyping();

                if (data.success) {
                    appendMessage('assistant', data.content);
                } else {
                    appendMessage('system', 'Error: ' + (data.error || 'Identity Link Interrupted'));
                }
            } catch (error) {
                removeTyping();
                appendMessage('system', 'Connectivity Failure: Unable to reach n8n node.');
            } finally {
                sendBtn.disabled = false;
                chatInput.focus();
            }
        });

        async function fetchNotifs() {
            try {
                const res = await fetch('{{ route('notifications') }}');
                const data = await res.json();
                if (data.length > 0) {
                    badge.innerText = data.length;
                    badge.classList.remove('hidden');
                    list.innerHTML = data.map(n => `
                        <div class="p-4 bg-slate-800/20 hover:bg-pink-600/5 border border-slate-800 rounded-2xl transition-all">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[9px] font-black text-pink-500 uppercase tracking-widest">${n.origen}</span>
                                <span class="text-[9px] text-slate-600 font-bold">${new Date(n.created_at).toLocaleDateString()}</span>
                            </div>
                            <h3 class="text-sm font-bold text-slate-200 mb-1 leading-tight">${n.titulo}</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">${n.texto}</p>
                        </div>
                    `).join('');
                } else {
                    list.innerHTML = '<div class="text-center py-10 text-slate-600 text-[10px] uppercase font-bold">Clear Records</div>';
                }
            } catch (e) {
                list.innerHTML = '<div class="text-center py-10 text-red-500 text-[10px] uppercase font-bold">Data Link Interrupted</div>';
            }
        }

        btn.addEventListener('click', () => {
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                container.classList.remove('scale-95', 'opacity-0');
            }, 10);
            fetchNotifs();
        });

        const hideModal = () => {
            container.classList.add('scale-95', 'opacity-0');
            modal.classList.add('opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 300);
        };

        close.addEventListener('click', hideModal);
        modal.addEventListener('click', (e) => { if(e.target === modal) hideModal(); });

        const accountBtn = document.getElementById('accountBtn');
        const accountDropdown = document.getElementById('accountDropdown');

        function closeAllDropdowns() {
            if (accountDropdown) accountDropdown.classList.add('opacity-0', 'invisible');
        }

        if (accountBtn) {
            accountBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                const isVisible = !accountDropdown.classList.contains('invisible');
                if (!isVisible) {
                    accountDropdown.classList.remove('opacity-0', 'invisible');
                } else {
                    accountDropdown.classList.add('opacity-0', 'invisible');
                }
            });
        }

        document.addEventListener('click', (e) => {
            if (accountDropdown && !accountDropdown.contains(e.target)) {
                closeAllDropdowns();
            }
        });

        fetchNotifs();
    </script>
</body>
</html>
