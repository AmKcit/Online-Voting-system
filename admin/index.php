<?php
    require_once("inc/header.php");
    require_once("inc/navigation.php");
?>

<div class="container-fluid d-flex flex-column min-vh-100 p-0">
    <div class="row flex-grow-1 p-0">
        <div class="col-12">
            <?php
                if (isset($_GET['Homepage'])) {
                    require_once("inc/homepage.php");
                } elseif (isset($_GET['addElectionPage'])) {
                    require_once("inc/add_election.php");
                } elseif (isset($_GET['addCandidatesPage'])) {
                    require_once("inc/add_candidates.php");
                } elseif (isset($_GET['viewResult'])) {
                    require_once("inc/viewResult.php");
                }
            ?>
        </div>
    </div>

  <!-- Footer section -->



</div>
<footer>
    <div class="row bg-dark text-center text-white mt-auto p-3">
        <div class="col-12">
            <p>&copy; Copyright 2024 - All Rights Reserved <br>
            Developed by OnlyVotes
            </p>
        </div>
    </div>
</footer>

<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>

</body>
