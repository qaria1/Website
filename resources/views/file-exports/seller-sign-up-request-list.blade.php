<html>
    <table>
        <thead>
            <tr>
                <th style="font-size: 18px">{{translate('vendor_Sign_Up_Request_List')}}</th>
            </tr>
            <tr>

                <th>{{ translate('vendor_Analytics') }} -</th>
                <th></th>
                <th>
                        {{translate('total_Vendor')}} - {{count($data['sellers'])}}
                </th>
            </tr>
            <tr>
                <th>{{translate('search_Criteria')}}-</th>
                <th></th>
                <th>  {{translate('search_Bar_Content')}} - {{!empty($data['search']) ? $data['search'] : 'N/A'}}</th>
            </tr>
            <tr>
                <td> {{translate('SL')}}</td>
                <td> {{translate('store_Logo')}}</td>
                <td> {{translate('store_Name')}}</td>
                <td> {{translate('vendor_Name')}}</td>
                <td> {{translate('phone')}}	</td>
                <td> {{translate('email')}}	</td>
                <td> {{translate('sex')}}	</td>
                <td> {{translate('age')}}	</td>
                <td> {{translate('joined_At')}}	</td>
            </tr>
            @foreach ($data['sellers'] as $key=>$item)
                <tr>
                    <td> {{++$key}}	</td>
                    <td style="height: 70px"></td>
                    <td> {{ucwords($item?->shop->name)}}</td>
                    <td> {{ucwords($item->f_name.' '.$item->l_name)}}</td>
                    <td> {{$item?->phone ?? translate('not_found')}}</td>
                    <td> {{ucwords($item->email)}}</td>
                    <td> {{$item?->sex}}</td>
                    <td> {{$item?->age}}</td>
                    <td> {{date('d M, Y h:i A',strtotime($item->created_at))}}</td>
                </tr>
            @endforeach
        </thead>
    </table>
</html>
