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
      <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"> -->

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
                        <a class="dropdown-item" href="{{ route('listProducts') }}">
                           Create new order
                        </a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
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
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <h2>Stripe Subscription</h2>
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                    <strong>{{ $message }}</strong>
                </div>
                @endif
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Ammont</th>
                            <th>Interval</th>
                            <th>Quantity</th>
                            <th>End Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($subscription))
                            @foreach($subscription->data as $key => $value)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>${{ $value->plan->amount/100 }}</td>
                                    <td>{{ $value->plan->interval }}</td>                                    
                                    <td>{{ $value->quantity }}</td>
                                    <td>{{ date('Y-m-d',$value->current_period_end) }}</td>
                                    <td><a class="btn btn-danger" href="{{ url('/editSubscription/'.$value->id.'/'.$value->items->data[0]->id.'/'.$value->items->data[0]->quantity) }}">Update</a> </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>