<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<style>
    /* Set print page size and margins */
    @page {
        size: A4;
    }

</style>
<body onload="window.print()" style="font-family:Times New Roman, sans-serif; margin:10px; font-size:13px; color:#000000;">
    {{-- PAGE 1 - Material Request --}}
    <div style="width:100%; padding:10px;">

        <!-- Company Header -->
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h2 style="margin:0; text-align:center;">HAN SEIN THANT CO., LTD</h2>
            <div style="text-align:right;">
                <p style="border-top:5px solid #182e6b; 
                border-bottom:5px solid #182e6b; 
                margin: 0px;
                font-size:40px; font-weight:bold; 
                color: #182e6b; 
                display:inline-block;">Han Sein Thant</p>
            </div>
        </div>

        <!-- Title -->
        <h3 style="text-align:center; margin:5px 0; padding-top:4px; position: relative;">
            Material Request & Goods Issued Note (MR&GI)
            <span style="font-size:14px;"> / ပစ္စည်းတောင်းခံထုတ်ပေးလွှာ</span>
            <span style="position: absolute; right: 2%; font-weight: bold; font-size: 14px;">
                Sr. <span style="color: rgb(166, 17, 17); font-size: 18px;">
                    {{ str_pad((int) $serial_no, 6, '0', STR_PAD_LEFT) }}
                    </span>
            </span>
        </h3>

        <!-- Title -->
        <div style="display:flex; justify-content:space-between; margin-top:8px;">
            <p style="margin:2px 0;">တောင်းခံသည့်ဌာန .....&nbsp;{{$issue->department->name}}&nbsp;......</p>
            <p style="margin:2px 0;">ရက်စွဲ ......&nbsp;{{ \Carbon\Carbon::today()->format('Y-m-d') }}&nbsp;.........</p>
        </div>

        <!-- Top Info -->
        <div style="display:flex; justify-content:space-between; margin-top:8px;">
            <div style="width:65%;">
                <label style="border:0.5px solid #928f8fff; padding:5px 10px; margin:0 5px; display:inline-block;">ကုန်ကြမ်း </label>
                <label style="border:2px solid #000; padding:5px 10px; margin:0 5px; display:inline-block; width: 30px; height: 20px;">&nbsp;</label>
                <label style="border:0.5px solid #928f8fff; padding:5px 10px; margin:0 5px; display:inline-block;">ကုန်ချော </label>
                <label style="border:2px solid #000; padding:5px 10px; margin:0 5px; display:inline-block; width: 30px; height: 20px;">&nbsp;</label>
                <label style="border:0.5px solid #928f8fff; padding:5px 10px; margin:0 5px; display:inline-block;">
                    For Service<br>အပိုပစ္စည်း
                </label>
                <label style="border:2px solid #000; padding:5px 10px; margin:0 5px; display:inline-block; width: 30px; height: 20px;">&nbsp;</label>
            </div>

            <div style="width:35%;">
                <div style="display: flex; justify-content: flex-start; align-items: center;">
                    <p style="width: 100px; margin: 3px 0; ">MR&GI အမှတ်</p>
                    <span style="font-weight: bold;">{{ $issue->mr_no ?? '075' }}</span>
                </div>

                <div style="display: flex; justify-content: flex-start; align-items: center;">
                    <p style="width: 100px; margin: 5px 0; ">Job No</p>
                    <span style="font-weight: bold;">{{ $issue->job_no ?? '' }}</span>
                </div>

                <div style="display: flex; justify-content: flex-start; align-items: center;">
                    <p style="width: 100px; margin: 5px 0; ">Customer Name</p>
                    <span style="font-weight: bold;">{{ $issue->customer->name ?? '' }}</span>
                </div>
                <div style="display: flex; justify-content: flex-start; align-items: center;">
                    <p style="width: 100px; margin: 5px 0; ">Page No</p>
                    <span style="font-weight: bold;">...............................</span>
                </div>
            </div>
        </div>

        <!-- Checkboxes -->
        <div style="display:flex; justify-content:center; align-items:center; margin:10px 0;">
            
        </div>

        <table style="width:100%; border-collapse:collapse; margin-top:12px;">
            <thead>
                <tr>
                    <th style="border:1px solid #000; padding:4px;">အမှတ်စဉ်</th>
                    <th style="border:1px solid #000; padding:4px;">ပစ္စည်းအမျိုးအမည်</th>
                    <th style="border:1px solid #000; padding:4px;">တောင်းခံ <br> အရေ <br>အတွက်</th>
                    <th style="border:1px solid #000; padding:4px;">ထုတ်ပေး <br>အရေ <br>အတွက်</th>
                    <th style="border:1px solid #000; padding:4px;">ထုတ်ပြီး <br> နေ့စွဲ</th>
                    <th style="border:1px solid #000; padding:4px;">Material location</th>
                    <th style="border:1px solid #000; padding:4px;">မှတ်ချက်</th>
                </tr>
            </thead>
            <tbody>
                @foreach($issues as $i => $item)
                    <tr>
                        <td style="border:1px solid #333; padding:6px 2px; text-align:center;">{{ $i + 1 }}</td>
                        <td style="border:1px solid #333; padding:6px 6px;">{{ $item->code->name ?? '' }} / {{ $item->code->brand->name ?? ''}} / {{$item->code->commodity->name ?? '' }} </td>
                        <td style="border:1px solid #333; padding:6px 6px; text-align:center;">    
                            {{ rtrim(rtrim(number_format($item->mr_qty, 3, '.', ''), '0'), '.') }} {{$item->product->unit->name}}
                        </td>
                        <td style="border:1px solid #333; padding:6px 6px; text-align:center;">    
                            {{ rtrim(rtrim(number_format($item->mr_qty, 3, '.', ''), '0'), '.') }} {{$item->product->unit->name}}
                        </td>
                        <td style="border:1px solid #333; padding:6px 6px;">{{ \Carbon\Carbon::parse($issue->created_at)->format('d/m/Y') }}</td>
                        <td style="border:1px solid #333; padding:6px 6px; text-align:center;">{{ $item->shelfNum->warehouse->name ?? '' }}</td>
                        <td style="border:1px solid #333; padding:6px 6px;">{{ $item->remarks ?? '' }}</td>
                    </tr>
                @endforeach
                @for($i = $issues->count(); $i < 15; $i++)
                    <tr>
                        <td style="border:1px solid #333; padding:6px 2px; text-align:center;"></td>
                        <td style="border:1px solid #333; padding:6px 6px;">&nbsp;</td>
                        <td style="border:1px solid #333; padding:6px 6px;">&nbsp;</td>
                        <td style="border:1px solid #333; padding:6px 6px;">&nbsp;</td>
                        <td style="border:1px solid #333; padding:6px 6px;">&nbsp;</td>
                        <td style="border:1px solid #333; padding:6px 6px; ">&nbsp;</td>
                        <td style="border:1px solid #333; padding:6px 6px;">&nbsp;</td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <!-- Top text -->

        <div style="display:flex; justify-content:space-between;">
            <div style="flex:1; padding:10px;">
                <p style="margin:0px; font-weight:bold; text-align:center;">တောင်းခံသူ</p>
                <p style="margin:20px 0px 0px 0px; padding-top:5px;">လက်မှတ် &nbsp;.....................</p>
                <p style="margin: 5px 0px"> အမည်&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;......................</p>
            </div>
            <div style="flex:1; padding:10px;">
                <p style="margin:0px; font-weight:bold; text-align:center;">ထုတ်ယူသူ</p>
                <p style="margin:20px 0px 0px 0px; padding-top:5px;">လက်မှတ် &nbsp;.....................</p>
                <p style="margin: 5px 0px"> အမည်&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;......................</p>
            </div>
            <div style="flex:1; padding:10px;">
                <p style="margin:0px; font-weight:bold; text-align:center;">ခွင့်ပြုသူ</p>
                <p style="margin:20px 0px 0px 0px; padding-top:5px;">လက်မှတ် &nbsp;.....................</p>
                <p style="margin: 5px 0px"> အမည်&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;......................</p>
            </div>
            <div style="flex:1; padding:10px;">
                <p style="margin:0px; font-weight:bold; text-align:center;">ထုတ်ပေးသူ</p>
                <p style="margin:20px 0px 0px 0px; padding-top:5px;">လက်မှတ် &nbsp;.....................</p>
                <p style="margin: 5px 0px"> အမည်&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;......................</p>
            </div>
        </div>
    </div>
    <div style="page-break-after: always;"></div>

</body>
</html>