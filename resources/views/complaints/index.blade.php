<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints Table</title>
    <!-- Include Bootstrap CSS for styling and modals -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <!-- Custom CSS for table design and margins -->
    <style>
        .custom-table-container {
            margin: 20px;
        }
        .custom-table th, .custom-table td {
            vertical-align: middle;
        }
        .img-thumbnail {
            width: 50px;
            height: auto;
            cursor: pointer;
        }
        .modal-img {
            max-width: 100%;
        }
    </style>
</head>
<body>
    <div class="container custom-table-container">
        <!-- Tabs for switching between unread and read complaints -->
        <ul class="nav nav-tabs" id="complaints-tabs">
            <li class="nav-item">
                <a class="nav-link active" id="unread-tab" href="#">Unread Complaints</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="read-tab" href="#">Read Complaints</a>
            </li>
        </ul>

        <!-- DataTable -->
        <table id="complaints-table" class="display custom-table" style="width:100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Complaint Type</th>
                    <th>Message</th>
                    <th>Images</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- DataTables will populate this automatically -->
            </tbody>
        </table>
    </div>

    <!-- Modal for displaying large images -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="modal-img" src="" alt="Complaint Image" class="modal-img">
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            let status = 'unread';
            let scrollPos = 0; // Variable to store scroll position

            loadComplaints(status);

            // Function to load complaints based on status
            function loadComplaints(status) {
                const table = $('#complaints-table').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true, // Destroy the previous instance
                    ajax: {
                        url: '{{ route("complaints.get") }}',
                        data: { status: status },
                        dataSrc: function (json) {
                        console.log("Received data:", json);
                        return json.data;
                        }
                        // dataSrc: function(json) {
                        //     if (!json.recordsTotal || !json.recordsFiltered) {
                        //         return [];
                        //     }
                        //     return json.data;
                        // }
                    },
                    columns: [
                        { data: 'name' },
                        { data: 'address' },
                        { data: 'phone' },
                        { data: 'complaint_type' },
                        { data: 'message' },
                        {
                            data: 'images', // Assuming the JSON has an 'images' array
                            render: function(data, type, row) {
                                if (data && data.length > 0) {
                                    return data.map(image => `<img src="${image.url}" class="img-thumbnail" data-full="${image.full_url}" alt="Image">`).join('');
                                } else {
                                    return 'No Image';
                                }
                            }
                        },
                        {
                            data: 'id',
                            render: function(data, type, row) {
                                if (status === 'unread') {
                                    return `<button class="mark-as-read btn btn-sm btn-primary" data-id="${data}">Mark as Read</button>`;
                                } else {
                                    return 'Already Read';
                                }
                            }
                        }
                    ],
                    paging: true,
                    pagingType: "simple_numbers", // Show pagination controls
                    pageLength: 10, // Set default page length
                    lengthChange: false, // Disable length change dropdown
                    autoWidth: false, // Disable automatic column width calculation
                    scrollCollapse: true, // Enable scroll collapse
                    order: [[0, 'asc']], // Default ordering on first column
                    stateSave: true, // Save the state of the table including pagination and search
                });

                // Preserve scroll position after reload
                table.on('preXhr.dt', function() {
                    scrollPos = $(window).scrollTop();
                });

                table.on('xhr.dt', function() {
                    $(window).scrollTop(scrollPos);
                });
            }

            // Auto-fetch new complaints every 30 seconds
            setInterval(function() {
                $('#complaints-table').DataTable().ajax.reload(null, false); // Reload without resetting paging
            }, 30000); // 30 seconds

            // Event listeners for tab clicks
            $('#unread-tab').on('click', function() {
                status = 'unread';
                loadComplaints(status);
            });

            $('#read-tab').on('click', function() {
                status = 'read';
                loadComplaints(status);
            });

            // Mark complaint as read
            $(document).on('click', '.mark-as-read', function() {
                let complaintId = $(this).data('id');

                $.ajax({
                    url: '{{ url("complaints/mark-as-read") }}/' + complaintId,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#complaints-table').DataTable().ajax.reload(null, false); // Reload without resetting paging
                    }
                });
            });

            // Image click event to open modal
            $(document).on('click', '.img-thumbnail', function() {
                const imgSrc = $(this).data('full');
                $('#modal-img').attr('src', imgSrc);
                $('#imageModal').modal('show');
            });
        });
    </script>
</body>
</html>
