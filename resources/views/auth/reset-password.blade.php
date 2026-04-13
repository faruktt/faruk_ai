<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password - Faruk AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-gray-50 font-sans min-h-screen flex items-center justify-center py-10 px-4">

    <div class="max-w-md w-full bg-white rounded-[2.5rem] shadow-xl shadow-blue-100/50 p-8 md:p-10 animate-fade-in border border-gray-100">

        <!-- Header Section -->
        <div class="flex flex-col items-center justify-center text-center mb-8">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center text-3xl mb-6 shadow-inner">
                <i class="fa-solid fa-lock-open"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">New Password</h2>
            <p class="text-gray-500 text-sm leading-relaxed px-4">
                Please enter your email and a strong new password to regain access.
            </p>
        </div>

        <!-- Reset Form -->
        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1 mb-2">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        <i class="fa-regular fa-envelope"></i>
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                        class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white focus:border-blue-400 transition-all text-gray-700 text-sm">
                </div>
                @if($errors->has('email'))
                    <p class="mt-1.5 text-[11px] text-red-500 font-medium ml-1">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1 mb-2">New Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        <i class="fa-solid fa-key"></i>
                    </span>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white focus:border-blue-400 transition-all text-gray-700 text-sm"
                        placeholder="••••••••">
                </div>
                @if($errors->has('password'))
                    <p class="mt-1.5 text-[11px] text-red-500 font-medium ml-1">{{ $errors->first('password') }}</p>
                @endif
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1 mb-2">Confirm New Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                        <i class="fa-solid fa-shield-check"></i>
                    </span>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:bg-white focus:border-blue-400 transition-all text-gray-700 text-sm"
                        placeholder="••••••••">
                </div>
            </div>

            <!-- Reset Button -->
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-200 transition-all active:scale-[0.98] flex items-center justify-center space-x-2 mt-4">
                <span>Reset Password</span>
                <i class="fa-solid fa-rotate mr-1 text-xs"></i>
            </button>
        </form>

        <!-- Footer Note -->
        <p class="mt-10 text-center text-[10px] text-gray-300 uppercase tracking-[0.2em]">
            Faruk AI • Recovery Mode
        </p>
    </div>

</body>
</html>
