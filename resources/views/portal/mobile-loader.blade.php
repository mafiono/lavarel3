<style>
    .mobile-loader {
        display: table;
        position: fixed;
        z-index: 2000000002;
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
        top: 15%;
        left: 15%;
        animation: fade-in 1s;
    }

    @media (min-width: 500px) {
        .mobile-loader-logo {
            width: 350px;
            left: calc((100% - 350px) / 2);
        }
    }

    .mobile-loader-fadeout {
        animation: fade-out ease-out 1s;
    }

    .mobile-loader-hide {
        display: none;
    }

    @keyframes fade-in {
        0%   { opacity: 0; }
        100%  { opacity: 1; }
    }

    @keyframes fade-out {
        0%   { opacity: 1; }
        100%  { opacity: 0; }
    }

    @media (min-width: 768px) {
        .mobile-loader {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .hide-scrollbar {
            overflow: hidden;
        }
    }

    .ispinner {
        display: inline-block;
        position: relative;
        width: 40px;
        height: 40px;
        margin-top: 40%;
        animation: fade-in 1s;
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

<div class="mobile-loader">
    <div class="mobile-loader-content">
        <img class="mobile-loader-logo" src="data:image/svg+xml;base64,PHN2ZyBpZD0iQ2FtYWRhXzEiIGRhdGEtbmFtZT0iQ2FtYWRhIDEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgdmlld0JveD0iMCAwIDEwNy44NiA1Ny4yMSI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiNkM2IwNTY7fS5jbHMtMntmaWxsOiNmZmY7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5Mb2dvLUNQLVNMT0dBTjwvdGl0bGU+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMzUuNDIsMTcuNjdIMjguMTZMMjcsMjEuMDVIMjIuNDhMMjkuNjksMkgzNC4ybDcsMTkuMUgzNi41N1ptLTEuMjktMy44MUwzMS44Miw3bC0yLjM0LDYuODJaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMi40NyAtMS43KSIvPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTQ5LjY3LDUuNzljLTEuMTIsMC0xLjg2LjQzLTEuODYsMS4zMSwwLDMuMTYsOS40MywxLjM2LDkuNDMsOC4yMywwLDMuODktMy4yMiw1Ljg4LTcuMjEsNS44OGExMywxMywwLDAsMS04LjI2LTMuMDVsMS44Mi0zLjlhMTEuMDgsMTEuMDgsMCwwLDAsNi40OSwyLjg5YzEuMzgsMCwyLjI1LS41NCwyLjI1LTEuNTUsMC0zLjI0LTkuNDMtMS4yOC05LjQzLTgsMC0zLjU3LDIuODMtNS44Myw3LjE2LTUuODNhMTMuMDksMTMuMDksMCwwLDEsNy4xNSwyLjE1bC0xLjc2LDRBMTMuMzYsMTMuMzYsMCwwLDAsNDkuNjcsNS43OVoiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0yLjQ3IC0xLjcpIi8+PHJlY3QgY2xhc3M9ImNscy0xIiB4PSI1Ny41NyIgeT0iMC4yNCIgd2lkdGg9IjQuOSIgaGVpZ2h0PSIxOS4xIi8+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9IjcxLjI4IDAuMjQgODAuMzkgMTEuNzQgODAuMzkgMC4yNCA4NS4yNSAwLjI0IDg1LjI1IDE5LjM1IDgwLjY3IDE5LjM1IDcxLjU2IDcuOSA3MS41NiAxOS4zNSA2Ni42NyAxOS4zNSA2Ni42NyAwLjI0IDcxLjI4IDAuMjQiLz48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0xMDAuMzksMS43YTkuNzgsOS43OCwwLDAsMSwzLjg2Ljc3LDEwLjQyLDEwLjQyLDAsMCwxLDMuMTYsMi4wOSwxMCwxMCwwLDAsMSwyLjE0LDMuMTEsOS4zMyw5LjMzLDAsMCwxLC43OCwzLjgsOS40MSw5LjQxLDAsMCwxLS43OCwzLjgxLDkuOTMsOS45MywwLDAsMS0yLjE0LDMuMSwxMC4yNCwxMC4yNCwwLDAsMS0zLjE2LDIuMDksMTAuMDYsMTAuMDYsMCwwLDEtNy43MiwwLDkuODQsOS44NCwwLDAsMS01LjI3LTUuMTksOS40MSw5LjQxLDAsMCwxLS43OC0zLjgxLDkuMzMsOS4zMywwLDAsMSwuNzgtMy44LDEwLjA5LDEwLjA5LDAsMCwxLDIuMTItMy4xMSwxMC4yNSwxMC4yNSwwLDAsMSwzLjE1LTIuMDlBOS43OCw5Ljc4LDAsMCwxLDEwMC4zOSwxLjdabTAsMTVhNS40MSw1LjQxLDAsMCwwLDIuMDktLjQxLDUuNDksNS40OSwwLDAsMCwxLjctMS4xMyw1LjI5LDUuMjksMCwwLDAsMS4xNS0xLjY4LDUuMjEsNS4yMSwwLDAsMCwwLTQuMSw1LjI5LDUuMjksMCwwLDAtMS4xNS0xLjY4LDUuNDksNS40OSwwLDAsMC0xLjctMS4xMyw1LjUxLDUuNTEsMCwwLDAtNC4xNywwLDUuNDksNS40OSwwLDAsMC0xLjcsMS4xMyw1LjI5LDUuMjksMCwwLDAtMS4xNSwxLjY4LDUuMjEsNS4yMSwwLDAsMCwwLDQuMSw1LjI5LDUuMjksMCwwLDAsMS4xNSwxLjY4LDUuNDksNS40OSwwLDAsMCwxLjcsMS4xM0E1LjM2LDUuMzYsMCwwLDAsMTAwLjQsMTYuNzRaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMi40NyAtMS43KSIvPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTEyLjU1LDE2Ljc0QTUuMzIsNS4zMiwwLDAsMCwxNy4yNywxNGg0Ljg5YTEwLjMxLDEwLjMxLDAsMCwxLS40NiwxLjMyLDkuNzcsOS43NywwLDAsMS0yLjE0LDMuMSwxMC4zMSwxMC4zMSwwLDAsMS0zLjE1LDIuMDksMTAuMDksMTAuMDksMCwwLDEtNy43MywwLDkuODQsOS44NCwwLDAsMS01LjI3LTUuMTksOS40MSw5LjQxLDAsMCwxLS43OC0zLjgxLDkuMzMsOS4zMywwLDAsMSwuNzgtMy44QTEwLjA5LDEwLjA5LDAsMCwxLDUuNTMsNC41NiwxMC4yNSwxMC4yNSwwLDAsMSw4LjY4LDIuNDdhMTAuMDksMTAuMDksMCwwLDEsNy43MywwLDEwLjUsMTAuNSwwLDAsMSwzLjE1LDIuMDlBOS44MSw5LjgxLDAsMCwxLDIxLjcsNy42Nyw5LjY3LDkuNjcsMCwwLDEsMjIuMTYsOUgxNy4yN2E1LjQyLDUuNDIsMCwwLDAtNi44LTIuMzcsNS40OSw1LjQ5LDAsMCwwLTEuNywxLjEzQTUuMjksNS4yOSwwLDAsMCw3LjYyLDkuNDJhNS4yMSw1LjIxLDAsMCwwLDAsNC4xQTUuMjksNS4yOSwwLDAsMCw4Ljc3LDE1LjJhNS40OSw1LjQ5LDAsMCwwLDEuNywxLjEzQTUuNCw1LjQsMCwwLDAsMTIuNTUsMTYuNzRaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMi40NyAtMS43KSIvPjxwYXRoIGNsYXNzPSJjbHMtMiIgZD0iTTEzLjEzLDMyLjA5YTYuNSw2LjUsMCwwLDEtLjUzLDIuNiw2Ljc5LDYuNzksMCwwLDEtMS40NCwyLjEzQTYuOTQsNi45NCwwLDAsMSw5LDM4LjI4YTYuNzYsNi43NiwwLDAsMS0yLjYyLjU2VjQxLjRIMi42M1YyNS4zM0g2LjM5QTYuNzYsNi43NiwwLDAsMSw5LDI1LjlhNy4xNCw3LjE0LDAsMCwxLDIuMTUsMS40N0E2Ljc5LDYuNzksMCwwLDEsMTIuNiwyOS41LDYuNDksNi40OSwwLDAsMSwxMy4xMywzMi4wOVptLTYuNzQsM2EzLjE0LDMuMTQsMCwwLDAsMS4xNy0uMjgsMy4zMiwzLjMyLDAsMCwwLC45NC0uNjYsMi44MiwyLjgyLDAsMCwwLC42My0xLDIuOTIsMi45MiwwLDAsMCwuMjQtMS4xN0EzLjExLDMuMTEsMCwwLDAsOC41LDMwYTMuMzIsMy4zMiwwLDAsMC0uOTQtLjY2QTMuMTMsMy4xMywwLDAsMCw2LjM5LDI5WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTIuNDcgLTEuNykiLz48cGF0aCBjbGFzcz0iY2xzLTIiIGQ9Ik0yMi44MywyNS4zMWE4LjA5LDguMDksMCwwLDEsMy4xOC42Myw4LjU1LDguNTUsMCwwLDEsMi41OSwxLjcyLDguMTMsOC4xMywwLDAsMSwxLjc2LDIuNTUsOCw4LDAsMCwxLDAsNi4yNkE4LDgsMCwwLDEsMjguNiwzOSw4LjIsOC4yLDAsMCwxLDI2LDQwLjc0YTguMjIsOC4yMiwwLDAsMS02LjM2LDAsOC4xNyw4LjE3LDAsMCwxLTQuMzMtNC4yNyw4LDgsMCwwLDEsMC02LjI2LDguMSw4LjEsMCwwLDEsMS43NS0yLjU1LDguMzgsOC4zOCwwLDAsMSwyLjU4LTEuNzJBOC4wOSw4LjA5LDAsMCwxLDIyLjgzLDI1LjMxWm0wLDEyLjM3YTQuNDIsNC40MiwwLDAsMCwxLjcxLS4zNEE0LjY3LDQuNjcsMCwwLDAsMjYsMzYuNDEsNC41MSw0LjUxLDAsMCwwLDI2LjksMzVhNC4xOSw0LjE5LDAsMCwwLC4zNS0xLjY5LDQuMTQsNC4xNCwwLDAsMC0uMzUtMS42OEE0LjI4LDQuMjgsMCwwLDAsMjYsMzAuMjhhNC40OSw0LjQ5LDAsMCwwLTEuNDEtLjkzLDQuNDcsNC40NywwLDAsMC0zLjQyLDAsNC40Miw0LjQyLDAsMCwwLTIuMzUsMi4zMSw0LjE0LDQuMTQsMCwwLDAtLjM0LDEuNjhBNC4xOCw0LjE4LDAsMCwwLDE4Ljc4LDM1YTQuNDIsNC40MiwwLDAsMCwyLjM1LDIuMzFBNC40Miw0LjQyLDAsMCwwLDIyLjg0LDM3LjY4WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTIuNDcgLTEuNykiLz48cGF0aCBjbGFzcz0iY2xzLTIiIGQ9Ik0zNy42NCwzNy4yNGwtLjU4LjA3LS42LDB2NEgzMi43di0xNmgzLjc2YTcuMzksNy4zOSwwLDAsMSw0Ljc3LDEuNzksNS45NCw1Ljk0LDAsMCwxLDEuNDQsMS44OCw1LjEzLDUuMTMsMCwwLDEsLjUzLDIuMjksNS4zLDUuMywwLDAsMS0uNTYsMi40LDUuODIsNS44MiwwLDAsMS0xLjU2LDEuOTFsMy42MSw1LjczSDQwLjI2Wm0tMS4xOC0zLjYxYTQuMjYsNC4yNiwwLDAsMCwxLjE0LS4yMSwzLjM0LDMuMzQsMCwwLDAsMS0uNDksMi4yNSwyLjI1LDAsMCwwLC42NC0uNzEsMS42OCwxLjY4LDAsMCwwLC4yNC0uODcsMS41OCwxLjU4LDAsMCwwLS4yNC0uODUsMi4xMiwyLjEyLDAsMCwwLS42NC0uNywzLjM1LDMuMzUsMCwwLDAtMS0uNSwzLjksMy45LDAsMCwwLTEuMTQtLjJaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMi40NyAtMS43KSIvPjxwb2x5Z29uIGNsYXNzPSJjbHMtMiIgcG9pbnRzPSI1Mi4yMiAyMy41NiA1Mi4yMiAyNy4yNiA0OS4wOSAyNy4yNiA0OS4wOSAzOS43IDQ1LjMzIDM5LjcgNDUuMzMgMjcuMjYgNDIuMjIgMjcuMjYgNDIuMjIgMjMuNTYgNTIuMjIgMjMuNTYiLz48cGF0aCBjbGFzcz0iY2xzLTIiIGQ9Ik02Mi41LDQxLjRhNi4yNiw2LjI2LDAsMCwxLTQuMzQtMS43Myw2LjI3LDYuMjcsMCwwLDEtMS4zNi0yLDYsNiwwLDAsMS0uNS0yLjM0di0xMGgzLjc2djEwQTIuMjcsMi4yNywwLDAsMCw2MC43OSwzN2EyLjQ5LDIuNDksMCwwLDAsMS43NC42OCwyLjMyLDIuMzIsMCwwLDAsLjkzLS4yLDIuNDgsMi40OCwwLDAsMCwuNzgtLjUyLDIuMzUsMi4zNSwwLDAsMCwuNTEtLjc3LDIuNDYsMi40NiwwLDAsMCwuMTgtLjk0di0xMGgzLjc2djkuOTRhNS43MSw1LjcxLDAsMCwxLS40NiwyLjM2LDYuMTQsNi4xNCwwLDAsMS0xLjMxLDIsNi4zMiw2LjMyLDAsMCwxLTIsMS4zMiw2LjIxLDYuMjEsMCwwLDEtMi4zOS41MloiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0yLjQ3IC0xLjcpIi8+PHBhdGggY2xhc3M9ImNscy0yIiBkPSJNODQuMjEsMzIuNHY5SDgwLjQzVjQxYTYuMzEsNi4zMSwwLDAsMS0xLjI4LjMyLDguMjIsOC4yMiwwLDAsMS0xLjMuMSw4LjUyLDguNTIsMCwwLDEtMy4wOS0uNTksNy44OCw3Ljg4LDAsMCwxLTIuNzEtMS43OCw3LjUsNy41LDAsMCwxLTEuNzktMi42NSw4LDgsMCwwLDEsMC02LjA4LDcuNTcsNy41NywwLDAsMSwxLjc5LTIuNjcsNy44NSw3Ljg1LDAsMCwxLDIuNzEtMS43Nyw4LjM5LDguMzksMCwwLDEsNi4xOCwwLDgsOCwwLDAsMSwyLjcyLDEuNzdMODEsMzAuMjhhNC4xMyw0LjEzLDAsMCwwLTEuNDctMSw0LjUyLDQuNTIsMCwwLDAtMy4zNCwwLDQsNCwwLDAsMC0xLjQ2LDEsNC4xLDQuMSwwLDAsMC0xLDEuNDQsNC4xNSw0LjE1LDAsMCwwLDAsMy4yOSw0LjEsNC4xLDAsMCwwLDEsMS40NCw0LjM4LDQuMzgsMCwwLDAsMS4yOS44OSw0LjYxLDQuNjEsMCwwLDAsMywuMjMsNC4xOCw0LjE4LDAsMCwwLDEuNC0uNjZWMzYuMUg3Ny4xNlYzMi40WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTIuNDcgLTEuNykiLz48cGF0aCBjbGFzcz0iY2xzLTIiIGQ9Ik05NS4zMyw0MS40bC0uNjQtMS45NUg4OS41NWwtLjY3LDEuOTVoLTRsNS41Ni0xNi4wN0g5NEw5OS4yOSw0MS40Wm0tNC40OS01LjY1aDIuNjVsLTEuMjktMy45WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTIuNDcgLTEuNykiLz48cG9seWdvbiBjbGFzcz0iY2xzLTIiIHBvaW50cz0iMTA3LjY1IDM5LjcgOTcuOTMgMzkuNyA5Ny45MyAyMy42MSAxMDEuNjkgMjMuNjEgMTAxLjY5IDM2IDEwNy42NSAzNiAxMDcuNjUgMzkuNyIvPjxwYXRoIGNsYXNzPSJjbHMtMiIgZD0iTTgsNDkuNVY1MUg0LjMxdjEuMzFINy42NnYxLjQ2SDQuMzF2MS4zMkg4LjEydjEuNDdIMi40N3YtN1oiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0yLjQ3IC0xLjcpIi8+PHBhdGggY2xhc3M9ImNscy0yIiBkPSJNMTEuMjUsNDkuNWwxLjg3LDQuMTFMMTUsNDkuNWgydjdIMTUuMzdWNTIuMDhsLTEuNjQsMy43NWgtMS4ybC0xLjY0LTMuNzV2NC40Nkg5LjI1di03WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTIuNDcgLTEuNykiLz48cGF0aCBjbGFzcz0iY2xzLTIiIGQ9Ik0yNS41MSw1M2EzLjYyLDMuNjIsMCwwLDEtMy44LDMuNjEsMy41OSwzLjU5LDAsMSwxLDAtNy4xN0EzLjYsMy42LDAsMCwxLDI1LjUxLDUzWm0tNS43MiwwYTIsMiwwLDAsMCwxLjk0LDIsMS45NCwxLjk0LDAsMCwwLDEuODktMiwxLjkyLDEuOTIsMCwwLDAtMS44OS0yQTIsMiwwLDAsMCwxOS43OSw1M1oiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0yLjQ3IC0xLjcpIi8+PHBhdGggY2xhc3M9ImNscy0yIiBkPSJNMzAuMDUsNTYuNThsLS4yNS40N2EuODQuODQsMCwwLDEsLjY5LjgxYzAsLjY0LS41MywxLTEuMjcsMWExLjc5LDEuNzksMCwwLDEtMS0uMzFsLjMzLS43NWEuNzQuNzQsMCwwLDAsLjQ4LjIuMzcuMzcsMCwwLDAsLjQxLS4zNmMwLS4yLS4xNi0uMzYtLjQ1LS4zNmExLjYzLDEuNjMsMCwwLDAtLjM2LjA1bC4zNy0uNzlBMy41NCwzLjU0LDAsMCwxLDI1Ljg1LDUzYTMuNTgsMy41OCwwLDAsMSwzLjc2LTMuNTUsNCw0LDAsMCwxLDIuODQsMS4yMWwtMS4wNiwxLjI3QTIuMzksMi4zOSwwLDAsMCwyOS42NCw1MWEyLDIsMCwwLDAsMCw0LDIuNiwyLjYsMCwwLDAsMS43NS0uODJsMS4wNywxLjE1QTQuMyw0LjMsMCwwLDEsMzAuMDUsNTYuNThaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMi40NyAtMS43KSIvPjxwYXRoIGNsYXNzPSJjbHMtMiIgZD0iTTM3Ljg1LDU1LjI5aC0zbC0uNDksMS4yNUgzMi40M2wzLTdoMS44OWwyLjk1LDdoLTJabS0yLjE5LTYuNThoLTEuMWMwLTEuMDkuMzYtMS42MSwxLjA2LTEuNjFzLjg5LjQ2LDEuMTYuNDYuMy0uMTQuMy0uNDJoMS4wOWMwLDEuMDktLjM1LDEuNjEtMS4wNSwxLjYxcy0uODgtLjQ2LTEuMTctLjQ2UzM1LjY2LDQ4LjQzLDM1LjY2LDQ4LjcxWm0xLjY1LDUuMTgtMS0yLjUxLTEsMi41MVoiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0yLjQ3IC0xLjcpIi8+PHBhdGggY2xhc3M9ImNscy0yIiBkPSJNNDcuODgsNTNhMy44MSwzLjgxLDAsMSwxLTMuOC0zLjU2QTMuNjIsMy42MiwwLDAsMSw0Ny44OCw1M1ptLTUuNzIsMGEyLDIsMCwwLDAsMS45NCwyQTIsMiwwLDAsMCw0Niw1M2ExLjkzLDEuOTMsMCwwLDAtMS45LTJBMiwyLDAsMCwwLDQyLjE2LDUzWiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTIuNDcgLTEuNykiLz48cGF0aCBjbGFzcz0iY2xzLTIiIGQ9Ik01Ny43Myw1MmMwLDEuNjYtMSwyLjYyLTIuODMsMi42Mkg1My42NHYxLjk1SDUxLjgxdi03SDU0LjlDNTYuNjksNDkuNSw1Ny43Myw1MC40LDU3LjczLDUyWk01Niw1MmMwLS42OS0uNDMtMS4wNi0xLjE3LTEuMDZINTMuNjR2Mi4xNWgxLjE3QTEsMSwwLDAsMCw1Niw1MloiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0yLjQ3IC0xLjcpIi8+PHBhdGggY2xhc3M9ImNscy0yIiBkPSJNNjQuMTgsNDkuNVY1MUg2MC40NnYxLjMxaDMuMzZ2MS40Nkg2MC40NnYxLjMyaDMuODJ2MS40N0g1OC42M3YtN1oiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0yLjQ3IC0xLjcpIi8+PHBhdGggY2xhc3M9ImNscy0yIiBkPSJNNjcuMjQsNDkuNVY1NWgzdjEuNTdINjUuNHYtN1oiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0yLjQ3IC0xLjcpIi8+PHBhdGggY2xhc3M9ImNscy0yIiBkPSJNNzguMiw1M2EzLjgxLDMuODEsMCwxLDEtMy44LTMuNTZBMy42MiwzLjYyLDAsMCwxLDc4LjIsNTNabS01LjcyLDBhMiwyLDAsMCwwLDEuOTQsMiwyLDIsMCwwLDAsMS45LTIsMS45MywxLjkzLDAsMCwwLTEuOS0yQTIsMiwwLDAsMCw3Mi40OCw1M1oiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0yLjQ3IC0xLjcpIi8+PHBhdGggY2xhc3M9ImNscy0yIiBkPSJNODMuODQsNTYuNjJhMy4xLDMuMSwwLDAsMS0yLjQ1LTEuMTRsLjg1LTEuMjVhMi4yNCwyLjI0LDAsMCwwLDEuNDcuNzljLjQ1LDAsLjczLS4yOC43My0uODFWNTFIODJWNDkuNWg0LjI2djQuNzVBMi4yMiwyLjIyLDAsMCwxLDgzLjg0LDU2LjYyWiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTIuNDcgLTEuNykiLz48cGF0aCBjbGFzcz0iY2xzLTIiIGQ9Ik05NC42OSw1M2EzLjgxLDMuODEsMCwxLDEtMy44MS0zLjU2QTMuNjMsMy42MywwLDAsMSw5NC42OSw1M1pNODksNTNhMiwyLDAsMCwwLDEuOTMsMiwxLjk0LDEuOTQsMCwwLDAsMS45LTIsMS45MiwxLjkyLDAsMCwwLTEuOS0yQTIsMiwwLDAsMCw4OSw1M1oiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0yLjQ3IC0xLjcpIi8+PHBhdGggY2xhc3M9ImNscy0yIiBkPSJNMTAwLjE0LDUyLjk0aDEuNTh2Mi43N2E1LjU3LDUuNTcsMCwwLDEtMi45My45MUEzLjYsMy42LDAsMCwxLDk1LDUzYTMuNjQsMy42NCwwLDAsMSwzLjg4LTMuNTcsNC41Miw0LjUyLDAsMCwxLDIuODksMWwtMSwxLjI5QTIuODYsMi44NiwwLDAsMCw5OC45LDUxYTIsMiwwLDAsMCwwLDQsMi45NCwyLjk0LDAsMCwwLDEuMjItLjMzWiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTIuNDcgLTEuNykiLz48cGF0aCBjbGFzcz0iY2xzLTIiIGQ9Ik0xMTAuMDksNTNhMy44MSwzLjgxLDAsMSwxLTMuOC0zLjU2QTMuNjIsMy42MiwwLDAsMSwxMTAuMDksNTNabS01LjcyLDBhMiwyLDAsMCwwLDEuOTQsMiwxLjk0LDEuOTQsMCwwLDAsMS44OS0yLDEuOTIsMS45MiwwLDAsMC0xLjg5LTJBMiwyLDAsMCwwLDEwNC4zNyw1M1oiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0yLjQ3IC0xLjcpIi8+PC9zdmc+">
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

<script>
    function addElementClass(element, className, delay) {
        window.setTimeout(function () {
            element.classList.add(className);
        }, delay);
    }

    function removeElementClass(element, className, delay) {
        window.setTimeout(function () {
            element.classList.remove(className);
        }, delay);
    }

    var body = document.getElementsByTagName("BODY")[0];
    var loader = document.getElementsByClassName('mobile-loader')[0];

    body.classList.add('hide-scrollbar');

    addElementClass(loader, 'mobile-loader-fadeout', 3000);
    addElementClass(loader, 'mobile-loader-hide', 4000);
    removeElementClass(body, 'hide-scrollbar', 4000);

</script>
