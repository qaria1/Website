@extends('layouts.back-end.app')

@section('title', translate('seller_waiting_list'))
@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <div class="page-title-wrap d-flex justify-content-between flex-wrap align-items-center gap-3 mb-3">
                <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                    <img src="{{asset('/public/assets/back-end/img/add-new-seller.png')}}" alt="">
                    {{translate('waiting_list')}}
                </h2>
                <a href="{{route('admin.sellers.waiting-list.add')}}" class="btn btn--primary">+ {{translate('add_seller')}}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="p-3">
                        <div class="row gy-1 align-items-center justify-content-between">
                            <div class="col-auto">
                                <h5>
                                {{ translate('total')}}
                                    <span class="badge badge-soft-dark radius-50 fz-12 ml-1"> {{ $waitingSellers->total() }}</span>
                                </h5>
                            </div>
                            {{-- <div class="col-auto">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                               placeholder="{{translate('search_Seller_Name')}}" aria-label="Search sellers"
                                               value="{{ request('searchValue') }}" required>
                                        <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                    </div>
                                </form>
                            </div> --}}
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                                style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('seller_name')}}</th>
                                <th>{{ translate('position') }}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($waitingSellers as $key => $seller)
                                <tr>
                                    <td>{{$waitingSellers->firstitem() + $key}}</td>
                                    <td>{{$seller?->seller?->f_name.' '.$seller?->seller?->l_name ?? '-'}}</td>
                                    <td>{{$seller->position ?? '-'}}</td>

                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <form action="{{route('admin.sellers.waiting-list.delete')}}"
                                                method="post">
                                                @csrf @method('delete')
                                                <input type="hidden" name="list_id" value="{{ $seller->id }}">
                                                <button class="btn btn-outline-danger btn-sm" onclick="return confirm('{{ translate('Are you sure?') }}')"
                                                    title="{{translate('delete')}}" data-id="delete-{{$seller->id}}">
                                                    {{ translate('remove') }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(count($waitingSellers)==0)
                            <div class="text-center p-4">
                                <img class="mb-3 w-160"
                                        src="{{asset('public/assets/back-end/svg/illustrations/sorry.svg')}}"
                                        alt="{{translate('image_description')}}">
                                <p class="mb-0">{{translate('no_data_to_show')}}</p>
                            </div>
                       @endif
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-center justify-content-md-end">
                            {{$waitingSellers->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
