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

                        <a class="dropdown-item" href="{{ route('subscription') }}">
                           Subscription List
                        </a>
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
                        <h3 class="panel-title display-td" >Upgrade and downgrade quantity</h3>
                     </div>
                  </div>
                  <div class="panel-body">
                     <div class="col-md-12">
                        <form method="POST" action="{{ route('updateSubscription') }}"  accept-charset="UTF-8" data-parsley-validate="" id="payment-form">
                            @csrf                          
                           <div class="form-group">
                              <input type ="text" class="form-control" required="required"  name="quantity" value="{{ $quantity }}">
                              <input type ="hidden" class="form-control" required="required"  name="subscription_id" value="{{ $subscription_id }}">
                              <input type ="hidden" class="form-control" required="required"  name="item_id" value="{{ $itemId }}">
                           </div>
                           <div class="form-group">
                              <input type="submit" value="Update quantity" class="btn btn-lg btn-block btn-success btn-order">
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
   </body>
</html>