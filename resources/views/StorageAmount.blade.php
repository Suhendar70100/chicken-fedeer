@extends('layout')

@section('content')
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <i class="fa fa-warehouse" style="font-size: 30px; padding-right: 30px; vertical-align: middle;"></i> Total Pakan dalam Storage (kg)
                </div>
                <form action="#">
                    @csrf
                    <div class="card-body text-center">
                        <input type="number" id="storageAmount" class="form-control" name="storageAmount" min="0" step="0.1" placeholder="Masukkan total pakan (kg)" required>
                        @if(session('storage_amount'))
                            <div class="alert alert-info mt-3">
                                Storage Amount: {{ session('storage_amount') }} kg
                            </div>
                        @endif
                    </div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-warning btn-sm" type="button" id="feed" onclick="submitForm()" style="width: 100px; border:none; padding: 10px; border-radius:20px; font-weight: bold; color: white; background-color: rgb(220, 220, 59);">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
<script>
    function submitForm() {
        // Get selected values
        const storageAmount = document.querySelector('input[name="storageAmount"]')?.value;

        if (!storageAmount) {
            Swal.fire({
                title: "Error",
                text: "Please input amount.",
                icon: "error"
            });
            return;
        }
        swal.fire({
            title: "Submit Form",
            text: "Are you sure to set with this amount?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Proceed"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('storage') }}", // Use Laravel route helper
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: {
                        STORAGE: storageAmount
                    },
                    success: function(response){
                        Swal.fire({
                            title: "Successfully Inserted",
                            text: `${response.message}`,
                            icon: "success"
                        }).then(() => {
                            location.reload(); // Reload the page to show updated storage amount
                        });
                    },
                    error: function(xhr){
                        Swal.fire({
                            title: "Error",
                            text: "There was an error processing your request.",
                            icon: "error"
                        });
                    }
                }); 
            }
        });
    }
</script>