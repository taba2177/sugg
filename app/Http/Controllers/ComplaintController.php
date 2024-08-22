<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{

    public function index(Request $request)
    {

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'complaint_type' => 'required|string|max:255',
            'message' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $file) {
                $path = $file->store('complaints', 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
        }

        Complaint::create($validated);

        return redirect()->back()->with('message', 'تم إرسال الشكوى بنجاح،ستتواصل معك الإدارة لحل المشكلة');
    }
}
