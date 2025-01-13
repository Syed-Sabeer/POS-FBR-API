<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="{{url('public/logo', $general_setting->site_logo)}}"/>
    <title>{{$general_setting->site_title}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <style type="text/css">
        * {
            font-size: 14px;
            line-height: 24px;
            font-family: 'Ubuntu', sans-serif;
            text-transform: capitalize;
        }

        .btn {
            padding: 7px 10px;
            text-decoration: none;
            border: none;
            display: block;
            text-align: center;
            margin: 7px;
            cursor: pointer;
        }

        .btn-info {
            background-color: #999;
            color: #FFF;
        }

        .btn-primary {
            background-color: #6449e7;
            color: #FFF;
            width: 100%;
        }

        td,
        th,
        tr,
        table {
            border-collapse: collapse;
        }

        tr {
            border-bottom: 1px dotted #ddd;
        }

        td, th {
            padding: 7px 0;
            width: 50%;
        }

        table {
            width: 100%;
        }

        tfoot tr th:first-child {
            text-align: left;
        }

        .centered {
            text-align: center;
            align-content: center;
        }

        small {
            font-size: 11px;
        }

        @media print {
            * {
                font-size: 12px;
                line-height: 20px;
            }

            td, th {
                padding: 5px 0;
            }

            .hidden-print {
                display: none !important;
            }

            @page {
                margin: 0;
            }

            body {
                margin: 0.5cm;
                margin-bottom: 1.6cm;
            }

            tbody::after {
                content: '';
                display: block;
                page-break-after: always;
                page-break-inside: always;
                page-break-before: avoid;
            }
        }
    </style>
</head>
<body>

<div style="max-width:400px;margin:0 auto">
    @if(preg_match('~[0-9]~', url()->previous()))
        @php $url = '../../pos'; @endphp
    @else
        @php $url = url()->previous(); @endphp
    @endif
    <div class="hidden-print">
        <table>
            <tr>
                <td><a href="{{$url}}" class="btn btn-info"><i class="fa fa-arrow-left"></i> {{trans('file.Back')}}</a>
                </td>
                <td>
                    <button onclick="window.print();" class="btn btn-primary"><i
                            class="dripicons-print"></i> {{trans('file.Print')}}</button>
                </td>
            </tr>
        </table>
        <br>
    </div>

    <div id="receipt-data">
        <div class="centered">
            @if($general_setting->site_logo)
                <img src="{{url('public/logo', $general_setting->site_logo)}}" height="120px" width="180"
                     style="margin:10px 0;">
            @endif

            {{--            <h2>{{$lims_biller_data->company_name}}</h2>--}}

            <p>{{trans('file.Address')}}: {{$lims_warehouse_data->address}}
                <br>{{trans('file.Phone Number')}}: {{$lims_warehouse_data->phone}}
                <br> STRN No: 3277876286257
                <br> NTN No: A334561-4
                <br> Tax Formation : Karachi
            </p>
        </div>
        <p>{{trans('file.Date')}}: {{$lims_sale_data->created_at}}<br>
            {{trans('file.reference')}}: {{$lims_sale_data->reference_no}}<br>
            {{trans('file.customer')}}: {{$lims_customer_data->name}}<br>
            POS ID : 152404
        </p>
        <table class="table-data">
            <tbody>
            <?php $total_product_tax = 0; ?>
            @foreach($lims_product_sale_data as $key => $product_sale_data)
                    <?php
                    $lims_product_data = \App\Product::find($product_sale_data->product_id);
                    if ($product_sale_data->variant_id) {
                        $variant_data = \App\Variant::find($product_sale_data->variant_id);
                        $product_name = $lims_product_data->name . ' [' . $variant_data->name . ']';
                    } else
                        $product_name = $lims_product_data->name;
                    ?>
                <tr>
                    <td colspan="2">
                        {{$product_name}}
                        @php
                            // dd($product_sale_data);
                                $product_original_price=\App\Product::where("id",$product_sale_data->product_id)->first();
                                // dd($product_original_price);

                        @endphp

                        <br>{{$product_sale_data->qty}}
                        x {{number_format((float)($product_original_price->price), 2, '.', '')}}


                            <?php $total_product_tax = (float)$product_original_price->price / 117 * 100 * .1 ?>
                            <!-- <br>
                        [GST(10%):{{$total_product_tax}} Rs] -->

                    </td>
                    <td style="text-align:right;vertical-align:bottom">{{number_format((float)($product_original_price->price)*$product_sale_data->qty, 2, '.', '')}}
                        Rs
                    </td>
                </tr>
            @endforeach

            <tr>
                <th colspan="2" style="text-align:left">Gross Total</th>
                @php

                    $gross_total=0;

                   foreach($lims_product_sale_data as $key => $product_sale_data)
                       {

                           $product=\App\Product::find($product_sale_data->product_id);
                           $total_per_item=($product->price)*$product_sale_data->qty;
                           $gross_total+=$total_per_item;
                       }

                @endphp
                <th style="text-align:right">{{number_format((float)$gross_total-$lims_sale_data->order_discount, 2, '.', '')}} Rs</th>
            </tr>


            {{--            @php--}}

            {{--                $discount=0;--}}

            {{--               foreach($lims_product_sale_data as $key => $product_sale_data)--}}
            {{--                   {--}}

            {{--                   $discount+=$product_sale_data->qty*$product_sale_data->net_unit_price  ;--}}
            {{--                   }--}}

            {{--            @endphp--}}

            {{--            <tr>--}}
            {{--                <th colspan="2" style="text-align:left">Total Discount</th>--}}
            {{--                <th style="text-align:right">{{number_format((float)$gross_total-$discount, 2, '.', '')}} Rs</th>--}}
            {{--            </tr>--}}

            <!-- <tfoot> -->
            {{--            <tr>--}}
            {{--                <th colspan="2" style="text-align:left">{{trans('file.Total')}}</th>--}}
            {{--                <th style="text-align:right">{{number_format($discount, 2, '.', '')}} Rs</th>--}}
            {{--            </tr>--}}
            @if($general_setting->invoice_format == 'gst' && $general_setting->state == 1)
                <tr>
                    <td colspan="2">IGST</td>
                    <td style="text-align:right">{{number_format((float)$total_product_tax, 2, '.', '')}}</td>
                </tr>
            @elseif($general_setting->invoice_format == 'gst' && $general_setting->state == 2)
                <tr>
                    <td colspan="2">SGST</td>
                    <td style="text-align:right">{{number_format((float)($total_product_tax / 2), 2, '.', '')}}</td>
                </tr>
                <tr>
                    <td colspan="2">CGST</td>
                    <td style="text-align:right">{{number_format((float)($total_product_tax / 2), 2, '.', '')}}</td>
                </tr>
            @endif

            <tr>
                <th colspan="2" style="text-align:left">{{trans('file.Order Tax')}}</th>
                <th style="text-align:right">{{number_format((float)$lims_sale_data->grand_total/100*17, 2, '.', '')}}
                    Rs
                </th>
            </tr>

            @if($lims_sale_data->order_discount)
                <tr>
                    <th colspan="2" style="text-align:left">{{trans('file.Order Discount')}}</th>
                    <th style="text-align:right">{{number_format((float)$lims_sale_data->order_discount, 2, '.', '')}}
                        Rs
                    </th>
                </tr>
            @endif


            <tr>
                <th colspan="2" style="text-align:left">FBR POS Service Fee
                    <br><small>(Not Added in Total)</small></th>
                <th style="text-align:right">1.00 Rs</th>
            </tr>

            @if($lims_sale_data->shipping_cost)
                <tr>
                    <th colspan="2" style="text-align:left">{{trans('file.Shipping Cost')}}</th>
                    <th style="text-align:right">{{number_format((float)$lims_sale_data->shipping_cost, 2, '.', '')}}</th>
                </tr>
            @endif


            <tr>
                <th colspan="2" style="text-align:left">{{trans('file.grand total')}}</th>
                <th style="text-align:right">{{number_format((float)$lims_sale_data->grand_total*0.17 + $lims_sale_data->grand_total, 2, '.', '')}}
                    Rs
                </th>
            </tr>
            <tr>
                @if($general_setting->currency_position == 'prefix')
                    <th class="centered" colspan="3">{{trans('file.In Words')}}: <span>{{$currency->code}}</span>
                        <span>{{str_replace("-"," ",$numberInWords)}}</span></th>
                @else
                    <th class="centered" colspan="3">{{trans('file.In Words')}}:
                        <span>{{str_replace("-"," ",$numberInWords)}}</span> <span>{{$currency->code}}</span></th>
                @endif
            </tr>
            </tbody>
            <!-- </tfoot> -->

        </table>
        <table>
            <tbody>
            <!-- @foreach($lims_payment_data as $payment_data)
                <tr style="background-color:#ddd;">
                    <td style="padding: 5px;width:30%">{{trans('file.Paid By')}}: {{$payment_data->paying_method}}</td>
                    <td style="padding: 5px;width:40%">{{trans('file.Amount')}}
                        : {{number_format((float)$payment_data->amount+$lims_sale_data->grand_total/100*17, 2, '.', '')}}</td>
                    <td style="padding: 5px;width:30%">{{trans('file.Change')}}
                        : {{number_format((float)$payment_data->change, 2, '.', '')}}</td>
                </tr>
            @endforeach -->
            <tr>
                <td class="centered"
                    colspan="3">{{trans('file.Thank you for shopping with us. Please come again')}}</td>
            </tr>
          <tr>
    <td class="centered" colspan="3">
        <!-- Barcode -->
        <?php echo '<img style="margin-top:10px;" src="data:image/png;base64,' . DNS1D::getBarcodePNG($bc, 'C128') . '" width="300" alt="barcode"   />'; ?>
        <br/>
        Your FBR Invoice Number is <strong>{{$bc}}</strong><br>

        <!-- QR Code -->
       <?php
    //   echo '<img style="margin-top:10px;" src="data:image/png;base64,' . DNS2D::getBarcodePNG($qr_code, 'QRCODE') . '" alt="qr_code"   />';
       ?> 
        <br>

        <img src="{{url('public/images', 'FBRpos.png')}}" height="64" width="64" style="margin:10px 0;filter: brightness(0);">
        <br>Verify this invoice through FBR TaxAsaan MobileApp or SMS at 9966 and win exciting prizes in draw.
        <br>Design and Developed by SuperSoft Technologies.<br/> Contact# +92 308 2595128
    </td>
</tr>

            </tbody>
        </table>

    </div>
</div>

<script type="text/javascript">
    localStorage.clear();

    function auto_print() {
        window.print()
    }

    setTimeout(auto_print, 1000);
</script>

</body>
</html>
