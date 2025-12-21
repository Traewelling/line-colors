<?php
include_once "../validation/common.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
    <link href="assets/images/favicon.png" rel="icon"/>
    <title>line-colors | Tr&auml;welling</title>
    <meta name="description" content="Your ThemeForest item Name and description">
    <meta name="author" content="harnishdesign.net">

    <!-- Stylesheet
    ============================== -->
    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="assets/vendor/bootstrap/css/bootstrap.min.css"/>
    <!-- Font Awesome Icon -->
    <link rel="stylesheet" type="text/css" href="assets/vendor/font-awesome/css/all.min.css"/>
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" type="text/css" href="assets/css/stylesheet.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/custom-elements.css"/>
</head>

<body data-spy="scroll" data-target=".idocs-navigation" data-offset="125">


<!-- Document Wrapper   
=============================== -->
<div id="main-wrapper">

    <!-- Header
    ============================ -->
    <header id="header" class="sticky-top">
        <!-- Navbar -->
        <nav class="primary-menu navbar navbar-expand-lg navbar-dropdown-dark">
            <div class="container-fluid">
                <!-- Sidebar Toggler -->
                <button id="sidebarCollapse" class="navbar-toggler d-block d-md-none" type="button"><span></span><span
                            class="w-75"></span><span class="w-50"></span></button>

                <!-- Logo -->
                <span class="logo ml-md-3 text-8"> line-colors </span>
                <!-- Logo End -->

                <!-- Navbar Toggler -->
                <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#header-nav">
                    <span></span><span></span><span></span></button>

                <div id="header-nav" class="collapse navbar-collapse justify-content-end">
                    <ul class="navbar-nav">
                        <li><a href="javascript:toggleDetails()" id="details-toggler">Show details</a></li>
                        <li><a target="_blank"
                               href="https://github.com/traewelling/line-colors">Source Code</a>
                        </li>
                    </ul>
                </div>

            </div>
        </nav>
        <!-- Navbar End -->
    </header>
    <!-- Header End -->

    <!-- Content
    ============================ -->
    <div id="content" role="main">

        <!-- Sidebar Navigation
        ============================ -->
        <div class="idocs-navigation bg-light">
            <ul class="nav flex-column">
                <li class="nav-item"><a href="#preamble" class="nav-link">Preamble</a></li>

                <?php foreach ($linesByOperatorCode as $key => $value): ?>
                    <li class="nav-item"><a class="nav-link" href="#<?= $key ?>"><?= $key ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Docs Content
        ============================ -->
        <div class="idocs-content">
            <div class="container">
                <section id="preamble">
                    <h1>Public transport line colors</h1>
                    <p>This repository collects line colors in public transport lines, so they can be displayed on
                        systems using DB HAFAS.</p>
                    <p>You are invited to use the dataset in your websites, apps, etc. Catch the latest version and the
                        schema specification of the dataset from <a href="https://github.com/traewelling/line-colors">our
                            GitHub repository</a>.</p>
                    <p>This dataset is far from complete! If you want to add or correct data, please <a
                                href="https://github.com/Traewelling/line-colors/edit/main/line-colors.csv">send a pull
                            request</a> to our code base.</p>
                </section>
                <hr class="divider">

                <?php foreach ($linesByOperatorCode as $operator => $lines): ?>
                    <h1><?= $operator; ?></h1>
                    <section class="operator-group" id="<?= $operator; ?>">
                        <?php foreach ($lines as $line): ?>
                            <article>
                                <line-logo-<?= $line["shape"] ?>
                                <?php foreach ($line as $key => $value): ?>
                                    <?= $key ?>="<?= $value ?>"
                                <?php endforeach ?>
                                >
                                <?php foreach ($line as $key => $value): ?>
                                    <span slot="<?= $key ?>"><?= $value ?></span>
                                <?php endforeach ?>
                                </line-logo-<?= $line["shape"] ?>>

                                <dl>
                                    <?php foreach ($line as $key => $value): ?>
                                        <?php if ($key === "borderColor" && $value === "") continue; ?>
                                        <dt><?= $key ?></dt>
                                        <dd class="<?= in_array($key, ["backgroundColor", "textColor", "borderColor", "shape"]) ? "monospace" : "" ?>">
                                            <?php if (in_array($key, ["backgroundColor", "textColor", "borderColor"])): ?>
                                                <span class="color-preview"
                                                      style="background-color: <?= $value ?>"></span>
                                            <?php endif ?>
                                            <?= $value ?>
                                        </dd>
                                    <?php endforeach ?>
                                </dl>
                            </article>
                        <?php endforeach; ?>
                    </section>
                    <hr class="divider">
                <?php endforeach; ?>


                <!-- Templates for line logo -->

                <template id="template-for-line-logo-circle">
                    <style>
                        div {
                            font-family: "Avenir Next Condensed", "Avenir Next", Arial, sans-serif;
                            font-size: 2em;

                            width: 2.5em;
                            height: 2.5em;
                            border-radius: 50%;
                            align-items: center;
                            justify-content: center;
                            display: inline-flex;
                        }

                    </style>
                    <div class="line-logo">
                        <slot name="lineName">Wurst</slot>
                    </div>
                </template>

                <template id="template-for-line-logo-hexagon">
                    <style>
                        div {
                            font-family: "IBM Plex Mono", Monaco, Consolas, monospace;
                            font-weight: 400;
                            font-size: 2em;
                            text-align: center;

                            display: inline-block;
                            line-height: 1.2em;

                            padding: 0 0.75em;
                            /* TODO: consider the edge case of border around a hexagon shape */
                            clip-path: polygon(50% -100%, 100% 50%, 50% 200%, 0 50%);
                        }

                    </style>
                    <div class="line-logo">
                        <slot name="lineName">Wurst</slot>
                    </div>
                </template>


                <template id="template-for-line-logo-pill">
                    <style>
                        div {
                            font-family: Arial, Baskerville, monospace;
                            font-weight: 600;
                            font-size: 2em;
                            text-align: center;

                            display: inline-block;
                            line-height: 1.2em;

                            padding: 0 0.75em;
                            border-radius: 0.6em;
                        }

                    </style>
                    <div class="line-logo">
                        <slot name="lineName">Wurst</slot>
                    </div>
                </template>

                <template id="template-for-line-logo-rectangle">
                    <style>
                        div {
                            font-family: "IBM Plex Mono", Monaco, Consolas, monospace;
                            font-weight: 400;
                            font-size: 2em;
                            text-align: center;

                            width: 4em;
                            line-height: 1.25em;
                        }

                    </style>
                    <div class="line-logo">
                        <slot name="lineName">Wurst</slot>
                    </div>
                </template>

                <template id="template-for-line-logo-rectangle-rounded-corner">
                    <style>
                        div {
                            font-family: "Avenir Next Condensed", "Avenir Next", Arial, sans-serif;
                            font-size: 2em;
                            text-align: center;

                            line-height: 1.5em;
                            padding: 0 0.5em;
                            border-radius: 0.3em;
                            display: inline-block;
                        }

                    </style>
                    <div class="line-logo">
                        <slot name="lineName">Wurst</slot>
                    </div>
                </template>

                <template id="template-for-line-logo-trapezoid">
                    <style>
                        div {
                            font-family: "IBM Plex Mono", Monaco, Consolas, monospace;
                            font-weight: 400;
                            font-size: 2em;
                            text-align: center;

                            display: inline-block;
                            line-height: 1.2em;

                            padding: 0 0.75em;
                            /* TODO: consider the edge case of border around a trapezoid shape */
                            clip-path: polygon( 100% 0, 50% 250%, 0 0);
                        }

                    </style>
                    <div class="line-logo">
                        <slot name="lineName">Wurst</slot>
                    </div>
                </template>


                <!-- Templates for pills end -->


                <footer>
                    Collected by Tr&auml;welling and its contributors. The dataset is published in <a
                            href="https://github.com/Traewelling/line-colors/blob/main/LICENSE">CC0</a>.
                </footer>

            </div>
            <!-- Content end -->


        </div>
        <!-- Document Wrapper end -->

        <!-- Back To Top -->
        <a id="back-to-top" data-toggle="tooltip" title="Back to Top" href="javascript:void(0)"><i
                    class="fa fa-chevron-up"></i></a>

        <!-- JavaScript
        ============================ -->
        <script src="assets/vendor/jquery/jquery.min.js"></script>
        <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- Easing -->
        <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
        <!-- Custom Script -->
        <script src="assets/js/theme.js"></script>
        <script src="assets/js/custom-elements.js"></script>
</body>
</html>
