<div role="dialog"  class="modal fade" style="display: none;">
   {!! Form::open(array('url' => route('postCreateDiscountCode', array('event_id' => $event->id)), 'class' => 'ajax')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 class="modal-title">
                    <i class="ico-ticket"></i>
                    Create Discount Code</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
		    	<div class="row">
		    	    <div class="col-sm-6">
                                <div class="form-group">
                                   {!! Form::label('code', 'Discount Code', array('class'=>'control-label required')) !!}
                           	   {!!  Form::text('code', Input::old('code'),
                                         	    array(
                                        	       'class'=>'form-control',
                                        	       'placeholder'=>'E.g: SUMMER15OFF'
                                        	        ))  !!}
                        	</div>
			    </div>
			    <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('amount', 'Discount Amount', array('class'=>'control-label required')) !!}
                                    {!!  Form::text('amount', Input::old('amount'),
                                                array(
                                                'class'=>'form-control',
                                                'placeholder'=>'E.g: 25'
                                                ))  !!}


                        	 </div>
 			    </div>
			</div>
		        <div class="row">
                           
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('max_times_used', 'Quantity Available', array('class'=>' control-label')) !!}
                                    {!!  Form::text('max_times_used', Input::old('max_times_used'),
                                                array(
                                                'class'=>'form-control',
                                                'placeholder'=>'E.g: 100 (Leave blank for unlimited)'
                                                )
                                                )  !!}
                                </div>
                            </div>
			    <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('exp_at', 'Expiration Date', array('class'=>' control-label')) !!}
                                    {!!  Form::text('exp_at', Input::old('exp_at'),
                                                    [
                                                'class'=>'form-control start hasDatepicker ',
                                                'data-field'=>'datetime',
                                                //'data-startend'=>'start',
                                                //'data-startendelem'=>'.end',
                                                'readonly'=>'',
						'placeholder' => 'Click to select a date'

                                            ])  !!}
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div> <!-- /end modal body-->
            <div class="modal-footer">
               {!! Form::button('Cancel', ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
               {!! Form::submit('Create Code', ['class'=>"btn btn-success"]) !!}
            </div>
        </div><!-- /end modal content-->
       {!! Form::close() !!}
    </div>
</div>