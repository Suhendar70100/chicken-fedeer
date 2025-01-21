@extends('layout')
@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div id="status-container" style="padding: 20px; background-color: #f9f9f9; border: 1px solid #ddd;">
        <h3>Motor Status</h3>
        <p>Status: <span id="motor-status">Loading...</span></p>
        <p>Timestamp: <span id="motor-timestamp">Loading...</span></p>
        @if(session('duration'))
            <p>Durasi: {{ session('duration') }}</p>
        @endif

    </div>

    <div style="padding-top: 30px">
        <div class="container">
            <div class="card" style="width: auto; height: 100%; background-color: #ffffff">
                <table id="log" class="row-border" width="100%">
                    <thead>
                        <tr>
                            <th class="col-1">Log ID</th>
                            <th class="col-1">Date</th>
                            <th class="col-1">Time</th>
                            <th class="col-1">Action</th>
                            <th class="col-1">Umur</th>
                            <th class="col-1">Berat</th>
                            <th class="col-1">Interval</th>
                            <th class="col-1">Rotasi</th>
                        </tr>
                    </thead>
                    <tbody id="log-body"> <!-- Pastikan ID ini ada -->
                        @foreach ($log as $x)
                            <tr>
                                <td>{{ $x->id }}</td>
                                <td>{{ $x->date }}</td>
                                <td>{{ $x->time }}</td>
                                <td>
                                    @if ($x->log == 1)
                                        <span style="color: lightgreen; font-weight: bold">Feed</span>
                                    @elseif ($x->log == 10)
                                        <span style="color: blue; font-weight: bold">Scheduled</span>
                                    @else
                                        <span style="color: red; font-weight: bold">Stop</span>
                                    @endif
                                </td>
                                <td>{{ $x->umur }} minggu</td>
                                <td>{{ $x->berat }} gram</td>
                                <td>{{ $x->interval }} jam</td>
                                <td>{{ $x->rotasi }} kali</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </table>
                <div class="d-flex justify-content-center">
                    <ul class="pagination pagination-sm">
                        {{ $log->links('pagination::bootstrap-4') }} <!-- Ensure this is set for Bootstrap -->
                    </ul>
                </div>
            </div>

            <div class="container2" style="margin-left: 50px">
                <div class="card clock" style="width: 35rem; height: 246px; background-color: lightgray">
                    <canvas id="weekHourChart" width="400" height="200"></canvas>
                </div>
                <div class="card history" style="width: 35rem; height: 246px; background-color: lightgray">
                    <div style="background-color: darkgrey">
                        <div style="margin-left: 10px"></div>
                    </div>
                    <form method="POST" action="#">
                        @csrf
                        <div style="display: flex; margin-top: 65px;">
                            <a href="{{ route('feed') }}" class="btn btn-warning btn-sm" id="feed"
                                style="margin-left: 120px; margin-right: 50px; 
                                height: 100px; width: 135px; display: flex; justify-content: center;
                                align-items: center">
                                <b>Putar Feeder</b></a>
                            <a href="{{ route('stop') }}" class="btn btn-warning btn-sm" id="stop"
                                style="margin-right: 10px; 
                                height: 100px; width: 135px; display: flex; justify-content: center; 
                                align-items: center; position: relative">
                                <b>Stop Feeder</b></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function getCurrentDate() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        $(document).ready(function() {
            $('#log').DataTable({
                paging: false,
                info: false,
                searching: false
            });
            document.getElementById('fromDate').value = getCurrentDate();
            document.getElementById('toDate').value = getCurrentDate();
        });

        // Extract labels (dates) and data values
        const chartData = @json($chartData);
        const labels = chartData.map(data => `${data.date} ${data.time}`); // Combine date and time group for x-axis labels
        const dataValues = chartData.map(data => data.count);
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('weekHourChart').getContext('2d');

            const weekHourChart = new Chart(ctx, {
                type: 'bar', // You can use 'bar' or 'line' depending on your preference
                data: {
                    labels: labels, // Use the combined date and time group as labels
                    datasets: [{
                        label: 'Activity Count by 4-Hour Interval', // Update label accordingly
                        data: dataValues, // Use your activity count data
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            title: {
                                display: true,
                                text: 'Number of Activities',
                            },
                            min: 0,
                            ticks: {
                                stepSize: 1 // Adjust step size if needed
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Date and Time Interval',
                            },
                            ticks: {
                                autoSkip: true, // Auto skip labels if they overlap
                                maxTicksLimit: 10 // Limit number of ticks if necessary
                            }
                        }
                    },
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Activity Count by 4-Hour Interval'
                        }
                    }
                }
            });
        });

        function formatTimestamp(timestamp) {
    const date = new Date(timestamp);

    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false,
    };

    const formatter = new Intl.DateTimeFormat('id-ID', options);
    return formatter.format(date);
}

        function fetchMotorStatus() {
    fetch('/status')
        .then(response => response.json())
        .then(data => {
            const statusText = data.status === "1" ? "ON" : "OFF";
            document.getElementById('motor-status').textContent = statusText || 'No Data';
            document.getElementById('motor-timestamp').textContent = data.timestamp
    ? formatTimestamp(data.timestamp)
    : 'No Data';
        })
        .catch(error => {
            console.error('Error fetching motor status:', error);
        });
}

setInterval(fetchMotorStatus, 5000);

fetchMotorStatus();

function fetchLogs() {
    fetch('/logs')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const logBody = document.getElementById('log-body');
            logBody.innerHTML = ''; 
            data.forEach(log => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${log.id}</td>
                    <td>${log.date}</td>
                    <td>${log.time}</td>
                    <td>
                        ${log.log == 1 ? '<span style="color: lightgreen; font-weight: bold">Feed</span>' : 
                          log.log == 10 ? '<span style="color: blue; font-weight: bold">Scheduled</span>' : 
                          '<span style="color: red; font-weight: bold">Stop</span>'}
                    </td>
                    <td>${log.umur} minggu</td>
                    <td>${log.berat} gram</td>
                    <td>${log.interval} jam</td>
                    <td>${log.rotasi} kali</td>
                `;
                logBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error fetching logs:', error);
        });
}

setInterval(fetchLogs, 5000);
fetchLogs();

    </script>

@endsection

<style>
    .pagination {
        font-size: 0.8rem;
        /* Adjust font size */
    }

    .pagination .page-link {
        padding: 0.25rem 0.5rem;
        /* Adjust padding */
    }

    .pagination .page-item {
        margin: 0 2px;
        /* Adjust spacing between items */
    }

    div.dt-container div.dt-layout-row {
        margin: 0 !important;
    }

    .dt-layout-row {
        padding: 5px 10px 10px 10px;
    }
</style>