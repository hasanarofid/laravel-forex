<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forex Sentiment</title>

<!-- Font Awesome 4 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Styles -->
    <style>
      body {
        font-family: 'Nunito', sans-serif;
      }

      .ratios {
  margin: 0 20px;
  margin-bottom: 0px;
  left: 60px;
  right: 20px;
  top: 0;
  height: 24px;
  color: #fff;
  font-size: 11px;
  border-radius: 2px;
  box-shadow: 2px 2px 0 rgba(0, 0, 0, 0.1);
  position: relative;
}

.average-line {
  width: 2px;
  top: -6px;
  bottom: -10px;
  left: 49.8%;
  background: #A6A6A6;
}

.ratio-bar-left,
.ratio-bar-right {
  position: absolute;
  top: 0;
  bottom: 0;
  line-height: 24px;
  border-radius: 2px;
}

.ratio-bar-left {
  left: 0;
  background: #007bff;
  padding-left: 10px;
}

.ratio-bar-divider {
  background: #000;
  border-left: 1px solid #fff;
  border-right: 1px solid #fff;
  position: absolute;
  top: -1px;
  bottom: -1px;
  width: 3px;
  z-index: 1;
  opacity: 0.7;
}

.ratio-bar-right {
  right: 0;
  background: #F06A7A;
  padding-right: 10px;
  text-align: right;
}

.tool-button {
    background-color: #A6A6A6; /* Blue background color */
    color: white; /* Text color */
    padding: 8px 16px; /* Adjust padding as needed */
    border-radius: 4px; /* Rounded corners */
    margin-bottom: 8px; /* Bottom margin */
    display: inline-block;
    cursor: pointer; /* Show pointer cursor on hover */
}

/* Hover effect */
.tool-button:hover {
    opacity: 0.8; /* Reduce opacity on hover */
}

/* Optional: Add transition effect */
.tool-button {
    transition: opacity 0.3s ease; /* Smooth opacity transition */
}

/* Active state */
.tool-button.active {
    background-color: #007bff; /* Change background color when active */
    color: white; /* Text color when active */
}


    </style>
  </head>
  <body>
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
      <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
          <h1>Forex Sentiment</h1>
        </div>
        <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
        

          <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="row">
              <div class="col-md-8">
                <div class="container">
                  <div class="row">
                      <div class="col"></div>
                      <div class="col-3" style="text-align:center;font-size: 12px;">BUY</div> <!-- Align "BUY" to the left -->
                      <div class="col-3" style="text-align:center;font-size: 12px;"><span id="currency"></span>{{ $cur }} <i class="fa fa-bar-chart" aria-hidden="true"></i></div>
                      <div class="col-3" style="text-align:right;font-size: 12px;">SELL</div> <!-- Align "SELL" to the right -->
                      <div class="col"></div>
                  </div>
              
                  @foreach ($ratios as $item)
                  <div class="row">
                      <div class="col">{{ $item->company }}</div>
                      <div class="col-9">
                          <div class="ratios">
                              <div class="average-line">&nbsp;</div>
                              <div class="ratio-bar-left" style="width: {{ $item->buy }}%;">{{ $item->buy }}%</div>
                              <div class="ratio-bar-divider" style="left: {{ $item->buy }}%;"></div>
                              <div class="ratio-bar-right" style="width: {{ $item->sell }}%;">{{ $item->sell }}%</div>
                          </div>
                          <br>
                      </div>
                      <div class="col"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i></div>
                  </div>
                  @endforeach
              </div>
              
              
              </div>
              <div class="col-md-4" style="background-color: blanchedalmond">
                <div class="group titled" id="thePairs" style="display: block;">
                  <div class="title">
                    <span>Last Update</span>
                    <div>
                      <p>{{ $lastUpdate }}</p>
                    </div>
                </div>

                  <div class="title">
                      <span>Currency pairs</span>
                  </div>
                  <div class="tool-button-group" data-api-key="filter">
                    <div class="column-2">
                      @foreach ($currency as $currencyItem)
                          <div class="tool-button @if($currencyItem->currency == $cur) active @endif" onclick="setForex('{{ $currencyItem->currency }}')">
                              {{ $currencyItem->currency }}                                             
                          </div>
                      @endforeach
                    </div>
                  </div>
                </div>

              </div>
            </div>
         
          </div>
        </div>
      </div>
    </div>
  </body>
  <script>
    function setForex(currency) {
        // Redirect to the URL with the filter parameter
        window.location.href = "{{ route('index') }}?filter=" + currency;
    }
</script>

</html>