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
            <label for="AccountName">Account Name</label>
            <input type="text" class="form-control" id="AccountName"  placeholder="Account Name" name = "AccountName" value = "{{ $oldData['OldName'] }}" required autofocus>
        </fieldset>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <fieldset class="form-group">
                <label for="Email">Email</label>
                <input type="email" class="form-control" id="Email"  placeholder="Email" name = "Email" value = "{{ $oldData['OldEmail'] }}" required>
            </fieldset>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <fieldset class="form-group">
                <label for="Password">Password</label>
                <input type="password" class="form-control" id="Password"  placeholder="Password" name = "Password"  required>
            </fieldset>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <fieldset class="form-group">
                <label for="ReTypePassword">ReTypePassword</label>
                <input type="password" class="form-control" id="ReTypePassword"  placeholder="ReTypePassword" name = "ReTypePassword" required>
            </fieldset>
        </div>
    </div>
</div>
