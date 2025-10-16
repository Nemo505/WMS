
{{-- PAGE 1 - DELIVERY --}}
<div style="width:100%; padding:10px 20px;">

    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
        <p style="border-top:5px solid #182e6b; border-bottom:5px solid #182e6b; font-size:40px; font-weight:bold; color: #182e6b; display:inline-block;">Han Sein Thant</p>
        <div style="margin-bottom:15px; font-weight:400">
            <p style="margin:2px 0; font-size: 15px; font-weight:700">Han Sein Thant Company Ltd.</p>
            <p style="margin:1px 0;">Bldg(112), Rm(28/29), 8 Mile Junction,</p>
            <p style="margin:1px 0;">Mayangone, Yangon, Myanmar.</p>
            <p style="margin:1px 0;">Tel: (951) 651251, 661030, 650488, 9669508 </p>
            <p style="margin:1px 0;"> <a href="mailto:sales@hstengineering.com" style="color:#000; text-decoration:none;">sales@hstengineering.com</a></p>
            <p style="margin:1px 0;"><a href="http://www.hstengineering.com" style="color:#000; text-decoration:none;">www.hstengineering.com</a></p>
            <p style="margin:1px 0;">Mandalay Office: No 1, Room 8, 26th Street,</p>
            <p style="margin:1px 0;">Between 78th & 79th Chan Aye Thar Zan, Mandalay.</p>
            <p style="margin:1px 0;">Tel: 02-4081783, 09-773177773</p>
        </div>
    </div>

    <h2 style="text-align:center; margin:0;">DELIVERY ORDER ({{$location ?? 'YGN'}})</h2>
    <p style="text-align: center; margin: 1px 0; position: relative;">
        (ORIGINAL)
        <span style="position: absolute; right: 13%; font-weight: bold; font-size: 14px;">
            Sr. <span style="color: rgb(166, 17, 17); font-size: 16px;">
                {{ str_pad((int) $serial_no, 6, '0', STR_PAD_LEFT) }}
                </span>
        </span>
    </p>

    <div style="display: flex; gap: 20px;">

        <!-- Left Section -->
        <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-between; height: 70px;">
            <p style="margin: 0; font-size: 16px;">TO</p>
            <p style="margin: 0; padding-left: 30px;">------------------------</p>
            <p style="margin: 0; padding-left: 30px;">------------------------</p>
        </div>

        <!-- Right Section -->
        <div style="flex: 0 0 200px; display: flex; flex-direction: column; justify-content: space-between; height: 70px;">
           
            <div style="display: flex; justify-content: flex-start; align-items: center;">
                <p style="width: 100px; margin: 0; font-size: 16px;">D.O No.</p>
                <span style="font-weight: bold;">{{ $issue->do_no ?? '075' }}</span>
            </div>

            <div style="display: flex; justify-content: flex-start; align-items: center;">
                <p style="width: 100px; margin: 0; font-size: 16px;">Date.</p>
                <span style="font-weight: bold;">{{ \Carbon\Carbon::parse($issue->created_at)->format('d/m/Y') }}</span>
            </div>

            <div style="display: flex; justify-content: flex-start; align-items: center;">
                <p style="width: 100px; margin: 0; font-size: 16px;">Ref. Job No.</p>
                <span style="font-weight: bold;">{{ $issue->job_no ?? '' }}</span>
            </div>
        </div>
    </div>

    <table style="width:100%; border-collapse:collapse; margin-top:12px;">
        <thead>
            <tr>
                <th style="border:1px solid #333; background-color:#f2f2f2; padding:2px; text-align:center;">Sr No.</th>
                <th style="border:1px solid #333; background-color:#f2f2f2; padding:6px; text-align:center;">Commodity</th>
                <th style="border:1px solid #333; background-color:#f2f2f2; padding:6px; text-align:center;">Parts <br> No.</th>
                <th style="border:1px solid #333; background-color:#f2f2f2; padding:4px; text-align:center;">Qty</th>
                <th style="border:1px solid #333; background-color:#f2f2f2; padding:6px; text-align:center;">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($issues as $i => $item)
                <tr>
                    <td style="border:1px solid #333; padding:6px 2px; text-align:center;">{{ $i + 1 }}</td>
                    <td style="border:1px solid #333; padding:6px 6px;">{{ $item->code->name ?? '' }} / {{ $item->code->brand->name ?? ''}} / {{$item->code->commodity->name ?? '' }}</td>
                    <td style="border:1px solid #333; padding:6px 6px; text-align:center;">                            
                        {{$item->product->unit->name}}
                    </td>
                    <td style="border:1px solid #333; padding:6px 4px; text-align:center;">{{ rtrim(rtrim(number_format($item->mr_qty, 3, '.', ''), '0'), '.') }} </td>
                    <td style="border:1px solid #333; padding:6px 6px;">{{ $item->remarks ?? '' }}</td>
                </tr>
            @endforeach
            @for($i = $issues->count(); $i < 15; $i++)
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

    <!-- Top text -->
    <p style="margin: 4px 0px; text-align: center; font-size: 14px;">Issued (-----------------) items only</p>

    <!-- Issued / Received headers -->
    <div style="display: flex; justify-content: space-between; text-align: center; margin-top: 2px;">
        <div style="flex: 1; text-align: center;">
            <p style="margin: 0; padding: 0px 1px; font-weight: bold; display: inline-block; border-bottom: 1px solid #000000;">
                Issued
            </p>
        </div>
        <div style="flex: 1; text-align: center;">
            <p style="margin: 0; padding: 0px 1px; font-weight: bold; display: inline-block; border-bottom: 1px solid #000000;">
                Received
            </p>
        </div>
    </div>

    <div style="display:flex; justify-content:space-between; text-align: center;">
        <div style="flex:1; padding:0 10px;">
            <p style="margin:0px; font-weight:bold;">Issued By</p>
            <p style="margin:20px 0px 0px 0px; padding-top:5px;">Signed&nbsp;&nbsp;------------------</p>
            <p style="margin: 5px 0px">Name&nbsp;&nbsp;&nbsp;------------------</p>
        </div>
        <div style="flex:1; padding:0 10px;">
            <p style="margin:0px; font-weight:bold;">Checked By</p>
            <p style="margin:20px 0px 0px 0px; padding-top:5px;">Signed&nbsp;&nbsp;------------------</p>
            <p style="margin: 5px 0px">Name&nbsp;&nbsp;&nbsp;------------------</p>
        </div>
        <div style="flex:1; padding:0 10px;">
            <p style="margin:0px; font-weight:bold;">On Behalf of Customer</p>
            <p style="margin:20px 0px 0px 0px; padding-top:5px;">Signed&nbsp;&nbsp;------------------</p>
            <p style="margin: 5px 0px">Name&nbsp;&nbsp;&nbsp;------------------</p>
            <p style="margin: 5px 0px">Car No.------------------</p>
        </div>
        <div style="flex:1; padding:0 10px;">
            <p style="margin:0px; font-weight:bold;">Received (Customer)</p>
            <p style="margin:20px 0px 0px 0px; padding-top:5px;">Signed&nbsp;&nbsp;------------------</p>
            <p style="margin: 5px 0px">Name&nbsp;&nbsp;&nbsp;------------------</p>
            <p style="margin: 5px 0px">Ph No.&nbsp;&nbsp;------------------</p>
        </div>
    </div>

</div>
<div style="page-break-after: always;"></div>
