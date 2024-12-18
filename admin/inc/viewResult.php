<?php
    // Validate and sanitize input
    $election_id = isset($_GET['viewResult']) ? intval($_GET['viewResult']) : 0;
    if (!$election_id) {
        echo "Invalid election ID.";
        exit;
    }
?>

<div class="row my-3">
    <div class="col-12">
        <h3>Elections Result</h3>
        <?php
            // Fetch election details
            $stmt = $db->prepare("SELECT * FROM elections WHERE id = ?");
            $stmt->bind_param("i", $election_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();
                $election_topic = htmlspecialchars($data['election_topic']);
        ?>
        <table class="table table-success table-striped">
            <thead>
                <tr>
                    <th colspan="3">Election Topic: <?php echo strtoupper($election_topic); ?></th>
                </tr>
                <tr>
                    <th>Photo</th>
                    <th>Candidate Details</th>
                    <th># of Votes</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Fetch candidates and their votes
                    $candidate_stmt = $db->prepare("SELECT * FROM candidate_details WHERE election_id = ?");
                    $candidate_stmt->bind_param("i", $election_id);
                    $candidate_stmt->execute();
                    $candidates_result = $candidate_stmt->get_result();

                    while ($candidate = $candidates_result->fetch_assoc()) {
                        $candidate_id = $candidate['id'];
                        $candidate_photo = htmlspecialchars($candidate['candidate_photo']);
                        $candidate_name = htmlspecialchars($candidate['candidate_name']);
                        $candidate_detail = htmlspecialchars($candidate['candidate_detail']);

                        $vote_stmt = $db->prepare("SELECT COUNT(*) AS vote_count FROM votings WHERE candidate_id = ?");
                        $vote_stmt->bind_param("i", $candidate_id);
                        $vote_stmt->execute();
                        $vote_result = $vote_stmt->get_result();
                        $votes = $vote_result->fetch_assoc()['vote_count'];
                ?>
                <tr>
                    <td><img src="<?php echo $candidate_photo; ?>" alt="Candidate Photo" style="width:80px; height:80px; border:2px solid #000; border-radius:100%;"></td>
                    <td><strong><?php echo $candidate_name; ?></strong><br><?php echo $candidate_detail; ?></td>
                    <td><?php echo $votes; ?></td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>

        <?php
            } else {
                echo "<p class='text-danger'>No Active Elections</p>";
            }
        ?>
        
        <hr>
        <h3>Voting Details</h3>
        <?php
            // Fetch voting details
            $vote_stmt = $db->prepare("SELECT v.*, u.username, u.contact_no, c.candidate_name 
                                       FROM votings v
                                       LEFT JOIN users u ON v.voters_id = u.id
                                       LEFT JOIN candidate_details c ON v.candidate_id = c.id
                                       WHERE v.election_id = ?");
            $vote_stmt->bind_param("i", $election_id);
            $vote_stmt->execute();
            $vote_result = $vote_stmt->get_result();

            if ($vote_result->num_rows > 0) {
                $sno = 1;
        ?>
        <table class="table table-success table-striped">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Voter Name</th>
                    <th>Contact No</th>
                    <th>Voted To</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ($vote = $vote_result->fetch_assoc()) {
                        $username = htmlspecialchars($vote['username'] ?: "No Data");
                        $contact_no = htmlspecialchars($vote['contact_no'] ?: "No Data");
                        $candidate_name = htmlspecialchars($vote['candidate_name'] ?: "No Data");
                        $vote_date = htmlspecialchars($vote['vote_date']);
                        $vote_time = htmlspecialchars($vote['vote_time']);
                ?>
                <tr>
                    <td><?php echo $sno++; ?></td>
                    <td><?php echo $username; ?></td>
                    <td><?php echo $contact_no; ?></td>
                    <td><?php echo $candidate_name; ?></td>
                    <td><?php echo $vote_date; ?></td>
                    <td><?php echo $vote_time; ?></td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
        <?php
            } else {
                echo "<p class='text-danger'>No vote details available.</p>";
            }
        ?>
    </div>
</div>
