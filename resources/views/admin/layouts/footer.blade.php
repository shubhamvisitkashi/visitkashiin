<!-- core:js -->
<script src="{{asset('backend/assets/vendors/core/core.js')}}"></script>
<!-- endinject -->

<!-- Plugin js for this page -->
<script src="{{asset('backend/assets/vendors/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/apexcharts/apexcharts.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/inputmask/jquery.inputmask.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/select2/select2.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/jquery-tags-input/jquery.tagsinput.min.js')}}"></script>
<script src="{{asset('backend/assets/vendors/moment/moment.min.js')}}"></script>
<!-- End plugin js for this page -->

<!-- inject:js -->
<script src="{{asset('backend/assets/vendors/feather-icons/feather.min.js')}}"></script>
<script src="{{asset('backend/assets/js/template.js')}}"></script>
<!-- endinject -->

<!-- Custom js for this page -->
@if(session()->has('selected_theme') && session()->get('selected_theme') == "Dark")
    <script src="{{asset('backend/assets/js/dashboard-dark.js')}}"></script>
@else
    <script src="{{asset('backend/assets/js/dashboard-light.js')}}"></script>
@endif

<script src="{{asset('backend/assets/js/sweet-alert.js')}}"></script>

<script src="{{asset('backend/assets/js/bootstrap-maxlength.js')}}"></script>
<script src="{{asset('backend/assets/js/inputmask.js')}}"></script>
<script src="{{asset('backend/assets/js/select2.js')}}"></script>
<script src="{{asset('backend/assets/js/tags-input.js')}}"></script>
<script src="{{asset('backend/assets/js/flatpickr.js')}}"></script>

<script src="{{asset('backend/assets/vendors/easymde/easymde.min.js')}}"></script>
<script src="{{asset('backend/assets/js/easymde.js')}}"></script>


<script src="https://cdn.ckeditor.com/4.20.2/standard/ckeditor.js"></script>

<!-- End custom js for this page -->
@yield('script')
@stack('scripts')
<script>
    $(document).ready(function() {
            CKEDITOR.replace( 'editor' );
            CKEDITOR.replace( 'editor1' );
        });

    $(function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        $( document ).ready(function() {
            var success_message = "{{Session::get('success')}}";
            var error_message = "{{Session::get('error')}}";

            if(success_message != ""){
                success_sweet_alert(success_message);
            }
            if(error_message !=""){
                error_sweet_alert(error_message)
            }

        });

        function success_sweet_alert(success_message){
            Toast.fire({
                icon: 'success',
                title: success_message
            });
        }

        function error_sweet_alert(error_message){
            Toast.fire({
                icon: 'error',
                title: error_message
            });
        }

        $('#valid_form').validate({
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });

    $(function() {
        $('input[name="daterange"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        })
        $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });

    $(function() {
        $('.daterange').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        })
        $('.daterange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });

    jQuery.fn.ForceNumericOnly =
    function() {
        return this.each(function()
        {
            $(this).keydown(function(e)
            {
                var key = e.charCode || e.keyCode || 0;
                // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
                // home, end, period, and numpad decimal
                return (
                    key == 8 ||
                    key == 9 ||
                    key == 13 ||
                    key == 46 ||
                    key == 110 ||
                    key == 190 ||
                    (key >= 35 && key <= 40) ||
                    (key >= 48 && key <= 57) ||
                    (key >= 96 && key <= 105));
            });
        });
    };
    $(".number_only").ForceNumericOnly();
</script>
<script>
    $('.deleteBtn').click(function(event) {
        var form =  $(this).closest("form");
        var name = $(this).data("name");
        event.preventDefault();
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger me-2'
            },
            buttonsStyling: false,
        })

        swalWithBootstrapButtons.fire({
            title: "Are you sure to delete this data.",
            text: "All related data to this will be deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Yes Delete",
            cancelButtonText: "No Cancel",
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                form.submit();
                // swalWithBootstrapButtons.fire(
                //     'Deleted!',
                //     'Your file has been deleted.',
                //     'success'
                // )
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire(
                    "Cancelled",
                    "Data is safe",
                    'error'
                )
            }
        })
    });
</script>
