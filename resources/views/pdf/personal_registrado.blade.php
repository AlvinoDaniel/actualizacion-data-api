<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Documento</title>
        <style>

            /* plus-jakarta-sans-200 - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: normal;
            font-weight: 200;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-200.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-300 - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: normal;
            font-weight: 300;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-300.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-regular - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: normal;
            font-weight: 400;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-regular.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-500 - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: normal;
            font-weight: 500;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-500.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-600 - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: normal;
            font-weight: 600;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-600.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-700 - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: normal;
            font-weight: 700;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-700.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-800 - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: normal;
            font-weight: 800;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-800.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-200italic - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: italic;
            font-weight: 200;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-200italic.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-300italic - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: italic;
            font-weight: 300;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-300italic.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-italic - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: italic;
            font-weight: 400;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-400italic.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-500italic - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: italic;
            font-weight: 500;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-500italic.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-600italic - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: italic;
            font-weight: 600;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-600italic.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-700italic - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: italic;
            font-weight: 700;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-700italic.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-800italic - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: italic;
            font-weight: 800;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-800italic.woff') }}) format('woff2');
        }

            @page {
                margin: 1cm;
                font-family: 'Plus Jakarta Sans', sans-serif;
                font-size: 11pt;
            }

            .pagenum:before {
                content: counter(page);
            }

            .page p {
                margin-bottom: 8px !important;
                text-align: justify;
            }

            .page-header {
                text-align: center;
                margin-bottom: 20px;
                font-weight: bold;
                line-height: 1.3rem;
                text-transform: uppercase;
            }

            .page-date {
                margin-bottom: 20px;
                text-align: right;
            }

            .page-header img {
                margin: 5px 10px;
            }
            span {
                display: block
            }

            .page-addressee {
                margin: 10px 0;
                display: -webkit-box !important;
                display: -ms-flexbox !important;
                display: flex !important;
                -webkit-box-orient: vertical !important;
                -webkit-box-direction: normal !important;
                -ms-flex-direction: column !important;
                flex-direction: column !important;
            }

            .page-body {
                margin:0;
                text-align: justify;
            }

            .page-copys {
                padding: 20px 0;
                text-align: justify;
                font-size: 10pt;
            }

            .page-sincerely {
                font-weight: bold;
                text-align: center;
                margin: 0 auto;
                padding-top: 16px !important;
                padding-bottom: 16px !important;

            }
            .page-footer {
                /* position: absolute; */
                /* left: 0; */
                /* bottom: -20px; */
                font-size: 12px;
                width: 100%;
                text-align: center;
                margin: 0 auto;

            }

            .page-user-signature {
                position: relative;
                padding-left: 40px;
                padding-right: 40px;
                margin: 0 auto;
                padding-top: 10px;
                border-color: black;
                border-style: solid;
                width: 40%;
                border-width: 1px 0 0 0;
            }
        footer {
            position: fixed;
            bottom: -0.5cm;
            left: 0;
            right: 0;
            background-color: #ffffff;
            line-height: normal;
        }
        header {
            position: fixed;
            top: -0.5cm;
            right: 0;
            background-color: #ffffff;
            line-height: normal;
        }

        .text-center {
            text-align: center !important;
        }

        .title-header {
        font-size: 0.9em;
        text-align: left !important;
        font-weight: bold;
        line-height: 0.7rem;
        text-transform: uppercase;
        padding-top: 20px;
        padding-bottom: 20px;
        }

        .font-bold {
        font-weight: bold !important;
        }

        .font-medium {
        font-weight: 400 !important;
        }

        .font-uppercase {
        text-transform: uppercase !important;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 10pt;
        }
        table.section {
            border: 0.1em solid #000000;
            border-radius: .5em;
            border-spacing: 0px;
            width: 100%;
        }
        table.striped td, th {
            padding-top: 1em;
            padding-bottom: 1em;
            border-width: 1px;
            border-style: solid;
            border-color: #000000;
        }

        /* table.striped > tbody > tr:nth-of-type(odd) > * {
            background-color: rgba(252, 253, 253, 1);
        } */

        td {
            padding: 0.5em;
        }
        th {
            padding: 0.5em;
            text-align: left;
        }

        td.justify {
            text-align: justify;
        }
        table td, table th {
        box-sizing: content-box;
        vertical-align: middle;
        }
        .bordered {
            border: 0.1em solid #000000;
        }
    </style>
</head>

    <body >
        <header>
            <span class="pagenum"></span>
        </header>
        <footer>
            <div class="page-footer">
              <span class="font-bold">DEL PUEBLO VENIMOS / HACIA EL PUEBLO VAMOS</span>
              {{-- <span style="font-size:10px">{{$nucleoDireccion}}</span> --}}
            </div>
        </footer>
        <div class="page-container">
            <div id="pageDocument" class="page page-shadow">
            <div class="page-content">
                <div>
                <div class="page-header">
                    <img src="{{ storage_path('app/public/Logo_UDO.png') }}" width="70" height="68">
                    <span>UNIVERSIDAD DE ORIENTE</span>
                    <span>{{$nucleo}}</span>
                </div>
                <div class="title-header">
                    <span>UNIDAD ADMINISTRATIVA: {{$unidad_admin}}</span> <br>
                    <span>UNIDAD EJECUTORA: {{$unidad_ejec}}</span>
                </div>
                <div class="page-header" style="font-size: 1em;">
                    <span>LISTADO DE PERSONAL REGISTRADO A LA FECHA {{$fecha}}</span>
                </div>
                <div class="page-body">
                    <table class="bordered striped">
                        <thead>
                        <tr>
                            <th width="25%">CÉDULA DE IDENTIDAD</th>
                            <th width="50%">NOMBRES Y APELLIDOS</th>
                            <th class="text-center">FIRMA</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($personal as $item)
                            <tr>
                            <td>{{number_format($item->cedula_identidad, 0, ',', '.')}}</td>
                            <td>{{$item->nombres_apellidos}}</td>
                            <td></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
            </div>
        </div>
    </body>
</html>
