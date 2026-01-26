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
    <div class="flex-1 overflow-y-auto">
        <div class="max-w-7xl mx-auto px-6 py-10">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-4xl font-black text-white italic tracking-tighter">All <span class="text-indigo-500">Clients</span></h1>
                    <p class="text-[10px] font-bold text-slate-500 tracking-[0.3em] mt-2">Complete client database</p>
                </div>
                <a href="{{ route('dashboard.clients') }}" class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all border border-slate-700 active:scale-95 flex items-center">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Back
                </a>
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
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                                <tr class="border-b border-slate-800 hover:bg-slate-800/30 transition-colors">
                                    <td class="px-8 py-6 text-[13px] text-white font-bold">{{ $client->name }}</td>
                                    <td class="px-8 py-6 text-[13px] text-slate-300">
                                        @if($client->email)
                                            <a href="mailto:{{ $client->email }}" class="text-indigo-400 hover:text-indigo-300">{{ $client->email }}</a>
                                        @else
                                            <span class="text-slate-500 italic">No email</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 text-[13px] text-slate-400">{{ $client->phone ?? '-' }}</td>
                                    <td class="px-8 py-6">
                                        @if(!$client->email)
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-700 text-slate-300">No Email</span>
                                        @elseif($client->status === 'sent')
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-600 text-white">Sent</span>
                                        @elseif($client->status === 'queued')
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500 text-white">Queued</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-700 text-slate-300">Unknown</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 text-[13px] text-slate-400">{{ $client->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-12 text-center text-slate-500">
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
                <div class="px-8 py-6 border-t border-slate-800 bg-slate-900/50 flex justify-end">
                    <button onclick="openExtractModal()" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-500 text-white rounded-2xl text-[10px] font-black tracking-[0.2em] transition-all shadow-2xl shadow-emerald-600/20 active:scale-95 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Extract
                    </button>
                </div>
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
