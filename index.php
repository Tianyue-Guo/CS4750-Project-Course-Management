<html>
    <head>
        <style>
            /*
        *
        * ==========================================
        * CUSTOM UTIL CLASSES
        * ==========================================
        *
        */
        .navbar {
            transition: all 0.4s;
        }

        .navbar .nav-link {
            color: #fff;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link:focus {
            color: #fff;
            text-decoration: none;
        }

        .navbar .navbar-brand {
            color: #fff;
        }


        /* Change navbar styling on scroll */
        .navbar.active {
            background: #fff;
            box-shadow: 1px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar.active .nav-link {
            color: #555;
        }

        .navbar.active .nav-link:hover,
        .navbar.active .nav-link:focus {
            color: #555;
            text-decoration: none;
        }

        .navbar.active .navbar-brand {
            color: #555;
        }


        /* Change navbar styling on small viewports */
        @media (max-width: 991.98px) {
            .navbar {
                background: #fff;
            }

            .navbar .navbar-brand, .navbar .nav-link {
                color: #555;
            }
        }



        /*
        *
        * ==========================================
        * FOR DEMO PURPOSES
        * ==========================================
        *
        */
        .text-small {
            font-size: 0.9rem !important;
        }


        body {
            min-height: 110vh;
            background-color: #4ca1af;
            background-image: linear-gradient(135deg, #4ca1af 0%, #c4e0e5 100%);
        }
        </style>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js" integrity="sha512-u9akINsQsAkG9xjc1cnGF4zw5TFDwkxuc9vUp5dltDWYCSmyd0meygbvgXrlc/z7/o4a19Fb5V0OUE58J7dcyw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    </head>
    <body>
        <?php 
        session_start();
        include "bar.php" ?>


        <!-- For demo purpose -->
        <div class="container">
            <div class="pt-5 text-white">
                <header class="py-5 mt-5">
                    <h1 class="display-4">UVA Course Requirement</h1>
                </header>
                <div class="py-5">
                    <p class="lead">The project is a UVA course management system (web-based app). It consists of five entity sets: pre-requisite courses, courses, users (students and professors), majors, and courses taken by users. On this platform, students can take courses constrained by pre-requisites and their already-taken courses in their majors; professors can add courses, add majors, add courses to major requirements, or edit the courses flow (which course is the prerequisite of the other). </p>
                </div>
                <div class="py-5">
                    <p class="lead">The domain of our database includes user data, course data and major requirements. The course and major data can be edited by users with the role “professor”. We plan to add some predefined majors and courses according to UVa's existing curriculum for demonstration purposes, but we allow “professors” to edit them and add any majors and courses. </p>
                </div>
            </div>
        </div>

        <script>
            $(function () {
                $(window).on('scroll', function () {
                    if ( $(window).scrollTop() > 10 ) {
                        $('.navbar').addClass('active');
                    } else {
                        $('.navbar').removeClass('active');
                    }
                });
            });
        </script>
</body>
</html>