@extends('layouts.back-end.app')

@section('title', translate('delivery_class_update'))

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <h2 class="h1 mb-0">
                <img src="{{ asset('public/assets/back-end/img/brand-setup.png') }}" class="mb-1 mr-1" alt="">
                {{ translate('delivery_class_update') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body text-start">
                        <form action="{{ route('admin.delivery-class.update', [$deliveryClass['id']]) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <ul class="nav nav-tabs w-fit-content mb-4">
                                @foreach ($languages as $lang)
                                    <li class="nav-item text-capitalize">
                                        <span
                                            class="nav-link form-system-language-tab cursor-pointer {{ $lang == $defaultLanguage ? 'active' : '' }}"
                                            id="{{ $lang }}-link">
                                            {{ getLanguageName($lang) . '(' . strtoupper($lang) . ')' }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="row">
                                <div
                                    class="col-lg-6">
                                    @foreach ($languages as $lang)
                                        <div>
                                            <?php
                                            if (count($deliveryClass['translations'])) {
                                                $translate = [];
                                                foreach ($deliveryClass['translations'] as $t) {
                                                    if ($t->locale == $lang && $t->key == 'name') {
                                                        $translate[$lang]['name'] = $t->value;
                                                    }
                                                }
                                            }
                                            ?>
                                            <div class="form-group {{ $lang != $defaultLanguage ? 'd-none' : '' }} form-system-language-form"
                                                id="{{ $lang }}-form">

                                                <div class="form-group" id="{{ $lang }}-form">
                                                    <label class="title-color">
                                                        {{ translate('delivery_class_name') }} ({{ strtoupper($lang) }})
                                                    </label>
                                                    <input type="text" name="name[]"
                                                        value="{{ $lang == $defaultLanguage ? $deliveryClass['name'] : $translate[$lang]['name'] ?? '' }}"
                                                        class="form-control" placeholder="{{ translate('new_delivery_class_name') }}"
                                                        {{ $lang == $defaultLanguage ? 'required' : '' }}>
                                                </div>

                                            <div class="form-group" id="{{ $lang }}-form">
                                                <label class="title-color">
                                                    {{ translate('delivery_class_code') }} ({{ strtoupper($lang) }})
                                                </label>
                                                <input type="text" name="code[]"
                                                    value="{{ $lang == $defaultLanguage ? $deliveryClass['code'] : $translate[$lang]['code'] ?? '' }}"
                                                    class="form-control" placeholder="{{ translate('new_delivery_class_code') }}"
                                                    {{ $lang == $defaultLanguage ? 'required' : '' }}>
                                            </div>

                                            <div class="form-group" id="{{ $lang }}-form">
                                                <label class="title-color">
                                                    {{ translate('delivery_class_description') }} ({{ strtoupper($lang) }})
                                                </label>
                                                <input type="text" name="description[]"
                                                    value="{{ $lang == $defaultLanguage ? $deliveryClass['description'] : $translate[$lang]['description'] ?? '' }}"
                                                    class="form-control" placeholder="{{ translate('new_delivery_class_description') }}"
                                                    {{ $lang == $defaultLanguage ? 'required' : '' }}>
                                            </div>

                                            <input type="hidden" name="lang[]" value="{{ $lang }}">
                                            <input type="hidden" name="id" value="{{ $deliveryClass['id'] }}">
                                        </div>
                                    @endforeach

                                </div>

                                {{-- @if (theme_root_path() != 'theme_aster') --}}
                                    <div class="d-flex justify-content-end gap-3">
                                        <button type="reset" id="reset" class="btn btn-secondary px-4">
                                            {{ translate('reset') }}
                                        </button>
                                        <button type="submit" class="btn btn--primary px-4">
                                            {{ translate('update') }}
                                        </button>
                                    </div>
                                {{-- @endif --}}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
