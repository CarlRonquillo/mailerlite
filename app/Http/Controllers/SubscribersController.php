<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MailerLiteApi\MailerLite;
use Http\Client\Exception\HttpException;
use App\Http\Requests\SubscriberValidationRequest;

class SubscribersController extends Controller
{

    protected $request;
    protected $subscribersApi;

    public function __construct(
        Request $request
    ) {
        $this->request = $request;
    }

    protected function initSubscribersApi()
    {
        try {
            $key = $this->request->session()->get('key');
            if($key) {
                $this->subscribersApi = (new MailerLite($key))->subscribers();
            }
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());
            return redirect('/');
        }
    }

    public function index()
    {
        return view('subscribers.index');
    }

    public function create()
    {
        return view('subscribers.create',[
            'countries' => $this->getCountries()
        ]);
    }

    public function store(SubscriberValidationRequest $request)
    {
        $request->validated();

        try {
            $this->initSubscribersApi();

            $email = $request->input('email');
            $name = $request->input('name');
            $country = $request->input('country');

            // check if subscriber already exists
            $checkSubscriber = $this->subscribersApi->find($email);
            if (isset($checkSubscriber->id)) {
                $alert = 'Subscriber already exists.';
                $alertType = 'error';
            } else {
                $subscriber = [
                    'email' => $email,
                    'name' => $name,
                    'fields' => [
                    'country' => $country
                    ]
                ];
        
                $addedSubscriber = $this->subscribersApi->create($subscriber);

                if (isset($addedSubscriber->error)) {
                    $alert = $addedSubscriber->error->message;
                    $alertType = 'error';
                } elseif (isset($addedSubscriber->id)) {
                    $alert = "User with email ".$email." was successfully subscribed. Go back to <a href='/subscribers'>list</a>.";
                    $alertType = 'success';
                } else {
                    $alert = "Adding subscriber failed. Please try again.";
                    $alertType = 'error';
                }
            }

        } catch (\Exception  $e) {
            $this->request->session()->flash('error', $e->getMessage());
        }

        if($alertType == 'error') {
            $this->request->session()->flash('name', $name);
            $this->request->session()->flash('email', $email);
            $this->request->session()->flash('country', $country);
        }

        return redirect('/subscribers/create')->with($alertType, $alert);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $message = '';
        $messageClass = '';
        $country = '';
        $subscriber = [];

        try {
            $this->initSubscribersApi();
            $subscriber = $this->subscribersApi->find($id);

            foreach ($subscriber->fields as $field){
                if ($field->key == 'country'){
                    $country = $field->value;
                    break;
                }
            }
        } catch (\Exception $e) {
            $this->request->session()->flash('error', $e->getMessage());
        }

        return view('subscribers.edit',[
            'subscriber' => $subscriber,
            'currentCountry' => $country,
            'countries' => $this->getCountries()
        ]);
    }

    public function update(SubscriberValidationRequest $request, $id)
    {
        $request->validated();

        $country = '';
        $subscriber = '';

        try {
            $this->initSubscribersApi();

            $subscriberData = [
                'name' => $request->get('name'),
                'fields' => [
                    'country' => $request->get('country')
                ]
            ];

            $subscriber = $this->subscribersApi->update($id, $subscriberData);

            if(isset($subscriber->id)) {
                $country = $request->get('country');

                $alert = 'Subscriber was successfully updated. Go back to <a href="/subscribers">list</a>.';
                $alertType = 'success';
            } else {
                $message = 'Something went wrong. Please try again.';
                $messageClass = 'error';
            }
            
        } catch (\Exception  $e) {
            $alert = $e->getMessage();
            $alertType = 'error';
        }

        return back()->with($alertType,$alert);
    }

    public function destroy($id)
    {
        try {
            $alert = 'Subscriber with id: '. $id .' was successfully deleted.';
            $alertClass = 'alert-success';
            $code = 200;

            $this->initSubscribersApi();
            $return = $this->subscribersApi->delete($id);

            if (isset($return)) {
                $alert = $response->error->message;
                $alertClass = 'alert-danger';
                $code = 500;
            }

            return response()->json([
                'alert' => $alert,
                'alertClass' => $alertClass
            ], $code);
            
        } catch (\Exception  $e) {
            return response()->json([
                'alert' => $e->getmessage()
            ], 500);
        }
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
