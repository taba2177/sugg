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
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'complaint_type' => 'required|string|max:255',
            'message' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ] ,[
            'name.required' => 'حقل الاسم مطلوب.',
            'name.string' => 'يجب أن يكون الاسم نصًا.',
            'name.max' => 'لا يجوز أن يتجاوز الاسم 255 حرفًا.',

            'address.required' => 'حقل العنوان مطلوب.',
            'address.string' => 'يجب أن يكون العنوان نصًا.',
            'address.max' => 'لا يجوز أن يتجاوز العنوان 255 حرفًا.',

            'phone.string' => 'يجب أن يكون رقم الهاتف نصًا.',
            'phone.max' => 'لا يجوز أن يتجاوز رقم الهاتف 20 حرفًا.',

            'complaint_type.required' => 'نوع الشكوى مطلوب.',
            'complaint_type.string' => 'يجب أن يكون نوع الشكوى نصًا.',
            'complaint_type.max' => 'لا يجوز أن يتجاوز نوع الشكوى 255 حرفًا.',

            'message.required' => 'حقل الرسالة مطلوب.',
            'message.string' => 'يجب أن تكون الرسالة نصًا.',

            'images.*.image' => 'يجب أن يكون كل ملف صورة.',
            'images.*.mimes' => 'يجب أن تكون كل صورة من نوع: jpeg, png, jpg, gif, svg.',
            'images.*.max' => 'لا يجوز أن يتجاوز حجم كل صورة 2048 كيلوبايت.',
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

        return redirect()->back()->with('message', 'تم إرسال الشكوى بنجاح، ستتواصل معك الإدارة لحل المشكلة');
    }

    public function index()
    {
        return view('complaints.index');
    }

    public function getComplaints(Request $request)
    {
        $status = $request->status ?? 'unread';
        $complaints = Complaint::where('status', $status)->get();

        return response()->json(['data' => $complaints]);
    }

    public function markAsRead($id)
    {
        $complaint = Complaint::find($id);
        if ($complaint) {
            $complaint->status = 'read';
            $complaint->save();
        }

        return response()->json(['status' => 'success']);
    }
}
