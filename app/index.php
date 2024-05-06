<?php
$dotenv = parse_ini_file('../.env');
$config["dbuser"] = $dotenv['DB_USER']; 
$config["dbpassword"] = $dotenv['DB_PASSWORD']; 
$config["dbserver"] = $dotenv['DB_SERVER'];

$db_conn = oci_connect($config["dbuser"], $config["dbpassword"], $config["dbserver"]);

if (!$db_conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$success = true;
$show_debug_alert_messages = False;
?>

<html>
<head>
    <title>CPSC 304 Collaboration App</title>
	<style>
        /* styles.css */
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                background-color: #f0f0f0;
            }

            .container {
                max-width: 100%;
                margin: auto;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                display: flex;
                overflow: auto;
            }

            .left-column {
                flex: 1;
                border: 4px solid grey;
                border-radius: 15px;
                padding-left: 4px;
            padding-right: 4px;
            }
            
            .right-column {
                flex: 1;
            }

            .result {
                position: fixed;
                top: 10px;
                width: 45%;
                padding: 2px 2px 2px 2px;
                margin-left: 1%;
                overflow-y: auto;
            height: 100vh;
            }
            
            h2 {
                margin-top: 30px;
            }

            form {
                margin-bottom: 20px;
            }

            select, input[type="submit"] {
                padding: 10px;
                margin-bottom: 10px;
                border: 1px solid #ccc;
                border-radius: 4px;
            }

            input[type="submit"] {
                cursor: pointer;
                background-color: #007bff;
                color: #fff;
                border: none;
            }

            input[type="submit"]:hover {
                background-color: #0056b3;
            }

            table {
                border-collapse: collapse;
                width: 100%;
            }

            table, th, td {
                border: 1px solid #ddd;
            }

            th,td {
                padding: 10px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;  
            }
    </style>

	<script> // So that the dropdown will show the selected value, instead of having the first table created as the dropdown placeholder.
        document.addEventListener("DOMContentLoaded", function() {
            var tableNameDropdown = document.getElementById('tableName');
            var submittedTableName = "<?php echo isset($_GET['tableName']) ? $_GET['tableName'] : ''; ?>";
            
            for (var i = 0; i < tableNameDropdown.options.length; i++) {
                if (tableNameDropdown.options[i].value === submittedTableName) {
                    tableNameDropdown.options[i].selected = true;
                    break;
                }
            }
        });
    </script>
	</head>

	<body>
		<div class="container">
			<div class="left-column">
				<h1>CPSC 304 Collaboration APP</h1>

				<hr />

				<h2>Insert a new Collaboration Request (INSERT)</h2>
				<p>Fill in the text boxes below to create a new Collaboration Request.</p>
				<p>The UserID and ProjectID must already exist; otherwise, an error message will occur. If the input is valid and has been added to the Database, a success message will be displayed.</p>

				<form method="POST" action="index.php">
					<input type="hidden" id="insertCollaborationRequest" name="insertCollaborationRequest">
					Request ID: <input type="text" name="insrequestID"> <br /><br />
					User ID: <input type="text" name="insuserID"> <br /><br />
					ProjectID: <input type="text" name="insprojectID"> <br /><br />
					Status: 
					<!-- <input type="text" name="insstatus">  -->
					<select name="insstatus">
						<option value="T">T</option>
						<option value="F">F</option>
					</select>
					<br /><br />
					<input type="submit" name="insertCollaborationRequestSubmit"></p>
				</form>

    			<hr />

				<h2>Delete A User (DELETE)</h2>
				<p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>

				<form method="POST" action="index.php">
					<input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
					User Email: <input type="text" name="delEmail"> <br /><br />
					<input type="submit" value="Delete" name="deletion"></p>
				</form>

				<hr />

				<h2>Update A Poll (UPDATE)</h2>
				<h>Leave the field blank if you do not wish to modify</h> <br /><br />
				
				<form method="POST" action="index.php">
					<input type="hidden" id="updateQueryRequest" name="updateQueryRequest">

					Poll ID(Poll you would like to update): <select name="PollID">
								<option value="Poll1">Poll1</option>
								<option value="Poll2">Poll2</option>
								<option value="Poll3">Poll3</option>
								<option value="Poll4">Poll4</option>
								<option value="Poll5">Poll5</option>
							</select>
							<br /><br />
					Project ID: <select name="projectID">
								<option value="">Please Select</option>
								<option value="P1">P1</option>
								<option value="P2">P2</option>
								<option value="P3">P3</option>
								<option value="P4">P4</option>
								<option value="P5">P5</option>
							</select>
							<br /><br />
					Topic: <input type="text" name="Topic"> <br /><br />
					OptionA: <input type="text" name="OptionA"> <br /><br />
					OptionB: <input type="text" name="OptionB"> <br /><br />
					OptionC: <input type="text" name="OptionC"> <br /><br />
					OptionD: <input type="text" name="OptionD"> <br /><br />

					<input type="submit" value="Update" name="updateSubmit"></p>
				</form>

				<hr />

				<h2>Meetup Event Table (Selection)</h2>
				<p>Please choose a time range or locations to view the results. Note that the input is case-sensitive, and the time input format must be in the 24-hour format as HH:MM.</p>
				<p>If you press the Submit button without entering any input, the result will display all tuples from the Database.</p>
				<form method="GET" action="index.php">
					<input type="hidden" id="displayMeetupEventRequest" name="displayMeetupEventRequest">
					Time After: <input type="text" name="timeAfter" placeholder="eg 09:00">
					<select name="logicOperator1">
						<option value="AND">AND</option>
						<option value="OR">OR</option>
					</select>
					Time Before: <input type="text" name="timeBefore" placeholder="eg 19:00">
					<br><br>
					Location 1: <input type="text" name="location1" placeholder="eg UBC">
					<select name="logicOperator2">
						<option value="AND">AND</option>
						<option value="OR">OR</option>
					</select>
					Location 2: <input type="text" name="location2" placeholder="eg Richmond">
					<br>
					<input type="submit" value="Submit" name="displayMeetupEvent"></br>
				</form>

				<hr />

				<h2>Display Tables (Projection)</h2>
				<form method="GET" action="index.php">
				<label for="tableName">Select Table:</label>
				<select name="tableName" id="tableName">
					<?php
					$table_names = [];
					$query = oci_parse($db_conn, "SELECT table_name FROM user_tables");
					oci_execute($query);
					while ($row = oci_fetch_array($query, OCI_ASSOC)) {
						foreach ($row as $item) {
							$table_names[] = $item;
							echo "<option value='$item'>$item</option>";
						}
					}
					?>
				</select>
				<br>
				<input type="submit" name="getColumns" value="Get Columns">
				<?php
					if (isset($_GET['getColumns'])) {
						if (isset($_GET['tableName'])) {
							$tableName = $_GET['tableName'];
							$query = executePlainSQL("SELECT * FROM $tableName");
							$numColumns = oci_num_fields($query);

							echo "<br/><label>Select Columns to Display:</label><br>";
							for ($i = 1; $i <= $numColumns; $i++) {
								$columnName = oci_field_name($query, $i);
								echo "<input type='checkbox' name='columns[]' value='$columnName'>$columnName<br>";
							}
						}
					}
    			?>
				<br>
				<input type="submit" name="displayTable" value="Display Table">
				</form>

				<hr />

				<h2>List The Ongoing Projects of A User (Join)</h2>
				<p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>

				<form method="GET" action="index.php">
					<input type="hidden" id="joinQueryRequest" name="joinQueryRequest">
					User Id: <input type="text" name="joinUserID"> <br /><br />
					<input type="submit" value="Search" name="join"></p>
				</form>
				
				<hr />

				<h2>Determine the Date with the number of Notifications (Aggregation with GROUP BY)</h2>
				<p>Identifying the Dates and Count of Generated Notifications.</p>
				<form method="GET" action="index.php">
						<input type="hidden" id="displayRecentNotificationRequest" name="displayRecentNotificationRequest">
						<input value="Display" type="submit" name="displayRecentNotification"></p>
				</form>

				<hr />

				<h2>Sort Projects by the Number of Subtasks (Aggregation with HAVING)</h2>
				<p>Filter out projects based on how many subtasks they have.</p>
				<form method="GET" action="index.php">
						<label for="tasks">Choose the number of the task(s) to filter:</label>
						<input type="hidden" id="displayProjectRequest" name="displayProjectRequest">
						<select name="numOfTask" id="numOfTask">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
						</select>
						<br><br>
						<input value="Display" type="submit" name="displayProjectWtConditions"></p>
				</form>

				<hr />

				<h2>Nested Aggregation with Group By</h2>
				<p>Gets the average number of attendees in each meetup event at each location.</p>
				<form method="GET" action="index.php">
					<input type="hidden" id="displayAvgAttendeesRequest" name="displayAvgAttendeesRequest">
					<input type="submit" name="displayAvgAttendees"></p>
				</form>

				<hr />

				<h2>List All Users Who Participated in All Projects (Division)</h2>
				<form method="GET" action="index.php">
					<input type="hidden" id="divisionQueryRequest" name="divisionQueryRequest">
					<input type="submit" value="Search" name="division"></p>
				</form>
			</div>

			<div class="right-column">
				<div class="result">
					<?php
							function debugAlertMessage($message)
							{
								global $show_debug_alert_messages;
									if ($show_debug_alert_messages) {
										echo "<script type='text/javascript'>alert('" . $message . "');</script>";
										}
							}

							function executeBoundSQL($cmdstr, $list){   
								global $db_conn, $success;
							    $statement = oci_parse($db_conn, $cmdstr);
								if (!$statement) {
									echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
									$e = OCI_Error($db_conn);
								 	echo htmlentities($e['message']);
									$success = False;
								}
								foreach ($list as $tuple) {
									foreach ($tuple as $bind => $val) {
										oci_bind_by_name($statement, $bind, $val);
										unset($val); 
									}
									
									$r = oci_execute($statement, OCI_DEFAULT);
									if (!$r) {
										echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
										$e = OCI_Error($statement); 
										echo htmlentities($e['message']);
										echo "<br>";
										$success = False;
										}
								}
							}

							function printUserResult($result){   
								echo "<br>Retrieved data from table appUser, appUser1, appUser2 and appUser3:<br>";
								echo "<table>";
								echo "<tr><th>User ID</th><th>Email</th><th>Password</th><th>First Name</th><th>Last Name</th></tr>";

								while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
									echo "<tr><td>" . $row["USERID"] . "</td><td>" . $row["EMAIL"] . "</td><td>" . $row["PASSWORD"] . "</td><td>" . $row["FIRSTNAME"] . "</td><td>" . $row["LASTNAME"] . "</td></tr>";	
								}

								echo "</table>";
							}

							function printTaskResult($result){   
								echo "<br>Retrieved data from table Task:<br>";
								echo "<table>";
								echo "<tr><th>Task ID</th><th>Task Name</th><th>Project ID</th><th>Due Date</th><th>Status</th><th>Description</th><th>Create Time</th></tr>";

								while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
									echo "<tr><td>" . $row["TASKID"] . "</td><td>" . $row["TASKNAME"] . "</td><td>" . $row["PROJECTID"] . "</td><td>" . $row["DUEDATE"] . "</td><td>" . $row["STATUS"] . "</td><td>" . $row["DESCRIPTION"] . "</td><td>" . $row["CREATETIME"] . "</td></tr>";
								}
								echo "</table>";
							}

							function printMostRecentNotification($result){ 
								echo "<br>Retrieved data from table Notification: <br>";
								echo "<table>";
								echo "<tr><th>Date Generated</th><th>Number of Notification per day</th></tr>";

								while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
									echo "<tr><td>" . $row["NOTIFICATIONDATE"] . "</td><td>" . $row["NOTIFICATIONSCOUNT"] . "</td></tr>";
								}

								echo "</table>";
							}

							function printProjectResult($result){   
								echo "<br>Retrieved data from tables Project and Task:<br>";
								echo "<table>";
								echo "<tr><th>Project ID</th><th>Project Name</th><th># of Task</th><th>Status</th></tr>";

								while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
									echo "<tr><td>" . $row["PROJECTID"] . "</td><td>" . $row["PROJECTNAME"] . "</td><td>" . $row["TASKCOUNT"] . "</td><td>" . $row["STATUS"] . "</td></tr>";
								}

								echo "</table>";
							}

					
							function printMeetUpResult($result){   
									echo "<br>Retrieved data from MeetupEvent table:<br>";
									echo "<table>";
									echo "<tr><th>Location</th><th>Average Attendees Per Event</th></tr>";

									while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
											echo "<tr><td>" . $row["LOCATION"] . "</td><td>" . $row["AVG_ATTENDEES"] . "</td></tr>";
									}

									echo "</table>";
							}

							function executePlainSQL($cmdstr){
								global $db_conn, $success;
								$statement = oci_parse($db_conn, $cmdstr);

								if (!$statement) {
										echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
										$e = OCI_Error($db_conn);
										echo htmlentities($e['message']);
										$success = False;
								}

								$r = oci_execute($statement, OCI_DEFAULT);
								if (!$r) {
										echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
										$e = oci_error($statement);
										echo htmlentities($e['message']);
										$success = False;
								}

								return $statement;
							}

							function handleDisplayTableRequest(){
								global $db_conn;
								$tableName = $_GET['tableName'];
								$columns = $_GET['columns'];
								$selectedColumns = implode(",", $columns);
								$projectionQuery = "SELECT $selectedColumns FROM $tableName"; // Equivalent to SELECT Col1, Col2, Col3 FROM SelectedTable;
								$result = executePlainSQL($projectionQuery);

								echo "<h2>$tableName Table</h2>";
								echo "<table>";
								echo "<tr>";
								foreach ($columns as $column) {
									echo "<th>$column</th>";
								}
								echo "</tr>";
								while ($row = oci_fetch_array($result, OCI_ASSOC)) {
									echo "<tr>";
									foreach ($columns as $column) {
										echo "<td>{$row[$column]}</td>";
									}
									echo "</tr>";
								}
								echo "</table>";
							}

							function printTableResult($result, $tableName){
								echo "<h2>$tableName Table</h2>";
								echo "<table>";
								$numColumns = oci_num_fields($result);

								echo "<tr>";
								for ($i = 1; $i <= $numColumns; $i++) {
									echo "<th>" . oci_field_name($result, $i) . "</th>";
								}

								echo "</tr>";
								while ($row = oci_fetch_array($result, OCI_ASSOC)) {
									echo "<tr>";
									foreach ($row as $item) {
										echo "<td>$item</td>";
									}
									echo "</tr>";
								}

								echo "</table>";
							}

							function handleDisplaySelectionRequest(){
								global $db_conn;

								$timeAfter = $_GET['timeAfter'];
								$timeBefore = $_GET['timeBefore'];
								$location1 = $_GET['location1'];
								$location2 = $_GET['location2'];
								$logicOperator1 = $_GET['logicOperator1']; 
								$logicOperator2 = $_GET['logicOperator2'];
						
								$conditions = [];
								if (!empty($timeAfter)) $conditions[] = "TO_CHAR(Time, 'HH24:MI') >= '$timeAfter'";
								
								if (!empty($timeBefore)) {
									if (!empty($conditions)) $conditions[] = "$logicOperator1 TO_CHAR(Time, 'HH24:MI') <= '$timeBefore'"; // timeAfter exists
									else $conditions[] = "TO_CHAR(Time, 'HH24:MI') <= '$timeBefore'";
								}

								if (!empty($conditions)) { // Time has been added
									if (!empty($location1) && !empty($location2) ) { // if both exists
										$conditions[] = "AND Location LIKE '%$location1%' $logicOperator2 Location LIKE '%$location2%'";
										
									} else if ( !empty($location1) && empty($location2)) {// only location1 is added
										$conditions[] = "AND Location LIKE '%$location1%'";
									} else if ( empty($location1) && !empty($location2)) {// only location2 is added
										$conditions[] = "AND Location LIKE '%$location2%'";
									}
								} else { // Time has not been added
									if (!empty($location1) && !empty($location2) ) { // if both exists
										$conditions[] = "Location LIKE '%$location1%'";
										$conditions[] = "$logicOperator2 Location LIKE '%$location2%'";
									} else if ( !empty($location1) && empty($location2)) {// only location1 is added
										$conditions[] = "Location LIKE '%$location1%'";
									} else if ( empty($location1) && !empty($location2)) {// only location2 is added
										$conditions[] = "Location LIKE '%$location2%'";
									}
								}
						
								$basicQuery = "SELECT * FROM MeetupEvent";
								if (!empty($conditions)) $basicQuery .= " WHERE " . implode(" ", $conditions);
								// echo $basicQuery;
								$result = executePlainSQL($basicQuery);
								if (empty($result)) {
									echo "No results found.";
								}	else {
									echo "<br>Retrieved data from table Meetup Event: <br>";
									echo "<table>";
									echo "<tr><th>MeetupEvent ID</th><th>Project ID</th><th>Location</th><th>Time</th><th>User ID</th></tr>";
									while ($row = OCI_Fetch_Array($result, OCI_ASSOC)) {
										echo "<tr><td>" . $row["MEETUPEVENTID"] . "</td><td>" . $row["PROJECTID"] . "</td><td>" . $row["LOCATION"] . "</td><td>" . $row["TIME"] . "</td><td>" . $row["USERID"] . "</td></tr>";
									}
									echo "</table>";
								}
							}


							function connectToDB(){
								global $db_conn;
								global $config;

								$db_conn = oci_connect($config["dbuser"], $config["dbpassword"], $config["dbserver"]);

								if ($db_conn) {
									debugAlertMessage("Database is Connected");
									return true;
								} else {
									debugAlertMessage("Cannot connect to Database");
									$e = OCI_Error();
									echo htmlentities($e['message']);
									return false;
								}
							}

							function disconnectFromDB(){
								global $db_conn;

								debugAlertMessage("Disconnect from Database");
								oci_close($db_conn);
							}

							function handleUpdateRequest(){
								global $db_conn;

								$PollID = $_POST['PollID'];
								$ProjectID = $_POST['projectID'];
								$Topic = $_POST['Topic'];
								$OptionA = $_POST['OptionA'];
								$OptionB = $_POST['OptionB'];
								$OptionC = $_POST['OptionC'];
								$OptionD = $_POST['OptionD'];
								
								// Attribute Email CAN NOT be updated because it is the PK of relational tables appUser1, appUser2 and appUser3. Updating it requires removing constraints.
								if (strlen($Topic)>225 || mb_strlen($OptionA, 'UTF-8')>50 || strlen($OptionB)>50 || strlen($OptionC)>50 || strlen($OptionD)>50 ){
									Echo "Input Invalid";
								} else{
									if ($ProjectID != ''){
										executePlainSQL("UPDATE Poll SET ProjectID='" . $ProjectID . "' WHERE PollID='" . $PollID . "'");
									}
									if ($Topic != ''){
										executePlainSQL("UPDATE Poll SET Topic='" . $Topic . "' WHERE PollID='" . $PollID . "'");
									}
									if ($OptionA != ''){
										executePlainSQL("UPDATE Poll SET OptionA='" . $OptionA . "' WHERE PollID='" . $PollID . "'");
									}
									if ($OptionB != ''){
										executePlainSQL("UPDATE Poll SET OptionB='" . $OptionB . "' WHERE PollID='" . $PollID . "'");
									}
									if ($OptionC != ''){
										executePlainSQL("UPDATE Poll SET OptionC='" . $OptionC . "' WHERE PollID='" . $PollID . "'");
									}
									if ($OptionD != ''){
										executePlainSQL("UPDATE Poll SET OptionD='" . $OptionD . "' WHERE PollID='" . $PollID . "'");
									}
									$result = executePlainSQL("SELECT * FROM Poll");
									Echo "Update successful";
									printTableResult($result, "");
								}
								oci_commit($db_conn);
							}

							function handleInsertPMRequest(){
								global $db_conn;

								$tuple = array(
									":bind1" => $_POST['insuserID'],
									":bind2" => $_POST['insemail'],
									":bind3" => $_POST['inspassword'],
									":bind4" => $_POST['insfirstname'],
									":bind5" => $_POST['inslastname'],
									":bind6" => $_POST['inspermissionid']
								);

								$alltuples = array(
									$tuple
								);

								executeBoundSQL("insert into appUser values (:bind1, :bind2)", $alltuples);
								executeBoundSQL("insert into appUser1 values (:bind2, :bind3)", $alltuples);
								executeBoundSQL("insert into appUser2 values (:bind2, :bind4)", $alltuples);
								executeBoundSQL("insert into appUser3 values (:bind2, :bind5)", $alltuples);
								executeBoundSQL("insert into ProjectManager values (:bind1, :bind6)", $alltuples);
								
								oci_commit($db_conn);
							}

							function handleInsertMemberRequest(){
								global $db_conn;

								$tuple = array(
									":bind1" => $_POST['insuserID'],
									":bind2" => $_POST['insemail'],
									":bind3" => $_POST['inspassword'],
									":bind4" => $_POST['insfirstname'],
									":bind5" => $_POST['inslastname'],
									":bind6" => $_POST['insrole']
								);

								$alltuples = array(
									$tuple
								);

								executeBoundSQL("insert into appUser values (:bind1, :bind2)", $alltuples);
								executeBoundSQL("insert into appUser1 values (:bind2, :bind3)", $alltuples);
								executeBoundSQL("insert into appUser2 values (:bind2, :bind4)", $alltuples);
								executeBoundSQL("insert into appUser3 values (:bind2, :bind5)", $alltuples);
								executeBoundSQL("insert into Member values (:bind1, :bind6)", $alltuples);
								
								oci_commit($db_conn);
							}

							function handleDeleteRequest(){
								global $db_conn;
								$email = $_POST['delEmail'];
								if(strlen($email) <= 50){
									$user = executePlainSQL("SELECT * FROM appUSER WHERE email = '" . $email . "'");
									$row = oci_fetch_array($user, OCI_ASSOC);
									if($row){
										executePlainSQL("DELETE FROM appUSER WHERE email = '" . $email . "'");
										Echo "User Deleted";
										$result = executePlainSQL("SELECT * FROM appUser");
										printTableResult($result, "");
									} else {
										Echo "User Does Not Exist";
									}
								} else {
									Echo "Input too long";
								}
										
								oci_commit($db_conn);
							}

							function handleJoinRequest() {
								global $db_conn;
								$userID = $_GET['joinUserID'];
								$result = executePlainSQL("SELECT u.userID, p.projectName 
															FROM appUSER u, WorksOn w, Project p 
															WHERE u.userID = w.userID AND w.ProjectID = p.ProjectID AND u.userID = '" . $userID . "' AND p.Status = 'F' ");
								printTableResult($result, "");
								oci_commit($db_conn);
							}

							function handleDivisionRequest() {
								global $db_conn;
								$result = executePlainSQL("SELECT A.userID, A1.firstName, A2.lastname 
															FROM appUser A, appUser2 A1, appUser3 A2 
															WHERE A.email = A1.email AND A1.email =A2.email AND A.email = A2.email AND NOT EXISTS
																																	((SELECT P.projectID 
																																	FROM Project P) 
																																	MINUS
																																	(SELECT W.projectID 
																																	FROM WorksOn W 
																																	WHERE W.userID=A.userID))");
								printTableResult($result, "");
								oci_commit($db_conn);
							}

							function handleInsertCollaborationRequest() {
								global $db_conn;

								$tuple = array(
										":bind1" => $_POST['insrequestID'],
										":bind2" => $_POST['insuserID'],
										":bind3" => $_POST['insprojectID'],
										":bind4" => $_POST['insstatus']
								);

								$alltuples = array(
										$tuple
								);

								try {
										$statement = oci_parse($db_conn, "insert into CollaborationRequest values (:bind1, :bind2, :bind3, :bind4)");
										// Copy code from executeBoundSQL() function 
										foreach ($alltuples as $tuple) {
												foreach ($tuple as $bind => $val) {
														oci_bind_by_name($statement, $bind, $val);
														unset($val); 
												}
												$r = oci_execute($statement, OCI_DEFAULT);
												if (!$r) {
														echo "<br>This command cannot be executed because the userID or projectID you entered may not exist, or the Collaboration Request already exists.<br>";
														$success = False;
												} else {
													echo "<br>A new Collaboration Request has been added to the Database.<br>";
												}
										}
										oci_commit($db_conn);
								} catch(Exception $e) {
										if (str_contains($e->getMessage(), 'ORA-02291')) {
												echo 'this userid or project id does not exist, please check again';
										} else {
												echo 'This is an error: ' .$e->getMessage();
										};
								}
							}

							function handleDisplayRecentNotificationRequest() {
								global $db_conn;
								$query = "SELECT TO_CHAR(dateGenerated, 'yyyy/mm/dd') AS NotificationDate, COUNT(notificationID) AS NotificationsCount FROM notification GROUP BY TO_CHAR(dateGenerated, 'yyyy/mm/dd') ORDER BY NotificationDate DESC";

								$result = executePlainSQL($query);
								printMostRecentNotification($result);
							}

							function handleDisplayProjectRequest() {
								global $db_conn;
								$user_input = $_GET['numOfTask'];

								$query = "SELECT p.ProjectID, p.ProjectName, COUNT(t.TaskID) AS TaskCount, p.Status FROM Project p, Task t WHERE t.ProjectID = p.ProjectID GROUP BY p.ProjectID, p.ProjectName, p.Status HAVING COUNT(t.TaskID) >= $user_input";

								$result = executePlainSQL($query);
								printProjectResult($result);
							}

							function handleAvgAttendeesRequest() {
								global $db_conn;
								$user_input = $_GET['avgAttendees'];

								$query = "SELECT Location, AVG(AttendeeCount) AS avg_attendees FROM (SELECT Location, MeetupEventID, COUNT(UserID) AS AttendeeCount FROM MeetupEvent GROUP BY Location, MeetupEventID) GROUP BY Location";

								$result = executePlainSQL($query);
								printMeetUpResult($result);
							}

							function handlePOSTRequest(){
								if (connectToDB()) {
									if (array_key_exists('updateQueryRequest', $_POST)) {
										handleUpdateRequest();
									} else if (array_key_exists('insertPMRequest', $_POST)) {
										handleInsertPMRequest();
									} else if (array_key_exists('insertMemberRequest', $_POST)) {
										handleInsertMemberRequest();
									} else if (array_key_exists('insertCollaborationRequest', $_POST)) {
										handleInsertCollaborationRequest();
									} else if (array_key_exists('deleteQueryRequest', $_POST)) {
										handleDeleteRequest();
									} 
									disconnectFromDB();
									}
							}

							function handleGETRequest(){
								if (connectToDB()) {
									if (array_key_exists('displayTuples', $_GET)) {
										handleDisplayRequest();
									} elseif (array_key_exists('displayRecentNotification', $_GET)) {
										handleDisplayRecentNotificationRequest();
									} elseif (array_key_exists('displayProjectWtConditions', $_GET)) {
										handleDisplayProjectRequest();
									} elseif (array_key_exists('displayTable', $_GET)) {
													handleDisplayTableRequest();
												} elseif(array_key_exists('tableName', $_GET)){
													handleDisplayTableRequest();
												} elseif(array_key_exists('displayAvgAttendeesRequest', $_GET)){
													handleAvgAttendeesRequest();
												} elseif(array_key_exists('displayMeetupEvent', $_GET)){
													handleDisplaySelectionRequest();
												} elseif (array_key_exists('joinQueryRequest', $_GET)) {
													handleJoinRequest();
												} elseif (array_key_exists('divisionQueryRequest', $_GET)) {
													handleDivisionRequest();
												}
									disconnectFromDB();
								}
							}

						if (isset($_POST['deletion']) || isset($_POST['updateSubmit']) || isset($_POST['insertPMSubmit']) || isset($_POST['insertMemberSubmit']) || isset($_POST['insertCollaborationRequestSubmit'])) { // Handle all the Post requests
							handlePOSTRequest();
						} else if (isset($_GET['division']) || isset($_GET['join']) || isset($_GET['displayTuplesRequest']) || isset($_GET['displayProjectRequest']) || isset($_GET['displayRecentNotificationRequest']) || isset($_GET['displayAvgAttendeesRequest']) || isset($_GET['displayTable']) || isset($_GET['tableName']) || isset($_GET['displayMeetupEventRequest'])) {
							handleGETRequest();
						}
    				?>
				</div> <!-- class="result" -->
			</div> <!-- class="right-column" -->
		</div> <!-- class="container" -->
	</body>
</html>