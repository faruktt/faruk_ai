<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Faruk AI</title>
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
                <i class="fa-solid fa-envelope-circle-check"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">Verify Email</h2>
            <p class="text-gray-500 text-sm leading-relaxed px-2">
                Thanks for signing up! Please verify your email by clicking the link we just sent you.
            </p>
        </div>

        <!-- Session Status (Success Message) -->
        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl animate-fade-in">
                <p class="text-xs text-green-600 font-medium text-center leading-relaxed">
                    <i class="fa-solid fa-paper-plane mr-2"></i> A new verification link has been sent to your email address.
                </p>
            </div>
        @endif

        <div class="space-y-4">
            <!-- Resend Verification Form -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-200 transition-all active:scale-[0.98] flex items-center justify-center space-x-2">
                    <span>Resend Email</span>
                    <i class="fa-solid fa-rotate-right text-xs"></i>
                </button>
            </form>

            <!-- Logout Option -->
            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit" class="text-sm text-gray-400 font-bold hover:text-red-500 transition-colors uppercase tracking-widest text-[10px]">
                    <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Log Out
                </button>
            </form>
        </div>

        <!-- Footer Note -->
        <p class="mt-10 text-center text-[10px] text-gray-300 uppercase tracking-[0.2em]">
            Faruk AI • Registration Step
        </p>
    </div>

</body>
</html>
