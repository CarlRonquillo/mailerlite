<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MailerLiteApi\MailerLite;
use Http\Client\Exception\HttpException;

use App\Models\Key;

class SubscribersController extends Controller
{

    public function list(Request $request)
    {
        try {
            $key = $request->session()->get('key');
            $subscribersApi = (new MailerLite($key))->subscribers();

            $columns = ['#','email','name','country','date','time'];

            $draw = $request->input('draw');
            $limit = $request->input('length') ? : 10;
            $offset = $request->input('start') ? : 0;
            $field = $columns[(int)$request->input('order.0.column') ? : 4];
            $direction = $request->input('order.0.dir') ? : 'DESC';
            $searchQuery = $request->input('search.value') ? : null;
            $subsTotal = $subscribersApi->get()->count();
            $recordsFiltered = $subsTotal;

            if ($searchQuery) {
                $subscribers = $subscribersApi->search($searchQuery);
                $recordsFiltered = count($subscribers);
            } else {
                $subscribers = $subscribersApi
                    ->orderBy($field, strtoupper($direction))
                    ->limit($limit)
                    ->offset($offset)
                    ->get()
                    ->toArray();
            }

            if (isset($subscribers[0]->error)) {
                $errorCode = $data[0]->error->code;
                if ($data[0]->error->code === 1) {
                    $errorCode = 401;
                } elseif ($data[0]->error->code === 2) {
                    $errorCode = 404;
                }
                
                return response()->json(['status' => false, 'message' => $data[0]->error->message], $errorCode)->setCallback($request->input('callback'));
            }

            $data = [];
            $count = 0;
            if ($recordsFiltered) {
                foreach ($subscribers as $subscriber) {
                    $count++;
                    $datum  = [
                        'id' => $subscriber->id,
                        '#' => $count,
                        'email' => $subscriber->email,
                        'name' => $subscriber->name,
                        'country' => get_country($subscriber->fields),
                        'date' => get_date_time($subscriber->date_created),
                        'time' => get_date_time($subscriber->date_created, true)
                    ];

                    array_push($data,$datum);
                }
            }

            $responseData = [
                'draw' => $draw,
                'recordsTotal' => $subsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
                'query' => $searchQuery
            ];

            return response()->json($responseData, 200)->setCallback($request->input('callback'));;
        } catch (HttpException $e) {
            return response()->json($e->getMessage(), $e->getStatusCode())->setCallback($request->input('callback'));
        }
        
    }

    public function index(Request $request)
    {
        return view('subscribers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = $this->getCountries();
        return view('subscribers.create',['countries' => $countries]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required',
            'country' => 'required'
        ]);
        
        $message = '';
        $messageClass = 'alert-info';
        $key = $request->session()->get('key');
        $subscribersApi = (new MailerLite($key))->subscribers();

        $email = $request->input('email');
        $name = $request->input('name');
        $country = $request->input('country');

        // check if subscriber already exists
        $checkSubscriber = $subscribersApi->find($email);
        if (isset($checkSubscriber->id)) {
            $message = 'Subscriber already exists.';
            $messageClass = 'alert-danger';
        } else {
            $subscriber = [
                'email' => $email,
                'name' => $name,
                'fields' => [
                  'country' => $country
                ]
            ];
    
            $addedSubscriber = $subscribersApi->create($subscriber);

            if (isset($addedSubscriber->error)) {
                $message = $addedSubscriber->error->message;
                $messageClass = 'alert-danger';
            } elseif (isset($addedSubscriber->id)) {
                $message = "User with email ".$email." was successfully subscribed.";
                $messageClass = 'alert-success';
            } else {
                $message = "Adding subscriber failed. Please try again.";
                $messageClass = 'alert-danger';
            }
        }

        $request->session()->flash('email', $email);
        $request->session()->flash('name', $name);
        $request->session()->flash('country', $country);

        // alert message
        $request->session()->flash('message', $message);
        $request->session()->flash('messageClass', $messageClass);

        $countries = $this->getCountries();

        return redirect('/subscribers/create')->with([
                'countries' => $countries
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getCountries()
    {
        $countries = [];
        $path = storage_path('app\json\countries.json');

        if (file_exists($path)) {
            $countries = json_decode(file_get_contents($path), true);
        }

        return $countries;
    }
}
