<div class="container my-4">      
    <div class="row">
        <div class="col-12">
            <h3 class="mb-4">Elections Held</h3>
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th scope="col">S.No</th>
                        <th scope="col">Election Name</th>
                        <th scope="col"># Candidates</th>
                        <th scope="col">Starting Date</th>
                        <th scope="col">Ending Date</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Fetch election data
                        $fetchingData = mysqli_query($db, "SELECT * FROM elections") or die(mysqli_error($db));
                        $isAnyElectionAdded = mysqli_num_rows($fetchingData);

                        if ($isAnyElectionAdded > 0) {
                            $sno = 1;
                            while ($row = mysqli_fetch_assoc($fetchingData)) {
                                $election_id = $row['id'];
                    ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><?php echo htmlspecialchars($row['election_topic']); ?></td>
                            <td><?php echo htmlspecialchars($row['no_of_candidates']); ?></td>
                            <td><?php echo htmlspecialchars($row['starting_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['ending_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td>
                                <a href="index.php?viewResult=<?php echo $election_id; ?>" class="btn btn-sm btn-success">View Results</a>
                            </td>
                        </tr>
                    <?php
                            }
                        } else {
                    ?>
                        <tr>
                            <td colspan="7" class="text-center">No Elections have been added yet.</td>
                        </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
