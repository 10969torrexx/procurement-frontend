<?php
    use App\Http\Controllers\AESCipher;
    $aes = new AESCipher();
?>

<form id="frmAll" autocomplete="off">
    <div id="allMsg"></div>
    
    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label>PAR #</label><br>
                {{substr($property->DateIssued, 0, 7)."-".str_pad($property->PARNo, 4, "0", STR_PAD_LEFT)}}
            </div>
        </div>

        <div class="col-4">
            <div class="form-group">
                <label>PO #</label><br>
                {{$property->PONumber}}
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label for="EmployeeName">Employee Name *</label>
                <select name="EmployeeName" class="form-control" id="EmployeeName">
                    <?php
                        $emp = (isset($oldsub['EmployeeID'])?$oldsub['EmployeeID']:"");
                        echo '<option value = "'.$aes->encrypt(0).'"></option>';
                        foreach($employees as $employee){
                            echo '<option value = "'.$aes->encrypt($employee['id']).'" '.($emp == $employee['id']?"Selected":"").'>'.$employee['LastName'].', '.$employee['FirstName'].'</option>';
                        }

                    ?>
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="FundCluster">Fund Cluster *</label>
                <select name="FundCluster" class="form-control" id="FundCluster" required>
                    <option value = "0"></option>
                    <option value="RAF">Regular Agency Fund</option>
                    <option value = "IGF">Internally Generated Funds</option>
                    <option value="BRF">Business Related Funds</option>
                </select>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="ItemName">Item Name *</label>
                <input type="text" name="ItemName" id="ItemName" class="form-control" value="{{isset($old['ItemName'])?$old['ItemName']:""}}">
            <div class="form-group">
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="Description">Description *</label>
                <textarea name="Description" id="Description" class="form-control" rows="5">{{isset($old['Description'])?$old['Description']:""}}</textarea>
            <div class="form-group">
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6 col-lg-3 col-sm-3">
            <div class="form-group">
                <label for="Quantity">Quantity *</label>
                <input type="number" name="Quantity" id="Quantity" class="form-control" value="{{isset($old['Quantity'])?$old['Quantity']:""}}">
            </div>
        </div>

        <div class="col-xs-6 col-lg-3 col-sm-3">
            <div class="form-group">
                <label for="Unit">Unit *</label>
                <input type="text" name="Unit" id="Unit" class="form-control" value="{{isset($old['Unit'])?$old['Unit']:""}}">
            </div>
        </div>

        <div class="col-xs-6 col-lg-3 col-sm-3">
            <div class="form-group">
                <label for="UnitPrice">Unit Price *</label>
                <input type="number" name="UnitPrice" id="UnitPrice" class="form-control" value="{{isset($old['UnitPrice'])?$old['UnitPrice']:""}}">
            </div>
        </div>

        <div class="col-xs-6 col-lg-3 col-sm-3">
            <div class="form-group">
                <label for="Etimatedusefullife">Etimated useful life</label>
                <input type="text" name="Etimatedusefullife" id="Etimatedusefullife" class="form-control" value="{{isset($old['Etimatedusefullife'])?$old['Etimatedusefullife']:""}}">
            </div>
        </div>

    </div>

</form>
