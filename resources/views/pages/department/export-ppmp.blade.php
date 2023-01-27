@php
    use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    use Carbon\Carbon;
    $total_estimated_price = 0.0;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    * {
        padding: 0px !important;
        margin: 0px !important;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif !important;
        font-size: 12px !important;
    }
    .width-100 {
        width: 100%;
    }
    .width-50 {
        width: 50%;
    }
    .float-left {
        float: left;
    }
    .text-center {
        text-align: center;
    }
    .padding-0 {
        padding: 0px !important;
    }
    .padding-5 {
        padding: 5px !important;
    }
    .padding-10 {
        padding: 15px !important;
    }
    .margin-0 {
        margin: 0px !important;
    }
    .text-white {
        color: white;
    }

    .border-2px {
        border: 2px black solid;
    }

    table, td, th {
        border: 1px solid;
        font-size: 10px !important;
        padding:5px !important;
        /* White-space: nowrap */
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    .font-weight-900 {
        font-weight: 900;
    }

    .flex-container {
        display: flex;
        flex-wrap: nowrap;
        /* background-color: DodgerBlue; */
    }
    .center {
        margin-left: auto;
        margin-right: auto;
        width: 100%;
        height: 100px;
        /* background-color: aqua; */
        display: flex;
        justify-content: center;
    }
    .content {
        width: 100%;
        height: 100%;
        /* background-color: blue; */
    }
    .slsu-logo {
        /* background-color: rebeccapurple; */
        width: 100px;
        position: relative;
        top: 20%;
        left: 15%;
        text-align: center;
        line-height: 75px;
        font-size: 30px;
    }
    .republic-act {
        position: fixed;
        top: 50px;
        /* background-color: pink; */
        width: 90%;
        text-align: center;
        font-size: 30px;
        line-height: 20px;
    }
</style>
<body>
   
    <div style="padding: 30px !important;">
        <div class="center">
            <div class="content">
                <div class="flex-container">
                    <div class="slsu-logo">
                        <img src="{{ public_path("images/slsu_logo.png") }}" alt="" height="60" width="60">
                    </div>
                    <div class="republic-act">
                        <p class="text-center padding-0">Republic of the Philippines</p>
                        <p class="text-center padding-0"><strong>SOUTHERN LEYTE STATE UNIVERSITY</strong></p>
                        <p class="text-center padding-0">Sogod, Southern Leyte</p>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="width-100 padding-5">
            <h4 class="text-white">TEXT</h4>
        </div> --}}
        <div class="width-100">
            <p class="text-center padding-0"><strong>PROJECT PROCUREMENT MANAGEMENT PLAN (PPMP) - {{ (new GlobalDeclare)->project_category($project_title[0]->project_category) }} </strong></p>
            <p class="text-center padding-0"><strong>CY {{ $project_title[0]->year_created }}</strong></p>
        </div>
        <div class="width-100 padding-5">
            <h4 class="text-white">TEXT</h4>
        </div>
        <div style="width: 50%">
            <p>Project Title: {{ $project_title[0]->project_title }}</p>
        </div>
        <div class="width-100 padding-5">
            <h4 class="text-white">TEXT</h4>
        </div>
        <div class="width-100">
            <p>END - USER / UNIT : Information System</p>
        </div>
        <div class="width-100 padding-5">
            <h4 class="text-white">TEXT</h4>
        </div>
        <div class="width-100">
            <table>
                <thead class="text-center">
                    <tr>
                        <td  rowspan="2" style="width: 5%;">CODE</td>
                        <td  rowspan="2" style="width: 16%;">GENERAL DESCRIPTION</td>
                        <td  colspan="2" rowspan="2" style="width: 16%; padding:5px !important;">QTY/SIZE</td>
                        <td  style="width: 16%;">ESTIMATED</td>
                        <td  rowspan="2" style="width: 16%;">MODE OF PROCUREMENT</td>
                        <td  colspan="12"style="width: 16%;">SCHEDULE/MILESTONE OF ACTIVITIES</td>
                    </tr>
                    <tr style="font-weight: 900;">
                        <td style="width: 18%;">BUDGET</td>
                        <td style="width: 1%;">JAN</td>
                        <td style="width: 1%;">FEB</td>
                        <td style="width: 1%;">MAR</td>
                        <td style="width: 1%;">APR</td>
                        <td style="width: 1%;">MAY</td>
                        <td style="width: 1%;">JUN</td>
                        <td style="width: 1%;">JULY</td>
                        <td style="width: 1%;">AUG</td>
                        <td style="width: 1%;">SEPT</td>
                        <td style="width: 1%;">OCT</td>
                        <td style="width: 1%;">NOV</td>
                        <td style="width: 1%;">DEC</td>
                    </tr>
                    <tr class="font-weight-900 text-center">
                        <td>{{ $project_title[0]->project_code }}</td>
                        <td>{{ $project_title[0]->project_title }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ppmps as $item)
                        @php
                            // getting total estimated price for this project
                            $total_estimated_price += $item->estimated_price
                        @endphp
                        <tr class="text-center font-weight-900">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td style="padding:5px !important;">{{ $item->quantity }}</td>
                            <td style="padding:5px !important;">{{ $item->unit_of_measurement }}</td>
                            <td><img src="{{ public_path("images/s_peso.png") }}" alt="" height="8" width="8"> {{ number_format($item->estimated_price,2,'.',',')  }}</td>
                            <td>{{ $item->abbreviation }}</td>
                            @for ($i = 1; $i <= 12; $i++)
                                    @if ($i == $item->expected_month)
                                        <td>
                                            <img src="{{ public_path("images/s_check.png") }}" alt="" height="20" width="20">
                                        </td>
                                    @else
                                        <td></td>
                                    @endif
                            @endfor
                        </tr>
                        <tr>
                            <td></td>
                            <td style="padding: 5px;">
                               {{ $item->item_description }}
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach
                    <tr class="text-center font-weight-900">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td> <img src="{{ public_path("images/s_peso.png") }}" alt="" height="8" width="8"> {{ number_format($total_estimated_price ,2,'.',',')  }}  </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>