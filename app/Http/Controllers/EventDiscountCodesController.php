<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\DiscountCode
use Carbon\Carbon;
use Illuminate\Http\Request;
use Log;

/*
  Attendize.com   - Event Management & Ticketing
 */

class EventDiscountCodesController extends MyBaseController
{
    /**
     * @param Request $request
     * @param $event_id
     * @return mixed
     */
     /*
    public function showDiscountCodes(Request $request, $event_id)
    {
        $allowed_sorts = [
            'created_at' => 'Creation date',
            'title' => 'Ticket title',
            'quantity_sold' => 'Quantity sold',
            'sales_volume' => 'Sales volume',
        ];

        // Getting get parameters.
        $q = $request->get('q', '');
        $sort_by = $request->get('sort_by');
        if (isset($allowed_sorts[$sort_by]) === false)
            $sort_by = 'title';

        // Find event or return 404 error.
        $event = Event::scope()->find($event_id);
        if ($event === null)
            abort(404);

        // Get tickets for event.
        $discount_codes = empty($q) === false
                ? $event->tickets()->where('code', 'like', '%'.$q.'%')->orderBy($sort_by, 'desc')->paginate()
                : $event->tickets()->orderBy($sort_by, 'desc')->paginate();

        // Return view.
	//TODO: change compact to what I need
        return view('ManageEvent.DiscountCodes', compact('event', 'tickets', 'sort_by', 'q', 'allowed_sorts'));
    }
*/
    /**
     * Show the edit discount code modal
     *
     * @param $event_id
     * @param $discount_code_id
     * @return mixed
     */
    public function showEditDiscountCode($event_id, $discount_code_id)
    {
        $data = [
            'event'    => Event::scope()->find($event_id),
            'discount_code'   => DiscountCode::scope()->find($discount_code_id),
        ];

        return view('ManageEvent.Modals.EditDiscountCode', $data);
    }

    /**
     * Show the create discount codes modal
     *
     * @param $event_id
     * @return \Illuminate\Contracts\View\View
     */
    public function showCreateDiscountCode($event_id)
    {
	//TODO: route to DiscountCodeModal
        return view('ManageEvent.Modals.CreateDiscountCode', [
                    'event'    => Event::scope()->find($event_id),
        ]);
    }

    /**
     * Creates a discount code
     *
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreateDiscountCode(Request $request, $event_id)
    {
        $discount_code = DiscountCode::createNew();

        if (!$discount_code->validate($request->all())) {
            return response()->json([
                        'status'   => 'error',
                        'messages' => $discount_code->errors(),
            ]);
        }

        $discount_code->event_id = $event_id;
        $discount_code->type = $request->get('type');
	$discount_code->amount = $request->get('amount');
	$discount_code->code = $request->get('code');
	$discount_code->exp_at = $request->get('exp_at') ? Carbon::createFromFormat('d-m-Y H:i', $request->get('exp_at')) : null;
	$discount_code->times_used = 0;
	$discount_code->max_times_used = $request->get('max_times_used') ? $request->get('max_times_used') : PHP_INT_MAX;

        $discount_code->save();

        session()->flash('message', 'Successfully Created Discount Code');

//TODO: showEventDiscountCodes
        return response()->json([
                    'status'      => 'success',
                    'id'          => $discount_code->id,
                    'message'     => 'Refreshing...',
                    'redirectUrl' => route('showEventDiscountCodes', [
                        'event_id' => $event_id,
                    ]),
        ]);
    }

    /**
     * Deleted a ticket
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postDeleteDiscountCode(Request $request)
    {
        $discount_code_id = $request->get('discount_code_id');

        $discount_code = DiscountCode::scope()->find($discount_code_id);

        /*
         * Don't allow deletion of tickets which have been sold already.
         */
        if ($discount_code->times_used > 0) {
            return response()->json([
                        'status'  => 'error',
                        'message' => 'Sorry, you can\'t delete this code, since users have used it before.',
                        'id'      => $discount_code->id,
            ]);
        }

        if ($discount_code->delete()) {
            return response()->json([
                        'status'  => 'success',
                        'message' => 'Discount Code Successfully Deleted',
                        'id'      => $discount_code->id,
            ]);
        }

        Log::error('DiscountCode Failed to delete', [
            'discount_code' => $discount_code,
        ]);

        return response()->json([
                    'status'  => 'error',
                    'id'      => $discount_code->id,
                    'message' => 'Whoops!, looks like something went wrong. Please try again.',
        ]);
    }

    /**
     * Edit a ticket
     *
     * @param Request $request
     * @param $event_id
     * @param $discount_code_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postEditDiscountCode(Request $request, $event_id, $discount_code_id)
    {
        $discount_code = DiscountCode::scope()->findOrFail($discount_code_id);

        /*
         * Override some validation rules
         */
        $validation_rules['max_times_used'] = ['integer', 'min:'.($discount_code->times_used)];
        $validation_messages['max_times_used.min'] = 'New maximum can\'t be less than the number already used.';

        $discount_code->rules = $validation_rules + $discount_code->rules;
        $discount_code->messages = $validation_messages + $discount_code->messages;

        if (!$discount_code->validate($request->all())) {
            return response()->json([
                        'status'   => 'error',
                        'messages' => $discount_code->errors(),
            ]);
        }

        $discount_code->title = $request->get('title');
        $discount_code->quantity_available = !$request->get('quantity_available') ? null : $request->get('quantity_available');
        $discount_code->price = $request->get('price');
        $discount_code->start_sale_date = $request->get('start_sale_date') ? Carbon::createFromFormat('d-m-Y H:i', $request->get('start_sale_date')) : null;
        $discount_code->end_sale_date = $request->get('end_sale_date') ? Carbon::createFromFormat('d-m-Y H:i', $request->get('end_sale_date')) : null;
        $discount_code->description = $request->get('description');
        $discount_code->min_per_person = $request->get('min_per_person');
        $discount_code->max_per_person = $request->get('max_per_person');

        $discount_code->save();

        return response()->json([
                    'status'      => 'success',
                    'id'          => $discount_code->id,
                    'message'     => 'Refreshing...',
                    'redirectUrl' => route('showEventTickets', [
                        'event_id' => $event_id,
                    ]),
        ]);
    }
}
