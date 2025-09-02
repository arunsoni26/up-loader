<style>
    body {
        background-color: #f8f9fa;
        color: #495057;
    }

    .profile-header {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 25px;
        margin-bottom: 25px;
    }

    .profile-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 5px solid #fff;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    }

    .profile-stats {
        display: flex;
        justify-content: space-around;
        text-align: center;
        margin: 20px 0;
    }

    .stat-item {
        padding: 0 15px;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: #4a6cf7;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #6c757d;
    }

    .profile-content {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 25px;
        margin-bottom: 25px;
    }

    .nav-tabs .nav-link {
        color: #495057;
        font-weight: 500;
        border: none;
        padding: 12px 20px;
    }

    .nav-tabs .nav-link.active {
        color: #4a6cf7;
        border-bottom: 3px solid #4a6cf7;
        background: transparent;
    }

    .profile-table {
        width: 100%;
    }

    .profile-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #e9ecef;
    }

    .profile-table tr:last-child td {
        border-bottom: none;
    }

    .btn-follow {
        background-color: #4a6cf7;
        color: white;
        padding: 8px 25px;
        font-weight: 500;
        border-radius: 6px;
    }

    .btn-follow:hover {
        background-color: #3b5be3;
    }
</style>
<div class="container py-5">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="row align-items-center">
            <div class="col-md-2 text-center">
                @php
                    $profilePic = url('/img/avatars/dummyavatar.png');
                    if (!empty($user->image)) {
                        $profilePic = Storage::disk('s3')->temporaryUrl($user->image, now()->addMinutes(120));
                    }
                @endphp
                <img src="{{ $profilePic }}" alt="Profile Image" class="profile-img">
            </div>

            <div class="col-md-7">
                <h2>{{ $customer->name }}</h2>
                <span class="text-primary"><i class="fa fa-envelope"></i> {{ $customer->email ?? 'N/A' }}</span><br>
                <span class="text-primary"><i class="fa fa-phone"></i> {{ $customer->mobile_no ?? 'N/A' }}</span>
            </div>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="profile-content mt-4">
        <h4 class="mb-4">View Detail</h4>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4" id="profileTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">
                    Personal Details
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">
                    GST Details
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="credential-tab" data-bs-toggle="tab" data-bs-target="#credentials" type="button" role="tab">
                    Credetials
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="other-tab" data-bs-toggle="tab" data-bs-target="#other" type="button" role="tab">
                    Others
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="profileTabContent">
            <!-- Basic Info Tab -->
            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                <table class="profile-table">
                    <tr>
                        <td><strong>Father's Name:</strong></td>
                        <td>{{ $customer->father_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Group</strong></td>
                        <td>{{ $customer->group->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Code</strong></td>
                        <td>{{ $customer->code ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Address</strong></td>
                        <td>{{ $customer->address ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Client Type Status</strong></td>
                        <td>{{ ucfirst($customer->client_type_status) ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Date of Birth</strong></td>
                        <td>{{ $customer->dob ? $customer->dob->format('d-m-Y') : 'N/A' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Documents Tab -->
            <div class="tab-pane fade" id="documents" role="tabpanel">
                <table class="profile-table">
                    <tr>
                        <td><strong>GST Name</strong></td>
                        <td>{{ $customer->gst_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>GST No</strong></td>
                        <td>
                            {{ $customer->gst ?? 'N/A' }}
                            @if($customer->gst_doc)
                            <a href="{{ asset('uploads/'.$customer->gst_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                <i class="fa fa-eye"></i> View GST Doc
                            </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>PAN</strong></td>
                        <td>
                            {{ $customer->pan ?? 'N/A' }}
                            @if($customer->pan_doc)
                                <a href="{{ Storage::disk('s3')->temporaryUrl($customer->pan_doc, now()->addMinutes(1), ['ResponseContentDisposition' => 'attachment; filename="' . basename($customer->pan_doc) . '"']) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                    <i class="fa fa-eye"></i> View PAN Doc
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Aadhaar</strong></td>
                        <td>
                            {{ $customer->aadhar ?? 'N/A' }}
                            @if($customer->aadhar_doc)
                            <a href="{{ asset('uploads/'.$customer->aadhar_doc) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                <i class="fa fa-eye"></i> View Aadhaar Doc
                            </a>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Credentials Tab -->
            <div class="tab-pane fade" id="credentials" role="tabpanel">
                <table class="profile-table">
                    <tr>
                        <td><strong>Password</strong></td>
                        <td>
                            <span id="passwordText">********</span>
                            <button type="button" class="btn btn-sm btn-link p-0 ms-2" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Other Tab -->
            <div class="tab-pane fade" id="other" role="tabpanel">
                <table class="profile-table">
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>
                            {!! $customer->status ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>' !!}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Dashboard</strong></td>
                        <td>
                            {!! $customer->hide_dashboard ? '<span class="badge bg-success">Visible</span>' : '<span class="badge bg-secondary">Hidden</span>' !!}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("click", function(e) {
        if (e.target.closest("#togglePassword")) {
            const togglePassword = e.target.closest("#togglePassword");
            const passwordText = document.getElementById("passwordText");
            const icon = togglePassword.querySelector("i");
            const actualPassword = @json($customer -> password ?? 'N/A');

            if (passwordText.dataset.visible === "true") {
                passwordText.textContent = "********";
                passwordText.dataset.visible = "false";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                passwordText.textContent = actualPassword;
                passwordText.dataset.visible = "true";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }
    });
</script>