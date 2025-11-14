@extends('front.layouts.app')
@section('main')
    <section class="section-5">
        <div class="container my-5">
            <div class="py-lg-2">&nbsp;</div>
            @if (Session::has('success'))  
            <div class="alert alert-success">
                <p>{{ Session::get('success') }}</p></div>  
            @endif
            <div class="row d-flex justify-content-center">
                <div class="col-md-5">
                    <div class="card shadow border-0 p-5">
                        <h1 class="h3">Register</h1>
                        <form action="#" name="registration_form" id="registration_form">
                            <div class="mb-3">
                                <label for="name" class="mb-2">Name*</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="Enter Name">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="mb-2">Email*</label>
                                <input type="text" name="email" id="email" class="form-control"
                                    placeholder="Enter Email">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="mb-2">Password*</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Please enter Password">
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="mb-2">Confirm Password*</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" placeholder="Please confirm the Password">
                                <p></p>
                            </div>
                            <button class="btn btn-primary mt-2">Register</button>
                        </form>
                    </div>
                    <div class="mt-4 text-center">
                        <p>Have an account? <a href="login.html">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('customJs')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#registration_form").submit(function(e) {
            e.preventDefault();
            // Clear previous errors
            $("input").removeClass('is-invalid');
            $("p").removeClass('invalid-feedback').html('');
            $.ajax({
                url: '{{ route('account.processRegistration') }}',
                type: 'post',
                data: $("#registration_form").serializeArray(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == false) {
                        var errors = response.errors;
                        if (errors.name) {
                            $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback')
                                .html(errors.name);
                        }
                        if (errors.email) {
                            $("#email").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.email);
                        }
                        if (errors.password) {
                            $("#password").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.password);
                        }
                        if (errors.password_confirmation) {
                            $("#password_confirmation").addClass('is-invalid').siblings('p').addClass(
                                'invalid-feedback').html(errors.password_confirmation);
                        }
                    } else {
                        alert(response.message);
                        $("#registration_form")[0].reset();
                        window.location.href='{{ route("account.login") }}';
                    }
                }
            });
        });
    </script>
@endsection
