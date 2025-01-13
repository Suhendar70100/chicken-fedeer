<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="path/to/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.6/css/dataTables.dataTables.css">
    <!-- Include a required theme -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.6/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        .clock {
            font-size: 4rem;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            display: flex;
            flex-direction: row;
            gap: 50px;
            padding-bottom: 15px;
        }

        .container2 {
            display: flex;
            flex-direction: column;
            gap: 50px;
            padding-bottom: 15px;
        }
        .nav-pills .nav-link.active {
            background-color: #545454; /* Customize the active background color */
            text: white;
        }
        .nav-link {
            color: white; /* Default color for unselected links */
        }

        .nav-link.active {
            color: white; /* Color for the selected link */
        }
        .nav-link:hover {
            color: white;
        }
    </style>
</head>

<body>
  @if (Route::currentRouteName() != 'dashboard')
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: black;">
        <a class="navbar-brand" href="#" style="margin-left: 20px; color: white;">DOCFeeder</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">|<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('homepage') ? 'active' : '' }}" href="/homepage">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('FeedingPage') ? 'active' : '' }}" href="/FeedingPage">Halaman Feed</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('StorageAmount') ? 'active' : '' }}" href="/StorageAmount">Total Pakan</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="content p-4 m-0" style="flex: 1;">
        @yield('content')
    </div>
  @else
  <div class="content p-4 m-0" style="flex: 1; position: relative; z-index:0;">
    @yield('content')
</div>
    @endif

</body>
</html>