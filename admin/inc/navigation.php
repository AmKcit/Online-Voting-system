<div class="row">
  <div class="col-12">
    <nav class="navbar navbar-expand-lg navbar-light">
      <div class="container-fluid">
        <!-- Logo or Navbar Brand can be added here -->
        <!-- <a class="navbar-brand" href="#">Navbar</a> -->
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
          <ul class="navbar-nav mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="index.php?Homepage=1">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?addElectionPage=1">Add Election</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php?addCandidatesPage=1">Add Candidate</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </div>
</div>

<!-- Add CSS to style the navbar -->
<style>
  .navbar {
    padding: 1rem;
    background-color: transparent; /* Removed background color */
  }

  .navbar-nav {
    display: flex;
    justify-content: center; /* Center-align the navbar items */
    width: 100%;
  }

  .nav-item {
    margin-right: 1.5rem; /* Adjust spacing between navbar items */
  }

  .nav-link {
    color: #000000; /* Black text color for nav links */
    font-weight: 500;
  }

  .nav-link:hover {
    color: #ffdb58; /* Gold hover color */
    text-decoration: underline; /* Underline on hover */
  }

  .navbar-toggler {
    border-color: #000000; /* Border color for the toggler icon */
  }

  .navbar-toggler-icon {
    background-color: #000000; /* Toggler icon color */
  }
</style>
