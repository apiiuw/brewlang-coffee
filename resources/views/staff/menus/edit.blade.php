@extends('layouts.owner')

@section('content')
<div class="max-w-2xl mx-auto animate-fade-in-up">
    <div class="mb-5 sm:mb-6">
        <a href="{{ route('owner.menus.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-stone-500 hover:text-amber-400 transition">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Back to Menu List
        </a>
    </div>

    <div>
        <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-amber-400/70 sm:text-xs sm:tracking-[0.3em]">Management</p>
        <h1 class="font-display mt-2 text-2xl font-black text-stone-50 mb-5 sm:mb-6 sm:text-3xl">Edit Menu Item</h1>
    </div>

    @if($errors->any())
        <div class="alert-error-dark mb-6 flex items-start gap-3">
            <i class="fa-solid fa-circle-exclamation text-red-400 mt-0.5 flex-shrink-0"></i>
            <ul class="text-sm list-disc pl-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-2xl border border-stone-800 bg-stone-900 p-4 sm:p-6">
        <form action="{{ route('owner.menus.update', $menu) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1.5 block text-xs font-semibold text-stone-500 uppercase tracking-wider">Name</label>
                <input type="text" name="name" value="{{ old('name', rtrim($menu->name, ' *')) }}" required
                    class="input-dark" placeholder="Menu item name">
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold text-stone-500 uppercase tracking-wider">Category</label>
                <select name="category_id" required
                    class="input-dark !appearance-none cursor-pointer">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}
                            class="bg-stone-800">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold text-stone-500 uppercase tracking-wider">Price (Rp)</label>
                <input type="number" name="price" value="{{ old('price', (int)$menu->price) }}" required min="0" step="1"
                    class="input-dark" placeholder="0">
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold text-stone-500 uppercase tracking-wider">Description <span class="normal-case text-stone-700">(Optional)</span></label>
                <textarea name="description" rows="3" class="input-dark resize-none"
                    placeholder="Brief description of the item">{{ old('description', $menu->description) }}</textarea>
            </div>

            {{-- Photo --}}
            <div>
                <label class="mb-2 block text-xs font-semibold text-stone-500 uppercase tracking-wider">Current Photo</label>
                <img src="{{ $menu->photo_url ?? asset('images/menu-placeholder.jpg') }}"
                     alt="{{ $menu->name }}"
                     class="h-28 w-28 rounded-2xl object-cover border border-stone-700 mb-4">

                <label class="mb-1.5 block text-xs font-semibold text-stone-500 uppercase tracking-wider">Replace Photo <span class="normal-case text-stone-700">(Optional)</span></label>
                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp"
                    class="block w-full text-sm text-stone-500
                           file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0
                           file:text-sm file:font-bold file:bg-amber-400/10 file:text-amber-400
                           file:border file:border-amber-400/20
                           hover:file:bg-amber-400/20 transition cursor-pointer">
                <p class="mt-1.5 text-xs text-stone-600">Max size 2MB (JPG, PNG, WEBP)</p>
            </div>

            {{-- Active toggle --}}
            <div class="flex items-center gap-3 rounded-xl bg-stone-800 border border-stone-700 px-4 py-3">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                    {{ old('is_active', $menu->is_active) ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-stone-600 bg-stone-700 text-amber-400 focus:ring-amber-400/30 cursor-pointer">
                <label for="is_active" class="text-sm font-semibold text-stone-300 cursor-pointer">Make item publicly active</label>
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-primary glow-amber min-h-12 w-full !rounded-2xl">
                    <i class="fa-solid fa-floppy-disk text-sm"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
