    {{-- View subscription detail in modal --}}
    <div class="modal fade" id="subscriptionDetailModal{{ $plan->id }}" tabindex="-1"
        aria-labelledby="subscriptionDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h4 class="text-center text-capitalize flex-grow-1 m-0">{{ translate('subscription_detail_for_') }} <u>{{ $plan?->plan?->name }}</u>{{ translate('_plan') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body d-flex flex-column gap-3">
                    {{-- <div class="border rounded bg-white">
                        <div class="p-3">
                            <div class="media gap-3">
                                <div class="media-body">
                                    <div>
                                        <small class="text-muted">
                                            {{ translate('subscription_name') }} : {{ $plan->plan?->name ?? '-' }}
                                        </small>
                                    </div>
                                    <div>
                                        <small class="text-muted">{{ translate('billing_type') }} :
                                            {{ $plan->billingType?->name ?? '-' }}</small>
                                    </div>
                                    <div>
                                        <small class="text-muted">{{ translate('started_at') }} :
                                            {{ $plan->current_start ?? '-' }}</small>
                                    </div>
                                    <div>
                                        <small class="text-muted">{{ translate('ended_at') }} :
                                            {{ $plan->current_end ?? '-' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border rounded bg-white">
                        <div class="p-3 fs-12 d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between gap-2">
                                <div class="text-muted text-capitalize">{{ translate('total_product_uploaded') }}</div>
                                <div>{{ $plan->plan?->name ?? '-' }}</div>
                            </div>
                            <div class="d-flex justify-content-between gap-2">
                                <div class="text-muted text-capitalize">{{ translate('total_active_products') }}</div>
                                <div>{{ $plan->plan?->name ?? '-' }}</div>
                            </div>
                            <div class="d-flex justify-content-between gap-2">
                                <div class="text-muted text-capitalize">{{ translate('total_product_archived') }}</div>
                                <div>{{ $plan->plan?->name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between gap-2 border-top py-2 px-3 fs-12">
                            <div class="text-muted font-weight-bold text-capitalize">
                                {{ translate('footer_header') }}</div>
                            <div class="font-weight-bold">{{ $plan->plan?->name ?? '-' }}</div>
                        </div>
                    </div> --}}

                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                               style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                               class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{ translate('SL') }}</th>
                                {{-- <th>{{ translate('subscription_plan') }}</th> --}}
                                <th class="text-center">{{ translate('billing_type') }}</th>
                                <th class="text-center">{{ translate('total_product_uploaded') }}</th>
                                <th class="text-center">{{ translate('total_active_products') }}</th>
                                <th class="text-center">{{ translate('total_archived_products') }}</th>
                                {{-- <th class="text-center">{{ translate('status') }}</th> --}}
                                <th class="text-center">{{ translate('total_days_used') }}</th>
                                <th class="text-center">{{ translate('trial') }}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">

                                {{-- @dd($plan->plan->subscriptions->where('seller_id', $plan->seller_id)->collect()) --}}
                                @forelse ($plan?->plan?->subscriptions?->where('seller_id', $plan?->seller_id) as $k => $plan)

                                    @php($currentStartDate = \Carbon\Carbon::createFromFormat('Y-m-d', $plan?->current_start))
                                    @php($currentEndDate = \Carbon\Carbon::createFromFormat('Y-m-d', $plan?->current_end))
                                    @php($totalDaysUsed = $currentStartDate->diffInDays($currentEndDate))
                                    <tr>
                                        <th scope="row">{{ $k + 1 }}</th>
                                        {{-- <td>
                                            {{ $plan?->plan?->name ?? '-' }}
                                        </td> --}}
                                        <td class="text-center">
                                            {{ $plan?->billingType?->name ?? '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ App\Models\Product::where('user_id', $plan?->seller_id)->where('seller_subscription_id', $plan?->id)->count() ?? '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ App\Models\Product::where('user_id', $plan?->seller_id)->where('seller_subscription_id', $plan?->id)->where('is_lifetime_ended', false)->where('status', 1)->count() ?? '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ App\Models\Product::where('user_id', $plan?->seller_id)->where('seller_subscription_id', $plan?->id)->where('is_lifetime_ended', true)->count() ?? '-' }}
                                        </td>
                                        {{-- <td class="text-center">
                                            @if ($plan?->status == 1)
                                                <label class="badge badge-soft-primary">{{ translate('active') }}</label>
                                            @else
                                                <label class="badge badge-soft-danger">{{ translate('inactive') }}</label>
                                            @endif
                                        </td> --}}
                                        <td class="text-center">
                                            {{ $totalDaysUsed }}
                                        </td>
                                        <td class="text-center">
                                            @if ($plan?->is_trial == 1)
                                                <label class="badge badge-soft-primary">{{ translate('yes') }}</label>
                                            @else
                                                <label class="badge badge-soft-warning">{{ translate('no') }}</label>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>

                        </table>
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
