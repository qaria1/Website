<div class="modal fade" id="suspendVendorModal" tabindex="-1" aria-labelledby="suspendVendorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h2 class="text-center text-capitalize flex-grow-1 m-0">{{ translate('vendor_suspension') }}</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body d-flex flex-column gap-3">

                <form action="{{ route('admin.sellers.updateStatus') }}" method="POST">
                    @csrf
                    <h4 class="d-flex gap-2 align-items-center">
                        {{ translate('give_a_suspend_reason') }}:
                    </h4>
                    <input type="hidden" name="id" value="{{ $seller->id }}">
                    <input type="hidden" name="status" value="suspended">

                    <textarea rows="4" class="form-control" name="suspend_reason"
                        placeholder="{{ translate('write_your_suspend_reason_here') }}..." required></textarea>
                    <div class="mt-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary text-capitalize">
                            {{ translate('submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
