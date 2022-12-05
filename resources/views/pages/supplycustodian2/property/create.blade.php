<?php
    use App\Http\Controllers\AESCipher;
    $aes = new AESCipher();
?>

<form id="frmAll" autocomplete="off">
    <div id="allMsg"></div>
    <div class="row">
        <div class="col-md-4">
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
                <label for="Type">Type *</label>
                <select name="Type" class="form-control" id="Type" required>
                    <option value = "0"></option>
                    <option <?=(isset($old['propertytype']) && $old['propertytype'] =="ICS"?"Selected":"")?>>ICS</option>
                    <option <?=(isset($old['propertytype']) && $old['propertytype'] =="PAR"?"Selected":"")?>>PAR</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="FundCluster">Fund Cluster *</label>
                <select name="FundCluster" class="form-control" id="FundCluster" required>
                    <option value = "0"></option>
                    <option value="RAF" <?=(isset($oldsub['FundCluster']) && $oldsub['FundCluster'] =="RAF"?"Selected":"")?>>Regular Agency Fund</option>
                    <option value = "IGF" <?=(isset($oldsub['FundCluster']) && $oldsub['FundCluster'] =="IGF"?"Selected":"")?>>Internally Generated Funds</option>
                    <option value="BRF" <?=(isset($oldsub['FundCluster']) && $oldsub['FundCluster']=="BRF"?"Selected":"")?>>Business Related Funds</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group">    
                <label for="ItemName">Item Name *</label>
                <input type="text" name="ItemName" id="ItemName" class="form-control" value="{{isset($oldsub['ItemName'])?$oldsub['ItemName']:""}}">
            <div class="form-group">
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="Description">Description *</label>
                <textarea name="Description" id="Description" class="form-control" rows="5">{{isset($oldsub['Description'])?$oldsub['Description']:""}}</textarea>
            <div class="form-group">
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6 col-lg-4 col-sm-4">
            <div class="form-group">
                <label for="Quantity">Quantity *</label>
                <input type="number" name="Quantity" id="Quantity" class="form-control" value="{{isset($oldsub['Quantity'])?$oldsub['Quantity']:""}}">
            </div>
        </div>

        <div class="col-xs-6 col-lg-4 col-sm-4">
            <div class="form-group">
                <label for="Unit">Unit *</label>
                <input type="text" name="Unit" id="Unit" class="form-control" value="{{isset($oldsub['Unit'])?$oldsub['Unit']:""}}">
            </div>
        </div>

        <div class="col-xs-6 col-lg-4 col-sm-4">
            <div class="form-group">
                <label for="UnitPrice">Unit Price *</label>
                <input type="number" name="UnitPrice" id="UnitPrice" class="form-control" value="{{isset($oldsub['UnitPrice'])?$oldsub['UnitPrice']:""}}">
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-xs-6 col-lg-8 col-sm-8">
            <div class="form-group">
                <label for="Issuedby">Issued by *</label>
                <select name="Issuedby" class="form-control" id="Issuedby">
                    <?php
                        $emp = (isset($old['IssuedBy'])?$old['IssuedBy']:"");
                        echo '<option value = "'.$aes->encrypt(0).'"></option>';
                        foreach($employees as $employee){
                            echo '<option value = "'.$aes->encrypt($employee['id']).'" '.($emp==$employee['id']?"Selected":"").'>'.$employee['LastName'].', '.$employee['FirstName'].'</option>';
                        }

                    ?>
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-lg-4 col-sm-4">
            <div class="form-group">
                <label for="DateIssued">Date Issued *</label>
                <input type="date" name="DateIssued" id="DateIssued" class="form-control" value="{{isset($old['DateIssued'])?$old['DateIssued']:""}}">
            </div>
        </div>

        

    </div>

    <div class="row">
        
        <div class="col-xs-6 col-lg-4 col-sm-4">
            <div class="form-group">
                <label for="DateAcquired">Date Acquired *</label>
                <input type="date" name="DateAcquired" id="DateAcquired" class="form-control" value="{{isset($old['DateAcquired'])?$old['DateAcquired']:""}}">
            </div>
        </div>

        <div class="col-xs-6 col-lg-4 col-sm-4">
            <div class="form-group">
                <label for="PONumber">PO Number *</label>
                <input type="text" name="PONumber" id="PONumber" class="form-control" value="{{isset($old['PONumber'])?$old['PONumber']:""}}">
            </div>
        </div>

        <div class="col-xs-6 col-lg-4 col-sm-4">
            <div class="form-group">
                <label for="DateReceived">Date Received *</label>
                <input type="date" name="DateReceived" id="DateReceived" class="form-control" value="{{isset($old['DateReceived'])?$old['DateReceived']:""}}">
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-8">
            <div class="form-group">
                <label for="Supplier">Supplier *</label>
                <select name="Supplier" class="form-control" id="Supplier" required>
                    <?php
                        $dr = (isset($old['StoreName'])?$old['StoreName']:"");
                        echo '<option value = "'.$aes->encrypt(0).'"></option>';
                        foreach($suppliers as $supplier){
                            echo '<option value = "'.$aes->encrypt($supplier['id']).'" '.($dr == $supplier['id']?"Selected":"").'>'.$supplier['SupplierName'].'</option>';
                        }

                    ?>
                </select>
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label for="Etimatedusefullife">Etimated useful life *</label>
                <input type="text" name="Etimatedusefullife" id="Etimatedusefullife" class="form-control" value="{{isset($oldsub['Etimatedusefullife'])?$oldsub['Etimatedusefullife']:""}}">
            </div>
        </div>
    </div>
</form>
