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
            Material Request Return Note
            <span style="position: absolute; right: 2%; font-weight: bold; font-size: 14px;">
                Sr. <span style="color: rgb(166, 17, 17); font-size: 18px;">
                    {{ str_pad((int) $serial_no, 6, '0', STR_PAD_LEFT) }}
                    </span>
            </span>
        </h3>

        <!-- Title -->
        <div style="display:flex; justify-content:space-between; margin-top:8px;">
            <p style="margin:2px 0;"><strong>MRR No</strong> .....&nbsp;{{$mrr->mrr_no}}&nbsp;......</p>
            <p style="margin:2px 0;"><strong>Date</strong> ......&nbsp;{{ \Carbon\Carbon::parse($mrr->issue_return_date)->format('Y-m-d') }}&nbsp;.........</p>
        </div>
        <div style="display:flex; justify-content:space-between; margin-top:8px;">
            <p style="margin:2px 0;"><strong>MR No</strong> .....&nbsp;{{$mrr->issue->mr_no}}&nbsp;......</p>
            <p style="margin:2px 0;"><strong>Customer Name</strong> ......&nbsp;{{ $mrr->issue->customer->name ?? '' }}&nbsp;.........</p>
        </div>

        <!-- Checkboxes -->
        <div style="display:flex; justify-content:center; align-items:center; margin:10px 0;">
            
        </div>

        <table style="width:100%; border-collapse:collapse; margin-top:12px;">
            <thead>
                <tr>
                    <th style="border:1px solid #333; background-color:#f2f2f2; padding:2px; text-align:center;">Sr No.</th>
                    <th style="border:1px solid #333; background-color:#f2f2f2; padding:6px; text-align:center;">Bramd Name</th>
                    <th style="border:1px solid #333; background-color:#f2f2f2; padding:6px; text-align:center;">Commodity</th>
                    <th style="border:1px solid #333; background-color:#f2f2f2; padding:4px; text-align:center;">Qty</th>
                    <th style="border:1px solid #333; background-color:#f2f2f2; padding:6px; text-align:center;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mrrs as $i => $item)
                    <tr>
                        <td style="border:1px solid #333; padding:6px 2px; text-align:center;">{{ $i + 1 }}</td>
                        <td style="border:1px solid #333; padding:6px 2px;">{{ $item->code->brand->name ?? ''}} </td>
                        <td style="border:1px solid #333; padding:6px 6px;">{{ $item->code->name ?? '' }} / {{$item->code->commodity->name ?? '' }}</td>
                        <td style="border:1px solid #333; padding:6px 4px; text-align:center;">{{ rtrim(rtrim(number_format($item->mrr_qty, 3, '.', ''), '0'), '.') }} </td>
                        <td style="border:1px solid #333; padding:6px 6px;">{{ $item->remarks ?? '' }}</td>
                    </tr>
                @endforeach
                @for($i = $mrrs->count(); $i < 15; $i++)
                    <tr>
                        <td style="border:1px solid #333; padding:6px 2px; text-align:center;"></td>
                        <td style="border:1px solid #333; padding:6px 6px;">&nbsp;</td>
                        <td style="border:1px solid #333; padding:6px 6px;">&nbsp;</td>
                        <td style="border:1px solid #333; padding:6px 4px; text-align:center;">&nbsp;</td>
                        <td style="border:1px solid #333; padding:6px 6px;">&nbsp;</td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <div style="display:flex; justify-content:space-between; text-align: center;">
            <div style="flex:1; padding:0 10px;">
                <p style="margin:0px; font-weight:bold; font-size: 14px;">Returned By</p>
                <p style="margin:0px; font-weight:bold; font-size: 14px;">(Customer)</p>
                <p style="margin:20px 0px 0px 0px; padding-top:5px;">Signed&nbsp;&nbsp;-----------------------</p>
                <p style="margin: 5px 0px;">Name&nbsp;&nbsp;&nbsp;-----------------------</p>
            </div>
            <div style="flex:1; padding:0 10px;">
                <p style="margin:0px; font-weight:bold; font-size: 14px;">Received By</p>
                <p style="margin:0px; font-weight:bold; font-size: 14px; ">(Store Staff)</p>
                <p style="margin:20px 0px 0px 0px; padding-top:5px;">Signed&nbsp;&nbsp;-----------------------</p>
                <p style="margin: 5px 0px">Name&nbsp;&nbsp;&nbsp;-----------------------</p>
            </div>
            <div style="flex:1; padding:0 10px;">
                <p style="margin:0px; font-weight:bold; font-size: 14px;">Checked By</p>
                <p style="margin:0px; font-weight:bold; font-size: 14px;">(Store Manager)</p>
                <p style="margin:20px 0px 0px 0px; padding-top:5px;">Signed&nbsp;&nbsp;-----------------------</p>
                <p style="margin: 5px 0px">Name&nbsp;&nbsp;&nbsp;-----------------------</p>
            </div>
        </div>
    </div>
    <div style="page-break-after: always;"></div>

</body>
</html>