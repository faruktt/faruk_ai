<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faruk AI - Smart Search</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="data:image/svg+xml,
        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 320 512'>
        <path fill='%230066ff' d='M296 160H180.6l31.1-144c3.5-16.1-12.8-28.6-27.1-20.6l-160 96C17.8 98.7 16 104.2 16 110c0 9.9 8.1 18 18 18h115.4l-31.1 144c-3.5 16.1 12.8 28.6 27.1 20.6l160-96c6.8-4.3 8.6-9.8 8.6-15.6c0-9.9-8.1-18-18-18z'/>
        </svg>">


    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        #sidebar { transition: transform 0.3s ease-in-out; }

        pre {
            background-color: #0d1117;
            border-radius: 8px;
            margin: 1rem 0;
            position: relative;
            padding: 1rem;
            overflow-x: auto;
        }
        code { font-family: 'Fira Code', monospace; font-size: 0.9rem; }
        .markdown-content h1, .markdown-content h2 { font-weight: bold; margin-bottom: 10px; }
        .markdown-content p { margin-bottom: 15px; }
        .copy-code-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(255, 255, 255, 0.1);
            color: #ccc;
            border: none;
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
            transition: 0.2s;
        }
        .copy-code-btn:hover { background: rgba(255, 255, 255, 0.2); color: white; }
    </style>
</head>
<body class="bg-white font-sans flex h-screen overflow-hidden">

    <!-- Sidebar Overlay (Mobile Only) -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->

    <aside id="sidebar" class="w-72 bg-gray-50 border-r border-gray-200 flex flex-col fixed inset-y-0 left-0 z-50 transform -translate-x-full md:translate-x-0 md:static md:flex h-full shrink-0">

        <!-- 1. Sidebar Header (Top) -->
        <div class="p-5 border-b border-gray-100 flex justify-between items-center">
            <a href="{{ route('search.index') }}" class="text-xl font-bold text-blue-600 flex items-center">
                <i class="fa-solid fa-bolt mr-2"></i> Faruk AI
            </a>
            <!-- Close Button for Mobile -->
            <button onclick="toggleSidebar()" class="md:hidden text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
        </div>

        <!-- 2. Recent Searches (Middle - Flexible space) -->
        <div class="flex-1 overflow-y-auto p-4 custom-scrollbar">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4 px-2">Recent Searches</h3>
            <div class="space-y-1">
                 <a href="{{ route('search.index') }}"
                    class="flex items-center gap-2 p-3 text-sm text-gray-700 hover:bg-gray-200 rounded-xl transition font-medium">
                        <i class="fa-solid fa-pen-to-square text-gray-500"></i>
                        <span>New Chat</span>
                 </a>
                @auth
                    @foreach($history as $item)
                        <a href="{{ route('search.history', $item->conversation_id) }}"
                        class="block p-3 text-sm {{ ($currentConversationId ?? '') == $item->conversation_id ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-600' }} hover:bg-gray-200 rounded-xl truncate transition">
                            <i class="fa-regular fa-message mr-2 text-gray-400"></i> {{ $item->query }}
                        </a>
                        @if(isset($msg->file_name) && $msg->file_name)
                            <div class="mt-2 inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs border border-blue-100">
                                <i class="fa-solid fa-file-image"></i> {{ $msg->file_name }} (Analyzed)
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="p-4 bg-blue-50 rounded-2xl text-center">
                        <p class="text-xs text-blue-600 font-medium italic">Login to save history.</p>
                    </div>
                @endauth
            </div>
        </div>

        <!-- 3. Account Section (Bottom Footer) -->
        @auth
            <div class="p-4 border-t border-gray-200 bg-gray-50/50">
                <div class="flex items-center space-x-3 p-3 bg-white rounded-2xl border border-gray-200 shadow-sm hover:border-blue-200 transition-colors">
                    <!-- User Avatar/Initial -->
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-lg shadow-blue-100 shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <!-- User Info -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-tighter">Unerified User</p>
                    </div>
                </div>
            </div>
        @endauth
    </aside>

    <main class="flex-1 flex flex-col relative min-w-0 h-screen bg-white">

        <!-- Header -->
        <header class="flex justify-between items-center px-4 md:px-6 py-4 bg-white/80 backdrop-blur-md z-30 shrink-0 border-b md:border-none">
            <!-- Sidebar Toggle (Mobile Only - md:hidden added) -->
            <button onclick="toggleSidebar()" class="md:hidden text-gray-600 hover:bg-gray-100 p-2 rounded-lg transition">
                <i class="fa-solid fa-bars-staggered text-xl"></i>
            </button>

            <!-- Logo (Centered on Mobile) -->
            <div class="absolute left-1/2 -translate-x-1/2 md:hidden">
                <a href="{{ route('search.index') }}" class="text-lg font-bold text-gray-800">Faruk AI</a>
            </div>

            <!-- Desktop Logo Replacement (Only for desktop to maintain space) -->
            <div class="hidden md:block"></div>

            <div class="flex items-center space-x-2">
                @auth
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button type="submit" class="text-xs font-bold text-gray-500 hover:text-red-500 uppercase tracking-tighter">Log Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="bg-black text-white px-4 py-1.5 rounded-full text-xs font-medium transition active:scale-95">Login</a>
                @endauth
            </div>
        </header>

        <!-- Chat Content Area -->
        <div id="chat-container" class="flex-1 overflow-y-auto custom-scrollbar">
            <div class="max-w-4xl mx-auto px-4 md:px-12 pt-8 pb-52">

                @php
                    $messages = $conversationMessages ?? (session('result') ? collect([session('result')]) : collect());
                @endphp

                @if($messages->count() > 0)
                    @foreach($messages as $msg)
                        <div class="mb-10 animate-fade-in">
                            <h2 class="text-xl md:text-3xl font-bold text-gray-900 leading-tight">{{ $msg->query }}</h2>
                        </div>


                        <div class="flex gap-4 md:gap-6 mb-16 animate-fade-in">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center shrink-0 shadow-lg">
                                <i class="fa-solid fa-bolt text-sm"></i>
                            </div>
                            <div class="flex-1 overflow-hidden">
                                    @if(isset($msg->file_name) && $msg->file_name)
                                        <div class="mt-4">
                                            @php
                                                $extension = pathinfo($msg->file_name, PATHINFO_EXTENSION);
                                                $isImg = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']);
                                            @endphp

                                            @if($isImg && isset($msg->file_path))
                                                <!-- preview for image -->
                                                <div class="mb-2 text-xs font-bold text-blue-600 uppercase tracking-widest bg-blue-50 w-fit px-2 py-1 rounded">
                                                    <i class="fa-solid fa-image"></i> {{ $msg->file_name }}
                                                </div>
                                                <img src="{{ asset('storage/' . $msg->file_path) }}"
                                                    class="max-w-full md:max-w-sm rounded-2xl border border-gray-100 shadow-sm cursor-zoom-in"
                                                    onclick="window.open(this.src, '_blank')">
                                            @else
                                                <!-- name fro pdf -->
                                                <div class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700">
                                                    <i class="fa-solid {{ $extension == 'pdf' ? 'fa-file-pdf text-red-500' : 'fa-file-lines text-blue-500' }}"></i>
                                                    <span class="font-bold">{{ $msg->file_name }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                <!-- AI Response (Markdown processed via JS) -->
                                <div class="markdown-content prose prose-base md:prose-lg max-w-none text-gray-800 leading-relaxed" data-content="{{ $msg->answer }}">
                                    <!-- Rendered Content will appear here -->
                                </div>

                                <div class="mt-4 flex items-center gap-4">
                                    <!-- Copy Full Answer Button -->
                                    <button onclick="copyToClipboard(this)" data-text="{{ $msg->answer }}" class="text-gray-400 hover:text-blue-600 transition flex items-center gap-1 text-xs font-medium">
                                        <i class="fa-regular fa-copy"></i> Copy Answer
                                    </button>
                                </div>

                                @if(!empty($msg->sources))
                                    <div class="mt-8">
                                        <p class="text-[10px] text-gray-400 uppercase font-bold mb-2">Sources:</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($msg->sources as $source)
                                                <a href="{{ $source['url'] }}" target="_blank" class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 border border-gray-100 rounded-lg text-[10px] md:text-xs font-medium text-gray-600 hover:bg-white transition-all">
                                                    {{ parse_url($source['url'], PHP_URL_HOST) }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="border-b border-gray-50 mb-12"></div>
                    @endforeach
                @else
                    <!-- Welcome Screen -->
                    <div class="h-[60vh] flex flex-col items-center justify-center text-center px-4">
                        <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center text-3xl mb-6"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                        <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Faruk AI</h2>
                        <p class="text-gray-500 max-w-sm text-sm">Real-time answers powered by verified web sources.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Search Form -->

        <div class="absolute bottom-8 left-0 right-0 bg-gradient-to-t from-white via-white to-transparent pt-12 pb-6 md:pb-8 px-4">
            <div class="max-w-3xl mx-auto">
                <!-- Error Message Display -->
                @if(session('error'))
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl flex items-center gap-2 animate-fade-in">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                    </div>
                @endif

                @php
                    $limitReached = (isset($messageCount) && $messageCount >= 4);
                @endphp

                @if($limitReached)
                    <div class="animate-fade-in text-center">
                        <a href="{{ route('search.index') }}" class="inline-flex items-center justify-center gap-2 w-full p-4 bg-blue-600 text-white rounded-2xl font-bold shadow-xl hover:bg-blue-700 transition active:scale-95">
                            <i class="fa-solid fa-plus-circle"></i> Open New Chat to Continue
                        </a>
                        <p class="text-[10px] text-gray-400 mt-3 uppercase tracking-widest">Conversation limit reached (4/4)</p>
                    </div>
                @else
                    <form action="{{ route('search.ask') }}" method="POST" enctype="multipart/form-data" class="relative group bg-white border border-gray-200 rounded-2xl shadow-xl focus-within:ring-2 focus-within:ring-blue-500/30 transition-all">
                        @csrf
                        <input type="hidden" name="conversation_id" value="{{ $currentConversationId ?? '' }}">

                        <!-- File Preview Area -->
                        <div id="file-preview" class="hidden px-4 pt-3 flex items-center gap-2"></div>

                        <div class="flex items-center px-2">
                            <!-- Image Upload Button -->
                            <label class="cursor-pointer p-3 text-gray-400 hover:text-blue-600 transition">
                                <i class="fa-solid fa-camera text-xl"></i>
                                <input type="file" name="file_upload" id="file_upload" class="hidden" accept="image/*,.pdf,.txt" onchange="previewFile(this)">
                            </label>

                            <textarea
                                name="query_text"
                                rows="1"
                                placeholder="upload (2/day).."
                                oninput="autoResize(this)"
                                class="flex-1 p-4 bg-transparent outline-none text-base md:text-lg resize-none max-h-40 overflow-y-auto"></textarea>

                            <button type="submit" class="bg-blue-600 text-white w-10 h-10 md:w-12 md:h-12 rounded-xl flex items-center justify-center hover:bg-blue-700 transition shadow-lg active:scale-95 shrink-0 m-2">
                                <i class="fa-solid fa-arrow-up text-lg"></i>
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </main>
    <script>
    function autoResize(el) {
        el.style.height = "auto";
        el.style.height = (el.scrollHeight) + "px";
    }
    </script>
    <script>
    // ১. Markdown Render (Marked.js )
    document.querySelectorAll('.markdown-content').forEach(div => {
        const rawContent = div.getAttribute('data-content');
        if (rawContent) {
            div.innerHTML = marked.parse(rawContent);
        }
    });

    // ২. Syntax Highlighting
    function setupCodeBlocks() {
        document.querySelectorAll('pre code').forEach((block) => {
            hljs.highlightElement(block);

            const pre = block.parentElement;

            if (!pre.querySelector('.copy-code-btn')) {
                pre.style.position = 'relative';
                const btn = document.createElement('button');
                btn.className = 'copy-code-btn';
                btn.innerHTML = '<i class="fa-regular fa-copy"></i> Copy';

                btn.onclick = () => {
                    const codeText = block.innerText;
                    copyTextToClipboard(codeText, btn);
                };
                pre.appendChild(btn);
            }
        });
    }


    function copyTextToClipboard(text, btn) {
        const originalHTML = btn.innerHTML;

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                showSuccess(btn, originalHTML);
            }).catch(err => {
                fallbackCopyTextToClipboard(text, btn, originalHTML);
            });
        } else {

            fallbackCopyTextToClipboard(text, btn, originalHTML);
        }
    }


    function fallbackCopyTextToClipboard(text, btn, originalHTML) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            showSuccess(btn, originalHTML);
        } catch (err) {
            console.error('Fallback: Copying failed', err);
        }
        document.body.removeChild(textArea);
    }


    function showSuccess(btn, originalHTML) {
        btn.innerHTML = '<i class="fa-solid fa-check text-green-500"></i> Copied!';
        btn.classList.add('text-green-500');
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.classList.remove('text-green-500');
        }, 2000);
    }


    function copyToClipboard(btn) {
        const text = btn.getAttribute('data-text');
        copyTextToClipboard(text, btn);
    }


    document.addEventListener('DOMContentLoaded', () => {
        setupCodeBlocks();

        const container = document.getElementById('chat-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    });


    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }






    function previewFile(input) {
    const preview = document.getElementById('file-preview');
    preview.innerHTML = '';
    if (input.files && input.files[0]) {
        const file = input.files[0];
        preview.classList.remove('hidden');

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <div class="relative">
                        <img src="${e.target.result}" class="h-12 w-12 object-cover rounded-lg border">
                        <button onclick="removeFile()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-4 h-4 text-[10px] flex items-center justify-center">×</button>
                    </div>`;
            }
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = `
                <div class="bg-gray-100 px-3 py-1 rounded-full text-xs flex items-center gap-2">
                    <i class="fa-solid fa-file-lines text-blue-600"></i> ${file.name}
                    <button onclick="removeFile()" class="text-red-500 font-bold">×</button>
                </div>`;
        }
    }
}

function removeFile() {
    document.getElementById('file_upload').value = '';
    document.getElementById('file-preview').classList.add('hidden');
}
</script>
</body>
</html>
