<div class="modal fade" id="refundPolicyNoteModal" tabindex="-1" aria-labelledby="refundPolicyNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h2 class="text-center text-capitalize flex-grow-1 m-0">{{ translate('refund_policy_detail') }}</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body d-flex flex-column gap-3">
                <div class="card __card">
                    <div class="card-body text-justify">
                        {!! $refund_policy['content'] !!}
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-end">
                    <button type="button" class="btn btn--primary text-light" data-dismiss="modal"
                        aria-label="Close">
                        {{ translate('close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
