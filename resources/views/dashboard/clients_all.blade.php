<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Clients - Control</title>
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
    </style>
</head>
<body class="h-full flex flex-col bg-slate-950 text-slate-200 overflow-hidden">

    <!-- Navbar -->
    <nav class="h-20 bg-slate-900/50 backdrop-blur-md border-b border-slate-800/50 flex items-center justify-between px-6 sticky top-0 z-30">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-4 group/brand">
            <span class="text-xl font-bold text-indigo-400 group-hover/brand:text-white transition-colors">josh dev</span>
            <span class="text-slate-700 font-light text-xl italic">/</span>
            <span class="text-sm font-medium text-slate-400 tracking-wide uppercase group-hover/brand:text-indigo-400 transition-colors">Control Panel</span>
        </a>
        <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard.chat') }}" class="flex items-center space-x-2 px-4 py-2 bg-indigo-600/10 hover:bg-indigo-600/20 border border-indigo-500/20 rounded-xl transition-all group">
                <svg class="w-4 h-4 text-indigo-400 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Copilot</span>
            </a>
            <!-- Account Dropdown -->
            <div class="relative">
                <button id="accountBtn" class="flex items-center justify-center w-10 h-10 bg-slate-800/50 border border-slate-800 rounded-full hover:border-indigo-500/30 transition-all focus:outline-none overflow-hidden group">
                    @if(Auth::user()->profile_photo_url)
                    <img src="{{ asset(Auth::user()->profile_photo_url) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                    @else
                        <div class="w-full h-full bg-indigo-600 flex items-center justify-center text-white text-xs font-black">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                </button>

                <!-- Dropdown Menu -->
                <div id="accountDropdown" class="absolute right-0 mt-3 w-56 bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl opacity-0 invisible transition-all z-50 p-2">
                    <div class="px-4 py-3 border-b border-slate-800 mb-2">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">Signed in as</p>
                        <p class="text-xs font-bold text-white truncate">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <button onclick="document.getElementById('profileModal').classList.remove('hidden'); closeAllDropdowns();" class="w-full flex items-center space-x-3 px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span>Profile Settings</span>
                    </button>

                    <div class="my-2 border-t border-slate-800"></div>

                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 text-sm text-red-500 hover:bg-red-500/10 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span class="font-bold">Logout System</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Notifications Dropdown -->
            <div class="relative">
                <button id="notifBtn" class="relative p-2.5 text-slate-400 hover:text-indigo-400 transition-colors focus:outline-none bg-slate-800/50 rounded-full border border-slate-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span id="notifBadge" class="absolute top-0 right-0 bg-indigo-600 text-[10px] font-bold text-white rounded-full w-4 h-4 flex items-center justify-center border-2 border-slate-950 hidden">0</span>
                </button>

                <!-- Notifications Dropdown Content -->
                <div id="notifDropdown" class="absolute right-0 mt-3 w-80 bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl opacity-0 invisible transition-all z-50 p-2 overflow-hidden">
                    <div class="p-4 border-b border-slate-800 flex items-center justify-between">
                        <h3 class="text-xs font-black text-white uppercase tracking-widest text-indigo-400">Broadcasts</h3>
                        <span class="text-[9px] font-bold text-slate-600 uppercase">Live Feed</span>
                    </div>
                    <div id="notifList" class="max-h-80 overflow-y-auto p-2 space-y-2">
                        <div class="text-center py-6 text-slate-600 text-[10px] italic uppercase tracking-widest">Scanning...</div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex-1 overflow-y-auto">
        <div class="max-w-7xl mx-auto px-6 py-10">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-4xl font-black text-white italic tracking-tighter">All <span class="text-indigo-500">Clients</span></h1>
                    <p class="text-[10px] font-bold text-slate-500 tracking-[0.3em] mt-2">Complete client database</p>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('dashboard.clients') }}" class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all border border-slate-700 active:scale-95 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Back
                    </a>
                    <button onclick="openExtractModal()" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-500 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all shadow-2xl shadow-emerald-600/20 active:scale-95 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Extract
                    </button>
                </div>
            </div>

            <!-- Clients Table -->
            <div class="bg-slate-900 border border-slate-800 rounded-[2.5rem] overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 bg-slate-950">
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Name / Company</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Email</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Phone</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Status</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Created</th>
                                <th class="px-8 py-6 text-[9px] font-black text-slate-500 tracking-[0.2em]">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                                <tr class="border-b border-slate-800 hover:bg-slate-800/30 transition-colors">
                                    <td class="px-8 py-6 text-[13px] text-white font-bold">{{ $client->name }}</td>
                                    <td class="px-8 py-6 text-[13px] text-slate-300">
                                        @if($client->email)
                                            <a href="mailto:{{ $client->email }}" target="_blank" class="text-indigo-400 hover:text-indigo-300">{{ $client->email }}</a>
                                        @else
                                            <span class="text-slate-500 italic">No email</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 text-[13px] text-slate-400">{{ $client->phone ?? '-' }}</td>
                                    <td class="px-8 py-6">
                                        <span class="text-[13px] font-bold text-slate-300">{{ $client->status ?? 'No Status' }}</span>
                                    </td>
                                    <td class="px-8 py-6 text-[13px] text-slate-400">{{ $client->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-8 py-6 flex items-center space-x-3">
                                        <a href="{{ route('dashboard.clients.edit', $client->id) }}" class="p-2 text-slate-400 hover:text-indigo-400 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </a>
                                        <button onclick="if(confirm('¿Eliminar este cliente?')) { document.getElementById('deleteForm{{ $client->id }}').submit(); }" class="p-2 text-slate-400 hover:text-red-500 transition-colors" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                        <form id="deleteForm{{ $client->id }}" action="{{ route('dashboard.clients.destroy', $client->id) }}" method="POST" style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-12 text-center text-slate-500">
                                        <p class="text-sm font-medium">No clients found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-8 py-6 border-t border-slate-800 bg-slate-900/50">
                    {{ $clients->links() }}
                </div>
                <!-- Extract button moved to top next to Back -->
            </div>
        </div>
    </div>

    <!-- Extract Modal -->
    <div id="extractModal" class="fixed inset-0 z-50 hidden bg-slate-950/90 backdrop-blur-xl flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 w-full max-w-2xl rounded-[2.5rem] overflow-hidden shadow-2xl p-8 animate-in fade-in zoom-in duration-300">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-black text-white italic tracking-tighter mb-0.5">Extract Clients</h2>
                    <p class="text-[8px] font-bold text-slate-500 tracking-widest leading-none">Filter & extract clients by language, country, city & industry</p>
                </div>
                <button onclick="document.getElementById('extractModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center hover:bg-white/5 rounded-xl text-slate-600 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>

            <form id="extractForm" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Language -->
                    <div>
                        <label class="block text-[9px] font-black text-emerald-400 tracking-widest mb-2 px-1">Language</label>
                        <div class="relative">
                            <select id="extract_language" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 text-xs font-bold text-white appearance-none transition-all cursor-pointer">
                                <option value="">Select Language</option>
                                <option value="english">English</option>
                                <option value="spanish">Spanish</option>
                                <option value="french">French</option>
                                <option value="portuguese">Portuguese</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Country -->
                    <div>
                        <label class="block text-[9px] font-black text-emerald-400 tracking-widest mb-2 px-1">Country</label>
                        <div class="relative">
                            <select id="extract_country" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 text-xs font-bold text-white appearance-none transition-all cursor-pointer">
                                <option value="">Select Country</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- City -->
                    <div>
                        <label class="block text-[9px] font-black text-emerald-400 tracking-widest mb-2 px-1">City</label>
                        <div class="relative">
                            <select id="extract_city" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 text-xs font-bold text-white appearance-none transition-all cursor-pointer">
                                <option value="">Select City</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Industry -->
                    <div>
                        <label class="block text-[9px] font-black text-emerald-400 tracking-widest mb-2 px-1">Industry</label>
                        <div class="relative">
                            <select id="extract_industry" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 text-xs font-bold text-white appearance-none transition-all cursor-pointer">
                                <option value="">All Industries</option>
                                <option value="Technology">Technology</option>
                                <option value="Finance">Finance</option>
                                <option value="Healthcare">Healthcare</option>
                                <option value="Manufacturing">Manufacturing</option>
                                <option value="Retail">Retail</option>
                                <option value="Automotive">Automotive</option>
                                <option value="Real Estate">Real Estate</option>
                                <option value="Hospitality">Hospitality</option>
                                <option value="Education">Education</option>
                                <option value="Energy">Energy</option>
                                <option value="Telecommunications">Telecommunications</option>
                                <option value="Construction">Construction</option>
                                <option value="Agriculture">Agriculture</option>
                                <option value="Media">Media</option>
                                <option value="Entertainment">Entertainment</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4 pt-4">
                    <button type="submit" onclick="extractClients(event)" class="flex-1 bg-emerald-600 hover:bg-emerald-500 text-white font-black py-4 rounded-2xl shadow-2xl shadow-emerald-600/20 transition-all active:scale-95 text-[10px] tracking-[0.3em]">
                        Extract Records
                    </button>
                    <button type="button" onclick="document.getElementById('extractModal').classList.add('hidden')" class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Country data by language
        const countryData = {
            english: {
                usa: ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia', 'San Antonio', 'San Diego'],
                uk: ['London', 'Manchester', 'Birmingham', 'Leeds', 'Glasgow', 'Liverpool', 'Newcastle', 'Sheffield'],
                canada: ['Toronto', 'Vancouver', 'Montreal', 'Calgary', 'Ottawa', 'Edmonton', 'Winnipeg', 'Quebec City'],
                australia: ['Sydney', 'Melbourne', 'Brisbane', 'Perth', 'Adelaide', 'Gold Coast', 'Canberra', 'Newcastle']
            },
            spanish: {
                spain: ['Madrid', 'Barcelona', 'Valencia', 'Seville', 'Bilbao', 'Malaga', 'Cordoba', 'Alicante'],
                mexico: ['Mexico City', 'Guadalajara', 'Monterrey', 'Puebla', 'Cancun', 'Playa del Carmen', 'Los Cabos', 'Acapulco'],
                argentina: ['Buenos Aires', 'Cordoba', 'Rosario', 'Mendoza', 'San Juan', 'La Plata', 'Mar del Plata', 'Quilmes'],
                colombia: ['Bogota', 'Medellín', 'Cali', 'Barranquilla', 'Cartagena', 'Bucaramanga', 'Santa Marta', 'Pereira']
            },
            french: {
                france: ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice', 'Nantes', 'Strasbourg', 'Bordeaux'],
                canada: ['Montreal', 'Quebec City', 'Ottawa', 'Gatineau', 'Sherbrooke', 'Trois-Rivières', 'Laval', 'Longueuil']
            },
            portuguese: {
                brazil: ['São Paulo', 'Rio de Janeiro', 'Brasília', 'Salvador', 'Fortaleza', 'Belo Horizonte', 'Manaus', 'Recife'],
                portugal: ['Lisbon', 'Porto', 'Braga', 'Covilhã', 'Aveiro', 'Funchal', 'Covilhan', 'Guarda']
            }
        };

        // Map country keys to readable names
        const countryNames = {
            english: { usa: 'USA', uk: 'United Kingdom', canada: 'Canada', australia: 'Australia' },
            spanish: { spain: 'Spain', mexico: 'Mexico', argentina: 'Argentina', colombia: 'Colombia' },
            french: { france: 'France', canada: 'Canada (FR)' },
            portuguese: { brazil: 'Brazil', portugal: 'Portugal' }
        };

        function openExtractModal() {
            document.getElementById('extract_language').value = '';
            document.getElementById('extract_country').value = '';
            document.getElementById('extract_city').value = '';
            document.getElementById('extract_industry').value = '';
            document.getElementById('extractModal').classList.remove('hidden');
        }

        document.getElementById('extract_language').addEventListener('change', function() {
            const language = this.value;
            const countrySelect = document.getElementById('extract_country');
            countrySelect.innerHTML = '<option value="">Select Country</option>';
            
            if (language && countryData[language]) {
                Object.keys(countryData[language]).forEach(key => {
                    const option = document.createElement('option');
                    option.value = key;
                    option.textContent = countryNames[language][key];
                    countrySelect.appendChild(option);
                });
            }
            document.getElementById('extract_city').innerHTML = '<option value="">Select City</option>';
        });

        document.getElementById('extract_country').addEventListener('change', function() {
            const language = document.getElementById('extract_language').value;
            const country = this.value;
            const citySelect = document.getElementById('extract_city');
            citySelect.innerHTML = '<option value="">Select City</option>';
            
            if (language && country && countryData[language][country]) {
                countryData[language][country].forEach(city => {
                    const option = document.createElement('option');
                    option.value = city;
                    option.textContent = city;
                    citySelect.appendChild(option);
                });
            }
        });

        async function extractClients(event) {
            event.preventDefault();
            
            const language = document.getElementById('extract_language').value;
            const country = document.getElementById('extract_country').value;
            const city = document.getElementById('extract_city').value;
            const industry = document.getElementById('extract_industry').value;
            
            if (!language || !country || !city) {
                alert('Please fill in all required fields');
                return;
            }

            // Language code mapping
            const languageCode = {
                'english': 'eng',
                'spanish': 'spa',
                'french': 'fra',
                'portuguese': 'por'
            };

            // Build the extraction text: lang_code country city industry (in one line)
            const langCode = languageCode[language] || language;
            const extractionText = `${langCode} ${country} ${city} ${industry || ''}`.trim();

            try {
                // Send to n8n webhook
                const n8nResponse = await fetch('https://n8n.srv1137974.hstgr.cloud/webhook-test/931c675e-6a24-4cd2-8fd6-46d0e787b9b3', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        language,
                        text: extractionText,
                        country,
                        city,
                        industry: industry || null,
                        user_id: '{{ Auth::id() }}',
                        user_email: '{{ Auth::user()->email }}'
                    })
                });

                const n8nData = await n8nResponse.json();

                if (n8nResponse.ok) {
                    alert('✓ Extraction initiated successfully!\nLanguage: ' + language + '\nData: ' + extractionText);
                    document.getElementById('extractModal').classList.add('hidden');
                    // Optionally reload the clients list
                    setTimeout(() => location.reload(), 1500);
                } else {
                    alert('Error: ' + (n8nData.message || 'Unknown error'));
                }
            } catch (e) {
                alert('Error processing extraction: ' + e.message);
            }
        }
    </script>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
