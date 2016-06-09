@extends('Shared.Layouts.Master')

@section('title')
    @parent
    Event Discount Codes
@stop

@section('top_nav')
    @include('ManageEvent.Partials.TopNav')
@stop

@section('page_title')
    <i class="ico-ticket mr5"></i>
    Event Discount Codes
@stop

@section('menu')
    @include('ManageEvent.Partials.Sidebar')
@stop

@section('page_header')
    <div class="col-md-9">
        <!-- Toolbar -->
        <div class="btn-toolbar" role="toolbar">
            <div class="btn-group btn-group-responsive">
                <button data-modal-id='CreateDiscountCode'
                        data-href="{{route('showCreateDiscountCode', array('event_id'=>$event->id))}}"
                        class='loadModal btn btn-success' type="button"><i class="ico-ticket"></i> Create Discount Code
                </button>
            </div>
        </div>
        <!--/ Toolbar -->
    </div>
@stop

@section('content')
        <!--Start discount code table-->
        <div class="row">
	    <div class="col-md-12">
	            @if($discount_codes->count())
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Amount</th>
                                    <th>Number Used </th>
                                    <th>Number available</th>
				    <th>Expiration Date</th>
                                </tr>
                                </thead>

                                <tbody>
			        @foreach($discount_codes->get() as $code)
                                    <tr
					style="cursor: pointer"
					href="javascript:void(0)"
				        data-modal-id="discount-code-{{$code->id}}"
                                 data-href="{{ route('showEditDiscountCode', ['event_id' => $event->id, 'discount_code_id' => $code->id]) }}"
				 class="loadModal"
				 >
                                        <td>{{ $code->code }}</td>
                                        <td>{{ $code->amount}}</td>
                                        <td>{{ $code->times_used}}</td>
                                        <td>{{ $code->max_times_used < PHP_INT_MAX ? $code->max_times_used : (none) }}</td>
					<td>{{ $code->getFormatedDate('exp_at')? $code->getFormatedDate('exp_at') : '(none)'}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No discount code availble yet. 
                        </div>
                    @endif
              </div>
        </div><!--/ end discount code table table-->
@stop

