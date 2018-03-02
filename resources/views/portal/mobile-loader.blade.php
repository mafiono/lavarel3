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
        animation: fade-in ease-in .5s;
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

    @keyframes fade-in {
        0%   { opacity: 0.5; }
        100%  { opacity: 1; }
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
        <img class="mobile-loader-logo" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMTAuOTUgNDAuOTEiPjxkZWZzPjxzdHlsZT4uY2xzLTF7ZmlsbDojZDNiMDU2O30uY2xzLTJ7ZmlsbDojZmZmO308L3N0eWxlPjwvZGVmcz48dGl0bGU+QXRpdm8gMjwvdGl0bGU+PGcgaWQ9IkNhbWFkYV8yIiBkYXRhLW5hbWU9IkNhbWFkYSAyIj48ZyBpZD0iQ2FtYWRhXzEtMiIgZGF0YS1uYW1lPSJDYW1hZGEgMSI+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMzMuNzgsMTYuNDVIMjYuM2wtMS4yMSwzLjQ4SDIwLjQ1TDI3Ljg4LjI1aDQuNjRsNy4yNSwxOS42OEgzNVptLTEuMzMtMy45My0yLjM3LTctMi40Miw3WiIvPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTQ4LjQ2LDQuMjFjLTEuMTYsMC0xLjkyLjQ1LTEuOTIsMS4zNSwwLDMuMjYsOS43MSwxLjQsOS43MSw4LjQ4LDAsNC0zLjMyLDYuMDYtNy40Miw2LjA2QTEzLjMzLDEzLjMzLDAsMCwxLDQwLjMyLDE3bDEuODctNGExMS40MSwxMS40MSwwLDAsMCw2LjY5LDNjMS40MiwwLDIuMzItLjU2LDIuMzItMS42QzUxLjIsMTEsNDEuNDgsMTMsNDEuNDgsNmMwLTMuNjgsMi45Mi02LDcuMzctNmExMy41LDEzLjUsMCwwLDEsNy4zNywyLjIyTDU0LjQxLDYuMzJBMTMuNzQsMTMuNzQsMCwwLDAsNDguNDYsNC4yMVoiLz48cmVjdCBjbGFzcz0iY2xzLTEiIHg9IjU5LjE0IiB5PSIwLjI1IiB3aWR0aD0iNS4wNSIgaGVpZ2h0PSIxOS42OCIvPjxwb2x5Z29uIGNsYXNzPSJjbHMtMSIgcG9pbnRzPSI3My4yNyAwLjI1IDgyLjY1IDEyLjEgODIuNjUgMC4yNSA4Ny42NiAwLjI1IDg3LjY2IDE5LjkzIDgyLjk0IDE5LjkzIDczLjU2IDguMTQgNzMuNTYgMTkuOTMgNjguNTIgMTkuOTMgNjguNTIgMC4yNSA3My4yNyAwLjI1Ii8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMTAwLjcxLDBhMTAuMDksMTAuMDksMCwwLDEsNCwuNzksMTAuNTMsMTAuNTMsMCwwLDEsMy4yNSwyLjE1LDEwLjA2LDEwLjA2LDAsMCwxLDIuMiwzLjIsOS42OCw5LjY4LDAsMCwxLC44MSwzLjkyLDkuNjksOS42OSwwLDAsMS0uODEsMy45MiwxMC4wNiwxMC4wNiwwLDAsMS0yLjIsMy4yLDEwLjUyLDEwLjUyLDAsMCwxLTMuMjUsMi4xNSwxMC4zNiwxMC4zNiwwLDAsMS04LDAsMTAuMzksMTAuMzksMCwwLDEtMy4yNC0yLjE1QTEwLjI0LDEwLjI0LDAsMCwxLDkxLjMsMTRhOS42Miw5LjYyLDAsMCwxLS44MS0zLjkyLDkuNjIsOS42MiwwLDAsMSwuODEtMy45MiwxMC4yNCwxMC4yNCwwLDAsMSwyLjE4LTMuMkExMC40LDEwLjQsMCwwLDEsOTYuNzMuNzksMTAuMDksMTAuMDksMCwwLDEsMTAwLjcxLDBabTAsMTUuNDlhNS40NSw1LjQ1LDAsMCwwLDIuMTUtLjQyLDUuNTEsNS41MSwwLDAsMCwyLjk0LTIuODksNS4zOCw1LjM4LDAsMCwwLDAtNC4yMiw1LjQ5LDUuNDksMCwwLDAtMi45NC0yLjg5LDUuNjIsNS42MiwwLDAsMC00LjI5LDAsNS41Nyw1LjU3LDAsMCwwLTEuNzUsMS4xNkE1LjUxLDUuNTEsMCwwLDAsOTUuNjQsOGE1LjQsNS40LDAsMCwwLDAsNC4yMiw1LjUsNS41LDAsMCwwLDEuMTksMS43Myw1LjU4LDUuNTgsMCwwLDAsMS43NSwxLjE3QTUuNDQsNS40NCwwLDAsMCwxMDAuNzIsMTUuNDlaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMTAuMjIsMTUuNDlhNS40NSw1LjQ1LDAsMCwwLDIuMTUtLjQyLDUuNTUsNS41NSwwLDAsMCwxLjc1LTEuMTcsNS4zOSw1LjM5LDAsMCwwLDEtMS4yN2g1QTkuNTYsOS41NiwwLDAsMSwxOS42NCwxNGExMC4wOSwxMC4wOSwwLDAsMS0yLjIsMy4yLDEwLjU1LDEwLjU1LDAsMCwxLTMuMjUsMi4xNSwxMC4wNywxMC4wNywwLDAsMS00LC44LDEwLjA1LDEwLjA1LDAsMCwxLTQtLjhBMTAuMzYsMTAuMzYsMCwwLDEsMywxNy4xOCwxMC4yMSwxMC4yMSwwLDAsMSwuODEsMTQsOS42Miw5LjYyLDAsMCwxLDAsMTAuMDYsOS42MSw5LjYxLDAsMCwxLC44MSw2LjE0LDEwLjIxLDEwLjIxLDAsMCwxLDMsMi45NSwxMC4zNywxMC4zNywwLDAsMSw2LjIzLjc5YTEwLjA5LDEwLjA5LDAsMCwxLDQtLjc5LDEwLjEsMTAuMSwwLDAsMSw0LC43OSwxMC41NiwxMC41NiwwLDAsMSwzLjI1LDIuMTUsMTAuMDksMTAuMDksMCwwLDEsMi4yLDMuMiw5LjgyLDkuODIsMCwwLDEsLjQ3LDEuMzVoLTVhNS40Niw1LjQ2LDAsMCwwLTEtMS4yNyw1LjU1LDUuNTUsMCwwLDAtMS43NS0xLjE2LDUuNjIsNS42MiwwLDAsMC00LjI5LDBBNS42LDUuNiwwLDAsMCw2LjMyLDYuMjIsNS41LDUuNSwwLDAsMCw1LjE0LDhhNS4zOCw1LjM4LDAsMCwwLDAsNC4yMkE1LjQ5LDUuNDksMCwwLDAsNi4zMiwxMy45YTUuNjEsNS42MSwwLDAsMCwxLjc2LDEuMTdBNS40NCw1LjQ0LDAsMCwwLDEwLjIyLDE1LjQ5WiIvPjxwYXRoIGNsYXNzPSJjbHMtMiIgZD0iTTEwLjgxLDMxLjMxQTYuNjgsNi42OCwwLDAsMSwxMC4yOCwzNGE3LDcsMCwwLDEtMS40OSwyLjIsNy4xNyw3LjE3LDAsMCwxLTIuMjEsMS41LDYuODYsNi44NiwwLDAsMS0yLjcuNTh2Mi42NEgwVjI0LjM0SDMuODdhNi44Niw2Ljg2LDAsMCwxLDIuNy41OSw3LjM4LDcuMzgsMCwwLDEsMi4yMSwxLjUxLDcsNywwLDAsMSwxLjQ5LDIuMkE2LjY4LDYuNjgsMCwwLDEsMTAuODEsMzEuMzFaTTMuODcsMzQuNDRhMy4xLDMuMSwwLDAsMCwxLjItLjI4LDMuMzcsMy4zNywwLDAsMCwxLS42OCwzLjExLDMuMTEsMCwwLDAsLjY1LTEsMy4xMSwzLjExLDAsMCwwLDAtMi40LDMuMTYsMy4xNiwwLDAsMC0uNjUtMSwzLjM3LDMuMzcsMCwwLDAtMS0uNjgsMy4xNCwzLjE0LDAsMCwwLTEuMi0uMjhaIi8+PHBhdGggY2xhc3M9ImNscy0yIiBkPSJNMjAuODEsMjQuMzJhOC4zLDguMywwLDAsMSwzLjI3LjY1LDguNjksOC42OSwwLDAsMSwyLjY4LDEuNzcsOC4zMyw4LjMzLDAsMCwxLDEuODEsMi42Myw4LjE2LDguMTYsMCwwLDEsMCw2LjQ1LDguMzEsOC4zMSwwLDAsMS0xLjgxLDIuNjMsOC43LDguNywwLDAsMS0yLjY4LDEuNzcsOC41MSw4LjUxLDAsMCwxLTYuNTQsMCw4LjU0LDguNTQsMCwwLDEtMi42Ny0xLjc3LDguNDQsOC40NCwwLDAsMS0xLjgtMi42Myw4LjE4LDguMTgsMCwwLDEsMC02LjQ1QTguMzUsOC4zNSwwLDAsMSwxNy41NCwyNSw4LjMsOC4zLDAsMCwxLDIwLjgxLDI0LjMyWm0wLDEyLjc0YTQuNDksNC40OSwwLDAsMCwxLjc3LS4zNSw0LjYxLDQuNjEsMCwwLDAsMS40NC0xLDQuNDksNC40OSwwLDAsMCwxLTEuNDIsNC40Miw0LjQyLDAsMCwwLDAtMy40Nyw0LjUxLDQuNTEsMCwwLDAtMS0xLjQyLDQuNjQsNC42NCwwLDAsMC0xLjQ0LTEsNC42Myw0LjYzLDAsMCwwLTMuNTMsMCw0LjYyLDQuNjIsMCwwLDAtMS40NCwxLDQuNTQsNC41NCwwLDAsMC0xLDEuNDIsNC40Miw0LjQyLDAsMCwwLDAsMy40Nyw0LjUyLDQuNTIsMCwwLDAsMSwxLjQyLDQuNTksNC41OSwwLDAsMCwxLjQ0LDFBNC40OCw0LjQ4LDAsMCwwLDIwLjgyLDM3LjA2WiIvPjxwYXRoIGNsYXNzPSJjbHMtMiIgZD0iTTM2LjA2LDM2LjYxbC0uNi4wNy0uNjIsMHY0LjE3SDMxVjI0LjQxaDMuODdhNy42Nyw3LjY3LDAsMCwxLDIuNy41Miw3LjQ4LDcuNDgsMCwwLDEsMi4yMSwxLjMzLDYuMTYsNi4xNiwwLDAsMSwxLjQ5LDEuOTQsNS4zMiw1LjMyLDAsMCwxLC41NCwyLjM3QTUuMzgsNS4zOCwwLDAsMSw0MS4yMiwzM2E2LDYsMCwwLDEtMS42LDJsMy43MSw1LjlIMzguNzZabS0xLjIxLTMuNzJBNC4wNSw0LjA1LDAsMCwwLDM2LDMyLjY3YTMuNTQsMy41NCwwLDAsMCwxLS41MSwyLjQ1LDIuNDUsMCwwLDAsLjY2LS43MywxLjc0LDEuNzQsMCwwLDAsLjI0LS44OSwxLjY3LDEuNjcsMCwwLDAtLjI0LS44OCwyLjQ5LDIuNDksMCwwLDAtLjY2LS43MiwzLjUzLDMuNTMsMCwwLDAtMS0uNTEsNC4wNSw0LjA1LDAsMCwwLTEuMTgtLjIxWiIvPjxwb2x5Z29uIGNsYXNzPSJjbHMtMiIgcG9pbnRzPSI1My42NCAyNC4yNyA1My42NCAyOC4wOCA1MC40MSAyOC4wOCA1MC40MSA0MC44OSA0Ni41MyA0MC44OSA0Ni41MyAyOC4wOCA0My4zMyAyOC4wOCA0My4zMyAyNC4yNyA1My42NCAyNC4yNyIvPjxwYXRoIGNsYXNzPSJjbHMtMiIgZD0iTTYxLjY4LDQwLjg5YTYuMzQsNi4zNCwwLDAsMS01Ljg4LTMuODEsNi4yMiw2LjIyLDAsMCwxLS41MS0yLjQxVjI0LjMyaDMuODdWMzQuNjVhMi4zNSwyLjM1LDAsMCwwLC43NiwxLjc0LDIuNTUsMi41NSwwLDAsMCwxLjc5LjcsMi4zNywyLjM3LDAsMCwwLDEtLjIsMi42OCwyLjY4LDAsMCwwLC44LS41NCwyLjI5LDIuMjksMCwwLDAsLjUzLS43OSwyLjU0LDIuNTQsMCwwLDAsLjE5LTFWMjQuMzJINjhWMzQuNTZBNS44NCw1Ljg0LDAsMCwxLDY3LjU4LDM3YTYuMzEsNi4zMSwwLDAsMS0xLjM1LDIsNi40OCw2LjQ4LDAsMCwxLTIsMS4zNiw2LjMyLDYuMzIsMCwwLDEtMi40Ni41M1oiLz48cGF0aCBjbGFzcz0iY2xzLTIiIGQ9Ik04NCwzMS42MnY5LjI3SDgwLjE0di0uNDFhNy4wOSw3LjA5LDAsMCwxLTEuMzIuMzMsOSw5LDAsMCwxLTEuMzQuMSw4LjczLDguNzMsMCwwLDEtMy4xOS0uNjEsOC4yMiw4LjIyLDAsMCwxLTIuNzktMS44Myw3LjksNy45LDAsMCwxLTEuODQtMi43NCw4LjM0LDguMzQsMCwwLDEtLjYxLTMuMTIsOC40NCw4LjQ0LDAsMCwxLC42MS0zLjEzLDcuOSw3LjksMCwwLDEsMS44NC0yLjc1QTguMjQsOC4yNCwwLDAsMSw3NC4zLDI0LjlhOC42Myw4LjYzLDAsMCwxLDYuMzcsMCw4LjI4LDguMjgsMCwwLDEsMi44LDEuODNsLTIuNzUsMi43MWE0LjM2LDQuMzYsMCwwLDAtMS41MS0xLDQuNjcsNC42NywwLDAsMC0zLjQ0LDAsNC4zMyw0LjMzLDAsMCwwLTEuNTEsMSw0LjQzLDQuNDMsMCwwLDAtMSwxLjQ5LDQuMzMsNC4zMywwLDAsMCwwLDMuMzgsNC41Nyw0LjU3LDAsMCwwLDIuMzUsMi40LDQuNjQsNC42NCwwLDAsMCwxLjU0LjM4LDQuODEsNC44MSwwLDAsMCwxLjU3LS4xNSw0LjMyLDQuMzIsMCwwLDAsMS40NC0uNjh2LS44M0g3Ni43OFYzMS42MloiLz48cGF0aCBjbGFzcz0iY2xzLTIiIGQ9Ik05NS41LDQwLjg5bC0uNjctMkg4OS41NGwtLjY5LDJoLTQuMWw1LjczLTE2LjU1aDMuNjZsNS40MywxNi41NVptLTQuNjMtNS44Mkg5My42bC0xLjMzLTRaIi8+PHBvbHlnb24gY2xhc3M9ImNscy0yIiBwb2ludHM9IjExMC43MyA0MC44OSAxMDAuNzIgNDAuODkgMTAwLjcyIDI0LjMyIDEwNC41OSAyNC4zMiAxMDQuNTkgMzcuMDggMTEwLjczIDM3LjA4IDExMC43MyA0MC44OSIvPjwvZz48L2c+PC9zdmc+">
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
