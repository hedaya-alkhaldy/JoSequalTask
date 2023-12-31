@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">

                <div class="col-sm-12">
                    <h1>
                    Create K M L S

                    </h1>


                </div>
            </div>
        </div>
    </section>



<!DOCTYPE html>
<html>

<head>
    <title>Upload a KML file in Laravel using Ajax and display on a Leaflet Map</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
    <script src='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.3.1/leaflet-omnivore.min.js'></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
    <div id="map" style="height: 300px; width:1000px ; margin-left:50px"></div>
    <br />
    <div class="container">
        <br />
        <form method="post" id="upload_form" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div id="myAlert" style="display: none;" class="alert alert-success" role="alert">
                File Uploaded and Saved Successfully
                      </div>
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
        lon: -1.534,
        lat: 47.213
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
            $.ajax({
                url: "{{ route('ajaxupload.action') }}",
                method: "POST",
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('#message').css('display', 'block');
                    $('#message').html(data.message);
                    $('#message').addClass(data.class_name);
                    $('#uploaded_image').html(data.uploaded_file);

                    var tmp = data.uploaded_file;
                    console.log('test',data.uploaded_file);
                    fetch(tmp)  //get the location with the new name of the saved file
                        .then(res => res.text())
                        .then(kmltext => {
                            // Create new kml overlay
                            const track = new omnivore.kml.parse(kmltext);
                            map.addLayer(track);    //add a layer with the coordinates in the file
                            // Adjust map to show the kml
                            const bounds = track.getBounds();
                            map.fitBounds(bounds);
                        }).catch((e) => {
                            console.log(e);
                        })

                        document.getElementById('myAlert').style.display = 'block';



                }
            })
        });
    });
</script>


@endsection
