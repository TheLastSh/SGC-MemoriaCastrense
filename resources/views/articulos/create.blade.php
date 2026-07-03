@extends('layouts.app')
@section('title', 'Nuevo Artículo — La Vela de Coro')

@section('content')
    <div class="max-w-4xl mx-auto" x-data="articuloForm()" @click.window="handleGlobalClick($event)">
        <h1 class="text-heading text-navy-900 mb-8">Nuevo Artículo</h1>

        <form action="{{ route('articulos.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-parchment-100/50 p-8 card-accent-gold" x-ref="form">
            @csrf
            <input type="hidden" name="redirect_to" x-model="redirectTo">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-navy-900 mb-1.5">Título *</label>
                    <div class="input-icon-wrapper relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none transition-colors" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                        <input type="text" name="titulo" value="{{ old('titulo') }}" required maxlength="255" placeholder="Título del artículo" class="pl-10 w-full bg-white/80 border border-parchment-200/50 rounded-lg py-2.5 text-sm " @change="marcarDirty">
                    </div>
                    @error('titulo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-navy-900 mb-1.5">Categoría *</label>
                    <div class="input-icon-wrapper relative">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none transition-colors" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/></svg>
                        <select name="categoria_id" required class="pl-10 w-full bg-white/80 border border-parchment-200/50 rounded-lg py-2.5 text-sm " x-model="categoriaId" @change="marcarDirty">
                            <option value="">Seleccionar categoría</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                            @endforeach
                            <option value="otro">Otro...</option>
                        </select>
                    </div>
                    @error('categoria_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <div x-show="categoriaId === 'otro'" x-cloak class="mt-2">
                        <input type="text" name="categoria_nueva" x-model="categoriaNueva" placeholder="Especificar nueva categoría" class="w-full bg-white/80 border border-teal-400/50 rounded-lg py-2 px-3 text-sm " @change="marcarDirty">
                        @error('categoria_nueva') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1.5">Extracto (opcional)</label>
                <div class="input-icon-wrapper relative">
                    <svg class="absolute left-3.5 top-4 w-4 h-4 text-gray-400 pointer-events-none transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    <textarea name="extracto" rows="2" maxlength="500" placeholder="Breve resumen del artículo" class="pl-10 w-full bg-white/80 border border-parchment-200/50 rounded-lg py-2.5 text-sm " @change="marcarDirty">{{ old('extracto') }}</textarea>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1.5">Contenido *</label>
                <input type="hidden" name="contenido" id="contenido" value="{{ old('contenido') }}">
                <trix-editor input="contenido" class="trix-content min-h-[300px] bg-white/80 border border-parchment-200/50 rounded-lg" x-init="$el.addEventListener('trix-change', () => marcarDirty())"></trix-editor>
                @error('contenido') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1.5">Tags (opcional)</label>
                <div class="input-icon-wrapper relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none transition-colors" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                    <select name="tags[]" multiple class="pl-10 w-full bg-white/80 border border-parchment-200/50 rounded-lg py-2.5 text-sm " @change="marcarDirty">
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>{{ $tag->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1.5">Imagen de Portada (opcional, máx 5MB)</label>
                <div class="input-icon-wrapper relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none transition-colors" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>
                    <input type="file" name="portada" accept="image/jpeg,image/png,image/webp" class="pl-10 w-full bg-white/80 border border-parchment-200/50 rounded-lg py-2.5 text-sm" @change="marcarDirty">
                </div>
                @error('portada') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-parchment-200/50">
                <button type="submit" name="status" value="publicado" class="bg-gold-600/90 hover:bg-gold-500 active:scale-[0.97] text-navy-950 px-8 py-3 rounded-lg text-sm font-bold shadow-sm transition-all">Publicar</button>
                <button type="submit" name="status" value="borrador" class="bg-navy-900/90 hover:bg-navy-800 active:scale-[0.97] text-white px-6 py-3 rounded-lg text-sm font-medium shadow-sm transition-all">Guardar como Borrador</button>
                <a href="{{ route('articulos.index') }}" @click.prevent="confirmarSalida($event)" class="text-gray-500 hover:text-gray-700 text-sm ml-auto">Cancelar</a>
            </div>
        </form>

        {{-- Modal de confirmación --}}
        <div x-show="showExitModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" @click.self="showExitModal = false">
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl border border-parchment-100/50 p-8 max-w-md w-full mx-4" @keydown.escape.window="showExitModal = false">
                <h3 class="text-xl font-display font-bold text-navy-900 mb-2">¿Salir sin guardar?</h3>
                <p class="text-gray-500 text-sm mb-6">Tienes cambios sin guardar en este artículo.</p>
                <div class="flex flex-col gap-3">
                    <button @click="guardarBorradorSalir" class="w-full bg-navy-900/90 hover:bg-navy-800 active:scale-[0.97] text-white px-6 py-3 rounded-xl text-sm font-bold shadow-sm transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293z"/></svg>
                        Guardar como Borrador
                    </button>
                    <button @click="salirSinGuardar" class="w-full bg-red-50/80 hover:bg-red-100 active:scale-[0.97] text-red-600 px-6 py-3 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Salir sin Guardar
                    </button>
                    <button @click="showExitModal = false" class="w-full bg-gray-100 hover:bg-gray-200 active:scale-[0.97] text-gray-700 px-6 py-3 rounded-xl text-sm font-bold transition-all">Quedarse</button>
                </div>
            </div>
        </div>
    </div>

<script>
function articuloForm() {
    return {
        categoriaId: '{{ old('categoria_id') }}',
        categoriaNueva: '',
        dirty: false,
        showExitModal: false,
        pendingHref: null,
        redirectTo: '',
        marcarDirty() { this.dirty = true; },
        confirmarSalida(ev) {
            const href = ev.currentTarget.getAttribute('href');
            if (this.dirty && !href.startsWith('#')) {
                ev.preventDefault();
                this.pendingHref = href;
                this.showExitModal = true;
            }
        },
        salirSinGuardar() {
            if (this.pendingHref) window.location.href = this.pendingHref;
        },
        guardarBorradorSalir() {
            this.redirectTo = this.pendingHref || '{{ route('articulos.index') }}';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'status';
            input.value = 'borrador';
            this.$refs.form.appendChild(input);
            this.$refs.form.submit();
        },
        handleGlobalClick(ev) {
            if (!this.dirty || ev.defaultPrevented) return;
            const link = ev.target.closest('a');
            if (!link) return;
            const href = link.getAttribute('href');
            if (!href || href.startsWith('#') || href.startsWith('javascript') || href === '') return;
            ev.preventDefault();
            this.pendingHref = href;
            this.showExitModal = true;
        },
        init() {
            window.addEventListener('beforeunload', (ev) => {
                if (this.dirty) {
                    ev.preventDefault();
                    ev.returnValue = '';
                }
            });
        }
    };
}
</script>
@endsection