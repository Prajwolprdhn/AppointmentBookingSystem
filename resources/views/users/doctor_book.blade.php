@extends('users.dashboard')

@section('content')
    {{-- {{ $errors }} --}}
    <!-- Content Header (Page header) -->
    <div class="content-header mt-4" style="margin-left: 500px">
        <div class="row">
            {{-- {{ dd($selectedDepartment) }} --}}
            <div class="col-sm-1">
                <div class="dropdown show text-center">
                    <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ $selectedDepartment ? $selectedDepartment->departments : 'Departments' }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        @foreach ($departments as $department)
                            <a class="dropdown-item" href="#" onclick="submitDepartment({{ $department->id }})">
                                {{ $department->departments }}
                            </a>
                        @endforeach
                    </div>

                    <form id="departmentForm" method="POST" action="{{ route('filter_doctor') }}" style="display: none;">
                        @csrf
                        <input type="hidden" name="department_id" id="departmentIdInput">
                    </form>

                </div>
            </div>
            <div class="col-sm-6" style="margin-left: 90px">
                <form class="form-inline my-2 my-lg-0" action="{{ route('filter_doctor') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <input class="form-control mr-sm-2" type="search" name="search" placeholder="Search"
                                aria-label="Search" required>
                        </div>
                        <div class="col-3">
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <!-- /.content-header -->
    <div class="content-wrapper mb-4">
        <div class="content ml-2">
            <div class="container-fluid">
                <div class="row">
                    @forelse ($doctors as $doctor)
                        <div class="col-lg-3">
                            <div class="card mb-3 pb-3">
                                <div class="card-body text-center">
                                    @if ($doctor->photo)
                                        <img src="{{ asset($doctor->photo) }}" alt="avatar"
                                            class="rounded-circle img-fluid" style="width: 150px; height: 150px;">
                                    @else
                                        <img src="{{ asset('images/blank.jpg') }}" alt="default-avatar"
                                            class="rounded-circle img-fluid" style="width: 150px; height: 150px;">
                                    @endif
                                    <h5 class="my-3">{{ $doctor->first_name . ' ' . $doctor->last_name }}</h5>
                                    <p class="text-muted mb-2">{{ $doctor->department->departments ?? '-' }}</p>
                                    <p class="text-muted mb-1">Bay Area, San Francisco, CA</p>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-9">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <p class="mb-0">Available Time</p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        @foreach ($doctor->schedule as $schedule)
                                            @if ($doctor->schedule)
                                                @php
                                                    $groupedSchedules = $doctor->schedule->groupBy('date_ad');
                                                    $dataFound = false;
                                                @endphp
                                                @foreach ($groupedSchedules as $date => $doctors)
                                                    @if ($date == $dateFormat['today'] || $date == $dateFormat['tomorrow'])
                                                        <div class="col-sm-3">
                                                            <p class="mb-0">{{ $date }}</p>
                                                        </div>

                                                        <div class="col-sm-9">
                                                            @foreach ($doctors as $doctor)
                                                                @if ($doctor->status == 0)
                                                                    @php
                                                                        $dataFound = true;
                                                                    @endphp
                                                                    <button type="button" class="btn btn-primary mb-3 mr-3"
                                                                        data-bs-toggle="modal" data-bs-target="#myModal"
                                                                        data-doctor-id="{{ $doctor->id }}"
                                                                        style="color:white;">
                                                                        {{ $doctor->start_time . ' - ' . $doctor->end_time }}
                                                                    </button>
                                                                @endif
                                                                <!-- Popup Form -->
                                                                <div class="modal" id="myModal">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <form action="{{ route('booking.store') }}"
                                                                                method="post">
                                                                                @csrf
                                                                                <div class="modal-header bg-primary">
                                                                                    <h5 class="modal-title"
                                                                                        style="color: white">Personal
                                                                                        Details
                                                                                    </h5>
                                                                                    <div class="ms-auto">
                                                                                        <button type="button"
                                                                                            class="btn-close"
                                                                                            data-bs-dismiss="modal"
                                                                                            style="background-color: white"></button>

                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="mb-3">
                                                                                        <label
                                                                                            class="form-label required">Full
                                                                                            Name</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            id="name" name="name"
                                                                                            placeholder="Full Name">
                                                                                        @error('name')
                                                                                            <span
                                                                                                class="text-danger">{{ $message }}</span>
                                                                                        @enderror
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label
                                                                                            class="form-label required">Address</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            id="address" name="address"
                                                                                            placeholder="Address">
                                                                                        @error('address')
                                                                                            <span
                                                                                                class="text-danger">{{ $message }}</span>
                                                                                        @enderror
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label
                                                                                            class="form-label required">Contact
                                                                                            No.</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            id="contact" name="contact"
                                                                                            placeholder="Contact Details">
                                                                                        @error('contact')
                                                                                            <span
                                                                                                class="text-danger">{{ $message }}</span>
                                                                                        @enderror
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label
                                                                                            class="form-label required">Gender</label>
                                                                                        <select
                                                                                            class ="form-control select2"
                                                                                            name="gender">
                                                                                            <option disabled="disabled"
                                                                                                selected="selected">--
                                                                                                Not
                                                                                                Selected --
                                                                                            </option>
                                                                                            <option value="Male">Male
                                                                                            </option>
                                                                                            <option value="Female">
                                                                                                Female
                                                                                            </option>
                                                                                            <option value="Others">
                                                                                                Other
                                                                                            </option>
                                                                                        </select>
                                                                                        @error('contact')
                                                                                            <span
                                                                                                class="text-danger">{{ $message }}</span>
                                                                                        @enderror
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label
                                                                                            class="form-label required">Email</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            id="email" name="email"
                                                                                            placeholder="Email ID">
                                                                                        @error('email')
                                                                                            <span
                                                                                                class="text-danger">{{ $message }}</span>
                                                                                        @enderror
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label
                                                                                            class="form-label required">Date
                                                                                            Of Birth</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            id="modal-nepali-date-picker"
                                                                                            name="dob_bs"
                                                                                            placeholder="YYYY-MM-DD"
                                                                                            required>
                                                                                        @error('dob_bs')
                                                                                            <span
                                                                                                class="text-danger">{{ $message }}</span>
                                                                                        @enderror
                                                                                    </div>
                                                                                    <div class="mb-3" hidden>
                                                                                        <label
                                                                                            class="form-label required">Date
                                                                                            in A.D</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            id="english_date"
                                                                                            onclick="getDate()"
                                                                                            name="dob_ad"
                                                                                            placeholder="YYYY-MM-DD">
                                                                                        @error('dob_ad')
                                                                                            <span
                                                                                                class="text-danger">{{ $message }}</span>
                                                                                        @enderror
                                                                                    </div>
                                                                                    <div class="mb-3" hidden>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            id="current_bs"
                                                                                            name="book_date_bs"
                                                                                            placeholder="YYYY-MM-DD">
                                                                                        @error('book_date_bs')
                                                                                            <span
                                                                                                class="text-danger">{{ $message }}</span>
                                                                                        @enderror
                                                                                    </div>
                                                                                    <div class="mb-3" hidden>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            id="current_ad"
                                                                                            name="book_date_ad"
                                                                                            placeholder="YYYY-MM-DD">
                                                                                        @error('book_date_ad')
                                                                                            <span
                                                                                                class="text-danger">{{ $message }}</span>
                                                                                        @enderror
                                                                                    </div>
                                                                                    <div class="mb-3" hidden>
                                                                                        <label
                                                                                            class="form-label required">Doctors
                                                                                            ID</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            id="doctors_id"
                                                                                            name="schedule_id">
                                                                                    </div>
                                                                                    <div class="mb-3">

                                                                                        {!! NoCaptcha::renderJs() !!}
                                                                                        {!! NoCaptcha::display() !!}
                                                                                        @error('g-recaptcha-response')
                                                                                            <span
                                                                                                class="text-danger">{{ $message }}</span>
                                                                                        @enderror
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer"
                                                                                    aria-required="true">
                                                                                    <button type="submit"
                                                                                        class="btn btn-danger"
                                                                                        data-bs-dismiss="modal">Cancel</button>

                                                                                    <button type="submit"
                                                                                        class="btn btn-success"
                                                                                        style="color: white"
                                                                                        onclick="getDate()">Submit</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                            @if (!$dataFound)
                                                                <p class="text-muted mb-3">No appointment available.
                                                                </p>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-lg-12 text-center">
                            <div class="card mx-4">
                                <div class="card-body" style="padding-top:60px; padding-bottom:60px;">
                                    <p class="h3 text-muted mb-0">Oops!! Currently No Doctor Are Available.</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        function submitDepartment(departmentId) {
            document.getElementById('departmentIdInput').value = departmentId;
            document.getElementById('departmentForm').submit();
        }
    </script>
@endsection
