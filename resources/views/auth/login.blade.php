<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Faruk AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-gray-50 font-sans min-h-screen flex items-center justify-center px-4">

    <div class="max-w-md w-full bg-white rounded-[2.5rem] shadow-xl shadow-blue-100/50 p-8 md:p-10 animate-fade-in border border-gray-100">

        <!-- Header / Welcome Section -->
        <div class="flex flex-col items-center justify-center text-center mb-8">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center text-3xl mb-6 shadow-inner">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">Faruk AI</h2>
            <p class="text-gray-500 text-sm leading-relaxed max-w-[250px]">
                Real-time answers powered by verified web sources.
            </p>
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
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
                        placeholder="example@mail.com">
                </div>
                @if($errors->has('email'))
                    <p class="mt-2 text-xs text-red-500 font-medium ml-1">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between mb-2 ml-1">
                    <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-widest">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs font-bold text-blue-600 hover:underline">Forgot?</a>
                    @endif
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input id="password" type="password" name="password" required
                        class="w-full pl-11 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white focus:border-blue-400 transition-all text-gray-700"
                        placeholder="••••••••">
                </div>
                @if($errors->has('password'))
                    <p class="mt-2 text-xs text-red-500 font-medium ml-1">{{ $errors->first('password') }}</p>
                @endif
            </div>

            <!-- Remember Me -->
            <div class="flex items-center ml-1">
                <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="remember_me" class="ml-2 text-sm text-gray-500">Keep me logged in</label>
            </div>

            <!-- Login Button -->
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-200 transition-all active:scale-[0.98] flex items-center justify-center space-x-2">
                <span>Sign In</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>

            <!-- Register Link -->
            <div class="pt-4 text-center">
                <p class="text-sm text-gray-500">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Create Account</a>
                </p>
            </div>
        </form>

        <!-- Footer Note -->
        <p class="mt-10 text-center text-[10px] text-gray-300 uppercase tracking-[0.2em]">
            Faruk AI • Secure Access
        </p>
    </div>

</body>
</html>
