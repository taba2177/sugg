
@extends('layouts.app')

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

    <script src="{{ asset('js/app.js') }}">
        $(document).ready(function() {
            loadComplaints('unread');

            $('#unread-tab').on('click', function() {
                loadComplaints('unread');
            });

            $('#read-tab').on('click', function() {
                loadComplaints('read');
            });

            function loadComplaints(status) {
                $('#complaints-table').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true, // Destroy the previous instance
                    ajax: {
                        url: '{{ route("complaints.get") }}',
                        data: { status: status },
                    },
                    columns: [
                        { data: 'name' },
                        { data: 'address' },
                        { data: 'phone' },
                        { data: 'complaint_type' },
                        { data: 'message' },
                        {
                            data: 'id',
                            render: function(data, type, row) {
                                if (status === 'unread') {
                                    return `<button class="mark-as-read" data-id="${data}">Mark as Read</button>`;
                                } else {
                                    return 'Already Read';
                                }
                            }
                        }
                    ]
                });
            }

            $(document).on('click', '.mark-as-read', function() {
                let complaintId = $(this).data('id');

                $.ajax({
                    url: '{{ url("complaints/mark-as-read") }}/' + complaintId,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#complaints-table').DataTable().ajax.reload();
                    }
                });
            });
        });
    </script>

@endsection
