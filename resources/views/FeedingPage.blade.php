@extends('layout')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

@section('content')

<h1>Atur Jumlah Pakan</h1>
<form id="feedForm" method="POST" role="form">
    @csrf
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        <i class="fa fa-egg" style="font-size: 30px; padding-right: 30px; vertical-align: middle;"></i> Umur Ayam
                    </div>
                    <div class="card-body" style="min-height: 160px; padding-top: 30px;">
                        <div class="row">
                            <div class="col-sm-6 text-center align-items-center">
                                <li>
                                    <input class="form-check-input me-1 chickAge" type="radio" name="chickAge" value="0" id="firstRadio">
                                    <label class="form-check-label" for="firstRadio" style="margin-right: 40px;">0 Minggu</label>
                                </li>
                                <li>
                                    <input class="form-check-input me-1 chickAge" type="radio" name="chickAge" value="1" id="secondRadio">
                                    <label class="form-check-label" for="secondRadio" style="margin-right: 40px;">1 Minggu</label>
                                </li>
                                <li>
                                    <input class="form-check-input me-1 chickAge" type="radio" name="chickAge" value="2" id="thirdRadio">
                                    <label class="form-check-label" for="thirdRadio" style="margin-right: 40px;">2 Minggu</label>
                                </li>
                                <li>
                                    <input class="form-check-input me-1 chickAge" type="radio" name="chickAge" value="3" id="fourthRadio">
                                    <label class="form-check-label" for="fourthRadio" style="margin-right: 40px;">3 Minggu</label>
                                </li>
                            </div>
                            <div class="col-sm-6 text-center">
                                <li>
                                    <input class="form-check-input me-1 chickAge" type="radio" name="chickAge" value="4" id="fifthRadio">
                                    <label class="form-check-label" for="fifthRadio" style="margin-right: 40px;">4 Minggu</label>
                                </li>
                                <li>
                                    <input class="form-check-input me-1 chickAge" type="radio" name="chickAge" value="5" id="sixthRadio">
                                    <label class="form-check-label" for="sixthRadio" style="margin-right: 40px;">5 Minggu</label>
                                </li>
                                <li>
                                    <input class="form-check-input me-1 chickAge" type="radio" name="chickAge" value="6" id="seventhRadio">
                                    <label class="form-check-label" for="seventhRadio" style="margin-right: 40px;">6 Minggu</label>
                                </li>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        <i class="fa fa-egg" style="font-size: 30px; padding-right: 30px; vertical-align: middle;"></i> Berat Pakan
                    </div>
                    <div class="card-body" style="min-height: 160px;">
                        <div class="row">
                            <div class="col-md-6 text-center">
                                <li>
                                    <input class="form-check-input me-1 feedAmount" type="radio" name="feedAmount" value="1" id="firstWeight">
                                    <label class="form-check-label" for="firstWeight">100gr</label>
                                </li>
                                <li>
                                    <input class="form-check-input me-1 feedAmount" type="radio" name="feedAmount" value="2" id="secondWeight">
                                    <label class="form-check-label" for="secondWeight">200gr</label>
                                </li>
                                <li>
                                    <input class="form-check-input me-1 feedAmount" type="radio" name="feedAmount" value="3" id="thirdWeight">
                                    <label class="form-check-label" for="thirdWeight">300gr</label>
                                </li>
                                <li>
                                    <input class="form-check-input me-1 feedAmount" type="radio" name="feedAmount" value="4" id="fourthWeight">
                                    <label class="form-check-label" for="fourthWeight">400gr</label>
                                </li>
                                <li>
                                    <input class="form-check-input me-1 feedAmount" type="radio" name="feedAmount" value="5" id="fifthWeight">
                                    <label class="form-check-label" for="fifthWeight">500gr</label>
                                </li>
                            </div>
                            <div class="col-md-6 text-center">
                                <li>
                                    <input class="form-check-input me-1 feedAmount" type="radio" name="feedAmount" value="6" id="sixthWeight">
                                    <label class="form-check-label" for="sixthWeight">600gr</label>
                                </li>
                                <li>
                                    <input class="form-check-input me-1 feedAmount" type="radio" name="feedAmount" value="7" id="seventhWeight">
                                    <label class="form-check-label" for="seventhWeight">700gr</label>
                                </li>
                                <li>
                                    <input class="form-check-input me-1 feedAmount" type="radio" name="feedAmount" value="8" id="eightWeight">
                                    <label class="form-check-label" for="eightWeight">800gr</label>
                                </li>
                                <li>
                                    <input class="form-check-input me-1 feedAmount" type="radio" name="feedAmount" value="9" id="ninthWeight">
                                    <label class="form-check-label" for="ninthWeight">900gr</label>
                                </li>
                                <li style="margin-left: 8px;">
                                    <input class="form-check-input me-1 feedAmount" type="radio" name="feedAmount" value="10" id="tenthWeight">
                                    <label class="form-check-label" for="tenthWeight">1000gr</label>
                                </li>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center">
                        <i class="fa fa-egg" style="font-size: 30px; padding-right: 30px; vertical-align: middle;"></i> Interval Waktu
                    </div>
                    <div class="card-body" style="min-height: 160px; padding-top: 30px;">
                        <div class="row">
                            <div class="col-sm-6 text-center">
                                <li style="margin-left: -8px;">
                                    <input class="form-check-input me-1 timeItv" type="radio" name="timeInterval" value="1">
                                    <label class="form-check-label" for="firstHours">1 jam</label>
                                </li>
                                <li>
                                    <input class="form-check-input me-1 timeItv" type="radio" name="timeInterval" value="2">
                                    <label class="form-check-label" for="secondHours">2 jam</label>
                                </li>
                                <li>
                                    <input class="form-check-input me-1 timeItv" type="radio" name="timeInterval" value="3">
                                    <label class="form-check-label" for="thirdHours">3 jam</label>
                                </li>
                            </div>
                            <div class="col-sm-6 text-center">
                                <li>
                                    <input class="form-check-input me-1 timeItv" type="radio" name="timeInterval" value="4">
                                    <label class="form-check-label" for="fourthHours">4 jam</label>
                                </li>
                                <li>
                                    <input class="form-check-input me-1 timeItv" type="radio" name="timeInterval" value="5">
                                    <label class="form-check-label" for="fifthHours">5 jam</label>
                                </li>
                                <li>
                                    <input class="form-check-input me-1 timeItv" type="radio" name="timeInterval" value="6">
                                    <label class="form-check-label" for="sixthHours">6 jam</label>
                                </li>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-4" style="width: 100%;">
            <div class="form-group">
                <button class="btn btn-warning btn-sm" type="button" id="feed" onclick="submitForm()" 
                style="width: 100px; border:none; padding: 10px; border-radius:20px; font-weight: bold; 
                color: white; background-color: rgb(220, 220, 59);">Submit</button>
            </div>
        </div>
    </div>
</form>

<script>
    function submitForm() {
    // Get selected values
    const age = document.querySelector('input[name="chickAge"]:checked')?.value;
    const feed = document.querySelector('input[name="feedAmount"]:checked')?.value;
    const time = document.querySelector('input[name="timeInterval"]:checked')?.value;

    if (!age || !feed || !time) {
        Swal.fire({
            title: "Error",
            text: "Please select all options.",
            icon: "error"
        });
        return;
    }

    // Confirm before sending AJAX request
    Swal.fire({
        title: "Submit Form",
        text: "Are you sure to set with this amount?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Proceed"
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request
            $.ajax({
                type: 'POST',
                url: "{{ route('scheduler') }}", // Make sure the route is named 'scheduler'
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Ensure CSRF token is included
                },
                data: {
                    AGE: age,
                    FEED: feed,
                    TIME: time
                },
                success: function(response) {
                    Swal.fire({
                        title: "Success",
                        text: response.message || "Data saved successfully!",
                        icon: "success"
                    });
                },
                error: function(xhr) {
                    let errorMsg = "There was an error processing your request.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message; // Show specific error if available
                    }
                    Swal.fire({
                        title: "Error",
                        text: errorMsg,
                        icon: "error"
                    });
                }
            });
        }
    });
}


    document.addEventListener('DOMContentLoaded', function () {
        const chickAgeRadios = document.querySelectorAll('input[name="chickAge"]');
        const feedAmountOptions = document.querySelectorAll('input[name="feedAmount"]');

        function toggleFeedOptions(age) {
            feedAmountOptions.forEach(option => {
                const value = parseInt(option.value);
                // Show only weights up to 500gr when 0 Minggu is selected
                if (age === "0") {
                    if (value > 5) {
                        option.parentElement.style.display = 'none';
                    } else {
                        option.parentElement.style.display = 'block';
                    }
                } else {
                    // Show all options for other ages
                    option.parentElement.style.display = 'block';
                }
            });
        }

        chickAgeRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                toggleFeedOptions(this.value);
            });
        });

        // Initialize based on the default selected age
        const selectedAge = document.querySelector('input[name="chickAge"]:checked');
        if (selectedAge) {
            toggleFeedOptions(selectedAge.value);
        }
    });
</script>

<style>
    #feed:hover {
        transition: 0.5s ease-in-out;
        background-color: rgb(93, 245, 93);
        transform: scale(1.1);
    }

    #feed:not(:hover) {
        transition: 0.5s ease-in-out;
        background-color: rgb(220, 220, 59);
        transform: scale(1.0);
    }
</style>
@endsection
