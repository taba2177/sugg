@extends('layouts.app2')

@section('content')
    <div class="container custom-table-container">
        <!-- Tabs for switching between unread and read complaints -->
        <ul class="nav nav-tabs" id="complaints-tabs">
            <li class="nav-item">
                <a class="nav-link active" id="unread-tab">جديد</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="read-tab" >مكتمل</a>
            </li>
        </ul>
    </div>
<br>

        <!-- DataTable -->
        <table id="complaints-table" class="display custom-table" style="width:100%"  >
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>الحي</th>
                    <th>الجوال</th>
                    <th>نوع الشكوى</th>
                    <th>التفاصيل</th>
                    <th>تاريخ الشكوى</th>
                    <th>صور</th>
                    <th>مكتمل/غير مكتمل</th>
                </tr>
            </thead>
            <tbody>
                <!-- DataTables will populate this automatically -->
            </tbody>
        </table>

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
                    language: {
                    url: '/ar.json',
                    },
                    processing: false,
                    serverSide: false,
                    destroy: true, // Destroy the previous instance
                    ajax: {
                        url: '{{ route("complaints.get") }}',
                        data: { status: status },
                        // dataSrc: function (json) {
                        // console.log("Received data:", json);
                        // return json.data;
                        // }
                        dataSrc: function(json) {
                            // if (!json.recordsTotal || !json.recordsFiltered) {
                            //     return [];
                            // }
                            // console.log("Received data:", json);
                            return json.data;
                        }
                    },
                    columns: [
                        { data: 'name' },
                        { data: 'address' },
                        { data: 'phone' },
                        { data: 'complaint_type' },
                        { data: 'message' },
                        { data: 'created_at' ,
                        render: function(data) {

                        var date = new Date(data);
                        var months = ["يناير", "فبراير", "مارس", "إبريل", "مايو", "يونيو",
                            "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر"
                            ];
                        var days = ["اﻷحد", "اﻷثنين", "الثلاثاء", "اﻷربعاء", "الخميس", "الجمعة", "السبت"];
                        var delDateString = days[date.getDay()] + ', ' + date.getDate() + ' ' + months[date.getMonth()] + ', ' + date.toLocaleTimeString();
                        return delDateString;
                        }
                        },
                        {
                            data: 'images', // Assuming the JSON has an 'images' array
                            // render: function(data, type, row) {
                            //     if (data && data.length > 0) {
                            //         return data.map(image => `<img src="/storage/${image}" class="img-thumbnail" data-full="/storage/${image}" style="max-width: 100px;" alt="Image">`).join('');
                            //     } else {
                            //         return 'No Image';
                            //     }
                            // }
                            render: function(data, type, row) {
                        if (data && data.length > 0) {
                            let gallery = '<div class="complaint-gallery">';
                            data.forEach(image => {
                                gallery += `<a href="/storage/${image}" data-lg-size="1600-1067">
                                              <img src="/storage/${image}" class="img-thumbnail" alt="Image">
                                            </a>`;
                            });
                            gallery += '</div>';
                            return gallery;
                        } else {
                            return 'No Image';
                        }
                    }
                        },
                        {
                            data: 'id',
                            render: function(data, type, row) {
                                if (status === 'unread') {
                                    return `<button class="mark-as-read btn btn-sm btn-primary" data-id="${data}">اكمال الاجراء</button>`;
                                } else {
                                    return 'مكتمل';
                                }
                            }
                        }
                    ],
                    responsive: true,
                    paging: true,
                    scrollY: 400,
                    searching: false,
                    pagingType: "simple_numbers", // Show pagination controls
                    pageLength: 10, // Set default page length
                    lengthChange: false, // Disable length change dropdown
                    autoWidth: true, // Disable automatic column width calculation
                    scrollCollapse: true, // Enable scroll collapse
                    order: [[0, 'asc']], // Default ordering on first column
                    stateSave: false, // Save the state of the table including pagination and search
                    drawCallback: function(settings) {
                $('.complaint-gallery').each(function() {
                    lightGallery(this, {
                        selector: 'a',
                        zoom: true,
                        fullScreen: true,
                        thumbnail: true,
                    });
                });
            }
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
            }, 500); // 30 seconds

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

            // Close modal when clicking the close button or outside the modal content
            $('.modal-close').on('click', function() {
                $('#imageModal').fadeOut();
            });

            // $('#imageModal').on('click', function(e) {
            //     if ($(e.target).is('.modal-content') === false) {
            //         $(this).fadeOut();
            //     }
            // });
        });
    </script>
@endsection
