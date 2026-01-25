<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Control</title>
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
    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        @keyframes glow {
            0% { text-shadow: 0 0 10px rgba(59, 130, 246, 0.5); }
            50% { text-shadow: 0 0 30px rgba(168, 85, 247, 0.8), 0 0 10px rgba(59, 130, 246, 0.8); }
            100% { text-shadow: 0 0 10px rgba(59, 130, 246, 0.5); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        .animate-glow {
            animation: glow 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="h-full flex flex-col items-center justify-center bg-slate-950 text-white relative overflow-hidden">

    <!-- Background Grid -->
    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
    <div class="absolute inset-0 bg-gradient-to-tr from-blue-900/20 via-black to-purple-900/20"></div>

    <div class="relative z-10 text-center space-y-8">
        <div class="animate-float">
            <h1 class="text-7xl md:text-9xl font-black tracking-tighter animate-glow bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 bg-clip-text text-transparent select-none">
                josh dev
            </h1>
        </div>
        
        <p class="text-gray-400 text-lg md:text-xl font-light tracking-widest uppercase opacity-0 animate-[fadeIn_1s_ease-out_1s_forwards]">
            Welcome to the Grid
        </p>

        <form action="{{ route('logout') }}" method="POST" class="mt-12 opacity-80 hover:opacity-100 transition-opacity">
            @csrf
            <button type="submit" 
                class="px-8 py-3 rounded-full border border-white/10 bg-white/5 hover:bg-white/10 hover:scale-105 transition-all duration-300 text-sm font-medium tracking-wider backdrop-blur-sm">
                LOGOUT
            </button>
        </form>
    </div>

</body>
</html>
