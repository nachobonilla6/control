<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Control</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center bg-[#0f172a] text-white overflow-hidden">
    
    <!-- Background Effects -->
    <div class="fixed inset-0 z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-purple-600/20 blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-blue-600/20 blur-[120px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-md p-8">
        <!-- Glass Card -->
        <div class="backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl shadow-2xl p-8 transform transition-all hover:scale-[1.01] duration-500">
            
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold tracking-tight bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">
                    Welcome Back
                </h2>
                <p class="mt-2 text-sm text-gray-400">Please sign in to continue</p>
            </div>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf
                
                @if ($errors->any())
                    <div class="p-3 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="space-y-2">
                    <label for="email" class="text-sm font-medium text-gray-300">Email</label>
                    <input type="email" name="email" id="email" required 
                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-300"
                        placeholder="you@example.com">
                </div>

                <div class="space-y-2">
                    <label for="password" class="text-sm font-medium text-gray-300">Password</label>
                    <input type="password" name="password" id="password" required 
                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-300"
                        placeholder="••••••••">
                </div>

                <button type="submit" 
                    class="w-full py-3.5 px-4 rounded-xl font-semibold text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-blue-500 shadow-lg shadow-blue-600/30 transform transition-all duration-300 hover:-translate-y-0.5 active:translate-y-0">
                    Sign In
                </button>
            </form>
        </div>
        
        <div class="mt-8 text-center text-sm text-gray-500">
            Protected by <span class="text-gray-400">Control System</span>
        </div>
    </div>

</body>
</html>
