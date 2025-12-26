<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex,nofollow">
    <title>{{ translate('maintenance_Mode_On') }}</title>
    <meta name="description" content="">
    <link rel="icon" href="assets/images/favicon.png" sizes="32x32" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,500,600,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <style>
        html {
            box-sizing: border-box;
        }

        * {
            box-sizing: inherit;
        }

        body,
        html {
            height: 100%;
        }

        body {
            background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('../images/bg.jpg');
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            position: relative;
        }

        textarea,
        input[type="text"],
        input[type="button"],
        input[type="submit"] {
            -webkit-appearance: none;
            opacity: 1;
        }

        ::-webkit-input-placeholder {
            color: #dcdcdc;
        }

        ::-moz-placeholder {
            color: #dcdcdc;
        }

        :-ms-input-placeholder {
            color: #dcdcdc;
        }

        :-moz-placeholder {
            color: #dcdcdc;
        }

        .highlight {
            color: #FCB800;
        }


        /*-- loader  --*/
        .preloader.fade {
            opacitgy: 0;
        }

        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #FCB800;
            z-index: 999;
        }

        #loader {
            display: block;
            position: relative;
            left: 50%;
            top: 50%;
            width: 150px;
            height: 150px;
            margin: -75px 0 0 -75px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #000;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        #loader:before {
            content: "";
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #000;
            -webkit-animation: spin 3s linear infinite;
            animation: spin 3s linear infinite;
        }

        #loader:after {
            content: "";
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #FCB800;
            -webkit-animation: spin 1.5s linear infinite;
            animation: spin 1.5s linear infinite;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        /*-- loader  --*/

        .content-wrap {
            margin: 20px auto 40px auto;
            max-width: 700px;
            padding: 0 15px;
            text-align: center;
        }

        .logo-box img {
            max-width: 420px;
        }

        .cta-box {}

        .cta-box h1 {
            color: #fff;
            font-size: 58px;
            font-weight: 800;
            text-shadow: 0 5px 10px rgba(0, 0, 0, 0.7);
        }

        .cta-box p {
            color: #fff;
            font-size: 20px;
            margin-top: 20px;
        }

        .newsletter {
            margin: 40px auto 0 auto;
            max-width: 450px;
            position: relative;
        }

        .newsletter .form-field {
            background: rgba(99, 99, 99, 0.5);
            border: 1px solid rgba(0, 0, 0, 0.17);
            border-radius: 30px;
            box-shadow: 0 0 0px 4px rgba(132, 115, 115, 0.28);
            color: #fff;
            font-size: 16px;
            height: 52px;
            padding: 0 20px 0 20px;
            width: 100%;
        }

        .newsletter .form-field:focus {
            border: 1px solid rgba(0, 0, 0, 0.17);
            box-shadow: 0 0 0px 4px rgba(132, 115, 115, 0.28);
            outline: none
        }

        .btn-main {
            background: linear-gradient(45deg, #ff0785, #ff5200);
            border: none;
            border-radius: 30px;
            color: #fff;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            height: 52px;
            position: absolute;
            top: 0;
            right: 0;
            padding: 0 20px;
            text-transform: uppercase;
            z-index: 11;
        }

        .btn-main:hover {
            background: linear-gradient(45deg, #ff5200, #ff0785);
            color: #fff;
        }

        .btn-main:focus {
            outline: none
        }

        .social-icons {
            margin-top: 20px
        }

        .social-icons a {
            padding: 0 10px;
            text-decoration: none;
        }

        .social-icons a:hover,
        .social-icons a:active,
        .social-icons a:visited {
            color: #fff;
        }

        .social-icons svg {
            fill: #fff;
            transition: all.2s ease-in-out;
            width: 20px;
        }

        .social-icons a:hover svg {
            transform: scale(1.1);
        }

        .countdown {
            color: #fff;
            margin-top: 40px;
        }

        .timer-cta {
            font-size: 18px;
        }

        .countdown ul {
            margin-top: 20px;
            padding-left: 0;
        }

        .countdown ul li {
            background: rgba(230, 230, 230, 0.12);
            border-radius: 100%;
            display: inline-block;
            height: 100px;
            list-style: none;
            position: relative;
            text-align: center;
            margin-right: 15px;
            width: 100px;
        }

        .time-box {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .time {
            font-size: 32px;
            font-weight: 800;
        }

        .time-txt {
            display: block;
            font-size: 12px;
        }

        @media screen and (min-width:1200px) {
            .content-wrap {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 700px;
            }
        }

        @media screen and (max-width:767px) {
            .content-wrap {
                margin-top: 80px;
                width: 100%;
            }

            .cta-box h1 {
                font-size: 28px;
            }

            .cta-box p {
                font-size: 17px;
            }

            .time {
                font-size: 22px;
            }

            .newsletter .form-field,
            .btn-main {
                height: 46px;
            }

            .countdown ul li {
                height: 75px;
                width: 75px;
            }
        }
    </style>
</head>

<body>
    <div class="preloader" id="preloader">
        <div id="loader"></div>
    </div>
    <div class="content-wrap">
        <div class="logo-box">
            <img src="{{ asset('public/assets/martreza-light-logo.png') }}">
        </div>
        <div class="cta-box">
            <h1>{{ translate('our_website_is_under_maintenance') }}</h1>
            <p>{{ translate('We are using this time to give our website a revamp!') }}</p>
            <p>
                {{ translate('Please come back later.') }}
            </p>
        </div>
        <div class="social-icons">
            <a href="#">
                <svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <title>Facebook icon</title>
                    <path
                        d="M23.9981 11.9991C23.9981 5.37216 18.626 0 11.9991 0C5.37216 0 0 5.37216 0 11.9991C0 17.9882 4.38789 22.9522 10.1242 23.8524V15.4676H7.07758V11.9991H10.1242V9.35553C10.1242 6.34826 11.9156 4.68714 14.6564 4.68714C15.9692 4.68714 17.3424 4.92149 17.3424 4.92149V7.87439H15.8294C14.3388 7.87439 13.8739 8.79933 13.8739 9.74824V11.9991H17.2018L16.6698 15.4676H13.8739V23.8524C19.6103 22.9522 23.9981 17.9882 23.9981 11.9991Z" />
                </svg>
            </a>
            <a href="#">
                <svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <title>Instagram icon</title>
                    <path
                        d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z" />
                </svg>
            </a>
            {{-- <a href="#">
                <svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <title>Twitter icon</title>
                    <path
                        d="M23.954 4.569c-.885.389-1.83.654-2.825.775 1.014-.611 1.794-1.574 2.163-2.723-.951.555-2.005.959-3.127 1.184-.896-.959-2.173-1.559-3.591-1.559-2.717 0-4.92 2.203-4.92 4.917 0 .39.045.765.127 1.124C7.691 8.094 4.066 6.13 1.64 3.161c-.427.722-.666 1.561-.666 2.475 0 1.71.87 3.213 2.188 4.096-.807-.026-1.566-.248-2.228-.616v.061c0 2.385 1.693 4.374 3.946 4.827-.413.111-.849.171-1.296.171-.314 0-.615-.03-.916-.086.631 1.953 2.445 3.377 4.604 3.417-1.68 1.319-3.809 2.105-6.102 2.105-.39 0-.779-.023-1.17-.067 2.189 1.394 4.768 2.209 7.557 2.209 9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63.961-.689 1.8-1.56 2.46-2.548l-.047-.02z" />
                </svg>
            </a>
            <a href="#">
                <svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <title>LinkedIn icon</title>
                    <path
                        d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                </svg>
            </a>
            <a href="#">
                <svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <title>YouTube icon</title>
                    <path
                        d="M23.495 6.205a3.007 3.007 0 0 0-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 0 0 .527 6.205a31.247 31.247 0 0 0-.522 5.805 31.247 31.247 0 0 0 .522 5.783 3.007 3.007 0 0 0 2.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 0 0 2.088-2.088 31.247 31.247 0 0 0 .5-5.783 31.247 31.247 0 0 0-.5-5.805zM9.609 15.601V8.408l6.264 3.602z" />
                </svg>
            </a> --}}
        </div>
    </div>
    <script src="assets/js/main.js"></script>
    <script>
        countdown('01/11/2027 03:14:07 AM');
    </script>
    <script>
        function countdown(dateEnd) {
            var timer, days, hours, minutes, seconds;

            dateEnd = new Date(dateEnd);
            dateEnd = dateEnd.getTime();

            if (isNaN(dateEnd)) {
                return;
            }

            timer = setInterval(calculate, 1000);

            function calculate() {
                var dateStart = new Date();
                var dateStart = new Date(dateStart.getUTCFullYear(),
                    dateStart.getUTCMonth(),
                    dateStart.getUTCDate(),
                    dateStart.getUTCHours(),
                    dateStart.getUTCMinutes(),
                    dateStart.getUTCSeconds());
                var timeRemaining = parseInt((dateEnd - dateStart.getTime()) / 1000)

                if (timeRemaining >= 0) {
                    days = parseInt(timeRemaining / 86400);
                    timeRemaining = (timeRemaining % 86400);
                    hours = parseInt(timeRemaining / 3600);
                    timeRemaining = (timeRemaining % 3600);
                    minutes = parseInt(timeRemaining / 60);
                    timeRemaining = (timeRemaining % 60);
                    seconds = parseInt(timeRemaining);

                    document.getElementById("days").innerHTML = parseInt(days, 10);
                    document.getElementById("hours").innerHTML = ("0" + hours).slice(-2);
                    document.getElementById("minutes").innerHTML = ("0" + minutes).slice(-2);
                    document.getElementById("seconds").innerHTML = ("0" + seconds).slice(-2);
                } else {
                    return;
                }
            }

            function display(days, hours, minutes, seconds) {}
        }

        window.onload = function() {
            var preloader = document.getElementsByClassName('preloader')[0];
            setTimeout(function() {
                preloader.style.display = 'none';
            }, 500);
        };
    </script>
</body>

</html>
