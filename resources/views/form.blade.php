@extends('layouts.app')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="contact-one__form contact-form-validated">
    <form action="{{ url('complaints') }}" method="POST" enctype="multipart/form-data" class="wpcf7-form init" aria-label="Contact form" novalidate="novalidate" data-status="init">
        @csrf
        <div class="row">
            <!-- Name Field -->
            <div class="col-md-6">
                <div class="contact-one__input-box">
             <input type="text" name="name" class="form-control" aria-invalid="false" placeholder="اسمك" required>
                </div>
            </div>

            <!-- Email Field -->
            <div class="col-md-6">
                <div class="contact-one__input-box">
                    <input type="text" name="address" class="form-control" placeholder="الحي" required>
                </div>
            </div>

            <!-- Phone Field -->
            <div class="col-md-6">
                <div class="contact-one__input-box">
                    <input type="text" name="phone" class="form-control" placeholder="رقم هاتفك" required>
                </div>
            </div>

            <!-- Complaint Type Field -->
            <div class="col-md-6">
                <div class="contact-one__input-box">
                    <select name="complaint_type" class="form-control" required>
                        <option value="" disabled selected>نوع الشكوى</option>
                        <option value="service">الخدمة</option>
                        <option value="product">المنتج</option>
                        <option value="other">آخر</option>
                    </select>
                </div>
            </div>

            <!-- Complaint Text Area -->
            <div class="col-md-12">
                <div class="contact-one__input-box">
                    <textarea name="message" class="form-control" rows="5" placeholder="وصف الشكوى" required></textarea>
                </div>
            </div>

            <!-- Upload Images Field -->
            <div class="col-md-12">
                <div class="contact-one__input-box">

                    <label for="images">رفع صور (اختياري):</label>
                    <button type="button" onclick="document.getElementById('getFile').click()" class="nisoz-btn">
                        <span class="nisoz-btn__shape"></span><span class="nisoz-btn__shape"></span><span class="nisoz-btn__shape"></span><span class="nisoz-btn__shape"></span>
                        <span class="nisoz-btn__text">اختر </span>
                    </button>

                    <input type="file" id="getFile" name="images[]" class="form-control-file" style="display:none" multiple>
                </div>
            </div>
            <!-- Submit Button -->
        <center>
            <div class="col-md-12" style="margin:0 50px">
                <div class="contact-one__btn-box">
                    <button type="submit" class="nisoz-btn">
                        <span class="nisoz-btn__shape"></span><span class="nisoz-btn__shape"></span><span class="nisoz-btn__shape"></span><span class="nisoz-btn__shape"></span>
                        <span class="nisoz-btn__text">إرسال </span>
                    </button>
                </div>
            </div>
        </center>
        </div>
    </form>
</div>
@endsection
