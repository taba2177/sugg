<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'complaint_type' => 'required|string|max:255',
            'message' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
dd($request);
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $file) {
                $path = $file->store('complaints', 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
        }

        Complaint::create($validated);

        return redirect()->back()->with('success', 'تم إرسال الشكوى/الاقتراح بنجاح');
    }
}
