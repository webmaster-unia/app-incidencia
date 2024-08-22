<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="Universidad Nacional Intercultural de la Amazonia" />

    <title>{{ $title ?? 'SIGEIN OTI' }}</title>

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('media/img/favicon.png') }}" type="image/x-icon" />
    <!-- [Font] Family -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/inter/inter.css') }}" id="main-font-link" />
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}" />
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}" />
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}" />
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}" />
    <!-- Toastify -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>

<body data-pc-preset="preset-2" data-pc-sidebar-caption="false" data-pc-layout="vertical" data-pc-direction="ltr"
    data-pc-theme_contrast="false" data-pc-theme="light">
    <!-- [ Pre-loader ] start -->
    <div class="page-loader">
        <div class="bar"></div>
    </div>

    <div class="auth-main">
        <div
            class="auth-wrapper v1"
            style="background-image: url('{{ asset('media/img/fondo-unia.webp') }}'); background-size: cover; background-repeat: no-repeat; background-attachment: fixed; background-position: center;"
        >
            {{ $slot }}
        </div>
    </div>

    <!-- Required Js -->
    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('assets/js/pcoded.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <!-- Toastify -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
        document.addEventListener('livewire:navigated', () => {
            window.addEventListener('toast', event => {
                let color = event.detail.color
                let clase = ''
                switch (color) {
                    case 'danger':
                        clase = 'px-5 py-3 text-white'
                        color = '#dc2626'
                        break;
                    case 'success':
                        clase = 'px-5 py-3 text-white'
                        color = '#2ca87f'
                        break;
                    case 'warning':
                        clase = 'px-5 py-3 text-white'
                        color = '#e58a00'
                        break;
                    case 'info':
                        clase = 'px-5 py-3 text-white'
                        color = '#3ec9d6'
                        break;
                }
                Toastify({
                    text: event.detail.text,
                    className: clase,
                    duration: 5000,
                    newWindow: true,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "right", // `left`, `center` or `right`
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                    style: {
                        background: color,
                    }
                }).showToast();
            })
            window.addEventListener('modal', event => {
                $(event.detail.modal).modal(event.detail.action)
            })
            window.addEventListener('confetti', event => {
                const jsConfetti = new JSConfetti()
                jsConfetti.addConfetti()
            })
        })
    </script>

    <script>
        layout_change('false');
        layout_theme_contrast_change('false');
        change_box_container('false');
        layout_caption_change('false');
        layout_rtl_change('false');
        preset_change('preset-2');
        main_layout_change('vertical');
    </script>

</body>

</html>
