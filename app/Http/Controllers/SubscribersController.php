<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MailerLiteApi\MailerLite;

class SubscribersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $key = $request->session()->get('key');

        $subscribers = (new MailerLite($key))
                        ->subscribers()
                        ->get()
                        ->toArray();

        if (isset($groupsApi['0']->error)) {
            $data = ['message' => 'API Key is invalid.'];
        } else {
            $data = ['subscribers' => $subscribers];
        }

        return view('subscribers.index',['data' => $data]);
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
