<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <title>{{ config('app.name') }}</title>

    <style>
        html {
            scroll-behavior:
        }

        body {
            font-family: Poppins, sans-serif;
        }
    </style>
</head>

<body class="max-w-[1920px] mx-auto">
    <div class="bg-black text-gray-100 text-[15px]">

        <div class="relative lg:min-h-screen 2xl:min-h-[730px] before:absolute before:inset-0 before:w-full before:bg-black before:opacity-60"
            style="background-image: url(https://drivetech.ph/assets/images/mobile/Decore1.svg);
       background-size: cover;
       background-repeat: no-repeat;
       background-position: calc(100% + 400px) calc(100% + 500px);">


            <header class='py-12 px-6 sm:px-24 z-50 min-h-[70px] relative'>
                <div class='lg:flex lg:items-center gap-x-2 relative'>
                    <div class="flex items-center shrink-0">
                        <a href="javascript:void(0)">
                            <h1 class='text-3xl'>{{ config('app.name') }}</h1>
                        </a>
                        <button id="toggleOpen" class='lg:hidden ml-auto'>
                            <svg class="w-7 h-7" fill="#fff" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>

                    <div id="collapseMenu"
                        class="lg:ml-14 max-lg:hidden lg:!block w-full max-lg:fixed max-lg:before:fixed max-lg:before:bg-black max-lg:before:opacity-50 max-lg:before:inset-0 max-lg:before:z-50 max-lg:before:inset-0 max-lg:before:z-50 z-50">
                        <button id="toggleClose"
                            class='mt-10 lg:hidden fixed top-2 right-4 z-[100] rounded-full bg-white p-3'>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 fill-black"
                                viewBox="0 0 320.591 320.591">
                                <path
                                    d="M30.391 318.583a30.37 30.37 0 0 1-21.56-7.288c-11.774-11.844-11.774-30.973 0-42.817L266.643 10.665c12.246-11.459 31.462-10.822 42.921 1.424 10.362 11.074 10.966 28.095 1.414 39.875L51.647 311.295a30.366 30.366 0 0 1-21.256 7.288z"
                                    data-original="#000000"></path>
                                <path
                                    d="M287.9 318.583a30.37 30.37 0 0 1-21.257-8.806L8.83 51.963C-2.078 39.225-.595 20.055 12.143 9.146c11.369-9.736 28.136-9.736 39.504 0l259.331 257.813c12.243 11.462 12.876 30.679 1.414 42.922-.456.487-.927.958-1.414 1.414a30.368 30.368 0 0 1-23.078 7.288z"
                                    data-original="#000000"></path>
                            </svg>
                        </button>

                        <div
                            class='pt-12 lg:flex items-center w-full gap-6 max-lg:fixed max-lg:bg-black max-lg:w-1/2 max-lg:min-w-[300px] max-lg:top-0 max-lg:left-0 max-lg:p-6 max-lg:pt-12 max-lg:h-full max-lg:shadow-md max-lg:overflow-auto z-50'>
                            <ul class='lg:flex gap-x-6 ml-auto max-lg:space-y-3'>
                                <li class='mb-6 hidden max-lg:block'>
                                    <a href="javascript:void(0)">
                                        <h1 class='text-3xl'>{{ config('app.name') }}</h1>
                                    </a>
                                </li>
                                <li class='max-lg:border-b max-lg:py-3 px-3'>
                                    <a href='#'
                                        class='hover:text-blue-600 text-blue-600 block transition-all'>Home</a>
                                </li>
                                <li class='max-lg:border-b max-lg:py-3 px-3'>
                                    <a href='#about'class='hover:text-blue-600 block transition-all'>About</a>
                                </li>
                                <li class='max-lg:border-b max-lg:py-3 px-3'>
                                    <a href='#features'class='hover:text-blue-600 block transition-all'>Features</a>
                                </li>
                                <li class='max-lg:border-b max-lg:py-3 px-3'><a href='#contact'
                                        class='hover:text-blue-600 block transition-all'>Contact</a>
                                </li>

                            </ul>

                        </div>
                    </div>
                </div>
            </header>
            <!-- Landing page -->
            <div class="max-w-5xl mx-auto text-center relative px-4 sm:px-10 mt-16">
                <h1 class="lg:text-5xl md:text-4xl text-3xl font-semibold mb-6 md:!leading-[80px]">
                    Comprehensive Evaluation System
                </h1>
                <p class="text-gray-400 text-lg">
                    Transform your assessment processes with our advanced evaluation system, designed to objectively
                    measure performance, track progress, and provide actionable insights. Elevate decision-making with
                    data-driven evaluations for students, employees, and organizational initiatives.
                </p>
                <div class="mt-14 flex gap-x-8 gap-y-4 justify-center max-sm:flex-col">
                    <a href="{{ route('filament.app.auth.login') }}">
                        <button type='button'
                            class="px-6 py-3.5 rounded-md text-gray-100 bg-blue-700 hover:bg-blue-800 transition-all">
                            Get Started
                        </button>
                    </a>
                    <a href="{{ route('filament.app.auth.register') }}">
                        <button type='button'
                            class="bg-transparent hover:bg-blue-600 border border-blue-600 px-6 py-3.5 rounded-md text-gray-100 transition-all">
                            Create an Account
                        </button>
                    </a>
                </div>
            </div>
        </div>

        <div class="px-4 sm:px-10">
            <!-- ABOUT SECTION -->
            <div class="mt-32 rounded-md px-4 py-12" id="about">
                <div class="grid md:grid-cols-2 justify-center items-center gap-12 max-w-7xl mx-auto">
                    <div>
                        <img src="https://readymadeui.com/management-img.webp" alt="Evaluation System"
                            class="w-full mx-auto" />
                    </div>
                    <div class="max-md:text-center">
                        <h2 class="md:text-4xl text-3xl font-semibold md:!leading-[50px] mb-6">
                            Our Evaluation System streamlines assessment processes for institutions and organizations of
                            all sizes.
                        </h2>
                        <p class="text-gray-400">
                            By automating evaluation workflows, performance tracking, and feedback collection, we help
                            organizations
                            make objective assessments, identify improvement areas, and foster continuous development.
                        </p>
                    </div>
                </div>
            </div>

            <!-- ABOUT SECTION  -->
            <div class="mt-32 rounded-md px-4 py-12">
                <div class="grid md:grid-cols-2 justify-center items-center gap-12 max-w-7xl mx-auto">
                    <div class="max-md:text-center">
                        <h2 class="md:text-4xl text-3xl font-semibold md:!leading-[50px] mb-6">
                            Enhance Decision-Making with Data-Driven Evaluations
                        </h2>
                        <p class="text-gray-400">
                            Our system is designed to elevate assessment quality through standardized evaluation
                            criteria,
                            comprehensive performance analytics, and actionable insights. With intelligent data
                            processing,
                            organizations can identify trends, recognize excellence, and implement targeted development
                            plans
                            for continuous improvement.
                        </p>
                        <button type="button"
                            class="px-6 py-3.5 rounded-md text-gray-100 bg-blue-700 hover:bg-blue-800 transition-all mt-10">
                            <a href="{{ route('filament.app.auth.login') }}">Try it today</a>
                        </button>
                    </div>
                    <div>
                        <img src="https://readymadeui.com/team-image.webp" alt="Evaluation System"
                            class="w-full mx-auto" />
                    </div>
                </div>
            </div>



            <!-- FEATURES -->
            <div class="mt-32 max-w-7xl mx-auto" id="features">
                <div class="mb-16 max-w-2xl text-center mx-auto">
                    <h2 class="md:text-4xl text-3xl font-semibold md:!leading-[50px] mb-6">Our Features</h2>
                    <p class="text-gray-400">Discover how our comprehensive evaluation system can transform your
                        assessment
                        processes with these powerful features designed for accuracy, efficiency, and insightful
                        analytics.</p>
                </div>
                <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-12 mt-16">
                    <div class="text-center bg-[#111] px-6 py-8 rounded-2xl">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            class="w-12 mb-6 inline-block bg-gray-700 p-3 rounded-xl" viewBox="0 0 32 32">
                            <path
                                d="M28.068 12h-.128a.934.934 0 0 1-.864-.6.924.924 0 0 1 .2-1.01l.091-.091a2.938 2.938 0 0 0 0-4.147l-1.511-1.51a2.935 2.935 0 0 0-4.146 0l-.091.091A.956.956 0 0 1 20 4.061v-.129A2.935 2.935 0 0 0 17.068 1h-2.136A2.935 2.935 0 0 0 12 3.932v.129a.956.956 0 0 1-1.614.668l-.086-.091a2.935 2.935 0 0 0-4.146 0l-1.516 1.51a2.938 2.938 0 0 0 0 4.147l.091.091a.935.935 0 0 1 .185 1.035.924.924 0 0 1-.854.579h-.128A2.935 2.935 0 0 0 1 14.932v2.136A2.935 2.935 0 0 0 3.932 20h.128a.934.934 0 0 1 .864.6.924.924 0 0 1-.2 1.01l-.091.091a2.938 2.938 0 0 0 0 4.147l1.51 1.509a2.934 2.934 0 0 0 4.147 0l.091-.091a.936.936 0 0 1 1.035-.185.922.922 0 0 1 .579.853v.129A2.935 2.935 0 0 0 14.932 31h2.136A2.935 2.935 0 0 0 20 28.068v-.129a.956.956 0 0 1 1.614-.668l.091.091a2.935 2.935 0 0 0 4.146 0l1.511-1.509a2.938 2.938 0 0 0 0-4.147l-.091-.091a.935.935 0 0 1-.185-1.035.924.924 0 0 1 .854-.58h.128A2.935 2.935 0 0 0 31 17.068v-2.136A2.935 2.935 0 0 0 28.068 12ZM29 17.068a.933.933 0 0 1-.932.932h-.128a2.956 2.956 0 0 0-2.083 5.028l.09.091a.934.934 0 0 1 0 1.319l-1.511 1.509a.932.932 0 0 1-1.318 0l-.09-.091A2.957 2.957 0 0 0 18 27.939v.129a.933.933 0 0 1-.932.932h-2.136a.933.933 0 0 1-.932-.932v-.129a2.951 2.951 0 0 0-5.028-2.082l-.091.091a.934.934 0 0 1-1.318 0l-1.51-1.509a.934.934 0 0 1 0-1.319l.091-.091A2.956 2.956 0 0 0 4.06 18h-.128A.933.933 0 0 1 3 17.068v-2.136A.933.933 0 0 1 3.932 14h.128a2.956 2.956 0 0 0 2.083-5.028l-.09-.091a.933.933 0 0 1 0-1.318l1.51-1.511a.932.932 0 0 1 1.318 0l.09.091A2.957 2.957 0 0 0 14 4.061v-.129A.933.933 0 0 1 14.932 3h2.136a.933.933 0 0 1 .932.932v.129a2.956 2.956 0 0 0 5.028 2.082l.091-.091a.932.932 0 0 1 1.318 0l1.51 1.511a.933.933 0 0 1 0 1.318l-.091.091A2.956 2.956 0 0 0 27.94 14h.128a.933.933 0 0 1 .932.932Z"
                                data-original="#000000" />
                            <path
                                d="M16 9a7 7 0 1 0 7 7 7.008 7.008 0 0 0-7-7Zm0 12a5 5 0 1 1 5-5 5.006 5.006 0 0 1-5 5Z"
                                data-original="#000000" />
                        </svg>
                        <h3 class="text-xl mb-4">Automated Assessment & Feedback Distribution</h3>
                        <p class="text-gray-400">Streamline your evaluation process! Our system automatically generates
                            assessments and distributes timely feedback to participants with comprehensive performance
                            insights.</p>
                    </div>
                    <div class="text-center bg-[#111] px-6 py-8 rounded-2xl">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            class="w-12 mb-6 inline-block bg-gray-700 p-3 rounded-xl" viewBox="0 0 682.667 682.667">
                            <defs>
                                <clipPath id="a" clipPathUnits="userSpaceOnUse">
                                    <path d="M0 512h512V0H0Z" data-original="#000000" />
                                </clipPath>
                            </defs>
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-miterlimit="10" stroke-width="40" clip-path="url(#a)"
                                transform="matrix(1.33 0 0 -1.33 0 682.667)">
                                <path
                                    d="M256 492 60 410.623v-98.925C60 183.674 137.469 68.38 256 20c118.53 48.38 196 163.674 196 291.698v98.925z"
                                    data-original="#000000" />
                                <path d="M178 271.894 233.894 216 334 316.105" data-original="#000000" />
                            </g>
                        </svg>
                        <h3 class="text-xl mb-4">Real-Time Performance Tracking</h3>
                        <p class="text-gray-400">Access real-time data on ongoing evaluations, performance metrics, and
                            improvement
                            trends—all in one centralized dashboard for immediate insights.</p>
                    </div>
                    <div class="text-center bg-[#111] px-6 py-8 rounded-2xl">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            class="w-12 mb-6 inline-block bg-gray-700 p-3 rounded-xl" viewBox="0 0 512.001 512.001">
                            <path
                                d="M271.029 0c-33.091 0-61 27.909-61 61s27.909 61 61 61 60-27.909 60-61-26.909-61-60-61zm66.592 122c-16.485 18.279-40.096 30-66.592 30-26.496 0-51.107-11.721-67.592-30-14.392 15.959-23.408 36.866-23.408 60v15c0 8.291 6.709 15 15 15h151c8.291 0 15-6.709 15-15v-15c0-23.134-9.016-44.041-23.408-60zM144.946 460.404 68.505 307.149c-7.381-14.799-25.345-20.834-40.162-13.493l-19.979 9.897c-7.439 3.689-10.466 12.73-6.753 20.156l90 180c3.701 7.423 12.704 10.377 20.083 6.738l19.722-9.771c14.875-7.368 20.938-25.417 13.53-40.272zM499.73 247.7c-12.301-9-29.401-7.2-39.6 3.9l-82 100.8c-5.7 6-16.5 9.6-22.2 9.6h-69.901c-8.401 0-15-6.599-15-15s6.599-15 15-15h60c16.5 0 30-13.5 30-30s-13.5-30-30-30h-78.6c-7.476 0-11.204-4.741-17.1-9.901-23.209-20.885-57.949-30.947-93.119-22.795-19.528 4.526-32.697 12.415-46.053 22.993l-.445-.361-21.696 19.094L174.28 452h171.749c28.2 0 55.201-13.5 72.001-36l87.999-126c9.9-13.201 7.2-32.399-6.299-42.3z"
                                data-original="#000000" />
                        </svg>
                        <h3 class="text-xl mb-4">Customizable Evaluation Criteria</h3>
                        <p class="text-gray-400">Create tailored assessment frameworks with customizable evaluation
                            criteria,
                            weighting systems, and scoring rubrics to match your specific organizational requirements.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <footer class="bg-[#111] px-4 sm:px-10 py-12 mt-32" id="contact">
            <div class="grid max-sm:grid-cols-1 max-lg:grid-cols-2 lg:grid-cols-3 gap-8">
                <div>
                    <h4 class="text-lg mb-6">About Us</h4>
                    <p class="text-gray-400 mb-2">We are a dedicated team of evaluation experts committed to developing
                        powerful assessment tools that drive meaningful insights and continuous improvement for
                        organizations
                        across industries.</p>
                </div>
                <div>
                    <h4 class="text-lg mb-6">Solutions</h4>
                    <ul class="space-y-4">
                        <li><a href="javascript:void(0)"
                                class="text-gray-400 hover:text-blue-600 transition-all">Academic
                                Assessments</a></li>
                        <li><a href="javascript:void(0)"
                                class="text-gray-400 hover:text-blue-600 transition-all">Employee Performance
                                Evaluations</a></li>
                        <li><a href="javascript:void(0)"
                                class="text-gray-400 hover:text-blue-600 transition-all">Program
                                Assessment</a></li>
                        <li><a href="javascript:void(0)"
                                class="text-gray-400 hover:text-blue-600 transition-all">Project
                                Evaluation</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg mb-6">Resources</h4>
                    <ul class="space-y-4">
                        <li><a href="javascript:void(0)"
                                class="text-gray-400 hover:text-blue-600 transition-all">Evaluation Guides</a>
                        </li>
                        <li><a href="javascript:void(0)"
                                class="text-gray-400 hover:text-blue-600 transition-all">Assessment Templates</a>
                        </li>
                        <li><a href="javascript:void(0)" class="text-gray-400 hover:text-blue-600 transition-all">Case
                                Studies</a>
                        </li>
                        <li><a href="javascript:void(0)" class="text-gray-400 hover:text-blue-600 transition-all">Best
                                Practices</a></li>
                    </ul>
                </div>
            </div>
        </footer>

    </div>

    <script>
        var toggleOpen = document.getElementById('toggleOpen');
        var toggleClose = document.getElementById('toggleClose');
        var collapseMenu = document.getElementById('collapseMenu');

        function handleClick() {
            if (collapseMenu.style.display === 'block') {
                collapseMenu.style.display = 'none';
            } else {
                collapseMenu.style.display = 'block';
            }
        }

        toggleOpen.addEventListener('click', handleClick);
        toggleClose.addEventListener('click', handleClick);
    </script>
</body>

</html>
