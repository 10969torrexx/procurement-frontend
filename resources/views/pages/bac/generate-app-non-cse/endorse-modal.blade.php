
@php
     use App\Http\Controllers\AESCipher;
    use App\Http\Controllers\GlobalDeclare;
    $aes = new AESCipher();
    // $global = new GlobalDeclare();
@endphp
<div class="modal fade" id="endorseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-primary ">
                    <strong> Upload Signed APP NON CSE</strong>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('upload_app') }}" method="POST" enctype="multipart/form-data"> @csrf
                    <input type="hidden" name="year_created" value="{{ (new AESCipher)->encrypt(reset($val)) }}">
                    <input type="hidden" name="type" value="3">
                    <input type="hidden" name="project_category" value="{{ (new AESCipher)->encrypt($project_category)}}">
                    <div class="form-group">
                        <label for="">File name</label>
                        <textarea name="file_name" class="form-control file_name" id="" cols="30" rows="1" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Signed APP File</label>
                        <input type="file" name="signed_app" id="signed-app" class="form-control signed_app" required>
                        <p class="card-text alert bg-rgba-info">This can only process (.pdf, .jpeg, .jpg, .png) files</p>
                    </div>
                    <button type="submit" class="btn btn-success text-white endorse" id="upload-signed-app">Upload Signed app</button>
                </form>
            </div>
        </div>
    </div>
</div>