@extends('layouts.app')

@section('content')
    <section class="content-header">






<body>
    <br />
    <div class="container">
            <div id="map" style="height: 200px; width:400px ;   align-items: center;
            " ></div>

        <h3 align="center">Uploading a KML file using Ajax and Displaying on a Leaflet Map</h3>
        <br />
        <form method="post" id="upload_form" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
                <table class="table">
                    <tr>
                        <td width="40%" align="right"><label>Select File for Upload</label></td>
                        <td width="30"><input type="file" name="select_file" id="select_file" /></td>
                        <td width="30%" align="left"><input type="submit" name="upload" id="upload" class="btn btn-primary" value="Upload"></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</body>

</html>



<script>
    // initialize Leaflet
    var map = L.map('map').setView({
        lat: 32.559299,
        lon:  35.841709
    }, 11);
    // add the OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
    }).addTo(map);
    // show the scale bar on the lower left corner
    L.control.scale().addTo(map);
    $(document).ready(function() {
        $('#upload_form').on('submit', function(event) {
            event.preventDefault();

        });
    });
</script>





