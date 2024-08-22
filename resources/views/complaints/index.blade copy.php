
@extends('layouts.app2')

@section('content')

    <div>
        <ul>
            <li><a href="#" id="unread-tab">New Complaints</a></li>
            <li><a href="#" id="read-tab">Read Complaints</a></li>
        </ul>
        <table id="complaints-table"  class="custom-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Complaint Type</th>
                    <th>Message</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

@endsection
