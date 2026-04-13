<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Faruk AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-gray-50 font-sans min-h-screen flex items-center justify-center px-4">

    <div class="max-w-md w-full bg-white rounded-[2.5rem] shadow-xl shadow-blue-100/50 p-8 md:p-10 animate-fade-in border border-gray-100">

        <!-- Header Section -->
        <div class="flex flex-col items-center justify-center text-center mb-8">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center text-3xl mb-6 shadow-inner">
                <i class="fa-solid fa-key"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">Forgot Password?</h2>
            <p class="text-gray-500 text-sm leading-relaxed">
                No problem. Enter your email and we'll send you a link to reset it.
            </p>
        </div>

        <!-- Session Status (Success Message) -->
        @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl animate-fade-in">
                <p class="text-sm text-green-600 font-medium text-center">
                    <i class="fa-solid fa-circle-check mr-2"></i> {{ session('status') }}
                </p>
            </div>
        @endif

        <!-- Reset Form -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1 mb-2">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        <i class="fa-regular fa-envelope"></i>
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white focus:border-blue-400 transition-all text-gray-700"
                        placeholder="your-email@example.com">
                </div>
                @if($errors->has('email'))
                    <p class="mt-2 text-xs text-red-500 font-medium ml-1">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-200 transition-all active:scale-[0.98] flex items-center justify-center space-x-2">
                <span>Send Reset Link</span>
                <i class="fa-solid fa-paper-plane text-xs"></i>
            </button>

            <!-- Back to Login -->
            <div class="pt-4 text-center">
                <a href="{{ route('login') }}" class="text-sm text-gray-400 font-bold hover:text-blue-600 transition-colors">
                    <i class="fa-solid fa-arrow-left mr-2 text-xs"></i> Back to Login
                </a>
            </div>
        </form>

        <!-- Footer Note -->
        <p class="mt-10 text-center text-[10px] text-gray-300 uppercase tracking-[0.2em]">
            Faruk AI • Security First
        </p>
    </div>

</body>
</html>
