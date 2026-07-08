<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::latest()->get();
        return view('admin.pages.banner.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.pages.banner.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'type' => 'required|in:header,section,footer',
            'link_url' => 'nullable|url'
        ]);

        $file = $request->file('image');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('img/banner'), $filename);
        $imagePath = 'img/banner/' . $filename;

        Banner::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'image_path' => $imagePath,
            'link_url' => $request->link_url,
            'type' => $request->type,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner slider asset uploaded successfully.');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.pages.banner.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'type' => 'required|in:header,section,footer',
            'link_url' => 'nullable|url'
        ]);

        if ($request->hasFile('image')) {
            $oldFilePath = public_path($banner->image_path);
            if (!empty($banner->image_path) && file_exists($oldFilePath)) {
                @unlink($oldFilePath);
            }

            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/banner'), $filename);
            $banner->image_path = 'img/banner/' . $filename;
        }

        $banner->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'link_url' => $request->link_url,
            'type' => $request->type,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner parameters modified successfully.');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $filePath = public_path($banner->image_path);
        if (!empty($banner->image_path) && file_exists($filePath)) {
            @unlink($filePath);
        }
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner removed from system rendering arrays.');
    }
}