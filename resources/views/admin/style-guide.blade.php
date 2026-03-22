@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-8">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Design System & Style Guide</h1>
        <p class="text-lg text-gray-600">Updated for full responsiveness, accessibility (WCAG 2.2 AA), and mobile-first architecture.</p>
    </div>

    {{-- Breakpoints --}}
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Responsive Breakpoints</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                <span class="block text-xs font-bold text-amber-600 uppercase mb-1">Mobile (xs)</span>
                <span class="text-xl font-bold">320px – 640px</span>
            </div>
            <div class="p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                <span class="block text-xs font-bold text-amber-600 uppercase mb-1">Tablet (sm/md)</span>
                <span class="text-xl font-bold">640px – 1024px</span>
            </div>
            <div class="p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                <span class="block text-xs font-bold text-amber-600 uppercase mb-1">Laptop (lg)</span>
                <span class="text-xl font-bold">1024px – 1440px</span>
            </div>
            <div class="p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                <span class="block text-xs font-bold text-amber-600 uppercase mb-1">Desktop (xl+)</span>
                <span class="text-xl font-bold">1440px+</span>
            </div>
        </div>
    </section>

    {{-- Typography --}}
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Typography (Relative Units)</h2>
        <div class="space-y-4 bg-white p-6 rounded-xl border border-gray-100">
            <div>
                <p class="text-xs text-gray-400 mb-1">text-4xl (2.25rem)</p>
                <h1 class="text-4xl font-bold">The quick brown fox jumps over the lazy dog</h1>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">text-3xl (1.875rem)</p>
                <h2 class="text-3xl font-bold">The quick brown fox jumps over the lazy dog</h2>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">text-2xl (1.5rem)</p>
                <h3 class="text-2xl font-bold">The quick brown fox jumps over the lazy dog</h3>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">text-base (1rem)</p>
                <p class="text-base">The quick brown fox jumps over the lazy dog. Standard body text using relative units for scalability and accessibility.</p>
            </div>
        </div>
    </section>

    {{-- Colors --}}
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Color Palette (Amber System)</h2>
        <div class="grid grid-cols-2 sm:grid-cols-5 lg:grid-cols-10 gap-2">
            @foreach([50, 100, 200, 300, 400, 500, 600, 700, 800, 900] as $shade)
                <div class="space-y-1">
                    <div class="h-16 w-full rounded-lg bg-amber-{{ $shade }} shadow-inner"></div>
                    <p class="text-center text-xs font-medium text-gray-600">{{ $shade }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Components --}}
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Component Library</h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Buttons --}}
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-700">Buttons (Touch-Friendly ≥48px)</h3>
                <div class="flex flex-wrap gap-4 items-center">
                    <button class="px-6 py-3 bg-amber-600 text-white rounded-xl font-bold hover:bg-amber-700 transition touch-target">
                        Primary Action
                    </button>
                    <button class="px-6 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition touch-target">
                        Secondary Action
                    </button>
                    <button class="p-3 text-amber-600 hover:bg-amber-50 rounded-full transition touch-target" aria-label="Icon Button">
                        <i class="fa-solid fa-plus text-xl"></i>
                    </button>
                </div>
                <p class="text-sm text-gray-500 italic">All interactive elements use the `.touch-target` class ensuring a minimum 48x48px hit area.</p>
            </div>

            {{-- Form Elements --}}
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-700">Form Inputs</h3>
                <div class="space-y-4">
                    <div>
                        <label for="sample-input" class="block text-sm font-semibold text-gray-700 mb-1">Standard Input</label>
                        <input type="text" id="sample-input" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition outline-none" placeholder="Enter text here...">
                    </div>
                    <div>
                        <label for="sample-select" class="block text-sm font-semibold text-gray-700 mb-1">Standard Select</label>
                        <select id="sample-select" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition outline-none bg-white">
                            <option>Option 1</option>
                            <option>Option 2</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Accessibility Checklist --}}
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Accessibility (WCAG 2.2 AA)</h2>
        <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-xl">
            <ul class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <li class="flex items-start gap-3">
                    <i class="fa-solid fa-check-circle text-emerald-600 mt-1"></i>
                    <div>
                        <span class="font-bold block">Touch Targets</span>
                        <span class="text-sm text-emerald-800">Minimum 48x48px hit area for all interactive elements.</span>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fa-solid fa-check-circle text-emerald-600 mt-1"></i>
                    <div>
                        <span class="font-bold block">Focus States</span>
                        <span class="text-sm text-emerald-800">High-contrast focus rings for keyboard navigation.</span>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fa-solid fa-check-circle text-emerald-600 mt-1"></i>
                    <div>
                        <span class="font-bold block">Color Contrast</span>
                        <span class="text-sm text-emerald-800">Text elements maintain at least 4.5:1 contrast ratio.</span>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <i class="fa-solid fa-check-circle text-emerald-600 mt-1"></i>
                    <div>
                        <span class="font-bold block">ARIA Labels</span>
                        <span class="text-sm text-emerald-800">Semantic HTML and ARIA labels for screen readers.</span>
                    </div>
                </li>
            </ul>
        </div>
    </section>
</div>
@endsection
