<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>{{ config('app.name', 'Laravel') }}</title>
      <!-- Scripts -->
      <script src="{{ asset('js/app.js') }}" defer></script>
      <!-- Fonts -->
      <link rel="dns-prefetch" href="//fonts.gstatic.com">
      <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
      <!-- Styles -->
      <link href="{{ asset('css/app.css') }}" rel="stylesheet">
      <style>
         .panel {
         margin-bottom: 20px;
         background-color: #fff;
         border: 1px solid transparent;
         border-radius: 4px;
         -webkit-box-shadow: 0 1px 1px rgb(0 0 0 / 5%);
         box-shadow: 0 1px 1px rgb(0 0 0 / 5%);
         }
         .panel-default>.panel-heading {
         color: #333;
         background-color: #f5f5f5;
         border-color: #ddd;
         }
         .panel-default {
         border-color: #ddd;
         }
         .col-md-offset-3 {
         margin-left: 25%;
         }
         .credit-card-box .display-td {
         display: table-cell;
         vertical-align: middle;
         width: 100%;
         text-align: center;
         padding:5px;
         }
      </style>
   </head>
   <body id="app-layout">
      <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
         <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
               <!-- Left Side Of Navbar -->
               <ul class="navbar-nav mr-auto">
               </ul>
               <!-- Right Side Of Navbar -->
               <ul class="navbar-nav ml-auto">
                  <!-- Authentication Links -->
                  @guest
                  <li class="nav-item">
                     <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                  </li>
                  @if (Route::has('register'))
                  <li class="nav-item">
                     <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                  </li>
                  @endif
                  @else
                  <li class="nav-item dropdown">
                     <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                     </a>
                     <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                        </a>
                           @if(!empty($showSubscriptionMenu))
                              <a class="dropdown-item" href="{{ route('subscription') }}">
                                 Subscription List
                              </a>
                           @endif
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                           @csrf
                        </form>
                     </div>
                  </li>                  
                  @endguest
               </ul>
            </div>
         </div>
      </nav>
      <div class="container">
         <div class="row" style="margin-top: 100px;">
            <div class="col-md-6 col-md-offset-3">
               <div class="panel panel-default credit-card-box">
                  <div class="panel-heading display-table" >
                     <div class="row display-tr" >
                        <h3 class="panel-title display-td" >Payment Details</h3>
                     </div>
                  </div>
                  <div class="panel-body">
                     <div class="col-md-12">
                        <form method="POST" action="{{ url('subscriptionPost') }}" accept-charset="UTF-8" data-parsley-validate="" id="payment-form">
                            @csrf
                           @if ($message = Session::get('success'))
                              <div class="alert alert-success alert-block">
                                 <button type="button" class="close" data-dismiss="alert">×</button> 
                                 <strong>{{ $message }}</strong>
                              </div>
                           @endif
                           <div class="form-group" id="product-group">
                              {!! Form::label('plane', 'Select Plan:') !!}
                              <select class="form-control" required="required" data-parsley-class-handler="#product-group" id="plane" name="plane">
                                 @foreach($allPlan as $plan)
                                 <option value="{{ $plan->id }}"><span class="plan-name">{{$plan->product->name}}</span>
                                    <span class="plan-price">{{$plan->currency}} {{$plan->amount/100}}<small> /{{$plan->interval}}</small></span>
                                 </option>
                                 @endforeach
                              </select>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="form-group">
                                    <div id="card-element">
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="form-group">
                              <button id="card-button" class="btn btn-lg btn-block btn-success btn-order">Place order !</button>
                           </div>
                           <div class="row">
                              <div class="col-md-12">
                                 <span class="payment-errors" id="card-errors" style="color: red;margin-top:10px;"></span>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- PARSLEY -->
      <script>
         window.ParsleyConfig = {
             errorsWrapper: '<div></div>',
             errorTemplate: '<div class="alert alert-danger parsley" role="alert"></div>',
             errorClass: 'has-error',
             successClass: 'has-success'
         };
      </script>
      <script src="http://parsleyjs.org/dist/parsley.js"></script>
      <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
      <script src="https://js.stripe.com/v3/"></script>
      <script>
         var style = {
             base: {
                 color: '#32325d',
                 lineHeight: '18px',
                 fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                 fontSmoothing: 'antialiased',
                 fontSize: '16px',
                 '::placeholder': {
                     color: '#aab7c4'
                 }
             },
             invalid: {
                 color: '#fa755a',
                 iconColor: '#fa755a'
             }
         };
         
         const stripe = Stripe('{{ env("STRIPE_KEY") }}', { locale: 'en' }); // Create a Stripe client.
         const elements = stripe.elements(); // Create an instance of Elements.
         const card = elements.create('card', {hidePostalCode: true, style: style }); // Create an instance of the card Element.
         
         card.mount('#card-element'); // Add an instance of the card Element into the `card-element` <div>.
         
         card.on('change', function(event) {
             var displayError = document.getElementById('card-errors');
             if (event.error) {
                 displayError.textContent = event.error.message;
             } else {
                 displayError.textContent = '';
             }
         });
         
         // Handle form submission.
         var form = document.getElementById('payment-form');
         form.addEventListener('submit', function(event) {
             event.preventDefault();
         
             stripe.createToken(card).then(function(result) {
                 if (result.error) {
                     // Inform the user if there was an error.
                     var errorElement = document.getElementById('card-errors');
                     errorElement.textContent = result.error.message;
                 } else {
                     // Send the token to your server.
                     stripeTokenHandler(result.token);
                 }
             });
         });
         
         // Submit the form with the token ID.
         function stripeTokenHandler(token) {
             // Insert the token ID into the form so it gets submitted to the server
             var form = document.getElementById('payment-form');
             var hiddenInput = document.createElement('input');
             hiddenInput.setAttribute('type', 'hidden');
             hiddenInput.setAttribute('name', 'stripeToken');
             hiddenInput.setAttribute('value', token.id);
             form.appendChild(hiddenInput);
         
             // Submit the form
             form.submit();
         }
      </script>
   </body>
</html>