@extends('admin.layouts.master')

@section('admin_content')
<div class="space-y-6 font-sans">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-6 bg-white rounded-2xl border border-gray-100 shadow-sm gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-images text-[#ff2d7a]"></i> Banner Management
            </h1>
            <p class="text-sm text-gray-500 mt-1">Create, organize, and control promotional layout images displayed across the storefront system.</p>
        </div>
        <div>
            <a href="{{ route('admin.banners.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#ff2d7a] hover:bg-[#e02469] text-white font-semibold text-sm rounded-xl shadow-lg shadow-[#ff2d7a]/20 transition-all duration-200">
                <i class="fa-solid fa-plus-circle"></i> Add New Banner
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 p-4 bg-emerald-50 text-emerald-800 border border-emerald-100 rounded-xl text-sm font-medium animate-fadeIn">
            <i class="fa-solid fa-circle-check text-lg text-emerald-600"></i>
            <span class="flex-1">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#111217] text-gray-300 text-xs font-bold uppercase tracking-wider border-b border-gray-800">
                        <th class="py-4 px-6">Layout Asset</th>
                        <th class="py-4 px-6">Banner Information</th>
                        <th class="py-4 px-6">Promotional Details</th>
                        <th class="py-4 px-6">Display Type</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($banners as $banner)
                    <tr class="hover:bg-gray-50/70 transition-colors duration-150">
                        <td class="py-4 px-6">
                            <img src="{{ asset($banner->image_path) }}" alt="Banner Graphic" class="w-28 h-16 object-cover rounded-xl border border-gray-200 shadow-sm">
                        </td>
                        <td class="py-4 px-6 max-w-xs">
                            <span class="font-bold text-gray-900 block text-base">{{ $banner->title }}</span>
                            @if($banner->subtitle)
                                <span class="text-xs text-gray-500 block mt-0.5 font-medium">{{ $banner->subtitle }}</span>
                            @endif
                            @if($banner->link_url)
                                <span class="text-xs text-[#ff2d7a] font-medium flex items-center gap-1 mt-1.5">
                                    <i class="fa-solid fa-link text-[10px]"></i> {{ Str::limit($banner->link_url, 30) }}
                                </span>
                            @else
                                <span class="text-xs text-gray-400 flex items-center gap-1 mt-1.5">
                                    <i class="fa-solid fa-link-slash text-[10px]"></i> No Redirect Link
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 max-w-sm">
                            @if($banner->description)
                                <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed">{{ $banner->description }}</p>
                            @else
                                <span class="text-xs text-gray-400 italic">No description details added.</span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-bold uppercase border tracking-wider
                                {{ $banner->type == 'header' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}
                                {{ $banner->type == 'section' ? 'bg-pink-50 text-[#ff2d7a] border-pink-100' : '' }}
                                {{ $banner->type == 'footer' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : '' }}
                            ">
                                {{ $banner->type }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            @if($banner->is_active)
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 block animate-pulse"></span> Live Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-500 bg-gray-50 px-2.5 py-1 rounded-full border border-gray-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400 block"></span> Disabled
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.banners.edit', $banner->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 font-semibold text-xs rounded-lg transition-colors">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <button type="button" onclick="triggerDeleteConfirm({{ $banner->id }}, '{{ addslashes($banner->title) }}')" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-100 font-semibold text-xs rounded-lg transition-colors">
                                    <i class="fa-solid fa-trash-can"></i> Delete
                                </button>
                                <form id="delete-system-form-{{ $banner->id }}" action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 px-6 text-center text-gray-400">
                            <i class="fa-solid fa-images text-5xl text-gray-200 block mb-3"></i>
                            <span class="font-bold text-gray-700 text-lg block">No Banners Found</span>
                            <p class="text-sm text-gray-400 mt-1 max-w-sm mx-auto">Click the button above to upload and register your first graphic component display layer.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="deleteConfirmationModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-fadeIn">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-xl border border-gray-100 overflow-hidden transform scale-95 transition-transform duration-200 p-6 text-center">
        <div class="w-14 h-14 bg-red-50 text-red-600 text-2xl rounded-full flex items-center justify-center mx-auto mb-4 border border-red-100">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-900">Delete Banner Layout Layer?</h3>
        <p class="text-sm text-gray-500 mt-2 leading-relaxed">
            Are you sure you want to permanently discard <span id="target-item-label" class="font-bold text-[#ff2d7a]"></span>? This action cannot be reversed.
        </p>
        <div class="flex items-center gap-3 mt-6">
            <button type="button" onclick="closeDeleteModal()" class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-xl transition-colors">Cancel</button>
            <button type="button" id="confirmed-execution-button" class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-red-600/10 transition-colors">Delete Asset</button>
        </div>
    </div>
</div>

<script>
    let dynamicTargetFormId = null;
    const modalEl = document.getElementById('deleteConfirmationModal');

    // Make sure modal references function perfectly
    function triggerDeleteConfirm(recordId, nameLabel) {
        dynamicTargetFormId = 'delete-system-form-' + recordId;
        document.getElementById('target-item-label').textContent = '"' + nameLabel + '"';
        modalEl.classList.remove('hidden');
        modalEl.classList.add('flex');
    }

    function closeDeleteModal() {
        modalEl.classList.add('hidden');
        modalEl.classList.remove('flex');
    }

    document.getElementById('confirmed-execution-button').addEventListener('click', function() {
        if(dynamicTargetFormId) {
            document.getElementById(dynamicTargetFormId).submit();
        }
    });
</script>
@endsection