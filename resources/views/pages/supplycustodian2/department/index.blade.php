<?php
    use App\Http\Controllers\AESCipher;
    $aes = new AESCipher();
?>

@extends('layouts.contentLayoutMaster')
{{-- title --}}
@section('title','Department')

{{-- vendor style --}}
@section('vendor-styles')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">
@endsection
{{-- page-styles --}}

@section('content')

<!-- Scroll - horizontal and vertical table -->
<section id="horizontal-vertical">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">MASTERLIST</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <p class="card-text"></p>
                        <div class="table-responsive">
                            <div class = "row">
                                <div class = "col-lg-12 col-xs-12">
                                    <a href = "#" class = "btn btn-success round mr-1 mb-1" data-flag = "{{ $aes->encrypt('department')}}" data-button = "{{ $aes->encrypt('add')}}" data-id = "{{ $aes->encrypt('0')}}" data-toggle = "modal" data-target = "#allModal"><i class="bx bx-plus"></i> New Department</a>
                                </div>
                                
                            </div>
                            <table class="table nowrap zero-configuration compact" id = "idAllTable">
                                <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>Name</th>
                                        <th>Description</th>
                            
                                    </tr>
                                </thead>
                                <tbody>

                                <?php
                                    $ctr = 1;
                                    foreach ($departments as $department){
                                    //    dd($department);bx-edit
                                        echo '<tr id = "'.$ctr.'">
                                                <td>
                                                    <a href = "#" data-flag = "'.$aes->encrypt('department').'" data-button = "'.$aes->encrypt('edit').'" data-id = "'.$aes->encrypt($department->id).'" data-toggle = "modal" data-target = "#allModal">
                                                        <i class="bx bxs-edit-alt text-warning"></i>&nbsp;
                                                    </a>
                                                    <a ctr = "'.$ctr.'" button = "'.$aes->encrypt("delete").'" class = "hrefdelete" flag = "'.$aes->encrypt("department").'" href = "'.urlencode($aes->encrypt($department->id)).'">
                                                        <i class="bx bxs-trash-alt text-danger"></i>
                                                    </a>
                                                </td>
                                                <td>'.$department->DepartmentName.'</td>
                                                <td>'.$department->Description.'</td>
             
                                              </tr>';
                                        $ctr++;
                                    }
                                ?>
                                    
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ Scroll - horizontal and vertical table -->
@endsection
{{-- vendor scripts --}}
@section('vendor-scripts')
<script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>
@endsection
{{-- page scripts --}}
@section('page-scripts')
<script src="{{asset('js/scripts/datatables/datatable.js')}}"></script>
@endsection