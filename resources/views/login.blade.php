<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Control</title>
<script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
          extend: {
            colors: {
              indigo: {
                50: '#eef2ff',
                100: '#e0e7ff',
                200: '#c7d2fe',
                300: '#a5b4fc',
                400: '#818cf8',
                500: '#6366f1',
                600: '#4f46e5',
                700: '#4338ca',
                800: '#3730a3',
                900: '#312e81',
              },
            },
          },
        },
      };
    </script>
</head>
<body class="h-full flex items-center justify-center bg-transparent text-white overflow-hidden relative">
    
    <!-- Background Effects -->
    <div class="fixed inset-0 z-0 bg-slate-950">
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-indigo-500/10 blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-indigo-600/10 blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute top-[20%] left-[20%] w-[20%] h-[20%] rounded-full bg-indigo-400/5 blur-[80px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-md p-6">
        <!-- Glass Card -->
        <div class="backdrop-blur-2xl bg-white/[0.03] border border-white/10 rounded-3xl shadow-2xl p-8 sm:p-10 transform transition-all duration-500 hover:shadow-indigo-500/10 hover:border-indigo-500/20">
            
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-500/20 text-indigo-400 mb-6 border border-indigo-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-bold tracking-tight text-white mb-2">
                    Welcome Back
                </h2>
                <p class="text-sm text-indigo-200/50">Enter your credentials to access the workspace</p>
            </div>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf
                
                @if ($errors->any())
                    <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 flex-shrink-0">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="space-y-2">
                    <label for="email" class="text-xs font-semibold text-indigo-200/70 uppercase tracking-wider ml-1">Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-1 flex items-center pointer-events-none">
                            <!-- Optional icon placeholder -->
                        </div>
                        <input type="email" name="email" id="email" required 
                            class="block w-full px-4 py-3.5 rounded-xl bg-white/[0.03] border border-white/10 text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 focus:bg-white/[0.05] transition-all duration-300 sm:text-sm"
                            placeholder="name@company.com">
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="password" class="text-xs font-semibold text-indigo-200/70 uppercase tracking-wider ml-1">Password</label>
                    </div>
                    <div class="relative group">
                        <input type="password" name="password" id="password" required 
                            class="block w-full px-4 py-3.5 rounded-xl bg-white/[0.03] border border-white/10 text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 focus:bg-white/[0.05] transition-all duration-300 sm:text-sm"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" 
                    class="w-full py-4 px-4 rounded-xl font-semibold text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/25 shadow-lg shadow-indigo-600/20 transform transition-all duration-300 hover:-translate-y-0.5 mt-8">
                    Sign In to Console
                </button>
            </form>
        </div>
        
        <div class="mt-8 text-center">
            <p class="text-xs text-indigo-300/30">
                Secure Access • <span class="text-indigo-300/50">Control v1.0</span>
            </p>
        </div>
    </div>

</body>
</html>
