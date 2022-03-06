<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\User;
use Stripe;
use Session;
use Exception;

class StripeSubscriptionController extends Controller
{

    /**
     * Show all Plan list     
    */
    

    public function listAllProducts() {        
        $allPlan =  $this->retrieveProducts();
        $showSubscriptionMenu = 0;
        $key = env('STRIPE_SECRET');
        $stripe = new \Stripe\StripeClient($key);
        $user = auth()->user();
        if (is_null($user->stripe_id)) {
            $stripeCustomer = $user->createAsStripeCustomer();
        }
        $subscription = $stripe->subscriptions->all(['customer' => $user->stripe_id]);  
        if(!empty($subscription->data)) {
            $showSubscriptionMenu = 1;
        }
        if(!empty($allPlan)) {
            return view('subscription.list',['allPlan' => $allPlan,'showSubscriptionMenu' => $showSubscriptionMenu]); 
        } else {
            return view('subscription.create'); 
        }  
    }

    public function retrieveProducts() {
        $key = env('STRIPE_SECRET');
        $stripe = new \Stripe\StripeClient($key);
        $plansraw = $stripe->plans->all();
        $plans = $plansraw->data;
        
        foreach($plans as $plan) {
            $prod = $stripe->products->retrieve(
                $plan->product,[]
            );
            $plan->product = $prod;
        }
        return $plans;
    }



    /**
     * Create all Products     
    */
    public function createAllProducts() {
        $key = env('STRIPE_SECRET');
        $stripe = new \Stripe\StripeClient($key);
        $allProductName = ['shared Hosting','VPS Hosting','cloud Hosting'];
        $productCreationResponse = [];
        foreach ($allProductName as $value) {
            try {
                $productCreationResponse['success'][]  = $stripe->products->create([
                    'name' => $value,
                ]);
            } catch (Exception $e) {
                $productCreationResponse["error_message"][] = $e->getMessage();                
            }
        }
        if(!empty($productCreationResponse['success'])) {
            if($this->setPriceForAllProducts($productCreationResponse['success']) == 'success') {
                  return  redirect('/listProducts');
            }               
        } else {
            echo "no product is created";
        }
    }

    /**
     * set  all Products Price      
    */
    public function setPriceForAllProducts($allProducts) {
        $priceCreationResponse = [];        
        $key = env('STRIPE_SECRET');
        $stripe = new \Stripe\StripeClient($key); 
        $amount = 0; 
        foreach ($allProducts as $product) {
            switch ($product->name) {
                case "shared Hosting":
                  $amount = 1000;
                  break;
                case "VPS Hosting":
                  $amount = 2000;
                  break;
                case "cloud Hosting":
                  $amount = 3000;
                  break;
                default:
                    $amount = 1000;
              }
            try {
                $priceCreationresponse['success_message'][]  = $stripe->prices->create([
                    "active" => true,
                    "billing_scheme" => "per_unit",
                    "currency" =>  "inr",
                    "lookup_key" =>  null,
                    "product" => $product->id,
                    "recurring" =>  [
                        "interval" => "month",
                        "interval_count" => 1,
                        "usage_type" => "licensed"
                    ],
                    "tax_behavior" => "unspecified",
                    "unit_amount" => $amount,
                  ]);
            } catch (Exception $e) {
                $priceCreationresponse["error_message"][] = $e->getMessage();                
            }
        }
        if(!empty($priceCreationresponse['success_message'])) {
            return 'success';
        } else {
            return 'failed';
        }
    }
    
    /**
     * set subscription request post      
    */
    public function subscriptionPost(Request $request) {
            $paymentMethod = array();
            $user = auth()->user();            
            $input = $request->all();
            $token =  $request->stripeToken;                   
            try {
                Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
              
                if (is_null($user->stripe_id)) {
                    $stripeCustomer = $user->createAsStripeCustomer();
                }

                \Stripe\Customer::createSource(
                    $user->stripe_id,
                    ['source' => $token]
                );
               
                $user->newSubscription('test',$input['plane'])
                    ->create('', [
                    'email' => $user->email,
                    'name' => $user->name,
                ]);
                return redirect()->route('subscription')->with('success','Subscription quantity is updated');
            } catch (Exception $e) {
                return back()->with('success',$e->getMessage());
            }
    }


    /**
     * Show subscription list     
    */
    
    public function subscription () {
        $key = env('STRIPE_SECRET');
        $stripe = new \Stripe\StripeClient($key);
        $user = auth()->user();
        if (is_null($user->stripe_id)) {
            $stripeCustomer = $user->createAsStripeCustomer();
        }
        $subscription = $stripe->subscriptions->all(['customer' => $user->stripe_id]);       
        if(empty($subscription->data)) {
            return view('subscription.create',compact('subscription'));
        }
        return view('subscription.index',compact('subscription'));
    }

    /**
     * Edit subscription     
    */
    
    public function editSubscription($subscription_id,$itemId,$quantity) {
        
        $data = array();
        $data['subscription_id'] = $subscription_id;
        $data['itemId'] = $itemId;
        $data['quantity'] = $quantity;
        return view('subscription.update',$data);
    }

    /**
     * Update subscription     
    */

    public function updateSubscription(Request $request) {
        try {
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            // $user = auth()->user();
            \Stripe\Subscription::update($request->subscription_id, [
            'items' => [
              [
                'id' => $request->item_id,
                'quantity' => $request->quantity,
              ],
            ],
          ]);
            return redirect()->route('subscription')->with('success','Subscription quantity is updated');
        } catch (Exception $e) {
            return back()->with('success',$e->getMessage());
        }
    }
}
