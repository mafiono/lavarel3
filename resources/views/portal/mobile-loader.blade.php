<style>
    .mobile-loader {
        display: table;
        position: fixed;
        z-index: 99999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        background-color: #232B48;
        padding: 50px 15% 0 15%;
    }

    .mobile-loader-content {
        display: table-cell;
        text-align: center;
        vertical-align: middle;

    }

    .mobile-loader-logo {
        position: absolute;
        width: 70%;
        top: 115px;
        left: 15%;
    }

    .mobile-loader-fadeout {
        animation: fade-out ease-out 1.5s;
    }

    .mobile-loader-hide {
        display: none;
    }

    @keyframes fade-out {
        0%   { opacity: 1; }
        100%  { opacity: 0; }
    }

    @media only screen and (min-width: 768px) {
        .mobile-loader {
            display: none;
        }
    }

    .ispinner {
        display: inline-block;
        position: relative;
        width: 40px;
        height: 40px;
        margin-top: 10%;
    }
    .ispinner .ispinner__blade {
        position: absolute;
        left: 44.5%;
        top: 37%;
        width: 10%;
        height: 25%;
        border-radius: 50%/20%;
        animation: ispinner__blade--fade 1s linear infinite;
        animation-play-state: paused; }
    .ispinner .ispinner__blade:nth-child(1) {
        animation-delay: -1.6666666667s;
        transform: rotate(30deg) translate(0, -150%); }
    .ispinner .ispinner__blade:nth-child(2) {
        animation-delay: -1.5833333333s;
        transform: rotate(60deg) translate(0, -150%); }
    .ispinner .ispinner__blade:nth-child(3) {
        animation-delay: -1.5s;
        transform: rotate(90deg) translate(0, -150%); }
    .ispinner .ispinner__blade:nth-child(4) {
        animation-delay: -1.4166666667s;
        transform: rotate(120deg) translate(0, -150%); }
    .ispinner .ispinner__blade:nth-child(5) {
        animation-delay: -1.3333333333s;
        transform: rotate(150deg) translate(0, -150%); }
    .ispinner .ispinner__blade:nth-child(6) {
        animation-delay: -1.25s;
        transform: rotate(180deg) translate(0, -150%); }
    .ispinner .ispinner__blade:nth-child(7) {
        animation-delay: -1.1666666667s;
        transform: rotate(210deg) translate(0, -150%); }
    .ispinner .ispinner__blade:nth-child(8) {
        animation-delay: -1.0833333333s;
        transform: rotate(240deg) translate(0, -150%); }
    .ispinner .ispinner__blade:nth-child(9) {
        animation-delay: -1s;
        transform: rotate(270deg) translate(0, -150%); }
    .ispinner .ispinner__blade:nth-child(10) {
        animation-delay: -0.9166666667s;
        transform: rotate(300deg) translate(0, -150%); }
    .ispinner .ispinner__blade:nth-child(11) {
        animation-delay: -0.8333333333s;
        transform: rotate(330deg) translate(0, -150%); }
    .ispinner .ispinner__blade:nth-child(12) {
        animation-delay: -0.75s;
        transform: rotate(360deg) translate(0, -150%); }
    .ispinner.ispinner--animating .ispinner__blade {
        animation-play-state: running; }
    .ispinner.ispinner--white .ispinner__blade {
        background-color: white; }
    .ispinner.ispinner--gray .ispinner__blade {
        background-color: #8c8c8c; }
    .ispinner.ispinner--large {
        width: 35px;
        height: 35px; }
    .ispinner.ispinner--large .ispinner__blade {
        width: 8.5714285714%;
        height: 25.7142857143%;
        border-radius: 50%/16.67%; }

    @keyframes ispinner__blade--fade {
        0% {
            opacity: 0.85; }
        50% {
            opacity: 0.25; }
        100% {
            opacity: 0.25; } }


</style>
<script>
    function addLoaderClass(className, delay) {
        window.setTimeout(function () {
            var loader = document.getElementsByClassName('mobile-loader')[0];
            loader.className += ' ' + className;
        }, delay);
    }

    addLoaderClass('mobile-loader-fadeout', 2500);
    addLoaderClass('mobile-loader-hide', 4000);
</script>
<div class="mobile-loader">
    <div class="mobile-loader-content">
        <img class="mobile-loader-logo" src="/assets/portal/img/Logo-CP.svg">
        <div class="ispinner ispinner--animating ispinner--white">
            <div class="ispinner__blade"></div>
            <div class="ispinner__blade"></div>
            <div class="ispinner__blade"></div>
            <div class="ispinner__blade"></div>
            <div class="ispinner__blade"></div>
            <div class="ispinner__blade"></div>
            <div class="ispinner__blade"></div>
            <div class="ispinner__blade"></div>
            <div class="ispinner__blade"></div>
            <div class="ispinner__blade"></div>
            <div class="ispinner__blade"></div>
            <div class="ispinner__blade"></div>
        </div>
    </div>
</div>
