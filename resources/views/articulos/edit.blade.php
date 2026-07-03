@extends('layouts.app')
@section('title', 'Editar: ' . $articulo->titulo)

@section('content')
    <div class="max-w-4xl mx-auto" x-data="articuloForm()" @click.window="handleGlobalClick($event)">
        <h1 class="text-heading text-navy-900 mb-8">Editar Artículo</h1>

        <form action="{{ route('articulos.update', $articulo) }}" method="POST" class="space-y-6 bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-parchment-100/50 p-8 card-accent-gold" x-ref="form">
            @csrf @method('PUT')
            <input type="hidden" name="redirect_to" x-model="redirectTo">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-navy-900 mb-1">Título *</label>
                    <input type="text" name="titulo" value="{{ old('titulo', $articulo->titulo) }}" required class="w-full bg-white/80 border border-parchment-200/50 rounded-lg px-4 py-2 text-sm " @change="marcarDirty">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-navy-900 mb-1">Categoría *</label>
                    <select name="categoria_id" required class="w-full bg-white/80 border border-parchment-200/50 rounded-lg px-4 py-2 text-sm " x-model="categoriaId" @change="marcarDirty">
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ old('categoria_id', $articulo->categoria_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                        <option value="otro">Otro...</option>
                    </select>
                    <div x-show="categoriaId === 'otro'" x-cloak class="mt-2">
                        <input type="text" name="categoria_nueva" x-model="categoriaNueva" placeholder="Especificar nueva categoría" class="w-full bg-white/80 border border-teal-400/50 rounded-lg py-2 px-3 text-sm " @change="marcarDirty">
                        @error('categoria_nueva') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Extracto</label>
                <textarea name="extracto" rows="2" class="w-full bg-white/80 border border-parchment-200/50 rounded-lg px-4 py-2 text-sm " @change="marcarDirty">{{ old('extracto', $articulo->extracto) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Contenido *</label>
                <input type="hidden" name="contenido" id="contenido" value="{{ old('contenido', $articulo->contenido) }}">
                <trix-editor input="contenido" class="trix-content min-h-[300px] bg-white/80 border border-parchment-200/50 rounded-lg" x-init="$el.addEventListener('trix-change', () => marcarDirty())"></trix-editor>
            </div>

            <div>
                <label class="block text-sm font-semibold text-navy-900 mb-1">Tags</label>
                <select name="tags[]" multiple class="w-full bg-white/80 border border-parchment-200/50 rounded-lg px-4 py-2 text-sm " @change="marcarDirty">
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $articulo->tags->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $tag->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-parchment-200/50">
                <button type="submit" name="status" value="publicado" class="bg-gold-600/90 hover:bg-gold-500 active:scale-[0.97] text-navy-950 px-8 py-3 rounded-lg text-sm font-bold shadow-sm transition-all">Publicar</button>
                <button type="submit" name="status" value="borrador" class="bg-navy-900/90 hover:bg-navy-800 active:scale-[0.97] text-white px-6 py-3 rounded-lg text-sm font-medium shadow-sm transition-all">Guardar como Borrador</button>
                <a href="{{ route('articulos.show', $articulo) }}" @click.prevent="confirmarSalida($event)" class="text-gray-500 hover:text-gray-700 text-sm ml-auto">Cancelar</a>
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
        categoriaId: '{{ old('categoria_id', $articulo->categoria_id) }}',
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
