@extends('layouts.master')

@section('title', 'My Profile')

@section('content')

    <style>
        body {
            background: #f7f7f7;
        }

        .profile-wrapper {
            max-width: 1000px;
            margin: 50px auto;
        }

        .profile-card {
            background: #fff;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .12);
        }

        .profile-header {
            background: linear-gradient(135deg, #ff2d7a, #ff4d94);
            padding: 70px 20px 90px;
            text-align: center;
            position: relative;
        }

        .profile-image-box {
            position: absolute;
            left: 50%;
            bottom: -65px;
            transform: translateX(-50%);
        }

        .profile-image {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 6px solid #fff;
            background: #fff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .25);
        }

        .camera-btn {
            position: absolute;
            right: 0;
            bottom: 5px;
            width: 38px;
            height: 38px;
            background: #ff2d7a;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 3px solid #fff;
        }

        .camera-btn input {
            display: none;
        }

        .profile-body {
            padding: 100px 40px 40px;
        }

        .heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .heading h3 {
            color: #ff2d7a;
            font-weight: 700;
            margin: 0;
        }

        .role-badge {
            background: #ff2d7a;
            color: #fff;
            padding: 8px 18px;
            border-radius: 30px;
            font-weight: 600;
        }

        .form-control {
            height: 55px;
            border-radius: 12px;
        }

        .form-control:focus {
            border-color: #ff2d7a;
            box-shadow: 0 0 10px rgba(255, 45, 122, .25);
        }

        .update-btn {
            background: #ff2d7a;
            color: #fff;
            border: none;
            border-radius: 40px;
            padding: 14px 50px;
            font-weight: 700;
            transition: .3s;
        }

        .update-btn:hover {
            background: #e91e63;
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 12px;
        }

        @media(max-width:768px) {

            .profile-body {
                padding: 90px 20px 30px;
            }

            .heading {
                text-align: center;
                justify-content: center;
                gap: 15px;
            }

        }
    </style>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="profile-wrapper">

            <div class="profile-card">

                <div class="profile-header">

                    <div class="profile-image-box">

                        <img src="{{ Auth::user()->image
        ? asset('img/userprofile/' . Auth::user()->image)
        : asset('img/default-user.png') }}" id="preview" class="profile-image">

                        <label class="camera-btn">

                            <i class="bi bi-camera-fill"></i>

                            <input type="file" name="image" id="image">

                        </label>

                    </div>

                </div>

                <div class="profile-body">

                    <div class="heading">

                        <h3>Update Profile</h3>

                        <span class="role-badge">

                            @if(Auth::user()->role_id == 1)

                                Admin

                            @else

                                User

                            @endif

                        </span>

                    </div>

                    @if(session('success'))

                        <div class="alert alert-success" id="success-alert">

                            {{ session('success') }}

                        </div>

                    @endif

                    @if($errors->any())

                        <div class="alert alert-danger" id="error-alert">

                            <ul class="mb-0">

                                @foreach($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif

                    <div class="row">

                        <div class="col-md-6 mb-4">

                            <label class="mb-2 fw-bold">Full Name</label>

                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', Auth::user()->name) }}">

                        </div>

                        <div class="col-md-6 mb-4">

                            <label class="mb-2 fw-bold">Email</label>

                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', Auth::user()->email) }}">

                        </div>

                        <div class="col-md-6 mb-4">

                            <label class="mb-2 fw-bold">Phone Number</label>

                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', Auth::user()->phone) }}">

                        </div>

                        <div class="col-md-6 mb-4">

                            <label class="mb-2 fw-bold">City</label>

                            <input type="text" name="city" class="form-control"
                                value="{{ old('city', Auth::user()->city) }}">

                        </div>

                        <div class="col-md-6 mb-4">

                            <label class="mb-2 fw-bold">New Password</label>

                            <input type="password" name="password" class="form-control">

                        </div>

                        <div class="col-md-6 mb-4">

                            <label class="mb-2 fw-bold">Confirm Password</label>

                            <input type="password" name="password_confirmation" class="form-control">

                        </div>

                    </div>
                    <div class="text-center mt-3 d-flex flex-column align-items-center gap-2">

                        <button class="update-btn">

                            <i class="bi bi-check-circle-fill"></i>

                            Update Profile

                        </button>



                    </div>

                </div>

            </div>

        </div>

    </form>
    <form action="{{ route('profile.delete') }}" method="POST" class="text-center mb-5">
        @csrf
        @method('DELETE')

        <button type="submit" class="btn btn-danger rounded-pill px-5 py-3 fw-bold"
            onclick="return confirm('Are you sure you want to delete your account?')">

            <i class="bi bi-trash-fill"></i>
            Delete Account

        </button>

    </form>

    <script>
        // Success Alert
        setTimeout(function () {
            let success = document.getElementById('success-alert');

            if (success) {
                success.style.transition = "opacity .5s";
                success.style.opacity = "0";

                setTimeout(function () {
                    success.remove();
                }, 500);
            }
        }, 3000);

        // Error Alert
        setTimeout(function () {
            let error = document.getElementById('error-alert');

            if (error) {
                error.style.transition = "opacity .5s";
                error.style.opacity = "0";

                setTimeout(function () {
                    error.remove();
                }, 500);
            }
        }, 3000);
    </script>

@endsection