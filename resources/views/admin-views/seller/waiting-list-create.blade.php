@extends('layouts.back-end.app')

@section('title', translate('add_vendor_to_waiting_list'))
@section('content')
    <div class="content container-fluid main-card {{ Session::get('direction') }}">
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{ asset('/public/assets/back-end/img/add-new-seller.png') }}" class="mb-1" alt="">
                {{ translate('add_vendor_to_waiting_list') }}
            </h2>
        </div>
        <form class="" action="{{ route('admin.sellers.waiting-list.store') }}" method="post">
            @csrf
            <div class="card mt-3 subscription-plan-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="plan-selection-select row">
                                <div class="col-lg-6 form-group">
                                    <label for="seller">{{ translate('select_seller') }}</label>
                                    <select name="seller"
                                        class="form-control fs-13 border-aliceblue max-240px"
                                        id="seller">
                                        <option value="" disabled selected>
                                            {{ translate('choose_seller') }}</option>
                                        @foreach ($allSellers as $seller)

                                            {{-- Skip sellers that has already active plan or put in waiting list --}}
                                            @if ($seller->waitingList || ($seller->subscriptions->where('status', true)->count() > 0))
                                                @continue
                                            @endif
                                            <option value="{{ $seller->id }}">
                                                {{ $seller->f_name.' '.$seller->l_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="">
                                <a href="{{ route('admin.sellers.waiting-list.index') }}" class="btn btn--secondary mx-2">{{translate('back')}}</a>
                                <button type="submit" class="btn btn--primary demo_check">{{translate('submit')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection