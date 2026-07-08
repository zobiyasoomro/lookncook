<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'nullable|max:255',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users')->ignore(Auth::id()),
            ],
            'phone' => 'nullable',
            'city' => 'nullable',
            'password' => 'nullable|confirmed|min:8',
            'image' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $user = Auth::user();

        $imageName = $user->image;

        if ($request->hasFile('image')) {

            if ($user->image && file_exists(public_path('img/userprofile/' . $user->image))) {
                unlink(public_path('img/userprofile/' . $user->image));
            }

            $image = $request->file('image');

            $imageName = time() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('img/userprofile'), $imageName);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'city' => $request->city,
            'image' => $imageName,
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return back()->with('success', 'Profile Updated Successfully!');
    }


    // Delete Profile Account
    public function destroy()
    {
        $user = Auth::user();

        // Delete profile image if exists
        if ($user->image && file_exists(public_path('img/userprofile/' . $user->image))) {
            unlink(public_path('img/userprofile/' . $user->image));
        }

        Auth::logout();

        $user->delete();

        return redirect()->route('/')
            ->with('success', 'Account deleted successfully!');
    }
}