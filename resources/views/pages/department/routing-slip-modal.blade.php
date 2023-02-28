<div class="modal fade" id="RoutingSlipModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-m modal-dialog-centered" role="document">
      <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">ROUTING SLIP</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="RSSubmit" method="POST" enctype="multipart/form-data"> @csrf
                    <div class="col-sm-12">
                        <div class="row text-center" hidden>
                            <div class="col-sm-12">
                                <fieldset class="form-group" >
                                    <label for="">Value:  </label>
                                    <input type="text" class="activityNumber" id="" name="activityNumber" value="" ></td>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row text-center" hidden>
                            <div class="col-sm-12">
                                <fieldset class="form-group" >
                                    <label for="">PR NO:  </label>
                                    <input type="text" class="pr_no" id="" name="pr_no" value="{{ $data->pr_no }}" ></td>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <fieldset class="form-group" >
                                    <label for="">DATE RECEIVED:  </label>
                                    <input class="form-control" type="date" id="date_received" name="date_received" value="" required></td>
                                </fieldset>
                            </div>
                            <div class="col-sm-6">
                                <fieldset class="form-group" >
                                    <label for="">TIME RECEIVED:  </label>
                                    <input class="form-control"  type="time" id="time_received" name="time_received" value="" required></td>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <fieldset class="form-group" >
                                    <label>DATE RELEASED:  </label>
                                    <input class="form-control" type="date" id="date_released" name="date_released" value="" required></td>
                                </fieldset>
                            </div>
                            <div class="col-sm-6">
                                <fieldset class="form-group" >
                                    <label for="">TIME RELEASED:  </label>
                                    <input class="form-control"  type="time" id="time_released" name="time_released" value="" required></td>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <fieldset class="form-group" >
                                    <label>REMARK:  </label>
                                    <input class="form-control" type="text" id="remark" name="remark" value="" required>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    
            </div>

                    <div class="modal-footer" id = "footModal">
                        <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                        <button type="submit" class="btn btn-primary">SUBMIT</button>
                    </div>
                </form>

        </div>
    </div>
  </div>