<?php
    use App\Http\Controllers\AESCipher;
    $aes = new AESCipher();
?>

<form id = "frmAll" method = "post">
    <div id = "allMsg"></div> 
    @csrf
    <div class="row">
        <div class="col-md-12">
        <fieldset class="form-group">
            <label for="basicInput">Department Name</label>
            <input type="text" class="form-control" id="basicInput"  placeholder="Department Name" name = "DepartmentName" value = "{{ $oldData['OldName'] }}" required autofocus>
        </fieldset>
        <div class="col-md-12">
    </div>

    <div class="row">
        <div class="col-md-12">
        <fieldset class="form-group">
            <label for="basicInput">Description</label>
            <input type="text" class="form-control" id="basicInput"  placeholder="Description" name = "Description" value = "{{ $oldData['OldDescription'] }}" required>
        </fieldset>
        <div class="col-md-12">
    </div>
</div>
