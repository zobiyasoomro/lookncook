@extends('admin.layouts.master')

@section('admin_content')
<div class="max-w-4xl mx-auto space-y-6 font-sans">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-6 bg-white rounded-2xl border border-gray-100 shadow-sm gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-square-plus text-[#ff2d7a]"></i> Add New Banner
            </h1>
            <p class="text-sm text-gray-500 mt-1">Upload an image and configure its landing properties for the promotional system loop.</p>
        </div>
        <div>
            <a href="{{ route('admin.banners.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition-colors duration-200">
                <i class="fa-solid fa-arrow-left"></i> Back to Management
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-red-50 border border-red-100 text-red-800 rounded-xl space-y-1">
            <span class="font-bold block text-sm">Please correct the following errors:</span>
            <ul class="list-disc list-inside text-xs space-y-0.5 opacity-90">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label for="title" class="block text-sm font-bold text-gray-700">Banner Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required placeholder="e.g., Summer Deal Campaign" 
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-[#ff2d7a] focus:ring-2 focus:ring-[#ff2d7a]/20 outline-none transition-all">
            </div>

            <div class="space-y-2">
                <label for="subtitle" class="block text-sm font-bold text-gray-700">Banner Subtitle <span class="text-xs text-gray-400 font-normal">(Optional)</span></label>
                <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle') }}" placeholder="e.g., Up to 50% Off on All Orders" 
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-[#ff2d7a] focus:ring-2 focus:ring-[#ff2d7a]/20 outline-none transition-all">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label for="link_url" class="block text-sm font-bold text-gray-700">Redirect Link Target URL <span class="text-xs text-gray-400 font-normal">(Optional)</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-link text-xs"></i>
                    </div>
                    <input type="url" name="link_url" id="link_url" value="{{ old('link_url') }}" placeholder="https://lookncook.com/offers/summer-deals" 
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-[#ff2d7a] focus:ring-2 focus:ring-[#ff2d7a]/20 outline-none transition-all">
                </div>
            </div>

            <div class="space-y-2">
                <label for="type" class="block text-sm font-bold text-gray-700">Display Position Placement <span class="text-red-500">*</span></label>
                <select name="type" id="type" required 
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-[#ff2d7a] focus:ring-2 focus:ring-[#ff2d7a]/20 outline-none transition-all bg-white">
                    <option value="header" {{ old('type') == 'header' ? 'selected' : '' }}>Header (Top Slider Hero Area)</option>
                    <option value="section" {{ old('type') == 'section' ? 'selected' : '' }}>Section (Middle Promotion Grid)</option>
                    <option value="footer" {{ old('type') == 'footer' ? 'selected' : '' }}>Footer (Bottom Promotional Stripe)</option>
                </select>
            </div>
        </div>

        <div class="space-y-2">
            <label for="description" class="block text-sm font-bold text-gray-700">Description / Promotional Text <span class="text-xs text-gray-400 font-normal">(Optional)</span></label>
            <textarea name="description" id="description" rows="3" placeholder="Describe the purpose of this promotional layer banner context..." 
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-[#ff2d7a] focus:ring-2 focus:ring-[#ff2d7a]/20 outline-none transition-all resize-none">{{ old('description') }}</textarea>
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-bold text-gray-700">Upload Image File Asset <span class="text-red-500">*</span></label>
            <div class="border-2 border-dashed border-gray-200 hover:border-[#ff2d7a] rounded-2xl p-6 transition-colors duration-200 bg-gray-50/50 text-center relative group">
                <input type="file" name="image" id="image_upload" accept="image/*" required class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10">
                <div id="upload_preview_container" class="space-y-2 pointer-events-none">
                    <div class="w-12 h-12 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center mx-auto text-gray-400 group-hover:text-[#ff2d7a] transition-colors">
                        <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-700" id="file_status_text">Click or drag image asset here</p>
                    <p class="text-xs text-gray-400">Supports JPEG, PNG, JPG, or WEBP layouts up to 3MB</p>
                </div>
                <img id="image_preview_el" class="hidden mx-auto max-h-48 rounded-xl shadow-sm border border-gray-200 p-1 bg-white">
            </div>
        </div>

        <div class="flex items-center p-4 bg-gray-50/60 rounded-xl border border-gray-100">
            <label class="flex items-center cursor-pointer select-none gap-3 w-full">
                <div class="relative">
                    <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                </div>
                <div>
                    <span class="text-sm font-bold text-gray-800 block">Set Status to Active</span>
                    <span class="text-xs text-gray-400 block mt-0.5">If disabled, this asset will be hidden from storefront API layout loops.</span>
                </div>
            </label>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.banners.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition-colors">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-[#ff2d7a] hover:bg-[#e02469] text-white font-semibold text-sm rounded-xl shadow-lg shadow-[#ff2d7a]/20 transition-all">
                <i class="fa-solid fa-paper-plane me-1.5 text-xs"></i> Save Asset
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('image_upload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const previewEl = document.getElementById('image_preview_el');
        const containerEl = document.getElementById('upload_preview_container');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewEl.src = event.target.result;
                previewEl.classList.remove('hidden');
                containerEl.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection