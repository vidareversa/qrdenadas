<div class="w-full min-h-screen bg-slate-50 text-slate-800 p-8 flex flex-col justify-between font-sans">
    
    <div class="flex flex-col md:flex-row justify-between items-center border-b border-cyan-100 pb-6 gap-6">
        
        <div class="text-center md:text-left flex-1">
            <h1 class="text-4xl font-black text-cyan-600 tracking-wide uppercase">🧩 PISTASQR</h1>
            <p class="text-sm text-slate-400 font-medium mt-1">
                Código de Partida: <span class="text-pink-500 font-bold text-lg tracking-wider">{{ $code }}</span>
            </p>
            <span class="inline-block mt-2 bg-cyan-100 text-cyan-700 text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">
                Tablero Central
            </span>
        </div>

        <div class="bg-white p-4 rounded-3xl shadow-xl border-2 border-cyan-400 flex flex-col items-center justify-center transform hover:scale-105 transition-transform duration-200">
            <div class="bg-slate-50 p-2 rounded-2xl border border-slate-100">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ env('APP_URL') }}/sacar-coordenada/{{ $code }}" alt="Escanear QR" class="rounded-xl shadow-sm">
            </div>
            <span class="text-xs text-cyan-600 font-black tracking-widest uppercase mt-2 flex items-center gap-1 animate-pulse">
                🎴 ESCANEAR MAZO AQUÍ
            </span>
        </div>

        <div class="hidden md:block flex-1 text-right">
        </div>
    </div>

    <div class="flex-1 flex items-center justify-center my-6 w-full">
        <div class="w-full max-w-7xl bg-white p-6 rounded-3xl shadow-xl border border-cyan-100">
            
            <div class="grid grid-cols-6 gap-4 w-full">
                
                <div class="flex items-center justify-center font-bold text-slate-300 text-xl">#</div>
                
                @php $letrasColumnas = ['A', 'B', 'C', 'D', 'E']; @endphp
                
                @foreach($palabrasColumnas as $indexCol => $palabraCol)
                    @php $letraCol = $letrasColumnas[$indexCol] ?? 'X'; @endphp
                    <div class="flex flex-col items-center justify-center bg-cyan-50 border-2 border-cyan-200 rounded-2xl p-3 shadow-sm text-center uppercase tracking-wide">
                        <span class="text-xl font-mono font-bold text-cyan-400 mb-0.5">{{ $letraCol }}</span>
                        <span class="font-black text-cyan-600 text-xl">{{ $palabraCol }}</span>
                    </div>
                @endforeach

                @foreach($palabrasFilas as $indexFila => $palabraFil)
                    @php $numFila = $indexFila + 1; @endphp
                    
                    <div class="flex flex-col items-center justify-center bg-pink-50 border-2 border-pink-200 rounded-2xl p-3 shadow-sm text-center uppercase tracking-wide aspect-square max-h-24">
                        <span class="text-xl font-mono font-bold text-pink-400 mb-0.5">{{ $numFila }}</span>
                        <span class="font-black text-pink-600 text-xl">{{ $palabraFil }}</span>
                    </div>
                    
                    @foreach($palabrasColumnas as $indexCol => $palabraCol)
                        @php 
                            $letraCol = $letrasColumnas[$indexCol] ?? 'X';
                            $coordenada = $letraCol . $numFila;
                        @endphp
                        
                        <div id="casilla-{{ $coordenada }}" 
                             class="bg-slate-50/50 border-2 border-dashed border-slate-300 rounded-2xl flex flex-col items-center justify-center p-2 shadow-inner hover:bg-cyan-50/30 hover:border-cyan-300 transition-all duration-200 aspect-square max-h-24 group relative cursor-pointer">
                            
                            <span class="opacity-0 group-hover:opacity-100 text-[10px] font-mono font-black text-cyan-500 absolute transition-opacity duration-200">
                                {{ $coordenada }}
                            </span>
                        </div>
                    @endforeach

                @endforeach

            </div>
        </div>
    </div>

    <div class="border-t border-slate-100 pt-4 text-center md:text-left">
        <div class="text-slate-400 text-xs inline-flex items-center gap-2">
            <span class="font-bold text-slate-600 uppercase tracking-wider bg-slate-200/60 px-2 py-0.5 rounded text-[10px]">💡 Reglas</span>
            <span>Cada jugador escanea el mazo para recibir una intersección secreta. ¡Debes dar una pista que conecte la palabra de su Columna y su Fila!</span>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const salaCode = "{{ $code }}";

    if (window.Echo) {
        console.log("estoy en la sala....!!!", `sala.${salaCode}`);
        
        window.Echo.channel(`sala.${salaCode}`)
            .listen('.celda.completada', (e) => {
                console.log("ACERTADA LA COORDENADA", e);

                const coord = e.coordenada.toUpperCase();
                let casilla = document.getElementById(`casilla-${coord}`);
                
                if (casilla) {
                    casilla.classList.remove('bg-slate-50/50', 'border-dashed', 'border-slate-300');
                    casilla.classList.add('bg-emerald-500', 'border-solid', 'border-emerald-600', 'shadow-lg', 'scale-105');
                    
                    casilla.innerHTML = `
                        <span class="text-white font-black text-2xl tracking-wider animate-bounce">✓</span>
                        <span class="text-[10px] text-emerald-100 font-mono font-bold mt-0.5">${coord}</span>
                    `;
                }
            });
    }
});
</script>