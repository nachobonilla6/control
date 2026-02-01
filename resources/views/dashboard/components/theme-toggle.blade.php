<div class="relative">
    <button id="themeToggleBtn" title="Toggle theme" class="flex items-center justify-center w-10 h-10 rounded-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-200 hover:shadow-sm transition-colors">
        <!-- Sun -->
        <svg id="theme-sun" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414m12.728 0l-1.414-1.414M7.05 7.05L5.636 5.636M12 7a5 5 0 100 10 5 5 0 000-10z"/></svg>
        <!-- Moon -->
        <svg id="theme-moon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
    </button>

    <script>
        (function(){
            const key = 'theme';
            const root = document.documentElement;
            const btn = document.getElementById('themeToggleBtn');
            const sun = document.getElementById('theme-sun');
            const moon = document.getElementById('theme-moon');

            function setIcons(theme){
                if(!sun || !moon) return;
                if(theme === 'dark'){
                    sun.classList.add('hidden');
                    moon.classList.remove('hidden');
                } else {
                    sun.classList.remove('hidden');
                    moon.classList.add('hidden');
                }
            }

            function applyTheme(theme){
                if(theme === 'dark') root.classList.add('dark');
                else root.classList.remove('dark');
                setIcons(theme);
            }

            // Initialize
            try{
                let stored = localStorage.getItem(key);
                if(!stored){
                    stored = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                }
                applyTheme(stored);
            } catch(e){ applyTheme('light'); }

            // Toggle on click (use delegation safe)
            if(btn){
                btn.addEventListener('click', function(){
                    try{
                        const current = localStorage.getItem(key) === 'dark' ? 'dark' : 'light';
                        const next = current === 'dark' ? 'light' : 'dark';
                        localStorage.setItem(key, next);
                        applyTheme(next);
                    } catch(e) { /* noop */ }
                });
            }
        })();
    </script>
</div>
