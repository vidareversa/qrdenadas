<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<div class="min-h-screen bg-slate-950 text-white flex flex-col items-center justify-center p-4 font-sans select-none">
    
    @if($tarjetaAsignada)
        <div class="w-full max-w-sm flex flex-col gap-5 animate-fade-in">
            
            <div class="relative overflow-hidden bg-slate-900 border-2 rounded-3xl p-6 shadow-[0_20px_50px_rgba(0,0,0,0.5)] transition-all duration-300
                {{ $estado === 'acertada' ? 'border-emerald-500 shadow-emerald-950/40 text-emerald-400' : '' }}
                {{ $estado === 'descartada' ? 'border-rose-950 bg-slate-950 opacity-40 line-through' : 'border-cyan-500/30' }}">
                
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-cyan-500/5 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -left-10 -top-10 w-40 h-40 bg-pink-500/5 rounded-full blur-2xl pointer-events-none"></div>

                <span class="text-[11px] uppercase tracking-widest font-black text-slate-500 block text-center mb-1">
                    🎯 OBJETIVO SECRETO
                </span>
                
                <span class="text-[11rem] font-black font-mono leading-none block text-center tracking-tighter text-transparent bg-clip-text bg-gradient-to-b from-white to-slate-300 drop-shadow-[0_6px_20px_rgba(34,211,238,0.25)]">
                    {{ $coordenada }}
                </span>
            </div>

            <div class="bg-slate-900 border-2 border-slate-800/80 rounded-3xl p-5 shadow-2xl flex flex-col gap-3 {{ $estado === 'descartada' ? 'opacity-20 line-through' : '' }}">
                
                <!--div class="relative bg-gradient-to-r from-cyan-950/40 to-slate-900 border border-cyan-500/20 py-4 px-5 rounded-2xl shadow-inner">
                    <span class="absolute top-2 right-4 text-[10px] font-mono font-bold text-cyan-500/40 tracking-wider">EJE HORIZONTAL</span>
                    <span class="text-xs font-bold text-cyan-400/80 uppercase block tracking-wider mb-0.5">Letra</span>
                    <span class="text-2xl font-black text-slate-100 uppercase tracking-wide">{{ $palabraCol }}</span>
                </div>

                <div class="relative bg-gradient-to-r from-pink-950/30 to-slate-900 border border-pink-500/20 py-4 px-5 rounded-2xl shadow-inner">
                    <span class="absolute top-2 right-4 text-[10px] font-mono font-bold text-pink-500/40 tracking-wider">EJE VERTICAL</span>
                    <span class="text-xs font-bold text-pink-400/80 uppercase block tracking-wider mb-0.5">Número</span>
                    <span class="text-2xl font-black text-slate-100 uppercase tracking-wide">{{ $palabraFil }}</span>
                </div-->

                @if($estado === 'pendiente')
                    <div class="mt-2">
                        <button wire:click="$set('estado', 'votando'); actualizarSesion();" 
                                class="w-full py-4 bg-gradient-to-r from-cyan-500 to-cyan-400 text-slate-950 rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-cyan-500/10 active:scale-95 transition-all cursor-pointer">
                            📢 PISTA DADA
                        </button>
                    </div>
                @endif

                @if($estado === 'votando')
                    <div class="mt-2 grid grid-cols-2 gap-3">
                        <button wire:click="marcarAcertada" class="py-4 bg-emerald-500 text-slate-950 rounded-xl text-xs font-black uppercase tracking-wider active:scale-95 transition-all cursor-pointer shadow-lg shadow-emerald-500/10">
                            🟢 ACERTADA
                        </button>
                        <button wire:click="marcarDescartada" class="py-4 bg-rose-600 text-white rounded-xl text-xs font-black uppercase tracking-wider active:scale-95 transition-all cursor-pointer shadow-lg shadow-rose-600/10">
                            🔴 NO ACERTADA
                        </button>
                    </div>
                @endif
            </div>

            @if($estado === 'acertada' || $estado === 'descartada')
                <div class="w-full">
                    <button wire:click="liberarMano" class="w-full py-4 bg-gradient-to-r from-cyan-400 to-teal-400 text-slate-950 rounded-xl text-xs font-black uppercase tracking-widest shadow-2xl active:scale-95 transition-all cursor-pointer">
                        🎴 SACAR OTRA CARTA
                    </button>
                </div>
            @endif

        </div>
    @else
        <div class="bg-slate-900 p-6 rounded-3xl border-2 border-slate-800 max-w-sm w-full text-center">
            <p class="text-amber-400 text-sm font-medium tracking-wide my-4">{{ $mensaje }}</p>
        </div>
    @endif

</div>