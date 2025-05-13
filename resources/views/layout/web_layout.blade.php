<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">

    <title>SenseLib</title>

    <link rel="icon" type="image/x-icon" href="/web-assets/img/logo/favicon.png">

    <link rel="stylesheet" href="{{ asset('web-assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/all-fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/magnific-popup.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/nice-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('web-assets/css/style.css') }}">

</head>

<body class="home-9">
    @include('layout.partial.header')

    @yield('content')

    @include('layout.partial.footer')

    <a href="#" id="scroll-top"><i class="far fa-arrow-up-from-arc"></i></a>

    <script src="{{ asset('web-assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/modernizr.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/jquery.appear.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/counter-up.js') }}"></script>
    <script src="{{ asset('web-assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/countdown.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('web-assets/js/main.js') }}"></script>
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script>
        $('#lfm').filemanager('file', {
            prefix: '/files-manager'
        });

        $('#lfm2').filemanager('file', {
            prefix: '/files-manager'
        });

        $(document).ready(function() {
            var lfm = function(options, cb) {
                var route_prefix = (options && options.prefix) ? options.prefix : '/files-manager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=700,height=400');
                window.SetUrl = cb;
            };

            var LFMButton = function(context) {
                var ui = $.summernote.ui;
                var button = ui.button({
                    contents: '<i class="note-icon-picture"></i> ',
                    tooltip: 'Insert image with filemanager',
                    click: function() {

                        lfm({
                            type: 'file',
                            prefix: '/files-manager'
                        }, function(lfmItems, path) {
                            lfmItems.forEach(function(lfmItem) {
                                context.invoke('insertImage', lfmItem.url);
                            });
                        });

                    }
                });
                return button.render();
            };

            var initialUrl = $('#thumbnail').val();
            if (initialUrl) {
                $('#holder').attr('src', initialUrl);
            } else {
                $('#holder').attr('src', '/storage/files/1/Avatar/no-image.jpg');
            }
            
            $('#lfm').filemanager('file');
            $('#lfm').on('click', function() {
                var route_prefix = '/files-manager';
                window.open(route_prefix + '?type=file', 'FileManager', 'width=700,height=400');
                window.SetUrl = function(items) {
                    var url = items[0].url;
                    $('#holder').attr('src', url);
                    $('#thumbnail').val(url);
                    $('#thumbnail').trigger('change');
                };               
            });
        });
    </script>

</body>

</html>